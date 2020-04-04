<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const PARTIAL_URL = 'fr/category/';
    private const URI_NEW = self::PARTIAL_URL . 'new';
    private const URI_SHOW_DELETE = self::PARTIAL_URL . 'test';
    private const URI_EDIT = self::URI_SHOW_DELETE . '/edit';

    public function testPageCategoryIndex(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::PARTIAL_URL);
        $this->assertResponseRedirects($this->path_login);
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
        $this->getPageWithUser($this->getUserSubscriber(), self::PARTIAL_URL);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryIndexWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::PARTIAL_URL);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryNew(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_NEW);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageCategoryNewWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryNewWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_NEW);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryShow(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageCategoryShowWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryShowWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_SHOW_DELETE);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryEdit(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_EDIT);
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageCategoryEditWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryEditWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_EDIT);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageCategoryDelete(): void
    {
        $this->loadFixture();
        $this->getPageWithoutUser(self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageCategoryDeleteWithUserSubscriber(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserSubscriber(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCategoryDeleteWithUserAdmin(): void
    {
        $this->loadFixture();
        $this->getPageWithUser($this->getUserAdmin(), self::URI_SHOW_DELETE, 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
