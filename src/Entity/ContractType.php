<?php

namespace App\Entity;

use App\Repository\ContractTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractTypeRepository::class)]
class ContractType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contractType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractTYpe): static
    {
        $this->contractType = $contractTYpe;

        return $this;
    }
}
