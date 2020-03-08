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

class AssetAccountTest extends TestCase {
    /**
     * @var User
     */
    private User $user;

    /**
     * @var AssetAccount
     */
    private AssetAccount $account1;

    public function __construct($name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->user = new User(true, 'Test', GenderType::MALE, 'test@test.test', null, new DateTime(), [], 'pt', 'Europe/Lisbon', 'EUR');
        $this->account1 = new AssetAccount($this->user, 'Test Account 1', 100, new DateTime());
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

}