<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

class History
{
    private string $filePath;

    public function __construct(string $filePath = '../../../storage/logs/history.log')
    {
        $this->filePath = $filePath;
    }

    public function log(string $word, bool $won, int $attemptsLeft): void
    {
        $dir = dirname($this->filePath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $result = $won ? "GANADO" : "PERDIDO";
        $entry = date('Y-m-d H:i:s') . " | Palabra: $word | Resultado: $result | Intentos restantes: $attemptsLeft\n";

        file_put_contents($this->filePath, $entry, FILE_APPEND);
    }
}
