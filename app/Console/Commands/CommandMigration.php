<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Console\Console;
use App\Helpers\Diamond;

class CommandMigration extends Command
{
    /**
     * Command executor.
     *
     * @param array $arguments
     *
     * @return void
     */
    public function execute(array $arguments): void
    {
        $name = strtolower($arguments[0] ?? 'migration');
        $timestamp = Diamond::now('Y_m_d_His');

        $classname = $timestamp. '_' . $name;
        $filename = "$classname.php";
        $filepath = __DIR__ . '/../../../database/migrations/' . $filename;

        $template = file_get_contents(__DIR__ . '/../../../stubs/migration.stub');

        file_put_contents($filepath, $template);

        Console::line("Migration created: $filename", 'success');
    }

    /**
     * Return command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Create new migration file';
    }
}
