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
        $bestscats = array(
            ['title' => 'Cakes', 'reference' => 'cat1'],
            ['title' => 'Entrées', 'reference' => 'cat2'],
            ['title' => 'Desserts', 'reference' => 'cat3'],
            ['title' => 'Plats', 'reference' => 'cat4'],
            ['title' => 'Tartes', 'reference' => 'cat5'],
            ['title' => 'Sauces', 'reference' => 'cat6'],
            ['title' => 'Omelettes', 'reference' => 'cat7'],
            ['title' => 'Viandes', 'reference' => 'cat8'],
            ['title' => 'Soupes', 'reference' => 'cat9'],
            ['title' => 'Boissons', 'reference' => 'cat10'],
            ['title' => 'Salades', 'reference' => 'cat11'],
            ['title' => 'Sandwiches, Burgers', 'reference' => 'cat12'],
            ['title' => 'Asiatique', 'reference' => 'cat13'],
        );

        foreach ($bestscats as $bestcat) {
            $category = new Category();
            $category->setTitle($bestcat['title']);
            $category->setDescription($faker->text);
            $category->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($category);
            $this->addReference($bestcat['reference'], $category);
        }

        $manager->flush();
    }
}
