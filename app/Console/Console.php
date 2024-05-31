<?php

namespace App\Console;

use App\Enum\ColorsEnum;

class Console
{
    /**
     * @var array $commands
     */
    protected array $commands = [];

    /**
     * Registering commands.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public function register(string $name, string $class): void
    {
        $this->commands[$name] = $class;
    }

    /**
     * Handle command execution.
     *
     * @param array $argv
     *
     * @return void
     */
    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? null;

        if (!$commandName || !isset($this->commands[$commandName])) {
            Console::line("Command $commandName not found!", 'error');

            /**
             * TODO: Wip, format list of commands with console coloration.
             * $this->listCommands();
             */
            exit(1);
        }

        $commandClass = $this->commands[$commandName];
        $command = new $commandClass();
        $command->execute(array_slice($argv, 2));
    }

    /**
     * Dynamic loading of commands
     *
     * @return void
     */
    public function loadCommands(): void
    {
        $files = scandir(__DIR__ . '/Commands');

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $className = pathinfo($file, PATHINFO_FILENAME);
            $class = "App\\Console\\Commands\\$className";

            if (class_exists($class)) {
                $commandName = strtolower(preg_replace('/^Command/', '', $className));
                $this->register($commandName, $class);
            }
        }
    }

    /**
     * Return list of commands.
     *
     * @return void
     */
    public function listCommands(): void
    {
        echo "Available commands :\n";

        foreach ($this->commands as $name => $class) {
            echo $name . " - " . $class::getDescription() . "\n";
        }
    }

    /**
     * Return colorized console line.
     *
     * @param string $string
     * @param ?string $status
     *
     * @return void
     */
    public static function line(string $string, string $status = null): void
    {
        switch ($status) {
            case 'info':
                echo ColorsEnum::BG_LIGHT_BLUE->value .
                    " INFO " .
                    ColorsEnum::RESET->value .
                    " " .
                    $string .
                    "\n";
                break;

            case 'error':
                echo ColorsEnum::BG_DARK_RED->value .
                    " ERROR " .
                    ColorsEnum::RESET->value .
                    " " .
                    $string .
                    "\n";
                break;

            case 'success':
                echo ColorsEnum::BG_LIGHT_GREEN->value .
                    " SUCCESS " .
                    ColorsEnum::RESET->value .
                    " " .
                    $string .
                    "\n";
                break;

            case 'debug':
                echo ColorsEnum::BG_LIGHT_MAGENTA->value .
                    " DEBUG " .
                    ColorsEnum::RESET->value .
                    " " .
                    $string .
                    "\n";
                break;

            default: echo $string;
        }
    }
}
