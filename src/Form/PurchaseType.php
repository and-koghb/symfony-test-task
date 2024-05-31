<?php

namespace App\Form;

use App\DTO\PurchaseRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', IntegerType::class)
            ->add('taxNumber', TextType::class)
            ->add('couponCode', TextType::class, ['required' => false])
            ->add('paymentProcessor', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PurchaseRequest::class,
            'csrf_protection' => false,
        ]);
    }
}
