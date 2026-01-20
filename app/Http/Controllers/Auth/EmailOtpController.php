<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;

class EmailOtpController extends Controller
{
    protected $otp;

    public function __construct(OtpService $otp)
    {
        $this->otp = $otp;
    }

    public function show()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect('/user/dashboard');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $ok = $this->otp->verify(auth()->user(), $request->otp);

        if (!$ok) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

     return auth()->user()->isAdmin()
    ? redirect('/admin/dashboard')->with('success', 'Email verified successfully.')
    : redirect('/user/dashboard')->with('success', 'Email verified successfully.');

    }

    public function resend()
    {
        $this->otp->send(auth()->user());
return back()->with('success', 'OTP has been sent to your email.');
    }
}
