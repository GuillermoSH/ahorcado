<?php
    declare(strict_types=1);

    namespace App\Infrastructure\Persistence;

    class WordProvider {
        private string $filePath;

        public function __construct(string $filePath) {
            $this->filePath = $filePath;
        }

        public function getRandomWord(): string {
            $words = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            return strtoupper($words[array_rand($words)]);
        }
    }
?>