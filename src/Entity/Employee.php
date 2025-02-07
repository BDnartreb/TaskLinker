<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

//NEW
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
// ENDNEW
use Doctrine\ORM\Mapping as ORM;
use Symfony\component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet Email')]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $recruitmentDate = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'employees', orphanRemoval: true)]
    private Collection $projects;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    private ?ContractStatus $contractStatus = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRecruitmentDate(): ?\DateTimeInterface
    {
        return $this->recruitmentDate;
    }

    public function setRecruitmentDate(\DateTimeInterface $recruitmentDate): static
    {
        $this->recruitmentDate = $recruitmentDate;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addEmployees($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeEmployees($this);
        }

        return $this;
    }

    public function getContractStatus(): ?contractstatus
    {
        return $this->contractStatus;
    }

    public function setContractStatus(?contractstatus $contractStatus): static
    {
        $this->contractStatus = $contractStatus;

        return $this;
    }

 /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

 /**
     * @var string The hashed password
     * La contrainte Regex valide que le mot de passe :
     * * contient au moins un chiffre
     * * contient au moins une lettre en minuscule
     * * contient au moins une lettre en majuscule
     * * contient au moins un caractère spécial qui n'est pas un espace
     * * fait entre 8 et 32 caractères de long
     */

    //#[Assert\NotCompromisedPassword()]
    //#[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)]
    //#[Assert\Regex('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,32}$/')]
    #[ORM\Column]
    private ?string $password = null;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getInitials(): string
    {
        return substr($this->firstName, 0, 1) . substr($this->lastName, 0, 1);
    }

    public function isAdmin(): bool 
    {
        return in_array('ROLE_ADMIN', $this->roles);//true if 'ROLE_ADMIN' is present in arrays roles
    }

    public function setAdmin(bool $admin): static 
    {
        $this->roles = $admin ? ['ROLE_ADMIN'] : [];// if $admin true set 'ROLE_ADMIN', else ''

        return $this;
    }
}
