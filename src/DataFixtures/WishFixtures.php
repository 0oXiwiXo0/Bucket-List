<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->word());
            $wish->setAuthor($faker->name());
            $wish->setDescription($faker->realText);
            $wish->setDateCreated($faker->dateTimeBetween('-6 months', 'now'));
            $wish->setIsPublished($faker->numberBetween(0,1));
            $manager->persist($wish);
        }

        $manager->flush();
    }
}
