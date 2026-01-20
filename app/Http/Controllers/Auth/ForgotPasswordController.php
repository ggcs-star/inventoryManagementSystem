<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    protected $otp;

    public function __construct(OtpService $otp)
    {
        $this->otp = $otp;
    }

    public function showEmail()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $this->otp->send($user);

        session(['reset_user_id' => $user->id]);

return redirect('/reset-password')
    ->with('success', 'OTP has been sent to your email address.');
    }

    public function showReset()
    {
        return view('auth.reset-password');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail(session('reset_user_id'));

        if (!$this->otp->verify($user, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget('reset_user_id');

return redirect('/login')->with('success', 'Password reset successful. Please login with your new password.');
    }
}
