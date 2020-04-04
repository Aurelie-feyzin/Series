<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SeasonControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = '/fr/program/test-title/season/';
    private const URI_NEW = self::PARTIAL_URL . 'new';
    private const URI_SHOW_DELETE = self::PARTIAL_URL . '1';
    private const URI_EDIT = self::URI_SHOW_DELETE . '/edit';

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
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_NEW);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageSeasonNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageSeasonNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_EDIT);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageSeasonEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageSeasonEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageSeasonDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageSeasonDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testSeasonProgramDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
