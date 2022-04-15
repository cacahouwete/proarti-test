<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RewardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ApiResource]
class Reward
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reward;

    #[ORM\OneToMany(mappedBy: 'reward', targetEntity: Donation::class)]
    private $donations;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'rewards')]
    #[ORM\JoinColumn(nullable: false)]
    private $projet;

    #[ORM\Column(type: 'integer')]
    private $rewardQuantity;

    public function __construct()
    {
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(string $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): self
    {
        if (!$this->donations->contains($donation)) {
            $this->donations[] = $donation;
            $donation->setReward($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): self
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getReward() === $this) {
                $donation->setReward(null);
            }
        }

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getRewardQuantity(): ?int
    {
        return $this->rewardQuantity;
    }

    public function setRewardQuantity(int $rewardQuantity): self
    {
        $this->rewardQuantity = $rewardQuantity;

        return $this;
    }
}
