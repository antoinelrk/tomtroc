<?php

namespace App\Core;

class ViewRenderer {
    public function render(string $template, array $data = null) {
        // TODO: Add layout système
        include __DIR__ . '/../Views/' . str_replace('.', '/', $template) . '.php';
    }
}
