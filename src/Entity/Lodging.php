<?php

namespace App\Entity;

use App\Repository\LodgingRepository;
use App\Traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LodgingRepository::class)]
class Lodging
{

    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $number_rooms = null;

    #[ORM\Column]
    private ?int $max_people = null;

    #[ORM\Column]
    private ?float $surface = null;

    #[ORM\Column]
    private ?float $weekly_base_price = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\ManyToOne(inversedBy: 'lodgings', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'lodgings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Criteria::class, inversedBy: 'lodgings', fetch: 'EAGER')]
    private Collection $criteria;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $image = null;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumberRooms(): ?int
    {
        return $this->number_rooms;
    }

    public function setNumberRooms(int $number_rooms): self
    {
        $this->number_rooms = $number_rooms;

        return $this;
    }

    public function getMaxPeople(): ?int
    {
        return $this->max_people;
    }

    public function setMaxPeople(int $max_people): self
    {
        $this->max_people = $max_people;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getWeeklyBasePrice(): ?float
    {
        return $this->weekly_base_price;
    }

    public function setWeeklyBasePrice(float $weekly_base_price): self
    {
        $this->weekly_base_price = $weekly_base_price;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    
    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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

    public function __toString(){
        return $this->name; // Remplacer champ par une propriété "string" de l'entité
    }

    /**
     * @return Collection<int, Criteria>
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriterion(Criteria $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria->add($criterion);
        }

        return $this;
    }

    public function removeCriterion(Criteria $criterion): self
    {
        $this->criteria->removeElement($criterion);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
