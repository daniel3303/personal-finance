<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 16/02/2020
 * Time: 01:52
 */

namespace App\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType {
    public const MALE = 'M';
    public const FEMALE = 'F';

    public function configureOptions(OptionsResolver $resolver) : void{
        $resolver->setDefaults([
            'choices' => [
                'Male' => self::MALE,
                'Female' => self::FEMALE,
            ],
        ]);
    }

    public function getParent() : string {
        return ChoiceType::class;
    }
}