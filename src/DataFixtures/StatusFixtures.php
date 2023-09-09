<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Status;

class Category {
    const NORMAL = 'Normal';
    const AVERTISSEMENT = 'Avertissement';
    const CRITIQUE = 'Critique';
}

class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $normal = new Status();
        $normal->setName('Normal');
        $normal->setCategory(Category::NORMAL);
        $manager->persist($normal);

        $batterieFaible = new Status();
        $batterieFaible->setName('BatterieFaible');
        $batterieFaible->setCategory(Category::AVERTISSEMENT);
        $manager->persist($batterieFaible);

        $hauteTemperature = new Status();
        $hauteTemperature->setName('HauteTempérature');
        $hauteTemperature->setCategory(Category::AVERTISSEMENT);
        $manager->persist($hauteTemperature);

        $problemeDeConnectivite = new Status();
        $problemeDeConnectivite->setName('ProblèmeDeConnectivité');
        $problemeDeConnectivite->setCategory(Category::CRITIQUE);
        $manager->persist($problemeDeConnectivite);

        $defaillanceMaterielle = new Status();
        $defaillanceMaterielle->setName('DéfaillanceMatérielle');
        $defaillanceMaterielle->setCategory(Category::CRITIQUE);
        $manager->persist($defaillanceMaterielle);

        $erreurLogicielle = new Status();
        $erreurLogicielle->setName('ErreurLogicielle');
        $erreurLogicielle->setCategory(Category::CRITIQUE);
        $manager->persist($erreurLogicielle);

        $manager->flush();
    }
}
