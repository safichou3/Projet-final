<?php

namespace App\DataFixtures;

use App\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $payment = new Payment();
            $payment->setUserOrder($this->getReference('order_' . $i));
            $payment->setPaymentDate($faker->dateTime);
            $payment->setAmount($faker->randomFloat(2, 10, 100));
            $payment->setPaymentMethod('Credit Card');
            $payment->setPaymentStatus('Completed');

            $manager->persist($payment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            OrderFixtures::class,
        ];
    }
}
