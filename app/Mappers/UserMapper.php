<?php

namespace App\Mappers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserMapper
{
    /**
     * Map NewsAPI response to unified structure.
     *
     * @param array $article
     * @return array
     */
    public function mapRegisterUser(array $user): array
    {
        return [
            'name' => $user['name'] ?? '',
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'email_verified_at' => Carbon::now(),
        ];
    }
}
