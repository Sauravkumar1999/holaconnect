<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewRegistrationNotification;
use App\Mail\UserRegistrationComplete;
use App\Models\Payment;
use App\Models\User;
use App\Services\VivaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'psp_number' => 'required|string|max:255',
            'taxi_driver_id' => 'required|string|max:255',
            'document_dashboard' => 'required|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'document_identity' => 'required|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'payment_type' => 'required|in:existing_user,partial_user,new_user',
            'document_payment_receipt' => 'required_if:payment_type,existing_user,partial_user|nullable|file|mimes:pdf,csv,xlsx,xls,doc,docx|max:10240',
            'terms_agreed' => 'required',
            'share_certificate_agreed' => 'required',
        ]);

        if ($validated['payment_type'] === 'existing_user') {
            return $this->processExistingUserRegistration($request, $validated);
        } else {
            return $this->processPaymentRegistration($request, $validated);
        }
    }

    private function processExistingUserRegistration(Request $request, array $validated)
    {
        // Handle file uploads - Store directly in public folder
        $documentDashboardPath = $this->uploadFile($request, 'document_dashboard', 'documents/dashboard', 'dashboard');
        $documentIdentityPath = $this->uploadFile($request, 'document_identity', 'documents/identity', 'identity');
        $documentPaymentReceiptPath = $this->uploadFile($request, 'document_payment_receipt', 'documents/payment_receipts', 'receipt');

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

        // Send email to user
        Mail::to($user->email)->send(new UserRegistrationComplete($user));

        // Send email to admin
        // $adminEmail = User::where('user_type', 0)->first()?->email ?? config('mail.from.address');
        // if ($adminEmail) {
        //     Mail::to($adminEmail)->send(new AdminNewRegistrationNotification($user));
        // }

        // Log the user in
        Auth::login($user);

        // Redirect with success message
        return redirect()->route('dashboard')->with('success', 'Successfully Registered! Welcome to Hola Connect.');
    }

    private function processPaymentRegistration(Request $request, array $validated)
    {
        // Temp Storage Directory
        $tempDir = 'documents/temp';
        if (!file_exists(public_path($tempDir))) {
            mkdir(public_path($tempDir), 0755, true);
        }

        // Upload files to temporary location
        $documentDashboardPath = $this->uploadFile($request, 'document_dashboard', $tempDir, 'dashboard');
        $documentIdentityPath = $this->uploadFile($request, 'document_identity', $tempDir, 'identity');

        $documentPaymentReceiptPath = null;
        if ($validated['payment_type'] === 'partial_user') {
            $documentPaymentReceiptPath = $this->uploadFile($request, 'document_payment_receipt', $tempDir, 'receipt');
        }

        // Calculate Amount
        $baseAmount = (float) \App\Models\Setting::get('registration_payment_amount', 50.00);
        $amount = $baseAmount;

        if ($validated['payment_type'] === 'partial_user') {
            $amount = $baseAmount - 5;
        }

        if ($amount < 0.30)
            $amount = 0.30; // Minimum Viva amount

        // Create Order
        $paymentData = [
            'amount' => $amount,
            'email' => $validated['email'],
            'fullName' => $validated['name'],
            'phone' => $validated['phone'],
            'customerTrns' => 'Registration Payment - ' . $validated['name'],
            'merchantTrns' => 'Registration for ' . $validated['email'],
        ];

        $paymentOrder = $this->vivaPayment->createPaymentOrder($paymentData);

        if (!$paymentOrder || !isset($paymentOrder['orderCode'])) {
            return redirect()->back()->withInput()->with('error', 'Failed to initiate payment. Please check your details and try again.');
        }

        // Create Payment Record
        $payment = Payment::create([
            'order_code' => $paymentOrder['orderCode'],
            'transaction_id' => $paymentOrder['transactionId'] ?? null,
            'amount' => $amount,
            'currency' => 'EUR',
            'status' => 'pending',
            'customer_trns' => $paymentData['customerTrns'],
            'merchant_trns' => $paymentData['merchantTrns'],
        ]);

        // Store Session Data (including temp file paths and NON-hashed password)
        // Store Session Data (including temp file paths and NON-hashed password)
        // We must exclude the file objects from $validated as they cannot be serialized
        $sessionData = $validated;
        unset($sessionData['document_dashboard']);
        unset($sessionData['document_identity']);
        unset($sessionData['document_payment_receipt']);

        session([
            'pending_registration' => array_merge($sessionData, [
                'document_dashboard_path' => $documentDashboardPath,
                'document_identity_path' => $documentIdentityPath,
                'document_payment_receipt_path' => $documentPaymentReceiptPath,
                'password' => $validated['password'],
            ])
        ]);

        // Redirect to Viva Checkout
        return redirect($paymentOrder['checkoutUrl']);
    }

    private function uploadFile($request, $key, $folder, $prefix)
    {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $fileName = time() . '_' . $prefix . '_' . $file->getClientOriginalName();
            // Ensure folder exists
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }
            $file->move(public_path($folder), $fileName);
            return $folder . '/' . $fileName;
        }
        return null;
    }
}
