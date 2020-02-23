<?php
declare(strict_types=1);

namespace App\Email\Composer;

use App\Email\Value\ResetPassword;

interface EmailComposer
{
    public function resetPassword(ResetPassword $resetPassword): void;
}
