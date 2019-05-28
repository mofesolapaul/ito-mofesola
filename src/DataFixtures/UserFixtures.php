<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\NameGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $nameGenerator;
    private $passwordEncoder;

    public function __construct(NameGenerator $nameGenerator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->nameGenerator = $nameGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setName($this->nameGenerator->getNewName());
            $user->setEmail("user{$i}@ito.dev");
            $password = $this->passwordEncoder->encodePassword($user, '123456');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
