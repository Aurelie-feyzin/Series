<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\EpisodeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("program/{programSlug}/season/{seasonNumber}/episode/{episodeNumber}/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function new(Request $request, EpisodeRepository $episodeRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        $episode = $episodeRepository->findEpisodeByUrlParameter($request->attributes);

        $comment = new Comment($episode, $user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{comment_id}/edit", name="comment_edit", methods={"GET","POST"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        if (!$this->isAuthorOrAdmin($comment)) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_show', ['id' => $comment->getEpisode()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{comment_id}", name="comment_delete", methods={"DELETE"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if (!$this->isAuthorOrAdmin($comment)) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_show', [
            'programSlug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
            'seasonNumber'=> $comment->getEpisode()->getSeason()->getNumber(),
            'number'      => $comment->getEpisode()->getNumber(), ]);
    }

    private function isAuthorOrAdmin(Comment $comment): bool
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->isGranted('ROLE_ADMIN') || $comment->getAuthor() === $user;
    }
}
