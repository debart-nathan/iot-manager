<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Module;
use App\Repository\ModuleRepository;

class ModuleCountService
{
    private $moduleRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->moduleRepository = $em->getRepository(Module::class);
    }

    public function getCounts()
    {
        $counts = [];
        $counts['advertisement'] = $this->moduleRepository->countByStatusCategory('Avertissement');
        $counts['critical'] = $this->moduleRepository->countByStatusCategory('Critique');

        return $counts;
    }
}
