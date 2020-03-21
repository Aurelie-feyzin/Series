<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    protected $loader;

    /** @var Registry */
    protected $doctrine;

    public function getUser(): User
    {
        $user = new User();
        $user->setEmail('user@mail.fr')
            ->setPassword('password');

        return $user;
    }

    public function testValidUser(): void
    {
        $user = $this->getUser();

        $this->assertHasErrors($user, 0);
        $this->assertInstanceOf(User::class, $user);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            []
        );
    }
}
