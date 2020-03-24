<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Traits\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ActorControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testPageActorIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/actor/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorNew(): void
    {
        $client = static::createClient();
        $client->request('GET', '/actor/new');
        $this->assertResponseRedirects('/login');
    }

    public function testPageActorNewWithUserSubscriber(): void
    {
        $client = static::createClient();
        $this->login($client, $this->getUserSubscriber());
        $client->request('GET', '/actor/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorNewWithUserAdmin(): void
    {
        $client = static::createClient();
        $this->login($client, $this->getUserAdmin());
        $client->request('GET', '/actor/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
                'tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/actorTest.yaml',
            ]
        );
    }

    public function testPageActorShow(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $client->request('GET', '/actor/prenom-nom');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActorEdit(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $client->request('GET', '/actor/prenom-nom/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testPageActorEditWithUserSubscriber(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $this->login($client, $this->getUserSubscriber());
        $client->request('GET', '/actor/prenom-nom/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorEditWithUserAdmin(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $this->login($client, $this->getUserAdmin());
        $client->request('GET', '/actor/prenom-nom/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageActordelete(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $client->request('DELETE', '/actor/prenom-nom');
        $this->assertResponseRedirects('/login');
    }

    public function testPageActorDeletetWithUserSubscriber(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $this->login($client, $this->getUserSubscriber());
        $client->request('DELETE', '/actor/prenom-nom');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageActorDeleteWithUserAdmin(): void
    {
        $client = static::createClient();
        $this->loadFixture();
        $this->login($client, $this->getUserAdmin());
        $client->request('DELETE', '/actor/prenom-nom');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
