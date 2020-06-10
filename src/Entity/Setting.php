<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(collectionOperations={}, itemOperations={"get", "put"})
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="setting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\NotNull
     * @Assert\Range(min = 1, max = 28, notInRangeMessage = "You must be between {{ min }} and {{ max }}")
     * @ORM\Column(type="smallint", options={"default": 1})
     */
    private $payday = 1;

    /**
     * @Assert\GreaterThanOrEqual(2)
     * @Assert\NotNull
     * @ORM\Column(type="smallint", options={"default": 12})
     */
    private $months = 12;

    /**
     * @Assert\NotNull
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $provisioning = false;

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     * @throws \Exception
     */
    public function setUser(User $user): self
    {
        if ($this->user !== null) {
            throw new \Exception('user already defined');
        }
        $this->user = $user;
        return $this;
    }

    public function getPayday(): ?int
    {
        return $this->payday;
    }

    public function setPayday(int $payday): self
    {
        $this->payday = $payday;
        return $this;
    }

    public function getMonths(): ?int
    {
        return $this->months;
    }

    public function setMonths(int $months): self
    {
        $this->months = $months;
        return $this;
    }

    public function getProvisioning(): ?bool
    {
        return $this->provisioning;
    }

    public function setProvisioning(bool $provisioning): self
    {
        $this->provisioning = $provisioning;
        return $this;
    }
}
