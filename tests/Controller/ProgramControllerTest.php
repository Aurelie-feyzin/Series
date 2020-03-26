<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProgramControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function testPageProgramIndex(): void
    {
        $this->getPageWithoutUser('/program/');
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
        $this->getPageWithoutUser('/program/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageProgramNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramShow(): void
    {
        $this->getPageWithoutUser('/program/test-title');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramShowByCategory(): void
    {
        $this->getPageWithoutUser('program/category/test');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramyEdit(): void
    {
        $this->getPageWithoutUser('/program/test-title/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageProgramEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/test-title/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/test-title/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageProgramDelete(): void
    {
        $this->getPageWithoutUser('/program/test-title', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageProgramDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/program/test-title', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageProgramDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/program/test-title', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
