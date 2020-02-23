<?php
declare(strict_types=1);

namespace App\Product\FormType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class UpdateProductType extends ProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('code');
        $builder->add('submit', SubmitType::class, ['label' => 'Update']);
    }
}
