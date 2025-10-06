<?php
    declare(strict_types=1);

    namespace App;

    class Storage {
        private string $key;

        public function __construct(string $key = 'ahorcado') {
            $this->key = $key;
            if (!isset($_SESSION[$this->key])) {
                $_SESSION[$this->key] = [];
            }
        }

        public function get(string $name, $default = null) {
            return $_SESSION[$this->key][$name] ?? $default;
        }

        public function set(string $name, $value): void {
            $_SESSION[$this->key][$name] = $value;
        }

        public function reset(): void {
            $_SESSION[$this->key] = [];
        }
    }
?>