<?php declare(strict_types=1);

namespace App\Entity;

use App\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActorRepository")
 * @ORM\Table(
 *      name="actor",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"firstname", "lastname"})}
 * )
 * @UniqueEntity(
 *      fields={"firstname","lastname"},
 *      errorPath="display name",
 *      message="This actor is already in database"
 * )
 *  @ORM\HasLifecycleCallbacks
 */
class Actor
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
     * @var string
     *
     * @ORM\Column(type="string", length=75)
     * @Assert\NotNull()
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75)
     * @Assert\NotNull()
     */
    private $lastname;

    /**
     * @var Program[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Program", inversedBy="actors")
     */
    private $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Program[]
     */
    public function getPrograms(): array
    {
        return $this->programs->toArray();
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
        }

        return $this;
    }

    public function displayName(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}
