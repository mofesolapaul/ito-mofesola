<?php

namespace App\Doctrine;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EmailNotificationListener implements EventSubscriber
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public final function getSubscribedEvents(): array
    {
        return ['postPersist'];
    }

    public final function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->sendEmail($entity);
    }

    private function sendEmail(User $user): void
    {
        $message = (new \Swift_Message('Welcome to iTo'))
            ->setFrom('app@ito.dev', 'iTo Awesome App')
            ->setTo($user->getEmail())
            ->setBody(
                sprintf("
                    Hello %s, and welcome to iTo.\n\n
                    Login with:\n
                    Email: %s\n
                    Password: %s\n\n
                    Cheers!", $user->getName(), $user->getEmail(), $user->getPlainPassword()),
                'text/plain'
            );
        $this->mailer->send($message);
    }
}