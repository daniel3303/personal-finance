<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/03/2020
 * Time: 15:11
 */

namespace App\Tests\Entity\Account;


use App\Entity\Account\AssetAccount;
use App\Entity\Category\Category;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Revenue;
use App\Entity\User\User;
use App\Form\Type\GenderType;
use DateTime;
use PHPUnit\Framework\TestCase;

class ExpenseTest extends TestCase {
    /**
     * @var User
     */
    private User $user;

    /**
     * @var AssetAccount
     */
    private AssetAccount $account;

    /**
     * @var TaxPayer
     */
    private TaxPayer $taxPayer;

    public function __construct($name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->user = new User(true, 'Test', GenderType::MALE, 'test@test.test', null, new DateTime(), []);
        $this->account = new AssetAccount($this->user, 'Test Account 1', 100, new DateTime());
        $this->taxPayer = new TaxPayer($this->user, true, 'Test Tax Payer');
    }

    public function testSpendingMoney(): void {
        $transaction = new Expense($this->user, -10, new DateTime(), $this->account, $this->taxPayer, new Category($this->user, 'Test'));
        $this->assertSame($this->account->getTotal(), 90.0);
        $this->assertSame($this->taxPayer->getTotal(), -10.0);
        $this->assertSame($transaction->getAccount(), $this->account);
        $this->assertSame($transaction->getTaxPayer(), $this->taxPayer);
    }

}