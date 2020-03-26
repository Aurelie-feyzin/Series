<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EpisodeControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = '/program/test-title/season/1/episode';

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/episodeTest.yaml',
                'tests/fixtures/seasonTest.yaml',
                'tests/fixtures/episodeTest.yaml',
            ]
        );
    }

    public function testPageEpisodeNew(): void
    {
        $this->getPageWithoutUser(self::PARTIAL_URL . '/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageEpisodeNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageEpisodeNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageEpisodeShow(): void
    {
        $this->getPageWithoutUser(self::PARTIAL_URL . '/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageEpisodeEdit(): void
    {
        $this->getPageWithoutUser(self::PARTIAL_URL . '/1/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageEpisodeEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageEpisodeEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageEpisodeDelete(): void
    {
        $this->getPageWithoutUser(self::PARTIAL_URL . '/1', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageEpisodeDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL . '/1', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testEpisodeDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL . '/1', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
