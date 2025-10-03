<?php
    declare(strict_types=1);

    class Renderer {
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

        public function ascii(int $attemptsLeft): string {
            return "<pre>" . $this->ascii[$attemptsLeft] ?? $this->ascii[0] . "</pre>";
        }
    }
?>