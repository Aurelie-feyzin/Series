<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public function testDisplayLogin(): void
    {
        $this->getPageWithoutUser('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testBadCredentials(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'email'    => 'user@email.fr',
            'password' => 'badPassword',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
            ]
        );
    }

    public function testSuccessfullLogin(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $this->loadFixture();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'email'    => 'user@email.fr',
            'password' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/');
    }
}
