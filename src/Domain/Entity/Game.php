<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use InvalidArgumentException;
use RuntimeException;

final class Game
{
    public string $id;
    private string $word;
    private int $maxAttempts;
    private int $attemptsLeft;
    private array $usedLetters;
    private int $pointsPerHint;
    private string $category;

    public function __construct(
        string $id,
        string $word,
        string $category = "animales",
        int $maxAttempts = 6,
        ?array $state = null,
        int $pointsPerHint = 1,
    ) {
        $this->id = $id;
        $this->word = $word;
        $this->maxAttempts = $maxAttempts;
        $this->attemptsLeft = $maxAttempts;
        $this->usedLetters = [];
        $this->pointsPerHint = $pointsPerHint;
        $this->category = $category;

        if ($state !== null) {
            $this->word = $state["word"];
            $this->maxAttempts = $state["maxAttempts"];
            $this->attemptsLeft = $state["attemptsLeft"];
            $this->usedLetters = $state["usedLetters"];
        }
    }


    public function guessLetter(string $letter): void
    {
        $letter = mb_strtoupper($letter, 'UTF-8');
        if (mb_strlen($letter, 'UTF-8') !== 1 || !preg_match('/^\p{L}$/u', $letter)) {
            throw new InvalidArgumentException("La letra debe ser un único carácter alfabético.");
        }

        if (in_array($letter, $this->usedLetters, true)) return;

        $this->usedLetters[] = $letter;

        if (mb_strpos($this->word, $letter) === false) $this->attemptsLeft--;
    }

    public function revealHint(): ?string
    {
        if ($this->attemptsLeft <= 1) throw new RuntimeException("No puedes pedir pista, no tienes intentos suficientes.");

        $maskedWord = $this->getMaskedWord();

        $hiddenIndexes = [];
        $len = mb_strlen($this->word, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            if (mb_substr($maskedWord, $i, 1, 'UTF-8') === '_') {
                $hiddenIndexes[] = $i;
            }
        }

        $index = $hiddenIndexes[array_rand($hiddenIndexes)];
        $hint = mb_substr($this->word, $index, 1, 'UTF-8');

        $this->guessLetter($hint);
        $this->attemptsLeft = $this->attemptsLeft - $this->pointsPerHint;

        return $hint;
    }

    public function getMaskedWord(): string
    {
        $maskedWord = "";
        $letters = preg_split('//u', $this->word, -1, PREG_SPLIT_NO_EMPTY); // multibyte-safe
        foreach ($letters as $letter) {
            $maskedWord .= in_array($letter, $this->usedLetters, true) ? $letter : "_";
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

    public function getCategory(): string
    {
        return $this->category;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'word' => $this->word,
            'maxAttempts' => $this->maxAttempts,
            'attemptsLeft' => $this->attemptsLeft,
            'usedLetters' => $this->usedLetters,
            'pointsPerHint' => $this->pointsPerHint,
            'category' => $this->category,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['word'],
            $data['maxAttempts'],
            $data['category'] ?? 'animales',
            [
                'word' => $data['word'],
                'maxAttempts' => $data['maxAttempts'],
                'attemptsLeft' => $data['attemptsLeft'],
                'usedLetters' => $data['usedLetters'] ?? [],
            ],
            $data['pointsPerHint'] ?? 1,
        );
    }
}
