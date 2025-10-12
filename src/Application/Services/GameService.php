<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entity\Game;
use App\Infrastructure\Persistence\JsonGameRepository;
use App\Infrastructure\Persistence\JsonWordRepository;
use App\Infrastructure\Persistence\Storage;
use App\Infrastructure\Persistence\History;

final class GameService
{
    public function __construct(
        private JsonGameRepository $gameRepo,
        private JsonWordRepository $wordRepo,
        private Storage $storage,
        private History $logger,
        private int $maxAttempts,
        private int $pointsPerHint
    ) {}

    public function startNewGame(string $category): Game
    {
        $word = $this->wordRepo->randomWord($category);
        $game = new Game(uniqid(), $word, $category, $this->maxAttempts, null, $this->pointsPerHint);
        $this->storage->set('state', $game->toState());
        $this->storage->set('category', $category);
        return $game;
    }

    public function getCurrentGame(): ?Game
    {
        $state = $this->storage->get('state');
        if ($state === null) return null;
        return new Game(
            $state['id'] ?? uniqid(),
            $state['word'],
            $state['category'] ?? 'animales',
            $state['maxAttempts'],
            $state,
            $this->pointsPerHint
        );
    }

    public function processLetter(Game $game, string $letter): void
    {
        $game->guessLetter($letter);
        $this->storage->set('state', $game->toState());
    }

    public function processHint(Game $game): string
    {
        $hint = $game->revealHint();
        $this->storage->set('state', $game->toState());
        return $hint;
    }

    public function finishGame(Game $game): void
    {
        $this->logger->log($game->getWord(), $game->isWon(), $game->getAttemptsLeft());
        $this->storage->reset();
    }
}
