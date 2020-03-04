<?php

namespace App\Form\Category;

use App\Dto\Category\CategoryData;
use App\Dto\Tag\TagData;
use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CategoryType extends AbstractType {
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        /**
         * @var CategoryData $category
         */
        $category = $options['data'];

        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        $category->setUser($user);
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => CategoryData::class,
        ]);
    }
}
