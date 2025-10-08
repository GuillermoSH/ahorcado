<?php
declare(strict_types=1);

return [
    'storage' => [
        'words_file' => '../storage/words.json',
        'games_file' => '../storage/games.json',
    ],
    'game' => [
        'max_attempts' => 10,
        'default_category' => 'animals',
        'points_per_hint' => 1,
    ],
];