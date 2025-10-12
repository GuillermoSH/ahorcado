<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Seleccionar categoría - Ahorcado</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container flex-center">
        <h1>Juego del Ahorcado</h1>

        <form method="get" class="select-category-form">
            <label for="category">Selecciona una categoría:</label>
            <div class="custom-select-wrapper">
                <select autofocus name="category" id="category" required>
                    <option value="animales">🐾 Animales</option>
                    <option value="frutas">🍎 Frutas</option>
                    <option value="paises">🌍 Países</option>
                    <option value="deportes">⚽ Deportes</option>
                    <option value="peliculas">🎬 Películas</option>
                </select>
                <span class="select-arrow">▾</span>
            </div>
            <button type="submit" class="btn-primary">Iniciar partida</button>
        </form>
    </div>

</body>

</html>