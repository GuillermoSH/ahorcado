<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/Renderer.php";
require_once __DIR__ . "/Game.php";
require_once __DIR__ . "/WordProvider.php";
require_once __DIR__ . "/Storage.php";

use App\Renderer;
use App\Game;
use App\WordProvider;
use App\Storage;

$storage = new Storage();

$state = $storage->get('state');

if ($state === null) {
    $wordProvider = new WordProvider(__DIR__ . "/words.txt");
    $game = new Game($wordProvider->getRandomWord());
} else {
    $game = new Game($state['word'], $state['maxAttempts'], $state);
}

$renderer = new Renderer();
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['letter'])) {
    $letter = strtoupper($_POST['letter']);
    try {
        $game->guessLetter($letter);
    } catch (InvalidArgumentException $e) {
        $errorMessage = $e->getMessage();
    }
    $storage->set('state', $game->toState());
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
        <h1>🎯 Juego del Ahorcado</h1>

        <pre class="ascii"><?php echo $renderer->ascii($game->getAttemptsLeft()); ?></pre>

        <p><strong>Palabra:</strong> <?php echo implode(" ", str_split($game->getMaskedWord())); ?></p>
        <p><strong>Intentos restantes:</strong> <?php echo $game->getAttemptsLeft(); ?></p>
        <p><strong>Letras usadas:</strong> <?php echo implode(", ", $game->getUsedLetters()); ?></p>

        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if ($game->isWon()): ?>
            <h2>🎉 ¡Ganaste! La palabra era <span style="color:#0077ff;"><?php echo $game->getWord(); ?></span></h2>
            <?php $storage->reset(); ?>
            <a href="">Jugar de nuevo</a>

        <?php elseif ($game->isLost()): ?>
            <h2>💀 Perdiste. La palabra era <span style="color:#ff4d4d;"><?php echo $game->getWord(); ?></span></h2>
            <?php $storage->reset(); ?>
            <a href="">Intentar otra vez</a>

        <?php else: ?>
            <form method="post">
                <label for="letter">Introduce una letra:</label>
                <input type="text" id="letter" name="letter" maxlength="1" required autofocus>
                <button type="submit">Adivinar</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>