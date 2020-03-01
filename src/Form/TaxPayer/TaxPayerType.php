<?php

namespace App\Form\TaxPayer;

use App\Dto\TaxPayer\TaxPayerData;
use App\Entity\TaxPayer\TaxPayer;
use App\Form\Type\ImageType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxPayerType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('enabled', CheckboxType::class, [
                'label' => 'Enabled'
            ])
            ->add('photo', ImageType::class, [
                'required' => false,
                'context' => 'tax_payer'
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => TaxPayerData::class,
        ]);
    }
}
