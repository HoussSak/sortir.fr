<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
#[ORM\Table(name: "sorties")]
#[ORM\HasLifecycleCallbacks]


 #[Vich\Uploadable]
class Sortie
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 30)]
    private $nom;

    #[ORM\Column(type: "datetime")]
    private $dateDebut;

    #[ORM\Column(type: "integer", nullable: true)]
    private $duree;

    #[ORM\Column(type: "datetime")]
    private $dateCloture;

    #[ORM\Column(type: "text")]
    private $descriptioninfos;

    #[ORM\Column(type: "integer")]
    private $nbInscriptionsMax;


    #[Vich\UploadableField(mapping: "sortie_image", fileNameProperty:"imageName")]
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "sortiesOrganisees")]
    #[ORM\JoinColumn(nullable: false)]
    private $auteur;


    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: "sorties")]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: "sorties")]
    private $site;

    #[ORM\ManyToMany(targetEntity: User::class, fetch: "EAGER", mappedBy: "inscriptions")]
    private $usersInscrits;

    #[ORM\Column(type: "text", nullable: true)]
    private $motifAnnulation;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    public function __construct()
    {
        $this->usersInscrits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(\DateTimeInterface $dateCloture): self
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getDescriptioninfos(): ?string
    {
        return $this->descriptioninfos;
    }

    public function setDescriptioninfos(string $descriptioninfos): self
    {
        $this->descriptioninfos = $descriptioninfos;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }


    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }


    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getUsersInscrits(): Collection
    {
        return $this->usersInscrits;
    }

    public function addUsersInscrit(User $usersInscrit): self
    {
        if (!$this->usersInscrits->contains($usersInscrit)) {
            $this->usersInscrits[] = $usersInscrit;
            $usersInscrit->addInscription($this);
        }

        return $this;
    }

    public function removeUsersInscrit(User $usersInscrit): self
    {
        if ($this->usersInscrits->removeElement($usersInscrit)) {
            $usersInscrit->removeInscription($this);
        }

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): self
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->setUpdatedAt(new \DateTimeImmutable);
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }



    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
}
