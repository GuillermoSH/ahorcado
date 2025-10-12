<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container flex-center">
        <h1>Juego del Ahorcado</h1>

        <?php echo $renderer->drawSvg($game->getAttemptsLeft()); ?>

        <h2 class="masked-word">
            <?php echo implode(" ", preg_split('//u', $game->getMaskedWord(), -1, PREG_SPLIT_NO_EMPTY)); ?>
        </h2>

        <div class="game-info">
            <div class="game-stat" id="attempts">
                <h3>Intentos restantes:</h3>
                <p class="attempts-value"><?php echo $game->getAttemptsLeft(); ?></p>
            </div>

            <div class="game-stat" id="used-letters">
                <h3>Letras usadas:</h3>
                <p class="used-letters-list"><?php echo implode(", ", $game->getUsedLetters()); ?></p>
            </div>
        </div>


        <?php if ($errorMessage): ?>
            <p class="error-message">🚨 <?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if ($hint): ?>
            <p class="hint-message">🔍 Se reveló la letra <strong><?php echo $hint; ?></strong>.</p>
        <?php endif; ?>

        <?php if ($game->isWon()): ?>
            <h2>🎉 ¡Ganaste! La palabra era <span class="highlight"><?php echo $game->getWord(); ?></span></h2>
            <a href="index.php?category=<?php echo urlencode($game->getCategory()); ?>">Volver a jugar</a>
            <form method="get" action="index.php" style="margin-top:10px;">
                <input type="hidden" name="category" value="">
                <button type="submit">🔄 Cambiar categoría</button>
            </form>

        <?php elseif ($game->isLost()): ?>
            <h2>💀 Perdiste. La palabra era <span class="highlight red"><?php echo $game->getWord(); ?></span></h2>
            <a href="index.php?category=<?php echo urlencode($game->getCategory()); ?>">Intentar otra vez</a>
            <form method="get" action="index.php" style="margin-top:10px;">
                <input type="hidden" name="category" value="">
                <button type="submit">🔄 Cambiar categoría</button>
            </form>

        <?php else: ?>
            <form method="post" class="game-form">
                <label for="letter" class="form-label">Introduce una letra:</label>
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