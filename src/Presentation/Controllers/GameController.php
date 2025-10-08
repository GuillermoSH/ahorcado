<?php
    namespace App\Presentation\Controllers;

    use App\Domain\Entity\Game;
    class GameController {
        private int $maxAttempts;
        private int $pointsPerHint;
        private int $category;

        public function __construct(mixed $config) {
            $this->maxAttempts = $config['game']['max_attempts'];
            $this->pointsPerHint = $config['game']['points_per_hint'];
        }

        public function createNewGame(string $word): Game {
            return new Game(1,$word, $this->maxAttempts, null, $this->pointsPerHint);
        }

        public function handle() {}
    }
?>