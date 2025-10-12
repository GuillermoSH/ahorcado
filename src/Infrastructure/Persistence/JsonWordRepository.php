<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\WordRepositoryInterface;

final class JsonWordRepository implements WordRepositoryInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function randomWord(string $category): string
    {
        if (!is_file($this->file)) {
            throw new \RuntimeException("No se encontró el fichero de palabras: {$this->file}");
        }

        $content = file_get_contents($this->file);
        $data = $content ? json_decode($content, true) : [];

        if (!isset($data['words'][$category])) {
            throw new \RuntimeException("La categoría '{$category}' no existe en el fichero de palabras.");
        }

        $words = $data['words'][$category];
        if (empty($words)) {
            throw new \RuntimeException("No hay palabras en la categoría '{$category}'.");
        }
        
        return strtoupper((string)$words[array_rand($words)]);
    }
}
