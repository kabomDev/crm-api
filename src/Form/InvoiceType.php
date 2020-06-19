<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Invoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'montant',
                'divisor' => 100,
                'attr' => [
                    'placeholder' => 'montant de la facture'
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'titre de la facture'
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'label' => 'client',
                'choice_label' => 'fullName'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
