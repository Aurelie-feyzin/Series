<?php declare(strict_types=1);

namespace App\Entity;

use App\Traits\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 * @UniqueEntity("title")
 */
class Program
{
    use SlugTrait;

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

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
