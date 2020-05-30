<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(collectionOperations={}, itemOperations={"get", "put"})
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="setting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     */
    private $payday;

    /**
     * @ORM\Column(type="smallint")
     */
    private $months;

    /**
     * @ORM\Column(type="boolean")
     */
    private $provisioning;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
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
