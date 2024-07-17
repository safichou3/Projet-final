<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        // Créer quelques catégories réalistes
        $categories = [];
        $categoriesData = [
            ['title' => 'Entrées', 'description' => 'Des idées d\'entrées délicieuses pour commencer votre repas.'],
            ['title' => 'Plats Principaux', 'description' => 'Des recettes pour des plats principaux savoureux et variés.'],
            ['title' => 'Desserts', 'description' => 'De délicieuses recettes de desserts pour terminer votre repas en beauté.'],
            ['title' => 'Salades', 'description' => 'Des recettes de salades fraîches et saines.'],
            ['title' => 'Soupes', 'description' => 'Des recettes de soupes réconfortantes pour toutes les saisons.'],
            ['title' => 'Halal', 'description' => 'Des recettes conformes aux exigences alimentaires halal.'],
            ['title' => 'Sans Gluten', 'description' => 'Des recettes spécialement conçues pour les personnes sensibles ou allergiques au gluten.'],
            ['title' => 'Végétarien', 'description' => 'Des recettes sans viande pour les végétariens.'],
            ['title' => 'Vegan', 'description' => 'Des recettes sans produits d\'origine animale pour les vegans.'],
            ['title' => 'Keto', 'description' => 'Des recettes faibles en glucides et riches en graisses pour le régime cétogène.']
        ];

        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setTitle($data['title']);
            $category->setDescription($data['description']);
            $category->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($category);
            $categories[] = $category;
        }

        // Créer des recettes avec des titres et descriptions réalistes
        $recipesData = [
            ['title' => 'Salade César au poulet', 'description' => 'Une salade croquante avec des morceaux de poulet grillé, des croûtons, et une sauce César crémeuse.'],
            ['title' => 'Soupe de légumes maison', 'description' => 'Une soupe réconfortante pleine de légumes frais et d\'herbes aromatiques.'],
            ['title' => 'Poulet rôti aux herbes', 'description' => 'Un poulet tendre et juteux rôti avec un mélange d\'herbes aromatiques.'],
            ['title' => 'Tarte aux pommes classique', 'description' => 'Une tarte traditionnelle avec des pommes sucrées et une pâte feuilletée croustillante.'],
            ['title' => 'Lasagnes à la bolognaise', 'description' => 'Des lasagnes généreuses avec une sauce bolognaise riche et du fromage fondant.'],
            ['title' => 'Crème brûlée', 'description' => 'Un dessert crémeux avec une croûte caramélisée croustillante.'],
            ['title' => 'Ratatouille', 'description' => 'Un mélange coloré de légumes mijotés dans une sauce tomate parfumée.'],
            ['title' => 'Quiche lorraine', 'description' => 'Une quiche savoureuse avec du lard fumé et du fromage.'],
            ['title' => 'Bœuf bourguignon', 'description' => 'Un plat traditionnel de bœuf mijoté dans du vin rouge avec des champignons et des carottes.'],
            ['title' => 'Mousse au chocolat', 'description' => 'Un dessert léger et aéré au chocolat riche et onctueux.']
        ];

        foreach ($recipesData as $data) {
            $recipe = new Recipe();
            $recipe->setTitle($data['title']);
            $recipe->setDescription($data['description']);
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $recipe->setTips($faker->sentence);
            $recipe->setCategory($categories[array_rand($categories)]);
            $recipe->setDifficulty($faker->randomElement(['facile', 'moyen', 'difficile']));
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
