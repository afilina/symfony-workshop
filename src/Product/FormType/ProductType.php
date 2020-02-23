<?php
declare(strict_types=1);

namespace App\Product\FormType;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;

abstract class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length(['min' => 6, 'max' => 6])
                ],

            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length(['max' => 200])
                ],

            ])
            ->add('price', MoneyType::class, [
                'currency' => 'CAD',
                'required' => true,
                'constraints' => [
                    new GreaterThanOrEqual(0)
                ],
            ])
        ;

        $builder->get('price')
            ->addModelTransformer(new CallbackTransformer(
                function ($modelPrice) {
                    return $modelPrice / 100;
                },
                function ($formPrice) {
                    return (int) ($formPrice * 100);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
