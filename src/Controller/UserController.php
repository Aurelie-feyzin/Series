<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CommentRepository;
use App\Security\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"}, requirements={"id"="\d+"})
     * @IsGranted(UserVoter::SHOW, subject="user")
     */
    public function show(CommentRepository $commentRepository, User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user'     => $user,
            'comments' => $commentRepository->findBy(['author' => $user]),
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", requirements={"id"="\d+"})
     * @IsGranted(UserVoter::EDIT, subject="user")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form'             => $form->createView(),
            'user'             => $user,
        ]);
    }
}
