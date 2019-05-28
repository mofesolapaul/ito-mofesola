<?php
/**
 * Created by PhpStorm.
 * User: mb-pro-home
 * Date: 2019-05-28
 * Time: 11:24
 */

namespace App\Doctrine;


use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
    }

    private function encodePassword(User $entity) {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encodedPassword = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encodedPassword);
    }
}