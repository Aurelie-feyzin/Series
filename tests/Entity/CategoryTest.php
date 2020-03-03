<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    protected $loader;

    /** @var Registry */
    protected $doctrine;

    public function getCategory(): Category
    {
        $category = new Category();
        $category->setName('Test');

        return $category;
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml']
        );
    }

    public function testValidCategory(): void
    {
        $category = $this->getCategory();
        $this->assertHasErrors($category, 0);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertClassHasAttribute('name', Category::class);
        $this->assertIsString($category->getName());
        $this->assertClassHasAttribute('slug', Category::class);
        $this->assertIsString($category->getSlug());
    }

    public function testBlankNameCategory(): void
    {
        $category = $this->getCategory();
        $category->setName('');
        $this->assertHasErrors($category, 2);
    }

    public function testBlankSlugCategory(): void
    {
        $category = $this->getCategory();
        $category->setSlug('');
        $this->assertHasErrors($category, 1);
    }

    public function testLoadAllCategory(): void
    {
        $this->loadFixture();

        $result = $this->doctrine->getRepository(Category::class)->findAll();

        $this->assertEquals(1, \count($result));
    }

    public function testReplicateCategory(): void
    {
        $this->loadFixture();

        $this->assertHasErrors($this->getCategory(), 1);
    }
}
