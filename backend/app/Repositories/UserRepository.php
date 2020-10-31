<?php

declare(strict_types=1);

namespace App\Repositories;

use App\User;

interface UserRepository
{
    function userFromEmail(string $email): ?User;
}
