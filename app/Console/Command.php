<?php

namespace App\Console;

abstract class Command {
    /**
     * Command executor
     *
     * @param array $arguments
     *
     * @return void
     */
    abstract public function execute(array $arguments): void;

    /**
     * Get command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return '';
    }
}
