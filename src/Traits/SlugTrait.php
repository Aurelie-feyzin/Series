<?php declare(strict_types=1);

namespace App\Traits;

use Cocur\Slugify\Slugify;

trait SlugTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75, unique=true)
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($slug);

        return $this;
    }
}
