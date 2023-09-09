<?php

namespace App\DataFixtures;

use App\Entity\ValueType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ValueTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $comptageDePersonnes = new ValueType();
        $comptageDePersonnes->setValueTypeName('ComptageDePersonnes');
        $comptageDePersonnes->setUnit('');
        $manager->persist($comptageDePersonnes);
        $this->addReference('comptageDePersonnes', $comptageDePersonnes);
    
        $latitude = new ValueType();
        $latitude->setValueTypeName('Latitude');
        $latitude->setUnit('°');
        $manager->persist($latitude);
        $this->addReference('latitude', $latitude);
    
        $longitude = new ValueType();
        $longitude->setValueTypeName('Longitude');
        $longitude->setUnit('°');
        $manager->persist($longitude);
        $this->addReference('longitude', $longitude);
    
        $vitesse = new ValueType();
        $vitesse->setValueTypeName('Vitesse');
        $vitesse->setUnit('kmh');
        $manager->persist($vitesse);
        $this->addReference('vitesse', $vitesse);
    
        $distanceParcourue = new ValueType();
        $distanceParcourue->setValueTypeName('DistanceParcourue');
        $distanceParcourue->setUnit('km');
        $manager->persist($distanceParcourue);
        $this->addReference('distanceParcourue', $distanceParcourue);
    
        $manager->flush();
    }
}