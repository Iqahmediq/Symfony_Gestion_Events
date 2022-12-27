<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $date_d = null;

    #[ORM\Column(length: 255)]
    private ?string $date_f = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_d = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_f = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?bool $valid = null;

    #[ORM\Column]
    private ?bool $archive = null;


    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $participations = [];

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getDateD(): ?string
    {
        return $this->date_d;
    }

    public function setDateD(string $date_d): self
    {
        $this->date_d = $date_d;

        return $this;
    }

    public function getDateF(): ?string
    {
        return $this->date_f;
    }

    public function setDateF(string $date_f): self
    {
        $this->date_f = $date_f;

        return $this;
    }

    public function getHeureD(): ?string
    {
        return $this->heure_d;
    }

    public function setHeureD(string $heure_d): self
    {
        $this->heure_d = $heure_d;

        return $this;
    }

    public function getHeureF(): ?string
    {
        return $this->heure_f;
    }

    public function setHeureF(string $heure_f): self
    {
        $this->heure_f = $heure_f;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

/**
* @return mixed
*/
    public function getImage(): ?string
    {
        return $this->image;
    }
/**
* @param mixed $image
*/

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getParticipations(): array
    {
        return $this->participations;
    }

    public function setParticipations(?array $participations): self
    {
        $this->participations = $participations;

        return $this;
    }


}
