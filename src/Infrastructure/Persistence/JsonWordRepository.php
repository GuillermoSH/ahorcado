<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\WordRepositoryInterface;

final class JsonWordRepository implements WordRepositoryInterface
{
    public function __construct(private string $file) {}

    public function randomWord(string $category): string
    {
        $content = file_get_contents($this->file);
        $data = $content ? json_decode($content, true) : ['words' => []];
        $words = $data['words'][$category] ?? [];
        if (!$words) throw new \RuntimeException('No hay palabras en words.json');
        return (string)$words[array_rand($words)];
    }
}