<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setUser($this->getReference('user_user'));
            $order->setOrderDate(new \DateTimeImmutable());
            $order->setPickupTime($faker->dateTime);
            $order->setStatus('Pending');
            $order->setTotalPrice($faker->randomFloat(2, 10, 100));

            $manager->persist($order);
            $this->addReference('order_' . $i, $order);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
