<?php

namespace App\Entity;

use App\Repository\HistoStoriesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoStoriesRepository::class)]
class HistoStories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(length: 50)]
    private ?string $nom_sortie = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_sortie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heure_debut = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_limite_inscription = null;

    #[ORM\Column]
    private ?int $nb_inscriptions_max = null;

    #[ORM\Column(length: 255)]
    private ?string $infos_sortie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif_annulation = null;

    #[ORM\Column(length: 10)]
    private ?string $libelle_etat = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_organisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom_organisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $tel_organisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $email_organisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo_organisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $site_organisateur = null;

    #[ORM\Column(type: Types::JSON)]
    private array $participants = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSortie(): ?string
    {
        return $this->nom_sortie;
    }

    public function setNomSortie(string $nom_sortie): static
    {
        $this->nom_sortie = $nom_sortie;

        return $this;
    }

    public function getAdresseSortie(): ?string
    {
        return $this->adresse_sortie;
    }

    public function setAdresseSortie(string $adresse_sortie): static
    {
        $this->adresse_sortie = $adresse_sortie;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->date_heure_debut;
    }

    public function setDateHeureDebut(\DateTimeInterface $date_heure_debut): static
    {
        $this->date_heure_debut = $date_heure_debut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->date_limite_inscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $date_limite_inscription): static
    {
        $this->date_limite_inscription = $date_limite_inscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nb_inscriptions_max;
    }

    public function setNbInscriptionsMax(int $nb_inscriptions_max): static
    {
        $this->nb_inscriptions_max = $nb_inscriptions_max;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infos_sortie;
    }

    public function setInfosSortie(string $infos_sortie): static
    {
        $this->infos_sortie = $infos_sortie;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motif_annulation;
    }

    public function setMotifAnnulation(string $motif_annulation): static
    {
        $this->motif_annulation = $motif_annulation;

        return $this;
    }

    public function getLibelleEtat(): ?string
    {
        return $this->libelle_etat;
    }

    public function setLibelleEtat(string $libelle_etat): static
    {
        $this->libelle_etat = $libelle_etat;

        return $this;
    }

    public function getNomOrganisateur(): ?string
    {
        return $this->nom_organisateur;
    }

    public function setNomOrganisateur(string $nom_organisateur): static
    {
        $this->nom_organisateur = $nom_organisateur;

        return $this;
    }

    public function getPrenomOrganisateur(): ?string
    {
        return $this->prenom_organisateur;
    }

    public function setPrenomOrganisateur(string $prenom_organisateur): static
    {
        $this->prenom_organisateur = $prenom_organisateur;

        return $this;
    }

    public function getTelOrganisateur(): ?string
    {
        return $this->tel_organisateur;
    }

    public function setTelOrganisateur(string $tel_organisateur): static
    {
        $this->tel_organisateur = $tel_organisateur;

        return $this;
    }

    public function getEmailOrganisateur(): ?string
    {
        return $this->email_organisateur;
    }

    public function setEmailOrganisateur(string $email_organisateur): static
    {
        $this->email_organisateur = $email_organisateur;

        return $this;
    }

    public function getPseudoOrganisateur(): ?string
    {
        return $this->pseudo_organisateur;
    }

    public function setPseudoOrganisateur(string $pseudo_organisateur): static
    {
        $this->pseudo_organisateur = $pseudo_organisateur;

        return $this;
    }

    public function getSiteOrganisateur(): ?string
    {
        return $this->site_organisateur;
    }

    public function setSiteOrganisateur(string $site_organisateur): static
    {
        $this->site_organisateur = $site_organisateur;

        return $this;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function setParticipants(array $participants): static
    {
        $this->participants = $participants;

        return $this;
    }
}
