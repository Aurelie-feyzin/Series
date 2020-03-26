<?php declare(strict_types=1);

namespace App\Tests\Traits;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait PageWithOrWithoutLogin
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

    public function getUserSubscriber(): User
    {
        return $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'user@email.fr']);
    }

    public function getUserAdmin(): User
    {
        return $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@email.fr']);
    }

    public function getPageWithoutUser(string $uri, string $method = 'GET'): void
    {
        $this->loadFixture();
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request($method, $uri);
    }

    public function getPageWithUser(User $user, string $uri, string $method = 'GET'): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $this->login($client, $user);
        $client->request($method, $uri);
    }
}
