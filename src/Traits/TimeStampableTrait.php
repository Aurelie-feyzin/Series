<?php declare(strict_types=1);

namespace App\Traits;

use DateTimeImmutable;

trait TimeStampableTrait
{
    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="created_at", type="datetime_immutable", nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=false)
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTimeImmutable('now');

        $this->setUpdatedAt($dateTimeNow);

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function setUpdatedAt(DateTimeImmutable $dateTimeNow): self
    {
        $this->updatedAt = $dateTimeNow;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
