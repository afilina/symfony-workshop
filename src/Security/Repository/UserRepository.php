<?php
declare(strict_types=1);

namespace App\Security\Repository;

use App\Security\User;

interface UserRepository
{
    public function getByEmail(string $email): User;
}
