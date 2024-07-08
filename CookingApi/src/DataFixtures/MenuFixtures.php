<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $menus = [
            ['name' => 'Pizza Margherita', 'description' => 'A classic Italian pizza with fresh tomatoes, mozzarella cheese, and basil leaves.', 'price' => 12.50, 'availability' => true, 'category' => 'cat4', 'chef' => 'user_admin'],
            ['name' => 'Spaghetti Carbonara', 'description' => 'Traditional Italian pasta with creamy egg-based sauce, pancetta, and Parmesan cheese.', 'price' => 10.00, 'availability' => true, 'category' => 'cat4', 'chef' => 'user_admin'],
            ['name' => 'Caesar Salad', 'description' => 'Crispy romaine lettuce, croutons, Parmesan cheese, and Caesar dressing.', 'price' => 8.00, 'availability' => true, 'category' => 'cat11', 'chef' => 'user_admin'],
            ['name' => 'Grilled Chicken Sandwich', 'description' => 'Grilled chicken breast with lettuce, tomato, and mayo on a toasted bun.', 'price' => 9.00, 'availability' => true, 'category' => 'cat12', 'chef' => 'user_admin'],
            ['name' => 'Chocolate Cake', 'description' => 'Rich and moist chocolate cake with chocolate frosting.', 'price' => 6.00, 'availability' => true, 'category' => 'cat3', 'chef' => 'user_admin'],
        ];

        foreach ($menus as $data) {
            $menu = new Menu();
            $menu->setName($data['name']);
            $menu->setDescription($data['description']);
            $menu->setPrice($data['price']);
            $menu->setAvailability($data['availability']);
            $menu->setCategory($this->getReference($data['category']));
            $menu->setChef($this->getReference($data['chef']));

            $manager->persist($menu);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
