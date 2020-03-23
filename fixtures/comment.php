<?php declare(strict_types=1);

$comments = [];

// Fixture pour user user@email.fr
$nbFixturesFix = 10;
for ($i = 1; $i <= $nbFixturesFix; ++$i) {
    $comments['App\Entity\Comment']['comment_' . $i] = [
        '__construct' => [
            'episode' => '@episode_' . $i,
            'author'  => '@user',
        ],
        'comment' => '<text(255)>',
        'rate'    => '<numberBetween(0, 5)>',
    ];
}
// Random Fixtures
$nbUser = 25;
$nbFixturesRandom = 50;
$nbFixturesTot = $nbFixturesFix + $nbFixturesRandom;
for ($i = $nbFixturesFix; $i <= $nbFixturesTot; ++$i) {
    $comments['App\Entity\Comment']['comment_' . $i] = [
        '__construct' => [
            'episode' => '@episode_*',
            'author'  => '@user_' . (($i % $nbUser) + 1),
        ],
        'comment' => '<text(255)>',
        'rate'    => '<numberBetween(0, 5)>',
    ];
}

return $comments;
