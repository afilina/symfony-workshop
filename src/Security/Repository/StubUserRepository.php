<?php
declare(strict_types=1);

namespace App\Security\Repository;

use App\Security\User;

final class StubUserRepository implements UserRepository
{
    public function getByEmail(string $email): User
    {
        // Generate password using bin/console security:encode-password
        $user = new User();
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$S8asXI8rvoXDGRBkn/luXA$sPmIVyaBGUOwULeLgG+/9zzhaekJds10ejaWH2GQlN8');
        $user->setEmail('me@afilina.com');

        return $user;
    }
}
