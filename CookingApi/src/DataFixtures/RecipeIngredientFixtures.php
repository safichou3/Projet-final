<?php

namespace App\DataFixtures;

use App\Entity\RecipeIngredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeIngredientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setRecipe($this->getReference('recipe_' . $i));
            $recipeIngredient->setIngredient($this->getReference('ingredient_' . $i));
            $recipeIngredient->setQuantity($faker->numberBetween(1, 100) . 'g');

            $manager->persist($recipeIngredient);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RecipeFixtures::class,
            IngredientFixtures::class,
        ];
    }
}
