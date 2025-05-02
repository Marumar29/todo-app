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

        // Check if the MFA code matches the one sent to the user's email
        if ($request->code == session('mfa_code')) {
            // Optional: Check if the code was sent within a specific time window (e.g., 5 minutes)
            if (Carbon::parse(session('mfa_code_sent_at'))->addMinutes(5)->isPast()) {
                session()->forget('mfa_code'); // Clear the expired code
                return back()->withErrors(['code' => 'The MFA code has expired.']);
            }

            // Code is correct, proceed to authenticate the user
            session()->forget('mfa_code'); // Clear the MFA code from session
            session()->forget('mfa_code_sent_at'); // Clear the time when the code was sent

            return redirect()->intended('/dashboard'); // Redirect to intended page (or wherever you want the user to go)
        }

        // If the code doesn't match, redirect back with an error message
        return back()->withErrors(['code' => 'The MFA code is incorrect.']);
    }
}
