<?php
declare(strict_types=1);

return [
    'storage' => [
        'words_file' => __DIR__ . '/../storage/words.json',
        'games_file' => __DIR__ . '/../storage/games.json',
    ],
    'game' => [
        'max_attempts' => 7,
        'default_category' => 'animals',
        'points_per_hint' => 1,
    ],
];