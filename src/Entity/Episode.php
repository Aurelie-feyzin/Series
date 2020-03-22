<?php declare(strict_types=1);

namespace App\Entity;

use App\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EpisodeRepository")
 * @ORM\Table(
 *      name="episode",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"number", "season_id"})}
 * )
 * @UniqueEntity(
 *      fields={"season","number"},
 *      errorPath="number",
 *      message="This number is already exist for this season"
 * )
 *  @ORM\HasLifecycleCallbacks
 */
class Episode
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
     * @var Season
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned":true})
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(1)
     */
    private $number;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $synopsis;

    /**
     * @var Collection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="episode", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        if (isset($this->season) && $this->season !== $season) {
            $this->season->removeEpisode($this);
        }
        $season->addEpisode($this);
        $this->season = $season;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEpisode($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getEpisode() === $this) {
                $comment->setEpisode(null);
            }
        }

        return $this;
    }
}
