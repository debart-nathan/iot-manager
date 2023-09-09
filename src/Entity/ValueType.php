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


    /**
     * Get the value of value_type_name
     */ 
    public function getName()
    {
        return $this->value_type_name;
    }

    /**
     * Set the value of value_type_name
     *
     * @return  self
     */ 
    public function setName($value_type_name)
    {
        $this->value_type_name = $value_type_name;

        return $this;
    }

    /**
     * Get the value of unit
     */ 
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set the value of unit
     *
     * @return  self
     */ 
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }
}
