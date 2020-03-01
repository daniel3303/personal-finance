<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-07-16
 * Time: 00:35
 */

namespace App\Security;


use App\Entity\User\User;
use App\Exception\AccountNotEnabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface {
    public function checkPreAuth(UserInterface $user) :void {
        if(!$user instanceof User){
            return;
        }

        if($user->isEnabled() === false){
            throw new AccountNotEnabledException();
        }


    }

    public function checkPostAuth(UserInterface $user):void {

    }


}