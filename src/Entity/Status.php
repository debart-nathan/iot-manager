<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $status_name = null;
    
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $status_category = null;


    public function getStatusName(): ?string
    {
        return $this->status_name;
    }


    public function getStatusCategory(): ?string
    {
        return $this->status_category;
    }

}
