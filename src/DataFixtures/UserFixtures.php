<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\NameGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $nameGenerator;

    public function __construct(NameGenerator $nameGenerator)
    {
//        parent::__construct();
        $this->nameGenerator = $nameGenerator;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setName($this->nameGenerator->getNewName());
            $user->setEmail("user{$i}@ito.dev");
            $user->setPassword("123456");
            $manager->persist($user);
        }

        $manager->flush();
    }
}
