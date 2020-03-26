<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SeasonControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/episodeTest.yaml',
                'tests/fixtures/seasonTest.yaml',
            ]
        );
    }

    public function testPageSeasonNew(): void
    {
        $this->getPageWithoutUser('/program/test-title/season/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageSeasonNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/test-title/season/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageSeasonNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/test-title/season/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonShow(): void
    {
        $this->getPageWithoutUser('/program/test-title/season/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonEdit(): void
    {
        $this->getPageWithoutUser('/program/test-title/season/1/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageSeasonEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/test-title/season/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageSeasonEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/test-title/season/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonDelete(): void
    {
        $this->getPageWithoutUser('/program/test-title/season/1', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageSeasonDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/test-title/season/1', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testSeasonProgramDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/test-title/season/1', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
