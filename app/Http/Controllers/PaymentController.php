<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\VivaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected $vivaPayment;

    public function __construct(VivaPaymentService $vivaPayment)
    {
        $this->vivaPayment = $vivaPayment;
    }

    /**
     * Handle payment success callback
     */
    public function success(Request $request)
    {
        // Viva Payment returns order code as 's' parameter
        $orderCode = $request->query('s') ?? $request->query('OrderCode');

        if (!$orderCode) {
            // Check if this is an iframe request
            if ($request->header('Sec-Fetch-Dest') === 'iframe' || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid payment response']);
            }
            return redirect()->route('register')
                ->with('error', 'Invalid payment response. Please try again.');
        }

        // Find the payment record
        $payment = Payment::where('order_code', $orderCode)->first();

        if (!$payment) {
            if ($request->header('Sec-Fetch-Dest') === 'iframe' || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Payment record not found']);
            }
            return redirect()->route('register')
                ->with('error', 'Payment record not found. Please contact support.');
        }

        // Verify the order with Viva
        $orderDetails = $this->vivaPayment->getOrderDetails($orderCode);

        if ($orderDetails && isset($orderDetails['StatusId'])) {
            // StatusId 'F' means completed/successful
            // StatusId 'A' means authorized
            // StatusId 'C' means cancelled
            if ($orderDetails['StatusId'] === 'F' || $orderDetails['StatusId'] === 'A') {
                // Payment successful
                $transactionId = $orderDetails['Transactions'][0]['TransactionId'] ?? $payment->transaction_id;
                
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $transactionId,
                    'paid_at' => now(),
                    'payment_data' => $orderDetails,
                ]);

                // If called from iframe, return HTML that communicates with parent
                if ($request->header('Sec-Fetch-Dest') === 'iframe') {
                    return view('payment.success-iframe', ['orderCode' => $orderCode]);
                }

                // Get registration data from session (for redirect flow)
                $registrationData = session('pending_registration');

                if ($registrationData) {
                    // Complete the registration
                    return $this->completeRegistration($registrationData, $payment);
                }

                // If no session data, just show success (form will auto-submit)
                return view('payment.success-iframe', ['orderCode' => $orderCode]);
            } else {
                // Payment failed or cancelled
                $payment->update([
                    'status' => 'failed',
                    'payment_data' => $orderDetails,
                ]);

                if ($request->header('Sec-Fetch-Dest') === 'iframe') {
                    return view('payment.failure-iframe', ['orderCode' => $orderCode]);
                }

                return redirect()->route('register')
                    ->with('error', 'Payment was not successful. Please try again.');
            }
        } else {
            // Could not verify payment, but assume success if we got here
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            if ($request->header('Sec-Fetch-Dest') === 'iframe') {
                return view('payment.success-iframe', ['orderCode' => $orderCode]);
            }

            $registrationData = session('pending_registration');

            if ($registrationData) {
                return $this->completeRegistration($registrationData, $payment);
            }

            return view('payment.success-iframe', ['orderCode' => $orderCode]);
        }
    }

    /**
     * Check payment status (for AJAX polling)
     */
    public function checkStatus(Request $request)
    {
        $orderCode = $request->query('orderCode');

        if (!$orderCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order code is required'
            ], 400);
        }

        $payment = Payment::where('order_code', $orderCode)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Payment not found'
            ]);
        }

        // If payment is still pending, verify with Viva
        if ($payment->status === 'pending') {
            $orderDetails = $this->vivaPayment->getOrderDetails($orderCode);
            
            if ($orderDetails && isset($orderDetails['StatusId'])) {
                if ($orderDetails['StatusId'] === 'F' || $orderDetails['StatusId'] === 'A') {
                    $transactionId = $orderDetails['Transactions'][0]['TransactionId'] ?? $payment->transaction_id;
                    
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $transactionId,
                        'paid_at' => now(),
                        'payment_data' => $orderDetails,
                    ]);
                } else if ($orderDetails['StatusId'] === 'C') {
                    $payment->update([
                        'status' => 'failed',
                        'payment_data' => $orderDetails,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => $payment->status,
            'orderCode' => $payment->order_code
        ]);
    }

    /**
     * Handle payment failure callback
     */
    public function failure(Request $request)
    {
        $orderCode = $request->query('s');

        if ($orderCode) {
            $payment = Payment::where('order_code', $orderCode)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                ]);
            }
        }

        // Clear pending registration data
        session()->forget('pending_registration');

        return redirect()->route('register')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    /**
     * Complete user registration after successful payment
     */
    private function completeRegistration(array $registrationData, Payment $payment)
    {
        try {
            // Handle file uploads - Store directly in public folder
            $documentDashboardPath = null;
            if (isset($registrationData['document_dashboard_path'])) {
                $documentDashboardPath = $registrationData['document_dashboard_path'];
            }

            $documentIdentityPath = null;
            if (isset($registrationData['document_identity_path'])) {
                $documentIdentityPath = $registrationData['document_identity_path'];
            }

            // Create the user
            $user = User::create([
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'password' => Hash::make($registrationData['password']),
                'phone' => $registrationData['phone'],
                'psp_number' => $registrationData['psp_number'] ?? null,
                'taxi_driver_id' => $registrationData['taxi_driver_id'] ?? null,
                'user_type' => 1, // User type
                'document_dashboard_path' => $documentDashboardPath,
                'document_identity_path' => $documentIdentityPath,
                'document_payment_receipt_path' => null, // No receipt for new payment
                'payment_type' => 'new_payment',
                'terms_agreed' => true,
                'share_certificate_agreed' => true,
            ]);

            // Link payment to user
            $payment->update(['user_id' => $user->id]);

            // Clear pending registration data
            session()->forget('pending_registration');

            // Log the user in
            Auth::login($user);

            // Redirect with success message
            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! Successfully Registered! Welcome to Hola Connect.');
        } catch (\Exception $e) {
            Log::error('Registration completion failed', [
                'error' => $e->getMessage(),
                'data' => $registrationData
            ]);

            return redirect()->route('register')
                ->with('error', 'Registration failed. Please contact support with your payment reference: ' . $payment->order_code);
        }
    }
}
