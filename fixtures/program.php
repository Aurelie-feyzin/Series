<?php declare(strict_types=1);
$programs = [];
$path_dir = dirname(__DIR__, 1);
$path = $path_dir . '/public/uploads/program';

for ($i = 1; $i <= 25; ++$i) {
    $programs['App\Entity\Program']['program_' . $i] = [
        'title'    => '<sentence(5, true)>',
        'synopsis' => '<text(255)>',
        'poster'   => "60%? <substr(<image($path)>, <strlen($path)>)>",
        'year'     => '40%? <numberBetween(1890, 1990)>',
        'country'  => '30%? @country_*',
        'category' => '@category_*',
    ];
}

return $programs;
