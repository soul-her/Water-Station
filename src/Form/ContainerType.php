<?php

namespace App\Form;

use App\Entity\Container;
use App\Entity\Order;
use App\Entity\Riders;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parentOrder', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
            ])
            ->add('rider', EntityType::class, [
                'class' => Riders::class,
                'choice_label' => 'id',
            ])
            ->add('orders', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Container::class,
        ]);
    }
}
