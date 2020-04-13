<?php declare(strict_types=1);

namespace App\Entity;

use App\Traits\SlugTrait;
use App\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 * @UniqueEntity("title")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Program
{
    use SlugTrait;
    use TimeStampableTrait;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=75, unique=true)
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster;

    /**
     * @Vich\UploadableField(mapping="program_file", fileNameProperty="poster")
     *
     * @var File
     * @Assert\File()
     */
    private $posterFile;

    /**
     * @var Country|null
     * @ORM\ManyToOne(targetEntity=Country::class)
     */
    private $country;

    // TODO max : mettre l'année actuel
    /**
     * @var int|null
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1890,
     *      max = 2500,
     * )
     */
    private $year;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="programs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $category;

    /**
     * @var Season[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="program", orphanRemoval=true)
     */
    private $seasons;

    /**
     * @var Actor[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Actor", mappedBy="programs")
     */
    private $actors;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setSlug($title);

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function setPosterFile(File $poster = null): self
    {
        $this->posterFile = $poster;
        if ($poster) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Season[]
     */
    public function getSeasons(): array
    {
        return $this->seasons->toArray();
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
        }

        return $this;
    }

    public function getNbEpisodes(): int
    {
        return array_reduce($this->getSeasons(), function (int $carry, Season $season) {
            return $carry + \count($season->getEpisodes());
        }, 0);
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
        }

        return $this;
    }

    /**
     * @return Actor[]
     */
    public function getActors(): array
    {
        return $this->actors->toArray();
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->contains($actor)) {
            $this->actors->removeElement($actor);
            $actor->removeProgram($this);
        }

        return $this;
    }
}
