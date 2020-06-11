<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Interfaces\RelatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={"get", "put"},
 *     normalizationContext = {"groups" = {"read"}},
 *     denormalizationContext = {"groups" = {"write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account implements RelatedUserInterface
{
    /**
     * @Groups({"read"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Account name must be at least {{ limit }} characters long",
     *      maxMessage = "Account name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotNull
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     */
    private $recap;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
