<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Interfaces\RelatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get", "put"},
 *     normalizationContext = {"groups" = {"read"}},
 *     denormalizationContext = {"groups" = {"write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category implements RelatedUserInterface
{
    /**
     * @Groups({"read"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
