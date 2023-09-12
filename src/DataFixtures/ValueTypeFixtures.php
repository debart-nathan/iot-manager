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
        $comptageDePersonnes->setValueTypeName('Comptage de personnes');
        $comptageDePersonnes->setUnit('');
        $manager->persist($comptageDePersonnes);
        $this->addReference('Comptage de personnes', $comptageDePersonnes);
    
        $latitude = new ValueType();
        $latitude->setValueTypeName('Latitude');
        $latitude->setUnit('°');
        $manager->persist($latitude);
        $this->addReference('Latitude', $latitude);
    
        $longitude = new ValueType();
        $longitude->setValueTypeName('Longitude');
        $longitude->setUnit('°');
        $manager->persist($longitude);
        $this->addReference('Longitude', $longitude);
    
        $vitesse = new ValueType();
        $vitesse->setValueTypeName('Vitesse');
        $vitesse->setUnit('kmh');
        $manager->persist($vitesse);
        $this->addReference('Vitesse', $vitesse);
    
        $distanceParcourue = new ValueType();
        $distanceParcourue->setValueTypeName('Distance parcourue');
        $distanceParcourue->setUnit('km');
        $manager->persist($distanceParcourue);
        $this->addReference('Distance parcourue', $distanceParcourue);
    
        $manager->flush();
    }
}