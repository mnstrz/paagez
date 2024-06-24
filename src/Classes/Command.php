<?php

namespace Monsterz\Paagez\Classes;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Command
{
	public static function artisan($command)
	{
		\Artisan::call($command);
	}

    public static function migrate()
    {
        \Artisan::call('migrate');
    }

    public static function storageLink()
    {
        \Artisan::call('storage:link');
    }

	public static function runComposerInstall()
    {
        $process = new Process([config('paagez.composer').'/composer', 'install','--working-dir',base_path()]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

    public static function requirePackage($package,$version)
    {
        if (!self::isValidPackageName($package) || !self::isValidPackageVersion($version)) {
            throw new \Exception('Invalid package name or version');
        }
        $command = [config('paagez.composer').'/composer','require', $package.":".$version,'--working-dir',base_path()];
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

    private static function isValidPackageName($package)
    {
        return preg_match('/^[a-z0-9\-\/]+$/', $package);
    }

    private static function isValidPackageVersion($version)
    {
        return preg_match('/^[a-z0-9\.\-\*]+$/', $version);
    }
}