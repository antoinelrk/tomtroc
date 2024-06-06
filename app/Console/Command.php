<?php

namespace App\Console;

abstract class Command {

    /**
     * Command executor
     *
     * @param array $args
     *
     * @return void
     */
    abstract public function execute(array $args): void;

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
