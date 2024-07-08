<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('safia.medjahed@gmail.com');
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        $admin->setChef(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'test'));
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        $user = new User();
        $user->setEmail('kangoo7658@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setChef(false);
        $user->setPassword($this->hasher->hashPassword($user, 'test'));
        $manager->persist($user);
        $this->addReference('user_user', $user);

        $manager->flush();
    }
}
