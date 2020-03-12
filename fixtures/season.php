<?php declare(strict_types=1);

$seasons = [];
$nbFixtures = 30;
for ($i = 1; $i <= $nbFixtures; ++$i) {
    $program = '$program_1';
    $seasons['App\Entity\Season']['season_' . $i] = [
        'program'     => '@program_' . (($i % 10) + 1),
        'number'      => (int) ($i / 10) + 1,
        'year'        => '40%? <numberBetween(1990, 2021)>',
        'description' => '60%? <text(255)>',
    ];
}

return $seasons;
