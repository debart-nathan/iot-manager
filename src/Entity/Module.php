<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $module_id = null;

#[ORM\Column(type: 'string', length: 255)]
private ?string $module_name = null;

#[ORM\Column(type: 'string', length: 255)]
private ?string $reference_code = null;

#[ORM\Column(type: 'string', length: 255)]
private ?string $model = null;

#[ORM\Column(type: 'datetime')]
private ?\DateTimeInterface $activation_date = null;

#[ORM\ManyToOne(targetEntity: 'ModuleType')]
#[ORM\JoinColumn(name: 'module_type_name', referencedColumnName: 'module_type_name')]
private ?ModuleType $module_type_name = null;

#[ORM\ManyToOne(targetEntity: 'Status')]
#[ORM\JoinColumn(name: 'status_name', referencedColumnName: 'status_name')]
private ?Status $status_name = null;

#[ORM\Column(type: 'string', length: 255)]
private ?string $status_message = null;


    public function getModuleId(): ?int
    {
        return $this->module_id;
    }

    public function getModuleName(): ?string
    {
        return $this->module_name;
    }

    public function setModuleName(string $module_name): static
    {
        $this->module_name = $module_name;

        return $this;
    }

    public function getReferenceCode(): ?string
    {
        return $this->reference_code;
    }

    public function setReferenceCode(string $reference_code): static
    {
        $this->reference_code = $reference_code;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getActivationDate(): ?\DateTimeInterface
    {
        return $this->activation_date;
    }

    public function setActivationDate(\DateTimeInterface $activation_date): static
    {
        $this->activation_date = $activation_date;

        return $this;
    }

    public function getModuleTypeName(): ?string
    {
        return $this->module_type_name;
    }

    public function setModuleTypeName(string $module_type_name): static
    {
        $this->module_type_name = $module_type_name;

        return $this;
    }

    public function getStatusName(): ?Status
    {
        return $this->status_name;
    }

    public function setStatusName(Status $status_name): static
    {
        $this->status_name = $status_name;

        return $this;
    }

    public function getStatusMessage(): ?string
    {
        return $this->status_message;
    }

    public function setStatusMessage(?string $status_message): static
    {
        $this->status_message = $status_message;

        return $this;
    }
}
