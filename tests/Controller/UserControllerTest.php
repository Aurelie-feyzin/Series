<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\PageWithOrWithoutLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use PageWithOrWithoutLogin;

    public const PARTIAL_URL_USER = '/fr/user/';

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
            ]
        );
    }

    public function testPageUserShow(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithoutUser(self::PARTIAL_URL_USER . $user->getId());
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageUserShowWithUser(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithUser($user, self::PARTIAL_URL_USER . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageUserShowWithOtherUser(): void
    {
        $this->loadFixture();
        $userConnected = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($userConnected, self::PARTIAL_URL_USER . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageUserShowWithAdmin(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $this->getPageWithUser($user, self::PARTIAL_URL_USER . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageUserShowAdminViewOtherUser(): void
    {
        $this->loadFixture();
        $admin = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($admin, self::PARTIAL_URL_USER . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', $user->getEmail());
        $this->assertSelectorTextNotContains('h1', $admin->getEmail());
    }

    public function testPageUserEdit(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithoutUser(self::PARTIAL_URL_USER . $user->getId() . '/edit');
        $this->assertResponseRedirects($this->path_login);
    }

    public function testPageUserEditLoginUser(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $this->getPageWithUser($user, self::PARTIAL_URL_USER . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageUserEditLoginUserTestOtherUser(): void
    {
        $this->loadFixture();
        $userConnected = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($userConnected, self::PARTIAL_URL_USER . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageUserEditWithAdmin(): void
    {
        $this->loadFixture();
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $this->getPageWithUser($user, self::PARTIAL_URL_USER . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageUserEditLoginAdminEditOtherUser(): void
    {
        $this->loadFixture();
        $admin = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'author@email.fr']);
        $this->getPageWithUser($admin, self::PARTIAL_URL_USER . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertInputValueSame('user[email]', $user->getEmail());
        $this->assertInputValueNotSame('user[email]', $admin->getEmail());
    }
}
