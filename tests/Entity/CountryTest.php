<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Country;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CountryTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    public function getCountry(): Country
    {
        $country = (new Country())->setName('France');

        return $country;
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/countryTest.yaml']
        );
    }

    public function testValidCountry(): void
    {
        $country = $this->getCountry();
        $this->assertHasErrors($country, 0);
        $this->assertInstanceOf(Country::class, $country);
        $this->assertClassHasAttribute('name', Country::class);
        $this->assertIsString($country->getName());
    }

    public function testBlankNameCountry(): void
    {
        $country = $this->getCountry()->setName('');
        $this->assertHasErrors($country, 1);
    }

    public function testLoadAllCountry(): void
    {
        $this->loadFixture();

        $result = $this->doctrine->getRepository(Country::class)->findAll();

        $this->assertEquals(1, \count($result));
    }

    public function testReplicateCategory(): void
    {
        $this->loadFixture();

        $this->assertHasErrors($this->getCountry(), 1);
    }
}
