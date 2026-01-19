<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show all registrations.
     */
    public function registrations()
    {
        // Check if user is admin
        if (Auth::user()->user_type !== 0) {
            abort(403, 'Unauthorized access.');
        }

        $registrations = User::where('user_type', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.registrations', compact('registrations'));
    }
}
