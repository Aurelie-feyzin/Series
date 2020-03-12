<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Program;
use App\Entity\Season;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SeasonTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    /**
     * Return partial Season (missing number).
     */
    public function getPartialSeason(): Season
    {
        $program = (new ProgramTest())->getProgram();

        $season = new Season();
        $season->setProgram($program)
            ->setYear($program->getYear())
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean venenatis mauris a nisi sollicitudin, at convallis nulla accumsan. Donec convallis egestas ligula, ac rhoncus nibh sollicitudin non. Sed sit amet congue leo. Nullam scelerisque sem at cursus elementum. Donec vehicula elementum diam. Sed eleifend, magna quis vulputate fringilla, libero augue tempor mi, at auctor orci dolor ac justo. ');

        return $season;
    }

    public function getSeason(): Season
    {
        return $this->getPartialSeason()->setNumber(1);
    }

    public function testValidSeason(): void
    {
        $season = $this->getSeason();

        $this->assertHasErrors($season, 0);
        $this->assertInstanceOf(Season::class, $season);
    }

    public function testEmptyNumber(): void
    {
        $season = $this->getPartialSeason();
        $this->assertHasErrors($season, 1);
    }

    public function testInvalidNumber(): void
    {
        $season = $this->getPartialSeason()->setNumber(-1);
        $this->assertHasErrors($season, 1);
        $season = $this->getPartialSeason()->setNumber(0);
        $this->assertHasErrors($season, 1);
    }

    public function testProgramNumberUnique(): void
    {
        $this->loadFixture();
        // Not same program => 0 error
        $season = $this->getSeason();
        $this->assertHasErrors($season, 0);
        $programs = $this->doctrine->getRepository(Program::class)->findAll();
        // Same program and same number => 1 error
        $season = $this->getSeason()->setProgram($programs[0]);
        $this->assertHasErrors($season, 1);
        // Not same number => 0 error
        $season = $this->getSeason()->setProgram($programs[0])->setNumber(2);
        $this->assertHasErrors($season, 0);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml', 'tests/fixtures/programTest.yaml', 'tests/fixtures/seasonTest.yaml']
        );
    }

    public function testNullYear(): void
    {
        $season = $this->getSeason()->setYear(null);
        $this->assertHasErrors($season, 0);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testTooMinYearProgram(): void
    {
        $this->getSeason()->setNumber(1)->setYear(1980);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testTooMaxYearProgram(): void
    {
        $actualYear = (new \DateTime('now'))->format('Y');
        $this->getSeason()->setNumber(1)->setYear((int) $actualYear + 2);
    }

    public function testNullDescription(): void
    {
        $season = $this->getSeason()->setDescription(null);
        $this->assertHasErrors($season, 0);
    }

    public function testBlankDescription(): void
    {
        $season = $this->getSeason()->setDescription('');
        $this->assertHasErrors($season, 0);
    }

    public function testLoadAllSeason(): void
    {
        $this->loadFixture();

        $result = $this->doctrine->getRepository(Season::class)->findAll();
        $this->assertEquals(1, \count($result));
    }
}
