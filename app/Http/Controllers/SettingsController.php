<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->user_type != "0") {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show settings page
     */
    public function index()
    {
        $paymentAmount = Setting::get('registration_payment_amount', 100.00);
        $companyName = Setting::get('company_name', 'HOLA TAXI IRELAND LIMITED');
        $companyLogo = Setting::get('company_logo_path');
        $directorName = Setting::get('director_name', 'Kamal S Gill');
        $directorSignature = Setting::get('director_signature_path');

        return view('admin.settings', compact('paymentAmount', 'companyName', 'companyLogo', 'directorName', 'directorSignature'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'registration_payment_amount' => 'required|numeric|min:0.01',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'director_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'director_name' => 'required|string|max:255',
        ]);

        Setting::set(
            'registration_payment_amount',
            $request->input('registration_payment_amount'),
            'number',
            'Default payment amount for registration (in EUR)'
        );

        Setting::set(
            'company_name',
            $request->input('company_name'),
            'string',
            'Company name shown on share certificates'
        );

        Setting::set(
            'director_name',
            $request->input('director_name'),
            'string',
            'Director name shown on share certificates'
        );

        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/logos'), $logoName);

            Setting::set(
                'company_logo_path',
                'uploads/logos/' . $logoName,
                'string',
                'Path to the company logo used on certificates'
            );
        }

        if ($request->hasFile('director_signature')) {
            $signature = $request->file('director_signature');
            $sigName = 'sig_' . time() . '.' . $signature->getClientOriginalExtension();
            $signature->move(public_path('uploads/signatures'), $sigName);

            Setting::set(
                'director_signature_path',
                'uploads/signatures/' . $sigName,
                'string',
                'Path to the director signature image used on certificates'
            );
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
