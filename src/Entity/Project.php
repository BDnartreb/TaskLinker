<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $archived = null;

    /**
     * @var Collection<int, employee>
     */
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
   // #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')] //in case of employee deleting
    private Collection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->archived = 0;
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

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection<int, employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployees(employee $employees): static
    {
        if (!$this->employees->contains($employees)) {
            $this->employees->add($employees);
        }

        return $this;
    }

    public function removeEmployees(employee $employees): static
    {
        $this->employees->removeElement($employees);

        return $this;
    }
}
