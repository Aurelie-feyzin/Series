<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testDisplayLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testBadCredentials(): void
    {
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

    public function testSuccessfullLogin(): void
    {
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
