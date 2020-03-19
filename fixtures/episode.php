<?php declare(strict_types=1);

$episodes = [];
$nbFixtures = 500;
$nbSeason = 100;
for ($i = 1; $i <= $nbFixtures; ++$i) {
    $program = '$program_1';
    $episodes['App\Entity\Episode']['episode' . $i] = [
        'season'   => '@season_' . (($i % $nbSeason) + 1),
        'title'    => '<sentence(5, true)>',
        'number'   => (int) ($i / $nbSeason) + 1,
        'synopsis' => '60%? <text(255)>',
    ];
}

return $episodes;
