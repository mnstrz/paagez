<?php

namespace Monsterz\Paagez\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandPaagez extends Command
{

	protected $signature = 'paagez';
	
	protected $description = 'List all paagez commands';

	public function handle()
    {
        $this->line('Paagez: 1.0');

        $this->comment('');
        $this->comment('Available:');

        $this->listofCommands();
    }

    protected function listofCommands()
    {
        $commands = collect(Artisan::all())->mapWithKeys(function ($command, $key) {
            if (Str::startsWith($key, 'paagez:')) {
                return [$key => $command];
            }

            return [];
        })->toArray();
        $width = $this->getWidth($commands);
        foreach ($commands as $command) {
            $this->line(sprintf(" %-{$width}s %s", $command->getName(), $command->getDescription()));
        }
    }

    private function getWidth(array $commands)
    {
        $widths = [];

        foreach ($commands as $command) {
            $widths[] = static::strlen($command->getName());
            foreach ($command->getAliases() as $alias) {
                $widths[] = static::strlen($alias);
            }
        }

        return $widths ? max($widths) + 2 : 0;
    }

    public static function strlen($string)
    {
        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return strlen($string);
        }

        return mb_strwidth($string, $encoding);
    }

}