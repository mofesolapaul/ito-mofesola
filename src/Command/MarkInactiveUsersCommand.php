<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MarkInactiveUsersCommand extends Command
{
    protected static $defaultName = 'app:mark-inactive-users';
    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Marks users as inactive after one month of inactivity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $aMonthAgo = new \DateTime('-1 month');
        $criteria = new Criteria();
        $criteria
            ->where(Criteria::expr()->eq('isActive', true))
            ->andWhere(Criteria::expr()->lt('lastLoginDate', $aMonthAgo));
        $users = $this->userRepository->matching($criteria);

        if (!$users->count()) {
            $io->comment('No users found inactive for over a month');
            return;
        } else {
            $io->title(sprintf('Marking %d user(s) as inactive', $users->count()));
        }

        /** @var User $user */
        foreach ($users as $user) {
            $user->setIsActive(false);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $io->success(sprintf('Marked %d user(s) as inactive.', $users->count()));
    }
}
