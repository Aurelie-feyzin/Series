<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Fidry\AliceDataFixtures\LoaderInterface;
use Monolog\Registry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class CategoryTest extends KernelTestCase
{
    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    public function setUp()
    {
        self::bootKernel();

        $this->loader = self::$container->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->doctrine = self::$container->get('doctrine');
    }

    public function tearDown()
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();
    }

    public function getCategory(): Category
    {
        $category = new Category();
        $category->setName('Test');

        return $category;
    }

    public function loadFixture()
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml']
        );
    }

    public function assertHasErrors(Category $category, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($category);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidCategory()
    {
        $category = $this->getCategory();
        $this->assertHasErrors($category, 0);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertClassHasAttribute('name', Category::class );
        $this->assertIsString($category->getName());
        $this->assertClassHasAttribute('slug', Category::class );
        $this->assertIsString($category->getSlug());
    }

    public function testBlankNameCategory()
    {
        $category = $this->getCategory();
        $category->setName('');
        $this->assertHasErrors($category, 2);
    }

    public function testBlankSlugCategory()
    {
        $category = $this->getCategory();
        $category->setSlug('');
        $this->assertHasErrors($category, 1);
    }

    public function testLoadAFile()
    {
        $this->loadFixture();

        $result = $this->doctrine->getRepository(Category::class)->findAll();

        $this->assertEquals(1, count($result));
    }

    public function testReplicateCategory()
    {
        $this->loadFixture();

        $this->assertHasErrors($this->getCategory(), 1);
    }
}
