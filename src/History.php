<?php

declare(strict_types=1);

namespace App;

class History {
    private string $filePath;

    public function __construct(string $filePath = '../logs/history.log') {
        $this->filePath = $filePath;
    }

    public function log(string $word, bool $won, int $attemptsLeft): void {
        $result = $won ? "GANADO" : "PERDIDO";
        $entry = date('Y-m-d H:i:s') . " | Palabra: $word | Resultado: $result | Intentos restantes: $attemptsLeft\n";
        file_put_contents($this->filePath, $entry, FILE_APPEND);
    }
}
