<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("program/{programSlug}/season/{seasonNumber}/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/new", name="episode_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, string $programSlug, int $seasonNumber): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/new.html.twig', [
            'programSlug'  => $programSlug,
            'seasonNumber' => $seasonNumber,
            'episode'      => $episode,
            'form'         => $form->createView(),
        ]);
    }

    /**
     * @Route("/{number}", name="episode_show", methods={"GET"})
     */
    public function show(CommentRepository $commentRepository, Episode $episode): Response
    {
        $user = $this->getUser();

        if ($user) {
            $userCommentEpisode = $this->getDoctrine()->getRepository(Comment::class)->findOneBy(['author' => $user, 'episode' => $episode]);
        }

        $comments = $commentRepository->findCommentByEpisode($episode);

        return $this->render('episode/show.html.twig', [
            'episode'               => $episode,
            'comments'              => $comments,
            'userHasCommentEpisode' => $userCommentEpisode ?? false,
        ]);
    }

    /**
     * @Route("/{number}/edit", name="episode_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Episode $episode): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{number}", name="episode_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete' . $episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('season_show', ['programSlug' => $episode->getSeason()->getProgram()->getSlug(), 'number' => $episode->getSeason()->getNumber()]);
    }
}
