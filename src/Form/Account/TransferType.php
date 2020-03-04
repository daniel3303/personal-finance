<?php

namespace App\Form\Account;

use App\Dto\Account\AssetAccountData;
use App\Dto\Account\TransferData;
use App\Entity\Account\Account;
use App\Entity\Tag\Tag;
use App\Entity\User\User;
use App\Repository\Account\AccountRepository;
use App\Repository\Tag\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TransferType extends AbstractType {
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) :void {
        /**
         * @var TransferData $transfer
         */
        $transfer = $options['data'];

        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        $transfer->setUser($user);

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Amount',
                'required' => false
            ])
            ->add('time', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text'
            ])
            ->add('source', EntityType::class, [
                'label' => 'Source',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('target', EntityType::class, [
                'label' => 'Target',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class,
                'query_builder' => static function (TagRepository $tagRepository) {
                    return $tagRepository->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) :void {
        $resolver->setDefaults([
            'data_class' => TransferData::class,
        ]);
    }
}
