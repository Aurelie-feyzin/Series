<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Comment;
use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CommentControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = '/program/test-title/season/1/episode/1/comment';

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/episodeTest.yaml',
                'tests/fixtures/seasonTest.yaml',
                'tests/fixtures/commentTest.yaml',
            ]
        );
    }

    public function testPageCommentNew(): void
    {
        $this->getPageWithoutUser(self::PARTIAL_URL . '/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCommentNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCommentNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCommentEdit(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithoutUser(self::PARTIAL_URL . '/' . ($comment->getId() + 1) . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCommentEditWithAuthor(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($comment->getAuthor(), self::PARTIAL_URL . '/' . $comment->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCommentEditWithSubscriberNotAuthor(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/' . $comment->getId() . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCommentEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/' . $comment->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCommentDelete(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithoutUser(self::PARTIAL_URL . '/' . ($comment->getId() + 1), 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCommentDeleteWithAuthor(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($comment->getAuthor(), self::PARTIAL_URL . '/' . $comment->getId(), 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testPageCommentDeleteWithSubscriberNotAuthor(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/' . $comment->getId(), 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCommentDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $comment = $this->doctrine->getRepository(Comment::class)->findOneBy(['rate' => 3]);
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/' . $comment->getId(), 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
