<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 07/03/2020
 * Time: 15:11
 */

namespace App\Tests\Entity\Account;


use App\Entity\Account\AssetAccount;
use App\Entity\Account\Transfer;
use App\Entity\User\User;
use App\Form\Type\GenderType;
use DateTime;
use PHPUnit\Framework\TestCase;

class AssetAccountTest extends TestCase {
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

    public function testCreate(): void {
        $account = $this->account1;

        $this->assertSame($account->getUser(), $this->user);
        $this->assertSame($account->getTotal(), 100.0);
        $this->assertSame($account->getName(), 'Test Account 1');
        $this->assertNull($account->getIban());
        $this->assertNull($account->getNotes());
        $account->setIban('123456789');
        $this->assertSame($account->getIban(), '123456789');
        $account->setNotes('Testing notes');
        $this->assertSame($account->getNotes(), 'Testing notes');
    }

    public function testTransferMoney() :void{
        $transfer = new Transfer($this->user, 'Transfer from Acc 1 to Acc 2', 50, new DateTime(), $this->account1, $this->account2);
        $this->assertSame($this->account1->getTotal(), 50.0);
        $this->assertSame($this->account2->getTotal(), 50.0);
    }

}