<?php
declare(strict_types=1);

namespace App\Upload\Repository;

use Symfony\Component\HttpFoundation\File\File;

abstract class ImageRepository
{
    abstract public function getUrlByFileName(string $fileName): string;

    abstract public function create(File $file): string;

    protected static function generateUniqueId(): string
    {
        return uniqid('', true);
    }
}
