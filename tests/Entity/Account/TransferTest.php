<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/03/2020
 * Time: 15:11
 */

namespace App\Tests\Entity\Account;


use App\Dto\TaxPayer\TaxPayerData;
use App\Entity\Account\AssetAccount;
use App\Entity\Account\Transfer;
use App\Entity\Category\Category;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Revenue;
use App\Entity\User\User;
use App\Form\Type\GenderType;
use DateTime;
use PHPUnit\Framework\TestCase;

class TransferTest extends TestCase {
    /**
     * @var User
     */
    private User $user;

    /**
     * @var AssetAccount
     */
    private AssetAccount $account1;

    /**
     * @var AssetAccount
     */
    private AssetAccount $account2;

    public function __construct($name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->user = new User(true, 'Test', GenderType::MALE, 'test@test.test', null, new DateTime(), []);
        $this->account1 = new AssetAccount($this->user, 'Test Account 1', 100, new DateTime());
        $this->account2 = new AssetAccount($this->user, 'Test Account 2', 0, new DateTime());
    }

    public function testTransferMoney(): void {
        $transfer = new Transfer($this->user, 'Transfer from Acc 1 to Acc 2', 50, new DateTime(), $this->account1, $this->account2);
        $this->assertSame($this->account1->getTotal(), 50.0);
        $this->assertSame($this->account2->getTotal(), 50.0);
    }

}