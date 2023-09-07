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


    public function getModuleTypeName(): ?string
    {
        return $this->module_type_name;
    }


    public function getPrictureFile(): ?string
    {
        return $this->picture_file;
    }

    public function setPrictureFile(string $picture_file): static
    {
        $this->picture_file = $picture_file;

        return $this;
    }
}
