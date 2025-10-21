<?php

namespace App\Form;

use App\Entity\Container;
use App\Entity\Order;
use App\Entity\Rider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Container1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id')
            ->add('status')
            ->add('pickup_time', null, [
                'widget' => 'single_text',
            ])
            ->add('delivered_at', null, [
                'widget' => 'single_text',
            ])
            ->add('order', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
            ])
            ->add('rider', EntityType::class, [
                'class' => Rider::class,
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
