<?php

namespace App\Entity;

use App\Repository\ModuleTypeValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleTypeValueRepository::class)]
class ModuleTypeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
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

    public function getModuleTypeName(): ?ModuleType
    {
        return $this->module_type_name;
    }

    public function setModuleTypeName(ModuleType $module_type_name): static
    {
        $this->module_type_name = $module_type_name;

        return $this;
    }

    public function getValueTypeName(): ?ValueType
    {
        return $this->value_type_name;
    }

    public function setValueTypeName(ValueType $value_type_name): static
    {
        $this->value_type_name = $value_type_name;

        return $this;
    }
}
