<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\VivaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    protected $vivaPayment;

    public function __construct(VivaPaymentService $vivaPayment)
    {
        $this->vivaPayment = $vivaPayment;
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'psp_number' => 'nullable|string|max:255',
            'taxi_driver_id' => 'nullable|string|max:255',
            'document_dashboard' => 'required|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'document_identity' => 'required|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'payment_type' => 'required|in:pre_payment,new_payment,full_payment',
            'document_payment_receipt' => 'required_if:payment_type,pre_payment,full_payment|nullable|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'payment_order_code' => 'required_if:payment_type,new_payment,pre_payment|nullable|string',
            'terms_agreed' => 'required|accepted',
            'share_certificate_agreed' => 'required|accepted',
        ]);

        // Handle file uploads - Store directly in public folder
        $documentDashboardPath = null;
        if ($request->hasFile('document_dashboard')) {
            $file = $request->file('document_dashboard');
            $fileName = time() . '_dashboard_' . $file->getClientOriginalName();
            $file->move(public_path('documents/dashboard'), $fileName);
            $documentDashboardPath = 'documents/dashboard/' . $fileName;
        }

        $documentIdentityPath = null;
        if ($request->hasFile('document_identity')) {
            $file = $request->file('document_identity');
            $fileName = time() . '_identity_' . $file->getClientOriginalName();
            $file->move(public_path('documents/identity'), $fileName);
            $documentIdentityPath = 'documents/identity/' . $fileName;
        }

        // Verify payment if applicable (new_payment or pre_payment)
        if (in_array($validated['payment_type'], ['new_payment', 'pre_payment'])) {
            $paymentOrderCode = $request->input('payment_order_code');

            if (!$paymentOrderCode) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Payment order code is required.');
            }

            // Verify payment was successful
            $payment = Payment::where('order_code', $paymentOrderCode)->first();

            if (!$payment || $payment->status !== 'completed') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Payment verification failed. Please complete the payment first.');
            }
        }

        // Handle document receipt upload (pre_payment or full_payment)
        $documentPaymentReceiptPath = null;
        if (in_array($validated['payment_type'], ['pre_payment', 'full_payment']) && $request->hasFile('document_payment_receipt')) {
            $file = $request->file('document_payment_receipt');
            $fileName = time() . '_receipt_' . $file->getClientOriginalName();
            $file->move(public_path('documents/payment_receipts'), $fileName);
            $documentPaymentReceiptPath = 'documents/payment_receipts/' . $fileName;
        }

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'psp_number' => $validated['psp_number'] ?? null,
            'taxi_driver_id' => $validated['taxi_driver_id'] ?? null,
            'user_type' => 1, // User type
            'document_dashboard_path' => $documentDashboardPath,
            'document_identity_path' => $documentIdentityPath,
            'document_payment_receipt_path' => $documentPaymentReceiptPath,
            'payment_type' => $validated['payment_type'],
            'terms_agreed' => true,
            'share_certificate_agreed' => true,
        ]);

        // Link payment to user if applicable
        if (isset($payment)) {
            $payment->update(['user_id' => $user->id]);
        }

        // Log the user in
        Auth::login($user);

        // Redirect with success message
        return redirect()->route('dashboard')->with('success', 'Successfully Registered! Welcome to Hola Connect.');
    }

    /**
     * Create payment order via AJAX
     */
    public function createPaymentOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        $paymentData = [
            'amount' => $request->input('amount'),
            'email' => $request->input('email'),
            'fullName' => $request->input('name'),
            'phone' => $request->input('phone'),
            'customerTrns' => 'Registration Payment - ' . $request->input('name'),
            'merchantTrns' => 'Registration for ' . $request->input('email'),
            // Note: successUrl and failUrl are configured in Viva dashboard Payment Source
        ];

        $paymentOrder = $this->vivaPayment->createPaymentOrder($paymentData);

        if (!$paymentOrder || !isset($paymentOrder['orderCode'])) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order. Please check your Viva Payment credentials and try again.'
            ], 500);
        }

        // Create payment record
        $payment = Payment::create([
            'order_code' => $paymentOrder['orderCode'],
            'transaction_id' => $paymentOrder['transactionId'] ?? null,
            'amount' => $request->input('amount'),
            'currency' => 'EUR',
            'status' => 'pending',
            'customer_trns' => $paymentData['customerTrns'],
            'merchant_trns' => $paymentData['merchantTrns'],
        ]);

        return response()->json([
            'success' => true,
            'orderCode' => $paymentOrder['orderCode'],
            'checkoutUrl' => $paymentOrder['checkoutUrl'],
        ]);
    }

}
