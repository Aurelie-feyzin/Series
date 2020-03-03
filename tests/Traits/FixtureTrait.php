<?php declare(strict_types=1);

namespace App\Tests\Traits;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait FixtureTrait.
 */
trait FixtureTrait
{
    public function setUp(): void
    {
        self::bootKernel();
        /** @var ContainerInterface $container */
        $container = self::$container;
        $this->loader = $container->get('fidry_alice_data_fixtures.loader.doctrine');
        /* @var Registry doctrine */
        $this->doctrine = $container->get('doctrine');
    }

    public function tearDown(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->doctrine->getManager();
        $purger = new DoctrineOrmPurger($em);
        $purger->purge();
    }
}
