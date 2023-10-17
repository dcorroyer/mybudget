<?php

declare(strict_types=1);

namespace App\Form\Charge;

use App\Entity\Charge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('date')
            ->add('chargeLines', CollectionType::class, [
                'entry_type' => ChargeLineFormType::class,
                'entry_options' => ['label' => false, 'mapped' => false],
                'allow_add' => true,
                'by_reference' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Charge::class,
        ]);
    }
}
