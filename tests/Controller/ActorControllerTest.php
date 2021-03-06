<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ActorControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function testPageActorIndex(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/actor/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorNew(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/actor/new');
        $this->assertResponseRedirects('/login');
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
        $this->getPageWithUser($this->getUserSubscriber(), '/actor/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/actor/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/actor/prenom-nom');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/actor/prenom-nom/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageActorEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/actor/prenom-nom/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/actor/prenom-nom/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/actor/prenom-nom', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageActorDeletetWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/actor/prenom-nom', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/actor/prenom-nom', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
