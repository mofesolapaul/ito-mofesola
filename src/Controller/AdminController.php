<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public final function index(UserRepository $userRepository) : Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public final function addUser(
        Request $request,
        EntityManagerInterface $entityManager
    ) : Response {
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
            'form_title' => 'Add new user',
        ]);
    }

    /**
     * @Route("/user/{id}/remove", name="user_delete")
     */
    public final function deleteUser(User $user, EntityManagerInterface $entityManager) : Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public final function editUser(User $user, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->remove('plainPassword');

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('forms/user.html.twig', [
            'user_form' => $userForm->createView(),
            'form_title' => "Edit user: {$user->getName()}",
            'button_label' => "Update details",
        ]);
    }
}
