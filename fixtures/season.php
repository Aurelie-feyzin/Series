<?php declare(strict_types=1);

$seasons = [];
$nbFixtures = 100;
$nbPrograms = 25;
for ($i = 1; $i <= $nbFixtures; ++$i) {
    $program = '$program_1';
    $seasons['App\Entity\Season']['season_' . $i] = [
        'program'     => '@program_' . (($i % $nbPrograms) + 1),
        'number'      => (int) ($i / $nbPrograms) + 1,
        'year'        => '40%? <numberBetween(1990, 2021)>',
        'description' => '60%? <text(255)>',
    ];
}

return $seasons;
