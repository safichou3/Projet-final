<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $bestsplats = array(
            ['title' => 'Le Magret de canard', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat8'],
            ['title' => 'Tarte Tatin', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat3'],
            ['title' => 'Fondant au chocolat', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat3'],
            ['title' => 'Quiche lorraine', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat5'],
            ['title' => 'La blanquette de veau', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat8'],
            ['title' => 'Bò bún', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat13'],
            ['title' => 'Soufflé au fromage', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => null],
            ['title' => 'Le steak-frites', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat8'],
            ['title' => 'Le bœuf bourguignon', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat4'],
            ['title' => 'Carbonnade flamande', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat4'],
            ['title' => 'Lasagnes à la bolognaise', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat4'],
            ['title' => 'Le croque-monsieur', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat12'],
            ['title' => 'Le pot-au-feu', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat4'],
            ['title' => 'Le hachis Parmentier', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat4'],
            ['title' => 'Coq au vin', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => null],
            ['title' => 'Nems', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat13'],
            ['title' => 'Soupe à l\'oignon', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat9'],
            ['title' => 'Salade César', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat11'],
            ['title' => 'Mojito', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat10'],
            ['title' => 'Oeuf mayonnaise', 'description' => null, 'tips' => null, 'difficulty' => null, 'category' => 'cat2'],

        );
        foreach ($bestsplats as $index => $plat) {
            $recipe = new Recipe();
            $recipe->setTitle($plat['title']);
            $recipe->setDescription($plat['description']);
            $recipe->setTimePrepare((new \DateTime())->setTime(0, 0, 0)->add(new \DateInterval('PT' . $faker->numberBetween(0, 59) . 'M' . $faker->numberBetween(0, 59) . 'S')));
            $recipe->setTimeCook((new \DateTime())->setTime(0, 0, 0)->add(new \DateInterval('PT' . $faker->numberBetween(0, 59) . 'M' . $faker->numberBetween(0, 59) . 'S')));
            $recipe->setTips($plat['tips']);
            $recipe->setDifficulty($plat['difficulty']);
            $recipe->setCreatedAt(new \DateTimeImmutable());

            if (isset($plat['category'])) {
                $recipe->setCategory($this->getReference($plat['category']));
            }

            $manager->persist($recipe);
            $this->addReference('recipe_' . $index, $recipe); // Ajout de la référence
        }

        $manager->flush();

    }
}