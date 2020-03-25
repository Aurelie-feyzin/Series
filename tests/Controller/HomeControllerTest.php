<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    public $client;

    public function getPage(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->client->request('GET', '/');
    }

    public function testHomePage(): void
    {
        $this->getPage();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testNavInHomepage(): void
    {
        $this->getPage();
        $this->assertSelectorExists('nav');
    }

    public function testFooterInHomepage(): void
    {
        $this->getPage();
        $this->assertSelectorExists('footer');
    }
}
