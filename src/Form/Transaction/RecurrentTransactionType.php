<?php

namespace App\Form\Transaction;

use App\Dto\Tag\TagData;
use App\Dto\Transaction\RecurrentTransactionData;
use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\User\User;
use App\Repository\Account\AccountRepository;
use App\Repository\Category\CategoryRepository;
use App\Repository\Tag\TagRepository;
use App\Repository\TaxPayer\TaxPayerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class RecurrentTransactionType extends AbstractType {
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        /**
         * @var RecurrentTransactionData $recurrentTransaction
         */
        $recurrentTransaction = $options['data'];

        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        $recurrentTransaction->setUser($user);

        $isEdit = $recurrentTransaction->getEntity() !== null && $recurrentTransaction->getEntity()->getId();

        $builder
            ->add('enabled', CheckboxType::class, [
                'label' => 'Enabled',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Amount',
                'help' => 'Use a positive value if you are going to receive money. Use a negative value if you are going to spend money.'
            ]);

        if ($isEdit === false) {
            $builder->add('startTime', DateType::class, [
                'label' => 'Start date',
                'widget' => 'single_text',
            ]);
        }

        $builder
            ->add('interval', DateIntervalType::class, [
                'label' => 'Interval',
            ])
            ->add('endTime', DateType::class, [
                'label' => 'Valid until',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('account', EntityType::class, [
                'label' => 'Account',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('taxPayer', EntityType::class, [
                'label' => 'Tax payer',
                'class' => TaxPayer::class,
                'query_builder' => static function (TaxPayerRepository $taxPayerRepository) {
                    return $taxPayerRepository->createQueryBuilder('tp')
                        ->where('tp.enabled = true')
                        ->orderBy('tp.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'query_builder' => static function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
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
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => RecurrentTransactionData::class,
        ]);
    }
}
