<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ModuleType;

class ModuleTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $eyes = new ModuleType();
        $eyes->setModuleTypeName('Eyes');
        $eyesDesc = 'Eyes, de Webreathe, est une cellule 3D IoT pour les entreprises de transport.';
        $eyesDesc .= "<br>" . 'Elle compte précisément le nombre de personnes passant par une porte de véhicule.';
        $eyesDesc .= "<br>" . 'Idéal pour la gestion du flux de personnes dans les véhicules de transport.';
        $eyes->setDescription($eyesDesc);
        $eyes->setPictureFile("default.png");
        $manager->persist($eyes);
        $this->addReference('eyes', $eyes);
    
        $odometre = new ModuleType();
        $odometre->setModuleTypeName('Odomètre');
        $odometreDesc = 'Un odomètre est un dispositif qui mesure la distance parcourue par un véhicule de transport.';
        $odometreDesc .= "<br>" . 'Il est généralement utilisé pour le suivi et l\'entretien des véhicules,';
        $odometreDesc .= ' permettant une gestion des ressources plus efficace pour les entreprises de transport.';
        $odometre->setDescription($odometreDesc);
        $odometre->setPictureFile("default.png");
        $manager->persist($odometre);
        $this->addReference('odometre', $odometre);
    
        $gps = new ModuleType();
        $gps->setModuleTypeName('GPS');
        $gpsDesc = 'Le GPS est un dispositif qui fournit des informations de géolocalisation précises';
        $gpsDesc .= ' pour les entreprises de transport.' . "<br>";
        $gpsDesc .= 'Il est essentiel pour le suivi en temps réel des véhicules et pour l\'optimisation';
        $gpsDesc .= ' des itinéraires de transport.';
        $gps->setDescription($gpsDesc);
        $gps->setPictureFile("default.png");
        $manager->persist($gps);
        $this->addReference('gps', $gps);
    
        $manager->flush();
    }
}
