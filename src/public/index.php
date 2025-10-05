<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . "/Renderer.php";
require_once __DIR__ . "/Game.php";
require_once __DIR__ . "/WordProvider.php";
require_once __DIR__ . "/Storage.php";
require_once __DIR__ . "/History.php";

use App\Renderer;
use App\Game;
use App\WordProvider;
use App\Storage;
use App\History;

$storage = new Storage();
$logger = new History();

$config = parse_ini_file(__DIR__ . '/config.ini');
$maxAttempts = (int) ($config['max_attempts'] ?? 6);
$defaultCategory = $config['default_category'] ?? 'animales';

$category = $_GET['category'] ?? $storage->get('category') ?? null;
$pointsPerHint = (int) $config['points_per_hint'];
$errorMessage = "";
$hint = "";

if ($category === null) {
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Seleccionar categoría - Ahorcado</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <div class="container start-screen">
            <h1>🎯 Juego del Ahorcado</h1>
            <form method="get">
                <label>Selecciona una categoría:</label>
                <select name="category" required>
                    <option value="animales">🐾 Animales</option>
                    <option value="frutas">🍎 Frutas</option>
                    <option value="paises">🌍 Países</option>
                    <option value="deportes">⚽ Deportes</option>
                    <option value="peliculas">🎬 Películas</option>
                </select>
                <button type="submit">Iniciar partida</button>
            </form>
        </div>
    </body>

    </html>
<?php
    exit;
}

$storage->set('category', $category);

$state = $storage->get('state');
if ($state === null) {
    $wordProvider = new WordProvider(__DIR__ . "/words/words_$category.txt");
    $game = new Game($wordProvider->getRandomWord(), $maxAttempts, null, $pointsPerHint);
} else {
    $game = new Game($state['word'], $state['maxAttempts'], $state, $pointsPerHint);
}

$renderer = new Renderer();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['letter'])) {
        try {
            $letter = strtoupper($_POST['letter']);
            $game->guessLetter($letter);
        } catch (InvalidArgumentException $e) {
            $errorMessage = $e->getMessage();
        }
    } elseif (isset($_POST['hint'])) {
        try {
            $hint = $game->revealHint();
        } catch (RuntimeException $e) {
            $errorMessage = $e->getMessage();
        }
    }
    $storage->set('state', $game->toState());
}

if ($game->isWon()) {
    $logger->log($game->getWord(), true, $game->getAttemptsLeft());
    $storage->reset();
} elseif ($game->isLost()) {
    $logger->log($game->getWord(), false, $game->getAttemptsLeft());
    $storage->reset();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>🎯 Juego del Ahorcado (<?php echo ucfirst($category); ?>)</h1>

        <pre class="ascii"><?php echo $renderer->ascii($game->getAttemptsLeft()); ?></pre>

        <p><strong>Palabra:</strong> <?php echo implode(" ", str_split($game->getMaskedWord())); ?></p>
        <p><strong>Intentos restantes:</strong> <?php echo $game->getAttemptsLeft(); ?></p>
        <p><strong>Letras usadas:</strong> <?php echo implode(", ", $game->getUsedLetters()); ?></p>

        <?php if ($errorMessage): ?>
            <p class="error-message">⚠️ <?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if ($hint): ?>
            <p class="hint-message">🔍 Se reveló la letra <strong><?php echo $hint; ?></strong>.</p>
        <?php endif; ?>

        <?php if ($game->isWon()): ?>
            <h2>🎉 ¡Ganaste! La palabra era <span class="highlight"><?php echo $game->getWord(); ?></span></h2>
            <a href="index.php">Volver al inicio</a>

        <?php elseif ($game->isLost()): ?>
            <h2>💀 Perdiste. La palabra era <span class="highlight red"><?php echo $game->getWord(); ?></span></h2>
            <a href="index.php">Intentar otra vez</a>

        <?php else: ?>
            <form method="post" class="game-form">
                <label for="letter">Introduce una letra:</label>
                <input type="text" id="letter" name="letter" maxlength="1" required autofocus>
                <button type="submit">Adivinar</button>
            </form>

            <form method="post" class="hint-form">
                <button name="hint" value="1" type="submit">🪄 Pedir pista (-<?php echo $pointsPerHint; ?> intento)</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>