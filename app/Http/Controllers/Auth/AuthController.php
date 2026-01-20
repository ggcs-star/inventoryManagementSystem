<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated());

        return redirect()->route('otp.notice');
    }


   public function showLogin()
{
  if (auth()->check()) {
    return auth()->user()->isAdmin()
        ? redirect('/admin/dashboard')
        : redirect('/user/dashboard');
}

    return view('auth.login');
}


   public function login(LoginRequest $request)
{
    $result = $this->authService->login($request->validated());

    if ($result === 'blocked') {
        return back()->withErrors([
            'email' => 'Your account is blocked.',
        ]);
    }

    if ($result === false) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

  if (!auth()->user()->hasVerifiedEmail()) {
    return redirect()->route('otp.notice');
}

return auth()->user()->isAdmin()
    ? redirect('/admin/dashboard')->with('success', 'Admin login successful.')
    : redirect('/user/dashboard')->with('success', 'Login successful. Welcome back!');
}

  public function logout()
{
    $this->authService->logout();

    return redirect('/login')
        ->with('success', 'You have been logged out successfully.');
}

}
