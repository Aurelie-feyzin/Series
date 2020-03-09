<?php declare(strict_types=1);

use App\Service\Importer\ImporterCsv;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

try {
    $path = dirname(__DIR__) . '/fixtures/data/list_country.csv';
    $allCountries = (new ImporterCsv())->getData($path);
    $countries = [];
    foreach ($allCountries as $index => $country) {
        $countries['App\Entity\Country']['country_' . ($index + 1)] = $country;
    }

    return $countries;
} catch (FileNotFoundException $e) {
    print_r($e);
}
