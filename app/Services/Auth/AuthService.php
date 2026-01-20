<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use App\Services\LocationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function register(array $data)
    {
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'active',
        ]);

        Auth::login($user);
        app(OtpService::class)->send($user);

        return $user;
    }

    public function login(array $credentials)
    {
        $email = strtolower($credentials['email']);

        $user = $this->users->findByEmail($email);

        if (!$user) {
            return false;
        }

        if ($user->status !== 'active') {
            return 'blocked';
        }

        if (!Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ])) {
            return false;
        }

        // ✅ IP + User Agent
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // ✅ Country + City via service
        $location = app(LocationService::class)->getLocationByIp($ip);

        // ✅ Save everything
        $this->users->updateLastLogin(
            $user->id,
            $ip,
            $userAgent,
            $location['country'],
            $location['city']
        );

        request()->session()->regenerate();

        return $user;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
