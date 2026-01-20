<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    // ✅ IP + USER AGENT + TIME
    public function updateLastLogin(
        int $userId,
        string $ip,
        ?string $userAgent
    ) {
        return User::where('id', $userId)->update([
            'last_login_ip' => $ip,
            'last_login_user_agent' => $userAgent,
            'last_login_at' => now(),
        ]);
    }
}
