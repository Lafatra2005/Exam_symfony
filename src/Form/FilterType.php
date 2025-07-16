<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Électronique' => 'electronics',
                    'Vêtements' => 'clothing',
                    'Alimentation' => 'food'
                ],
                'required' => false,
                'placeholder' => 'Toutes catégories',
                'label' => false
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => false
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => false
            ])
            ->add('filter', SubmitType::class, ['label' => 'Filtrer'])
            ->add('export', SubmitType::class, ['label' => 'Exporter CSV']);
    }
}
