<?php

namespace Jaffran\PeddosLaravelTools\Actions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateFileFromStubAction
{
    public function execute(string $stub, string $filenameSuffix, string $fileLocation, string $name): void
    {
        $className = ucfirst($name);

        $stubPath = dirname(__DIR__, 2) . '/stubs/';
        $content = File::get($stubPath . "$stub.stub");

        $content = str_replace('{name}', Str::lower($className), $content);
        $content = str_replace('{Name}', ucfirst($className), $content);

        File::ensureDirectoryExists(app_path($fileLocation));
        File::put(app_path("$fileLocation" . $className . '.php'), $content);
    }
}
