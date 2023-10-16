<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        // Assign a role based on the 'administrateur' field
        $role = ($this->getAdministrateur()) ? 'ROLE_ADMIN' : 'ROLE_USER';
        $this->setRoles([$role]);
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner une adresse mail!')]
    #[Assert\Email(message: "Votre adresse mail n'est pas valide!")]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/",
        message: "Le mot de passe doit contenir au moins 12 caractères avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial."
     )]
    private $password;

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un nom!')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Minimum 2 caractères svp!',
        maxMessage: 'Maximum 50 caractères svp!'
    )]
    private $nom;

    #[ORM\Column(type: 'string', length: 40)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un prénom!')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Minimum 2 caractères svp!',
        maxMessage: 'Maximum 50 caractères svp!'
    )]
    private $prenom;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $administrateur;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $actif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\Column(type: 'string', length: 30, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un pseudo!')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Minimum 2 caractères svp!',
        maxMessage: 'Maximum 50 caractères svp!'
    )]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un numéro de téléphone!')]
    #[Assert\Length(
        min : 10,
        minMessage : "Le numéro de téléphone doit contenir au moins {{ limit }} chiffres",
    )]
    #[Assert\Regex(
        pattern: '/^0[1-9]/',
        message: 'Le numéro de téléphone doit commencer par un chiffre entre 01 et 09',
    )]
    private $telephone;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'users')]
    private $site;

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'auteur')]
    private $sortiesOrganisees;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'usersInscrits')]
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
            $this->sortiesOrganisees[] = $sortiesOrganisee;
            $sortiesOrganisee->setAuteur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
            if ($sortiesOrganisee->getAuteur() === $this) {
                $sortiesOrganisee->setAuteur(null);
            }
        }

        return $this;
    }

    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Sortie $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
        }

        return $this;
    }

    public function removeInscription(Sortie $inscription): self
    {
        $this->inscriptions->removeElement($inscription);

        return $this;
    }
}
