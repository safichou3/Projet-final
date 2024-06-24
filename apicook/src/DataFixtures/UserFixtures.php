<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $h)
    {
        $this->hasher = $h;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('safia.medjahed@gmail.com');
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'test'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('kangoo7658@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($admin, 'test'));
        $manager->persist($user);

        $manager->flush();

        $this->addReference('user_admin', $admin);
        $this->addReference('user_user', $user);
    }
}
