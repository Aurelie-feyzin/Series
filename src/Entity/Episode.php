<?php declare(strict_types=1);

namespace App\Entity;

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
 */
class Episode
{
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
}
