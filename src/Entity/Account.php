<?php
/**
 * @see 35 https://symfonycasts.com/screencast/api-platform-security/validator-logic
 * @see 36 https://symfonycasts.com/screencast/api-platform-security/entity-listener
 */
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\IsValidOwner;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     denormalizationContext={"groups"={"write"}},
 *     normalizationContext={"groups"={"read"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\EntityListeners({"App\Doctrine\AccountSetOwnerListener"})
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="user_account_idx", columns={"user_id", "name"})})
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @IsValidOwner()
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     */
    private $recap;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRecap(): ?bool
    {
        return $this->recap;
    }

    public function setRecap(bool $recap): self
    {
        $this->recap = $recap;

        return $this;
    }
}
