<?php declare(strict_types=1);

namespace App\Service\Importer;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImporterCsv
{
    /**
     * @var Serializer
     */
    protected $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    public function getData(string $path): array
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException('file not found with path:' . $path);
        }

        return $this->serializer->decode(file_get_contents($path) ?: '', 'csv');
    }
}
