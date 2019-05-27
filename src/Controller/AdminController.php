<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(UserRepository $userRepository)
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager) {
        $userForm = $this->createForm(UserType::class, new User());

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /** @var User $userFormData */
            $userFormData = $userForm->getData();
            $userFormData->setLastLoginDate(new \DateTime());
            $entityManager->persist($userFormData);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('forms/user.html.twig', [
            'user_form' => $userForm->createView(),
            'form_title'  => 'Add new user',
        ]);
    }
}
