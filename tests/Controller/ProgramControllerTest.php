<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProgramControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = 'fr/program/';
    private const URI_NEW = self::PARTIAL_URL . 'new';
    private const URI_SHOW_DELETE = self::PARTIAL_URL . 'test-title';
    private const URI_EDIT = self::URI_SHOW_DELETE . '/edit';

    public function testPageProgramIndex(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::PARTIAL_URL);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
            ]
        );
    }

    public function testPageProgramNew(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_NEW);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageProgramNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramShowByCategory(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramyEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_EDIT);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageProgramEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageProgramDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
