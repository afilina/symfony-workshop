<?php
declare(strict_types=1);

namespace App\Product\FormType;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'CAD',
                'required' => true,
            ])
            ->add('cover', FileType::class, [
                'label' => 'Cover image',
                'mapped' => true,
                'required' => true,
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

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        // Override the upload requirement based on the provided data.
        if ($form->getViewData()->coverFileName !== '') {
            $view['cover']->vars['required'] = false;
        }
    }
}
