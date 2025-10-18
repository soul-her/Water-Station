<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * This method builds the form, defining the fields that Twig will access.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Now Twig can correctly reference form.name, form.contact, etc.
            ->add('name', TextType::class, [
                'label' => 'Customer Name',
                'attr' => [
                    'placeholder' => 'Enter customer name',
                ],
            ])
            ->add('contact', TextType::class, [
                'label' => 'Contact Number/Email',
                'attr' => [
                    'placeholder' => 'Enter phone number or email',
                ],
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Shipping Address',
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Enter full shipping address',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Order Status',
                'choices' => [
                    'Pending' => 'Pending',
                    'Processing' => 'Processing',
                    'Shipped' => 'Shipped',
                    'Delivered' => 'Delivered',
                    'Cancelled' => 'Cancelled',
                ],
                'help' => 'Select the current status of the order.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
