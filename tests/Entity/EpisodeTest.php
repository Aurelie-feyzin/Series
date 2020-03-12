<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Episode;
use App\Entity\Season;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EpisodeTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    private $loader;

    /** @var Registry */
    private $doctrine;

    /**
     * Return partial Episode (missing title and number).
     */
    public function getPartialEpisode(): Episode
    {
        $season = (new SeasonTest())->getSeason();

        $episode = new Episode();
        $episode->setSeason($season)
            ->setSynopsis('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean venenatis mauris a nisi sollicitudin, at convallis nulla accumsan. Donec convallis egestas ligula, ac rhoncus nibh sollicitudin non. Sed sit amet congue leo. Nullam scelerisque sem at cursus elementum. Donec vehicula elementum diam. Sed eleifend, magna quis vulputate fringilla, libero augue tempor mi, at auctor orci dolor ac justo. ');

        return $episode;
    }

    public function setNumber(Episode $episode): Episode
    {
        return $episode->setNumber(1);
    }

    public function setTitle(Episode $episode): Episode
    {
        return $episode->setTitle('Test_Episode');
    }

    public function getEpisode(): Episode
    {
        $episode = $this->getPartialEpisode();
        $episode = $this->setTitle($episode);

        return $this->setNumber($episode);
    }

    public function testValidEpisode(): void
    {
        $episode = $this->getEpisode();

        $this->assertHasErrors($episode, 0);
        $this->assertInstanceOf(Episode::class, $episode);
    }

    public function testNullTitle(): void
    {
        $episode = $this->getPartialEpisode();
        $episode = $this->setNumber($episode);

        $this->assertHasErrors($episode, 1);
    }

    public function testInvalidNumber(): void
    {
        $episode = $this->getPartialEpisode();
        $episode = $this->setTitle($episode);
        $this->assertHasErrors($episode, 1);

        $this->assertHasErrors($episode->setNumber(0), 1);
    }

    public function testNullSynopsis(): void
    {
        $episode = $this->getEpisode();

        $this->assertHasErrors($episode->setSynopsis(null), 0);
    }

    public function testBlankSynopsis(): void
    {
        $episode = $this->getEpisode();

        $this->assertHasErrors($episode->setSynopsis(''), 0);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            ['tests/fixtures/categoryTest.yaml',
                'tests/fixtures/programTest.yaml',
                'tests/fixtures/seasonTest.yaml',
                'tests/fixtures/episodeTest.yaml', ]
        );
    }

    public function testSeasonNumberUnique(): void
    {
        $this->loadFixture();
        // Not same program => 0 error
        $episode = $this->getEpisode();
        $this->assertHasErrors($episode, 0);
        $seasons = $this->doctrine->getRepository(Season::class)->findAll();
        // Same program and same number => 1 error
        $season = $this->getEpisode()->setSeason($seasons[0]);
        $this->assertHasErrors($season, 1);
        // Not same number => 0 error
        $season = $this->getEpisode()->setSeason($seasons[0])->setNumber(2);
        $this->assertHasErrors($season, 0);
    }
}
