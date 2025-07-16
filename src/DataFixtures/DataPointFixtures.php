<?php

namespace App\DataFixtures;

use App\Entity\DataPoint;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DataPointFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create(); 
        $categories = ['electronics', 'clothing', 'food'];
        
        for ($i = 0; $i < 100; $i++) {
            $dataPoint = new DataPoint();
            $dataPoint->setCategory($faker->randomElement($categories));
            $dataPoint->setValue($faker->randomFloat(2, 10, 1000));
            $dataPoint->setDate($faker->dateTimeBetween('-1 year'));
            $manager->persist($dataPoint);
        }
        
        $manager->flush();
    }
}