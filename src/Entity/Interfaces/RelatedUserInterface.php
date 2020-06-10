<?php

namespace App\Entity\Interfaces;

use App\Entity\User;

interface RelatedUserInterface
{
    public function setUser(User $user);

    public function getUser(): ?User;
}
