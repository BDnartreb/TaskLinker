<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?project $project = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'id')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')] //in case of employee deleting
    private ?employee $employee = null;

    #[ORM\ManyToOne(targetEntity: StatusTask::class, inversedBy: 'id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?statustask $status = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deadline = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?project
    {
        return $this->project;
    }

    public function setProject(?project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getEmployee(): ?employee
    {
        return $this->employee;
    }

    public function setEmployee(?employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStatus(): ?statustask
    {
        return $this->status;
    }

    public function setStatus(statustask $status): static
    {
        $this->status = $status;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDeadline(): ?String
    {
        return $this->deadline?->format('Y-m-d' ?? '');
    }

    public function setDeadline(?\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

}
