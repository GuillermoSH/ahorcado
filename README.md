# 🎮 Juego del Ahorcado en PHP

Este es un proyecto simple en **PHP** que implementa el clásico juego del **ahorcado** en el navegador usando sesiones.

---

## 🚀 Requisitos

- Tener docker y contenedor de docker de **PHP >= 7.4**.
- Un navegador web.
- Opcional: WSL si estás en Windows para trabajar más cómodo por línea de comandos

---

## 📂 Estructura de archivos

```
ahorcado/
│── src/public
|    │── words <---------------- Carpeta de archivos con las palabras
|    |    │── ...
|    │── config.ini <----------- Archivo de configuracion
|    │── Game.php <------------- Logica del juego general
|    │── History.php <---------- Logica de logging
|    │── index.php
|    │── Renderer.php <--------- Canva del ahorcado
|    │── Storage.php <---------- Guardado del estado de la partida
|    │── WordProvider.php <----- Recogida de la palabra
|    │── style.css 
|── .dockerignore
|── docker-compose.yml
|── Dockerfile
|── LICENSE
|── php.ini
|── README.md
```

---

## 🎨 Dibujo del ahorcado

El juego muestra el progreso del ahorcado en **ASCII** según los intentos restantes:

```
  +---+
  |   |
      |
      |
      |
      |
=========
```

```
  +---+
  |   |
  O   |
      |
      |
      |
=========
```

```
  +---+
  |   |
  O   |
  |   |
      |
      |
=========
```

```
  +---+
  |   |
  O   |
 /|   |
      |
      |
=========
```

```
  +---+
  |   |
  O   |
 /|\  |
      |
      |
=========
```

```
  +---+
  |   |
  O   |
 /|\  |
 /    |
      |
=========
```

```
  +---+
  |   |
  O   |
 /|\  |
 / \  |
      |
=========
```

---

## ▶️ Cómo ejecutar el proyecto

1. Entra a la carpeta del proyecto clonado anteriormente llamada `ahorcado` y ejecuta el comando para levantar la imagen de docker del proyecto:  

   ```bash
   docker compose up
   ```
2. Abre en el navegador:

   ```bash
   http://localhost:8080
   ```
