<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ActorControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = 'fr/actor/';
    private const URI_NEW = self::PARTIAL_URL . 'new';
    private const URI_SHOW_DELETE = self::PARTIAL_URL . 'prenom-nom';
    private const URI_EDIT = self::URI_SHOW_DELETE . '/edit';

    public function testPageActorIndex(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::PARTIAL_URL);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorNew(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_NEW);
        $this->assertResponseRedirects($this->path_login);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/actorTest.yaml',
            ]
        );
    }

    public function testPageActorNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_EDIT);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageActorEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageActorDeletetWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
