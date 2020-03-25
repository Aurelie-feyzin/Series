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
        $this->getPageWithUser($this->getUserSubscriber(), '/category/');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryIndexWithUserAdmin(): void
    {
        $this->getPageWithUser($this->getUserAdmin(), '/category/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryNew(): void
    {
        $this->getPageWithoutUser('/category/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryNewWithUserSubscriber(): void
    {
        $this->getPageWithUser($this->getUserSubscriber(), '/category/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryNewWithUserAdmin(): void
    {
        $this->getPageWithUser($this->getUserAdmin(), '/category/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryShow(): void
    {
        $this->getPageWithoutUser('/category/test');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryShowWithUserSubscriber(): void
    {
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryShowWithUserAdmin(): void
    {
        $this->getPageWithUser($this->getUserAdmin(), '/category/test');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryEdit(): void
    {
        $this->getPageWithoutUser('/category/test/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryEditWithUserSubscriber(): void
    {
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryEditWithUserAdmin(): void
    {
        $this->getPageWithUser($this->getUserAdmin(), '/category/test/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryDelete(): void
    {
        $this->getPageWithoutUser('/category/test', 'DELETE');
        $this->assertResponseRedirects('/login');
    }

    public function testPageCategoryDeleteWithUserSubscriber(): void
    {
        $this->getPageWithUser($this->getUserSubscriber(), '/category/test', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryDeleteWithUserAdmin(): void
    {
        $this->getPageWithUser($this->getUserAdmin(), '/category/test', 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
