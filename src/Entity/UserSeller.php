<?php

namespace App\Entity;

use App\Repository\UserSellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSellerRepository::class)]
class UserSeller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $companyName = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $UpdatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'userSellers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: MediaSeller::class, mappedBy: 'userSeller')]
    private Collection $mediaSellers;

    #[ORM\ManyToMany(targetEntity: Label::class, mappedBy: 'userSeller')]
    private Collection $labels;

    #[ORM\OneToOne(inversedBy: 'userSeller', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $adress = null;

    public function __construct()
    {
        $this->mediaSellers = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): static
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, MediaSeller>
     */
    public function getMediaSellers(): Collection
    {
        return $this->mediaSellers;
    }

    public function addMediaSeller(MediaSeller $mediaSeller): static
    {
        if (!$this->mediaSellers->contains($mediaSeller)) {
            $this->mediaSellers->add($mediaSeller);
            $mediaSeller->setUserSeller($this);
        }

        return $this;
    }

    public function removeMediaSeller(MediaSeller $mediaSeller): static
    {
        if ($this->mediaSellers->removeElement($mediaSeller)) {
            // set the owning side to null (unless already changed)
            if ($mediaSeller->getUserSeller() === $this) {
                $mediaSeller->setUserSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Label>
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): static
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
            $label->addUserSeller($this);
        }

        return $this;
    }

    public function removeLabel(Label $label): static
    {
        if ($this->labels->removeElement($label)) {
            $label->removeUserSeller($this);
        }

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(Adress $adress): static
    {
        // set the owning side of the relation if necessary
        if ($adress->getUserSeller() !== $this) {
            $adress->setUserSeller($this);
        }

        $this->adress = $adress;

        return $this;
    }
}
