<?php declare(strict_types=1);

namespace App\Entity;

use App\Traits\TimeStampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(
 *      name="comment",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"episode_id", "author_id"})}
 * )
 * @UniqueEntity(
 *      fields={"episode","author"},
 *      errorPath="episode",
 *      message="You're alreday write a comment for this episode"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    use TimeStampableTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Episode
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Episode", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $episode;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned":true})
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     * )
     */
    private $rate;

    /**
     * Comment constructor.
     */
    public function __construct(Episode $episode, User $user)
    {
        $this->episode = $episode;
        $this->author = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
