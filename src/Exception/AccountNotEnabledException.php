<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-07-16
 * Time: 00:40
 */

namespace App\Exception;


use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountNotEnabledException extends AccountStatusException {

    /**
     * AccountNotEnabledException constructor.
     * @param string $string
     */
    public function __construct() {
        parent::__construct('Your account is not enabled.');
    }


}