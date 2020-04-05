<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const LOCALE_FR = '/fr/';
    private const LOCALE_EN = '/en/';

    public function testHomePage(): void
    {
        $this->getPageWithoutUser('/');
        $this->assertResponseRedirects(self::LOCALE_FR);
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
        $this->getPageWithoutUser(self::LOCALE_FR);
        $this->assertSelectorExists('nav');
        $this->assertSelectorExists('ul');
        $this->assertSelectorExists('li');
        $this->assertSelectorExists('.dropdown-menu');
        // TODO ajouter un test pour vérifier que la liste des categories existe bien
    }

    public function testLocaleFr(): void
    {
        $this->getPageWithoutUser(self::LOCALE_FR);
        $this->assertSelectorTextContains('.nav-link', 'Catégories');
    }

    public function testLocaleEn(): void
    {
        $this->getPageWithoutUser(self::LOCALE_EN);
        $this->assertSelectorTextContains('.nav-link', 'Categories');
    }

    public function testFooterInHomepage(): void
    {
        $this->getPageWithoutUser(self::LOCALE_FR);
        $this->assertSelectorExists('footer');
    }
}
