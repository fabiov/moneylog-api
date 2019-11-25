<?php

namespace App\Doctrine;

use App\Entity\Account;
use Symfony\Component\Security\Core\Security;

/**
 * Class AccountSetOwnerListener
 * @package App\Doctrine
 */
class AccountSetOwnerListener
{
    private $security;

    /**
     * AccountSetOwnerListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Account $account
     */
    public function prePersist(Account $account)
    {
        if (!$account->getUser() && $this->security->getUser()) {
            $account->setUser($this->security->getUser());
        }
    }
}