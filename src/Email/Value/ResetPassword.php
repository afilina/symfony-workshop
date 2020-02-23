<?php
declare(strict_types=1);

namespace App\Email\Value;

final class ResetPassword
{
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
