<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Tests\Interfaces\FixtureInterface;
use App\Tests\Traits\AssertHasErrorsTraits;
use App\Tests\Traits\FixtureTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase implements FixtureInterface
{
    use FixtureTrait;
    use AssertHasErrorsTraits;

    /** @var LoaderInterface */
    protected $loader;

    /** @var Registry */
    protected $doctrine;

    public function getComment(): Comment
    {
        $user = (new UserTest())->getUser();
        $episode = (new EpisodeTest())->getEpisode();

        $comment = new Comment($episode, $user);
        $comment->setComment('Comment about episode')->setRate(2);

        return $comment;
    }

    public function testValidComment(): void
    {
        $comment = $this->getComment();

        $this->assertHasErrors($comment, 0);
        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function testRangeRate(): void
    {
        $comment = $this->getComment();
        $this->assertHasErrors($comment->setRate(-1), 1);
        $this->assertHasErrors($comment->setRate(0), 0);
        $this->assertHasErrors($comment->setRate(5), 0);
        $this->assertHasErrors($comment->setRate(6), 1);
    }

    public function loadFixture(): void
    {
        $this->loader->load(
            []
        );
    }
}
