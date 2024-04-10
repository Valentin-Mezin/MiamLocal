<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LabelRepository::class)]
class Label
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: UserSeller::class, inversedBy: 'labels')]
    private Collection $userSeller;

    public function __construct()
    {
        $this->userSeller = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, UserSeller>
     */
    public function getUserSeller(): Collection
    {
        return $this->userSeller;
    }

    public function addUserSeller(UserSeller $userSeller): static
    {
        if (!$this->userSeller->contains($userSeller)) {
            $this->userSeller->add($userSeller);
        }

        return $this;
    }

    public function removeUserSeller(UserSeller $userSeller): static
    {
        $this->userSeller->removeElement($userSeller);

        return $this;
    }
}
