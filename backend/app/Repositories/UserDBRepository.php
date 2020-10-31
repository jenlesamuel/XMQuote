<?php

declare(strict_types=1);

namespace App\Repositories;

use App\User;

class UserDBRepository implements UserRepository
{
    public function userFromEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return null;
        }

        return $user;
    }
}
