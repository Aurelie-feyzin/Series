<?php declare(strict_types=1);

$comments = [];

// Fixture pour user user@email.fr
$nbFixturesUser = 250;
for ($i = 1; $i <= $nbFixturesUser; ++$i) {
    $comments['App\Entity\Comment']['comment_' . $i] = [
        '__construct' => [
            'episode' => '@episode_' . $i,
            'author'  => '@user',
        ],
        'comment' => '<text(255)>',
        'rate'    => '<numberBetween(0, 5)>',
    ];
}

// Fixture pour episode 1
$nbFixturesComment = 250;
$nbUser = 500;
$nbFixturesFixed = $nbFixturesUser + $nbFixturesComment;
for ($i = $nbFixturesUser; $i <= $nbFixturesFixed; ++$i) {
    $comments['App\Entity\Comment']['comment_' . $i] = [
        '__construct' => [
            'episode' => '@episodeComment',
            'author'  => '@user_' . (($i % $nbUser) + 1),
        ],
        'comment' => '<text(255)>',
        'rate'    => '<numberBetween(0, 5)>',
    ];
}

// Random Fixtures
$nbFixturesRandom = $nbUser;
$nbFixturesTot = $nbFixturesFixed + $nbFixturesRandom;
for ($i = $nbFixturesFixed; $i <= $nbFixturesTot; ++$i) {
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
