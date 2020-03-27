<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function show(CommentRepository $commentRepository, User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->checkUser($user),
            'comments' => $commentRepository->findBy(['author' => $user]),
        ]);
    }

    private function checkUser(User $user): User
    {
        /** @var User $userLogin */
        $userLogin = $this->getUser();

        if ($this->isGranted('ROLE_SUBSCRIBER')) {
            $user = $userLogin;
        }

        return $user;
    }
}
