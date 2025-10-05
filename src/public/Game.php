<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use RuntimeException;

class Game
{
    private string $word;
    private int $maxAttempts;
    private int $attemptsLeft;
    private array $usedLetters;
    private int $pointsPerHint;

    public function __construct(string $word, int $maxAttempts = 6, ?array $state = null, int $pointsPerHint = 1)
    {
        $this->word = $word;
        $this->maxAttempts = $maxAttempts;
        $this->attemptsLeft = $maxAttempts;
        $this->usedLetters = [];
        if ($state !== null) {
            $this->word = $state["word"];
            $this->maxAttempts = $state["maxAttempts"];
            $this->attemptsLeft = $state["attemptsLeft"];
            $this->usedLetters = $state["usedLetters"];
        }
        $this->pointsPerHint = $pointsPerHint; 
    }

    public function guessLetter(string $letter): void
    {
        if (strlen($letter) !== 1 || !ctype_alpha($letter)) throw new InvalidArgumentException("La letra debe ser un único carácter alfabético.");
        if (in_array($letter, $this->usedLetters)) return;
        $this->usedLetters[] = $letter;
        if (strpos($this->word, $letter) === false) {
            $this->attemptsLeft--;
        }
        $this->toState();
    }

    public function revealHint(): ?string
    {
        if ($this->attemptsLeft <= 1) throw new RuntimeException("No puedes pedir pista, no tienes intentos suficientes.");

        $maskedWord = $this->getMaskedWord();

        $hiddenIndexes = [];
        for ($i = 0, $len = strlen($this->word); $i < $len; $i++) {
            if ($maskedWord[$i] === '_') $hiddenIndexes[] = $i;
        }

        $index = $hiddenIndexes[array_rand($hiddenIndexes)];
        $hint = $this->word[$index];

        $this->guessLetter($hint);
        $this->attemptsLeft = $this->attemptsLeft - $this->pointsPerHint;

        return $hint;
    }

    public function getMaskedWord(): string
    {
        $maskedWord = "";
        foreach (str_split($this->word) as $letter) {
            $maskedWord .= in_array($letter, $this->usedLetters) ? $letter : "_";
        }
        return $maskedWord;
    }

    public function getAttemptsLeft(): int
    {
        return $this->attemptsLeft;
    }

    public function getUsedLetters(): array
    {
        return $this->usedLetters;
    }

    public function isWon(): bool
    {
        return $this->getMaskedWord() === $this->word;
    }

    public function isLost(): bool
    {
        return $this->attemptsLeft === 0;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function toState(): array
    {
        return [
            "word" => $this->word,
            "maxAttempts" => $this->maxAttempts,
            "attemptsLeft" => $this->attemptsLeft,
            "usedLetters" => $this->usedLetters,
        ];
    }
}
