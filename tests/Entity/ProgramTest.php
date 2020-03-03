<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Program;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProgramTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    public function getProgram(): Program
    {
        $category = (new CategoryTest())->getCategory();

        $program = new Program();
        $program->setTitle('Test Title')
            ->setSynopsis('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet lobortis facilisis. Vestibulum eu sapien a quam ultricies dignissim.')
             ->setPoster('https://picsum.photos/200/300')
            ->setYear(1984)
            ->setCategory($category);

        return $program;
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/programTest.yaml', 'tests/fixtures/categoryTest.yaml']
        );
    }

    public function testValidProgram(): void
    {
        $program = $this->getProgram();

        $this->assertHasErrors($program, 0);
        $this->assertInstanceOf(Program::class, $program);
    }

    public function testBlankTitleProgram(): void
    {
        $program = $this->getProgram();
        $program->setTitle('');
        $this->assertHasErrors($program, 2);
    }

    public function testBlankSlugProgram(): void
    {
        $program = $this->getProgram();
        $program->setSlug('');
        $this->assertHasErrors($program, 1);
    }

    public function testNullYearProgram(): void
    {
        $program = $this->getProgram();
        $program->setYear(null);
        $this->assertHasErrors($program, 0);
    }

    public function testTooMinYearProgram(): void
    {
        $program = $this->getProgram();
        $program->setYear(188);
        $this->assertHasErrors($program, 1);
    }

    public function testTooMaxYearProgram(): void
    {
        $program = $this->getProgram();
        $program->setYear(2501);
        $this->assertHasErrors($program, 1);
    }

    public function testLoadAllProgram(): void
    {
        $this->loadFixture();

        $result = $this->doctrine->getRepository(Program::class)->findAll();

        $this->assertEquals(1, \count($result));
    }

    public function testReplicateProgram(): void
    {
        $this->loadFixture();

        $this->assertHasErrors($this->getProgram(), 1);
    }
}