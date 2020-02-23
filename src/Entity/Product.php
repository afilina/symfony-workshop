<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class Product
{
    /**
     * @Assert\Length(min=6, max=6)
     * @var string
     */
    public $code;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     * @var string
     */
    public $name;
    /**
     * A price is represented in cents. 5.25$ will be stored as 525.
     * This avoids floating point calculation issues (typically resulting in off-by-a-penny).
     *
     * @Assert\GreaterThanOrEqual(0)
     * @var int
     */
    public $price;
    /**
     * Unique name of the uploaded file. The actual file resides on the server.
     *
     * @var string
     */
    public $coverFileName;
    /**
     * This is only used to extract the file data from the form.
     * We don't actually persist this field.
     * @Assert\File(maxSize="1m", mimeTypes={"image/jpeg","image/png"})
     * @var File
     */
    public $cover;
    /**
     * URL of the image. This should not be persisted as it can be
     * extrapolated from the `coverFileName`.
     *
     * @var string
     */
    public $coverUrl;
}
