<?php

namespace App\Core;

class View {
    public function render(string $template, array $data) {
        // TODO: Testing with template engine (blade / twig?), not for final project.
        include $template . '.php';
    }
}
