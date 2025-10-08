<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Renderer
{
    private int $maxAttempts;
    private array $ascii = [
        6 => "
            ______
            |    |
            |    
            |    
            |    
            |    
            |______
            ",
        5 => "
            ______
            |    |
            |    O
            |    
            |    
            |    
            |______
            ",
        4 => "
            ______
            |    |
            |    O
            |    |
            |    
            |    
            |______
            ",
        3 => "
            ______
            |    |
            |    O
            |   /|
            |    
            |    
            |______
            ",
        2 => "
            ______
            |    |
            |    O
            |   /|\\
            |    
            |    
            |______
            ",
        1 => "
            ______
            |    |
            |    O
            |   /|\\
            |   / 
            |    
            |______
            ",
        0 => "
            ______
            |    |
            |    O
            |   /|\\
            |   / \\
            |    
            |______
            ",
    ];

    public function __construct(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    public function ascii(int $attemptsLeft): string
    {
        return $this->ascii[$attemptsLeft] ?? $this->ascii[6];
    }

    public function drawSvg(int $attemptsLeft): string
    {
        $staticParts = [
            '<line x1="5" y1="135" x2="115" y2="135" />',
            '<line x1="30" y1="135" x2="30" y2="20" />',
            '<line x1="30" y1="20" x2="80" y2="20" />',
            '<line x1="30" y1="40" x2="50" y2="20" />',
        ];

        $bodyParts = [
            '<line x1="80" y1="20" x2="80" y2="35" />',
            '<circle cx="80" cy="45" r="8" />',
            '<line x1="80" y1="53" x2="80" y2="80" />',
            '<line x1="80" y1="60" x2="68" y2="70" />',
            '<line x1="80" y1="60" x2="92" y2="70" />',
            '<line x1="80" y1="80" x2="70" y2="100" />',
            '<line x1="80" y1="80" x2="90" y2="100" />',
        ];

        $totalParts = count($bodyParts);
        $fails = $this->maxAttempts - $attemptsLeft;

        $ratio = $fails / $this->maxAttempts;
        if ($attemptsLeft <= 0) {
            $ratio = 1.0;
        }

        $visibleParts = min($totalParts, (int) floor($ratio * $totalParts));


        ob_start();
        ?>
        <div class="hangman-wrapper small">
            <svg id="hangman" viewBox="0 0 120 140">
                <?php foreach ($staticParts as $line): ?>
                    <g class="part static"><?= $line ?></g>
                <?php endforeach; ?>

                <?php foreach ($bodyParts as $i => $part): ?>
                    <g class="part body <?= $i < $visibleParts ? 'visible' : '' ?>">
                        <?= $part ?>
                    </g>
                <?php endforeach; ?>
            </svg>
        </div>
        <?php
        return ob_get_clean();
    }
}
?>