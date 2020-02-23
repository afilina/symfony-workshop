<?php
declare(strict_types=1);

namespace App\Entity;

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
}
