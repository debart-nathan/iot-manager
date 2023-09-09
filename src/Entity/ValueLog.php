<?php

namespace App\Entity;

use App\Repository\ValueLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValueLogRepository::class)]
class ValueLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $value_log_id = null;
    
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $data = null;
    
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $log_date = null;
    
    #[ORM\ManyToOne(targetEntity: 'ModuleTypeValue')]
    #[ORM\JoinColumn(name: 'module_type_value_id', referencedColumnName: 'module_type_value_id')]
    private ?ModuleTypeValue $module_type_value_id = null;
    
    #[ORM\ManyToOne(targetEntity: 'Module')]
    #[ORM\JoinColumn(name: 'module_id', referencedColumnName: 'module_id')]
    private ?Module $module_id = null;


    public function getId(): ?int
    {
        return $this->value_log_id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getLogDate(): ?\DateTimeInterface
    {
        return $this->log_date;
    }

    public function setLogDate(\DateTimeInterface $log_date): static
    {
        $this->log_date = $log_date;

        return $this;
    }

    public function getModuleTypeValueId(): ?ModuleTypeValue
    {
        return $this->module_type_value_id;
    }

    public function setModuleTypeValueId(ModuleTypeValue $module_type_value_id): static
    {
        $this->module_type_value_id = $module_type_value_id;

        return $this;
    }

    public function getModuleId(): ?Module
    {
        return $this->module_id;
    }

    public function setModuleId(Module $module_id): static
    {
        $this->module_id = $module_id;

        return $this;
    }
}
