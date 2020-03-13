<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Actor;
use App\Entity\Program;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ActorTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    public function getPartialActor(): Actor
    {
        $program = (new ProgramTest())->getProgram();

        $actor = new Actor();
        $actor->addProgram($program);

        return $actor;
    }

    public function setFirstnameActor(Actor $actor): Actor
    {
        $actor->setFirstname('Prenom');

        return $actor;
    }

    public function setLastnameActor(Actor $actor): Actor
    {
        $actor->setLastname('Nom');

        return $actor;
    }

    public function getActor(): Actor
    {
        $actor = $this->getPartialActor();
        $actor = $this->setLastnameActor($actor);

        return $this->setFirstnameActor($actor);
    }

    public function testValidActor(): void
    {
        $actor = $this->getActor();

        $this->assertHasErrors($actor, 0);
        $this->assertInstanceOf(Actor::class, $actor);
    }

    public function testEmptyFirstname(): void
    {
        $actor = $this->getPartialActor();
        $actor = $this->setLastnameActor($actor);
        $this->assertHasErrors($actor, 1);
    }

    public function testEmptyLastname(): void
    {
        $actor = $this->getPartialActor();
        $actor = $this->setFirstnameActor($actor);
        $this->assertHasErrors($actor, 1);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/actorTest.yaml',
            ]
        );
    }

    public function testDisplayNameUnique(): void
    {
        $this->loadFixture();

        $actor = $this->getActor();
        $this->assertHasErrors($actor, 1);
    }

    public function testAddProgram(): void
    {
        $newProgram = (new ProgramTest())->getProgram();
        $newProgram = $newProgram->setTitle('New Program');
        $actor = $this->getActor();
        $actor->addProgram($newProgram);
        $this->assertEquals(2, \count($actor->getPrograms()));
        // Check not add the same Program twice
        $actor->addProgram($newProgram);
        $this->assertEquals(2, \count($actor->getPrograms()));
    }

    public function testRemoveProgram(): void
    {
        $actor = $this->getActor();
        $programs = $actor->getPrograms();
        $actor->removeProgram($programs[0]);
        $this->assertEquals(0, \count($actor->getPrograms()));
    }

    public function testLoadAllActor(): void
    {
        $this->loadFixture();

        $allActors = $this->doctrine->getRepository(Actor::class)->findAll();
        $this->assertEquals(1, \count($allActors));
    }
}
