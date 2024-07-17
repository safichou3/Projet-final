<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
        }

        $manager->flush();
    }
}
