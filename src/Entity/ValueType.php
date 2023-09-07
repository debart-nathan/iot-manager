<?php

namespace App\Entity;

use App\Repository\ValueTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValueTypeRepository::class)]
class ValueType
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $value_type_name = null;
    
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $unit = null;

    public function getValueTypeName(): ?string
    {
        return $this->value_type_name;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
