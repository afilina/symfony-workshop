<?php
declare(strict_types=1);

namespace App\EventListener;

use Assert\Assert;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class UserPreferences
{
    private string $locale;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->locale = $parameterBag->get('default_locale');
    }

    public function setLocale(string $locale): void
    {
        Assert::that($locale)
            ->length(2);

        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
