<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ErrorException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonRepository")
 * @ORM\Table(
 *      name="season",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"number", "program_id"})}
 * )
 * @UniqueEntity(
 *      fields={"program","number"},
 *      errorPath="number",
 *      message="This number is already exist for this program"
 * )
 */
class Season
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
     * @var Program
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Program", inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $program;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned":true})
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(1)
     */
    private $number;

    // TODO min: mettre l'année de Program, max : mettre l'année actuel
    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1890,
     *      max = 2500,
     * )
     */
    private $year;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $actualYear = (new \DateTime('now'))->format('Y');
        $programYear = $this->getProgram()->getYear();

        if ((bool) $year && (bool) $programYear && $year < $programYear) {
            throw new ErrorException('L\'année ne peux pas être inférieur à l\'année de création de la série');
        }

        if ((bool) $year && $year > (int) $actualYear + 1) {
            throw new ErrorException('L\'année ne peux pas être supérieur à l\'année prochaine');
        }

        $this->year = $year;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): self
    {
        if (isset($this->programm) && $this->program !== $program) {
            $this->program->removeSeason($this);
        }
        $program->addSeason($this);
        $this->program = $program;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
