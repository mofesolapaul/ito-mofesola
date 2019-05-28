<?php
namespace App\Doctrine;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmailNotificationListener implements EventSubscriber
{
    private $mailer;
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
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
        $this->sendEmail($entity);
    }

    public function preUpdate(LifecycleEventArgs $args) {
        return;
    }

    private function sendEmail(User $user) {
//        $message = (new \Swift_Message('Welcome to iTo'))
//            ->setFrom('people@ito.dev')
//            ->setTo('silly.pacote@mailinator.com')
//            ->setBody(
//                sprintf("Hello %s, and welcome to iTo. Cheers!", $user->getName()),
//                'text/plain'
//            );
//        $this->mailer->send($message);
    }
}