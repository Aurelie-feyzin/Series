<?php declare(strict_types=1);

$episodes = [];
$nbFixtures = 500;
$nbSeason = 100;

$episodes['App\Entity\Episode']['episodeComment'] = [
    'season'   => '@season_1',
    'title'    => '<sentence(5, true)>',
    'number'   => 1,
    'synopsis' => '60%? <text(255)>',
];

for ($i = 1; $i <= $nbFixtures; ++$i) {
    $episodes['App\Entity\Episode']['episode_' . $i] = [
        'season'   => '@season_' . (($i % $nbSeason) + 1),
        'title'    => '<sentence(5, true)>',
        'number'   => (int) ($i / $nbSeason) + 1,
        'synopsis' => '60%? <text(255)>',
    ];
}

return $episodes;
