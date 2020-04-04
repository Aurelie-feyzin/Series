<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public const LOCALE = '/fr/';

    public function testHomePage(): void
    {
        $this->getPageWithoutUser('/');
        $this->assertResponseRedirects(self::LOCALE);
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
        $this->getPageWithoutUser(self::LOCALE);
        $this->assertSelectorExists('nav');
        $this->assertSelectorExists('ul');
        $this->assertSelectorExists('li');
        $this->assertSelectorExists('.dropdown-menu');
        // TODO ajouter un test pour vérifier que la liste des categories existe bien
    }

    public function testFooterInHomepage(): void
    {
        $this->getPageWithoutUser(self::LOCALE);
        $this->assertSelectorExists('footer');
    }
}
