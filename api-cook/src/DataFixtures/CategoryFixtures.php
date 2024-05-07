<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('fr_FR');
        $bestCats = array(
            'Cakes', 'Entree', 'Desserts', 'Plats', 'Viandes', 'Soupes', 'Boissons', 'Sandwiches', 'Salades', 'Asiatique'
        );

        foreach ($bestCats as $category) {
            $cat = new Category();
            $cat->setTitle($category);
            $cat->setContent($faker->realTextBetween(1, 160));
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
