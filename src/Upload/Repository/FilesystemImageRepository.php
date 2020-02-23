<?php
declare(strict_types=1);

namespace App\Upload\Repository;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

final class FilesystemImageRepository extends ImageRepository
{
    private Filesystem $filesystem;
    private UploadConfiguration $uploadConfig;

    public function __construct(Filesystem $filesystem, UploadConfiguration $uploadConfig)
    {
        $this->filesystem = $filesystem;
        $this->uploadConfig = $uploadConfig;
    }

    public function getUrlByFileName(string $fileName): string
    {
        return $this->uploadConfig->getUploadUrl() . '/' . $fileName;
    }

    public function create(File $file): string
    {
        $uploadFileName = $this->generateUniqueId() . '.' . $file->getClientOriginalExtension();
        $file->move($this->uploadConfig->getUploadFolder(), $uploadFileName);

        return $uploadFileName;
    }
}
