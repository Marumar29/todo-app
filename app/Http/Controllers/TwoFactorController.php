<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    public function verify(Request $request)
    {
        $user = auth()->user();

        // Ensure MFA code is a valid 6-digit number
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        // Check if the MFA code has expired
        if (Carbon::parse(session('mfa_code_sent_at'))->addMinutes(5)->isPast()) {
            session()->forget('mfa_code'); // Clear the expired code
            session()->forget('mfa_code_sent_at'); // Clear the timestamp
            return back()->withErrors(['code' => 'The MFA code has expired. Please request a new one.']);
        }

        // Check if the MFA code matches the one sent to the user's email
        if ($request->code == session('mfa_code')) {
            // Code is correct, clear the MFA code and timestamp from session
            session()->forget('mfa_code');
            session()->forget('mfa_code_sent_at');

            // Redirect to the intended page or dashboard
            return redirect()->intended('/dashboard'); // Redirect to intended page (or wherever you want the user to go)
        }

        // If the code doesn't match, redirect back with an error message
        return back()->withErrors(['code' => 'The MFA code is incorrect.']);
    }
}
