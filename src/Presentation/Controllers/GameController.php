<?php
declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Services\GameService;
use App\Infrastructure\Persistence\JsonGameRepository;
use App\Infrastructure\Persistence\JsonWordRepository;
use App\Infrastructure\Persistence\Storage;
use App\Infrastructure\Persistence\History;
use App\Domain\Entity\Renderer;
use InvalidArgumentException;
use RuntimeException;

final class GameController
{
    private GameService $gameService;
    private Renderer $renderer;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;

        $storage = new Storage();
        $gameRepo = new JsonGameRepository($config['storage']['games_file']);
        $wordRepo = new JsonWordRepository($config['storage']['words_file']);
        $logger = new History('../logs/history.log');

        $this->gameService = new GameService(
            $gameRepo,
            $wordRepo,
            $storage,
            $logger,
            $config['game']['max_attempts'],
            $config['game']['points_per_hint']
        );

        $this->renderer = new Renderer($config['game']['max_attempts']);
    }

    public function handle(): void
    {
        $currentGame = $this->gameService->getCurrentGame();
        $category = $_GET['category'] ?? $currentGame?->getCategory();

        if (!$category) {
            include __DIR__ . '/../Views/SelectCategory.php';
            return;
        }

        $game = $currentGame ?? $this->gameService->startNewGame($category);

        $errorMessage = '';
        $hint = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['letter'])) {
                    $this->gameService->processLetter($game, strtoupper($_POST['letter']));
                } elseif (isset($_POST['hint'])) {
                    $hint = $this->gameService->processHint($game);
                }
            } catch (InvalidArgumentException | RuntimeException $e) {
                $errorMessage = $e->getMessage();
            }
        }

        if ($game->isWon() || $game->isLost()) {
            $this->gameService->finishGame($game);
        }

        $renderer = $this->renderer;
        $pointsPerHint = $this->config['game']['points_per_hint'];

        include __DIR__ . '/../Views/GameView.php';
    }
}
