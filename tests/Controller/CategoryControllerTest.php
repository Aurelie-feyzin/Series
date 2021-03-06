<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function testPageCategoryIndex(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/category/');
        $this->assertResponseRedirects('/login');
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
            ]
        );
    }

    public function testPageCategoryIndexWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/category/');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryIndexWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/category/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryNew(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/category/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/category/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/category/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/category/test');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryShowWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryShowWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/category/test');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/category/test/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/category/test/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser('/category/test', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), '/category/test', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
