<?php

namespace App\Entity;

use App\Repository\ModuleTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleTypeRepository::class)]
class ModuleType
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $module_type_name = null;
    
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $picture_file = null;

    #[ORM\Column(type: "text")]
    private string $description;


    public function getModuleTypeName(): ?string
    {
        return $this->module_type_name;
    }

        /**
     * Set the value of module_type_name
     *
     * @return  self
     */ 
    public function setModuleTypeName($module_type_name)
    {
        $this->module_type_name = $module_type_name;

        return $this;
    }
    

    public function getPictureFile(): ?string
    {
        return $this->picture_file;
    }

    public function setPictureFile(string $picture_file): static
    {
        $this->picture_file = $picture_file;

        return $this;
    }
    

    /**
     * Get the value of description
     */ 
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description): static
    {
        $this->description = $description;

        return $this;
    }


}
