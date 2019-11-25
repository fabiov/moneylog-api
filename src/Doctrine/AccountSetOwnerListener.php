<?php

namespace App\Doctrine;

use App\Entity\Account;
use Symfony\Component\Security\Core\Security;

class AccountSetOwnerListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Account $account)
    {
        if ($account->getUser()) {
            return;
        }
        if ($this->security->getUser()) {
            $account->setUser($this->security->getUser());
        }
    }
}