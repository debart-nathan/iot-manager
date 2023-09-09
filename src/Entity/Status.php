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




    /**
     * Get the value of status_name
     */ 
    public function getStatusName(): ?string
    {
        return $this->status_name;
    }

    /**
     * Set the value of status_name
     *
     * @return  self
     */ 
    public function setStatusName(string $status_name)
    {
        $this->status_name = $status_name;

        return $this;
    }

    /**
     * Get the value of status_category
     */ 
    public function getCategory()
    {
        return $this->status_category;
    }

    /**
     * Set the value of status_category
     *
     * @return  self
     */ 
    public function setCategory($status_category)
    {
        $this->status_category = $status_category;

        return $this;
    }
}
