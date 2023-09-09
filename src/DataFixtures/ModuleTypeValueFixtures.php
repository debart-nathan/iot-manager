<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ModuleTypeValue;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ModuleTypeValueFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Create ModuleTypeValue for 'eyes' with 'comptageDePersonnes', 'latitude', 'longitude'
        $this->createModuleTypeValue($manager, 'eyes', ['comptageDePersonnes', 'latitude', 'longitude']);

        // Create ModuleTypeValue for 'gps' with 'latitude', 'longitude', 'vitesse'
        $this->createModuleTypeValue($manager, 'gps', ['latitude', 'longitude', 'vitesse']);

        // Create ModuleTypeValue for 'odometre' with 'distanceParourue'
        $this->createModuleTypeValue($manager, 'odometre', ['distanceParcourue']);

        $manager->flush();
    }

    private function createModuleTypeValue(ObjectManager $manager, string $moduleTypeName, array $valueTypeNames)
    {
        $moduleType = $this->getReference($moduleTypeName);
        foreach ($valueTypeNames as $valueTypeName) {
            $valueType = $this->getReference($valueTypeName);
            $moduleTypeValue = new ModuleTypeValue();
            $moduleTypeValue->setModuleType($moduleType);
            $moduleTypeValue->setValueType($valueType);
            $manager->persist($moduleTypeValue);
        }
    }

    public function getDependencies()
    {
        return [
            ModuleTypeFixtures::class,
            ValueTypeFixtures::class,
        ];
    }
}
