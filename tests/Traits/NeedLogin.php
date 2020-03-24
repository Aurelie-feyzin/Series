<?php declare(strict_types=1);

namespace App\Tests\Traits;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{
    use FixtureTrait;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    public function login(KernelBrowser $client, User $user): void
    {
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/userTest.yaml',
            ]
        );
    }

    public function getUserSubscriber(): User
    {
        $this->loadFixture();

        return $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
    }

    public function getUserAdmin(): User
    {
        $this->loadFixture();

        return $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
    }
}
