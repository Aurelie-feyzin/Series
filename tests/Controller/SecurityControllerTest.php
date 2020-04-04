<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    private const URL_REGISTER = '/fr/register';
    private const URL_RESET_PASSWORD = '/fr/reset_password';

    public function testDisplayLogin(): void
    {
        $this->getPageWithoutUser($this->path_login);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testBadCredentials(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', $this->path_login);
        $form = $crawler->selectButton('Connexion')->form([
            'email'    => 'user@email.fr',
            'password' => 'badPassword',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($this->path_login);
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
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);

        $crawler = $client->request('GET', $this->path_login);
        $form = $crawler->selectButton('Connexion')->form([
            'email'    => 'user@email.fr',
            'password' => 'password',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects(UserControllerTest::PARTIAL_URL_USER . $user->getId());
    }

    public function testSuccesfullRegister(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->selectButton('S\'inscire')->form([
            'registration_form[email]'         => 'user@email.fr',
            'registration_form[plainPassword]' => 'password',
        ]);
        $client->submit($form);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->assertResponseRedirects(UserControllerTest::PARTIAL_URL_USER . $user->getId());
        $transport = self::$container->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());
    }

    public function testRegisterWithPasswordTooShort(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->selectButton('S\'inscire')->form([
            'registration_form[email]'         => 'user@email.fr',
            'registration_form[plainPassword]' => 'pass',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
        $transport = self::$container->get('messenger.transport.async');
        $this->assertCount(0, $transport->get());
    }

    public function testRegisterWithEmailAlreadyUse(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $this->loadFixture();

        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->selectButton('S\'inscire')->form([
            'registration_form[email]'         => 'user@email.fr',
            'registration_form[plainPassword]' => 'password',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
        $transport = self::$container->get('messenger.transport.async');
        $this->assertCount(0, $transport->get());
    }

    public function testSuccesfullResetPassword(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->login($client, $user);
        $crawler = $client->request('GET', self::URL_RESET_PASSWORD);
        $form = $crawler->selectButton('Modifier mot de passe')->form([
            'reset_password[oldPassword]'         => 'password',
            'reset_password[newPassword]'         => 'newpassword',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects(UserControllerTest::PARTIAL_URL_USER . $user->getId());
    }

    public function testBadOldPasswordResetPassword(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->login($client, $user);
        $crawler = $client->request('GET', self::URL_RESET_PASSWORD);
        $form = $crawler->selectButton('Modifier mot de passe')->form([
            'reset_password[oldPassword]'         => 'badpassword',
            'reset_password[newPassword]'         => 'newpassword',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
    }

    public function testDeleteAccount(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithoutUser(UserControllerTest::PARTIAL_URL_USER . $user->getId(), 'DELETE');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testDeleteAccountSubscriber(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithUser($user, UserControllerTest::PARTIAL_URL_USER . $user->getId(), 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDeleteAccountAdmin(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $this->getPageWithUser($user, UserControllerTest::PARTIAL_URL_USER . $user->getId(), 'DELETE');
        $this->assertResponseRedirects(UserControllerTest::PARTIAL_URL_USER . $user->getId());
    }

    public function testDeleteAccountSubscriberWithLoginSubscriber(): void
    {
        $this->loadFixture();
        $userLog = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $userNotLog = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($userLog, UserControllerTest::PARTIAL_URL_USER . $userNotLog->getId(), 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND, 'user/' . $userLog->getId());
    }

    public function testDeleteAccountSubscriberWithLoginAdmin(): void
    {
        $this->loadFixture();
        $admin = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($admin, UserControllerTest::PARTIAL_URL_USER . $user->getId(), 'DELETE');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
