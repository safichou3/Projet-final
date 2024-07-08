<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word);
            $ingredient->setDescription($faker->sentence);
            $ingredient->setCalories($faker->numberBetween(50, 500));
            $ingredient->setProtein($faker->randomFloat(2, 0, 100));
            $ingredient->setCarbs($faker->randomFloat(2, 0, 100));

            $manager->persist($ingredient);
            $this->addReference('ingredient_' . $i, $ingredient); // Ajout de la référence ici
        }

        $manager->flush();
    }
}
