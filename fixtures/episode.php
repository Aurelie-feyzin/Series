<?php declare(strict_types=1);

$episodes = [];
$nbFixtures = 300;
for ($i = 1; $i <= $nbFixtures; ++$i) {
    $program = '$program_1';
    $episodes['App\Entity\Episode']['episode' . $i] = [
        'season'   => '@season_' . (($i % 30) + 1),
        'title'    => '<sentence(5, true)>',
        'number'   => (int) ($i / 30) + 1,
        'synopsis' => '60%? <text(255)>',
    ];
}

return $episodes;
