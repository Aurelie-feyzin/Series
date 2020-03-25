<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function testHomePage(): void
    {
        $this->getPageWithoutUser('/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml',
            ]
        );
    }

    public function testNavInHomepage(): void
    {
        $this->getPageWithoutUser('/');
        $this->assertSelectorExists('nav');
        $this->assertSelectorExists('ul');
        $this->assertSelectorExists('li');
        $this->assertSelectorExists('.dropdown-menu');
        // TODO ajouter un test pour vérifier que la liste des categories existe bien
    }

    public function testFooterInHomepage(): void
    {
        $this->getPageWithoutUser('/');
        $this->assertSelectorExists('footer');
    }
}
