<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/03/2020
 * Time: 00:01
 */

namespace App\Form\Filter;


use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) :void {
        $builder
            ->add('name', Filters\TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label' => 'Name',
            ])
            ->add('email', Filters\TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label' => 'Email',
            ])
            ->add('gender', Filters\ChoiceFilterType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'M',
                    'Female' => 'F',
                ]
            ])
            ->add('creationTime', Filters\DateRangeFilterType::class, [
                'label' => 'Registration date',
                'left_date_options' => [
                    'label' => 'After',
                    'widget' => 'single_text',
                ],
                'right_date_options' => [
                    'label' => 'Before',
                    'widget' => 'single_text',
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver):void {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}