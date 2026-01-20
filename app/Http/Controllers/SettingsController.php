<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->user_type !== 0) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show settings page
     */
    public function index()
    {
        $paymentAmount = Setting::get('registration_payment_amount', 100.00);
        
        return view('admin.settings', [
            'paymentAmount' => $paymentAmount,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'registration_payment_amount' => 'required|numeric|min:0.01',
        ]);

        Setting::set(
            'registration_payment_amount',
            $request->input('registration_payment_amount'),
            'number',
            'Default payment amount for registration (in EUR)'
        );

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
