<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: UserSeller::class, mappedBy: 'region')]
    private Collection $userSellers;

    public function __construct()
    {
        $this->userSellers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, UserSeller>
     */
    public function getUserSellers(): Collection
    {
        return $this->userSellers;
    }

    public function addUserSeller(UserSeller $userSeller): static
    {
        if (!$this->userSellers->contains($userSeller)) {
            $this->userSellers->add($userSeller);
            $userSeller->setRegion($this);
        }

        return $this;
    }

    public function removeUserSeller(UserSeller $userSeller): static
    {
        if ($this->userSellers->removeElement($userSeller)) {
            // set the owning side to null (unless already changed)
            if ($userSeller->getRegion() === $this) {
                $userSeller->setRegion(null);
            }
        }

        return $this;
    }
}
