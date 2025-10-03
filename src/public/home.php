<?php
    require_once __DIR__ . "/Renderer.php";
    require_once __DIR__ . "/Game.php";
    require_once __DIR__ . "/WordProvider.php";
    require_once __DIR__ . "/Storage.php";
    $storage = new Storage();
    
    if (!isset($_SESSION["ahorcado"])) {
        $renderer = new Renderer();
        $wordProvider = new WordProvider(__DIR__ . "/words.txt");
        $game = new Game($wordProvider->getRandomWord());
        foreach ($game->toState() as $key => $value) {
            $storage->set($key, $value);
        }
    }

    if (isset($_POST['letter'])) {
        $letter = strtoupper($_POST['letter']);
        $game->guessLetter($letter);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
</head>
<body>
<h1>Juego del Ahorcado</h1>

<?php echo $renderer->ascii($storage->get("attemptsLeft")); ?>

<p>Palabra: <?php echo implode(" ", str_split($game->getMaskedWord())); ?></p>
<p>Intentos restantes: <?php echo $game->getAttemptsLeft(); ?></p>
<p>Letras usadas: <?php echo implode(", ", $game->getUsedLetters()); ?></p>

<?php if ($game->isWon() || $game->isLost()): ?>
    <a href="reset.php">Jugar de nuevo</a>
<?php else: ?>
    <form method="post">
        <label>Introduce una letra:</label>
        <input type="text" name="letra" maxlength="1" required>
        <button type="submit">Adivinar</button>
    </form>
<?php endif; ?>

</body>
</html>