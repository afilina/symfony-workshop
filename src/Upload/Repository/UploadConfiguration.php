<?php
declare(strict_types=1);

namespace App\Upload\Repository;

final class UploadConfiguration
{
    private string $uploadFolder;
    private string $uploadUrl;

    public function __construct(string $uploadFolder, string $uploadUrl)
    {
        $this->uploadFolder = $uploadFolder;
        $this->uploadUrl = $uploadUrl;
    }

    public function getUploadFolder(): string
    {
        return $this->uploadFolder;
    }

    public function getUploadUrl(): string
    {
        return $this->uploadUrl;
    }
}
