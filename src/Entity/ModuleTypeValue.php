<?php

namespace App\Entity;

use App\Repository\ModuleTypeValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleTypeValueRepository::class)]
class ModuleTypeValue
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $module_type_value_id = null;
    
    #[ORM\ManyToOne(targetEntity: 'ModuleType')]
    #[ORM\JoinColumn(name: 'module_type_name', referencedColumnName: 'module_type_name')]
    private ?ModuleType $module_type_name = null;
    
    #[ORM\ManyToOne(targetEntity: 'ValueType')]
    #[ORM\JoinColumn(name: 'value_type_name', referencedColumnName: 'value_type_name')]
    private ?ValueType $value_type_name = null;
    
    public function getModuleTypeValueId(): ?int
    {
        return $this->module_type_value_id;
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

    public function getValueTypeName(): ?string
    {
        return $this->value_type_name;
    }

    public function setValueTypeName(string $value_type_name): static
    {
        $this->value_type_name = $value_type_name;

        return $this;
    }
}
