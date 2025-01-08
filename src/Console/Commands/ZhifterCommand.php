<?php

namespace Zydnrbrn\Zhifter\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ZhifterCommand extends Command
{
    protected $signature = 'zhifter:generate {table_name} {frontend_stack}';
    protected $description = 'Generate CRUD Pages with Rich Feature.';

    public function handle()
    {
        $tableName = $this->argument('table_name');
        $frontendStack = $this->argument('frontend_stack');
        $this->info("Generating CRUD for {$tableName}...");
        $this->generateModel($tableName);
        $this->generateController($tableName);
        $this->generateFrontendPages($tableName, $frontendStack);
    }

    protected function generateModel($name)
    {
        $modelTemplate = File::get(__DIR__ . '/../stubs/model.stub');
        $modelContent = str_replace('{{modelName}}', $name, $modelTemplate);
        File::put(app_path("/Models/{$name}.php"), $modelContent);
    }

    protected function generateController($name)
    {
        $controllerTemplate = File::get(__DIR__ . '/../stubs/controller.stub');
        $controllerContent = str_replace('{{modelName}}', $name, $controllerTemplate);
        File::put(app_path("/Http/Controllers/{$name}Controller.php"), $controllerContent);
    }

    protected function generateFrontendPages($name, $stack)
    {
        $this->nodePackageVerifications();

        $stackPath = __DIR__ . "/../stubs/{$stack}";
        $files = File::allFiles($stackPath);

        foreach ($files as $file) {
            $fileContent = File::get($file);
            $processedContent = str_replace('{{ModelName}}', $name, $fileContent);

            $destinationPath = resource_path("js/Pages/$name");
            File::ensureDirectoryExists($destinationPath);

            $fileName = $file->getFilename();
            File::put("$destinationPath/$fileName", $processedContent);
        }
    }

    protected function nodePackageVerifications()
    {
        $package = 'react';
        $output = shell_exec("npm list $package");

        if (strpos($output, 'missing') !== false) {
            $this->error("Please install $package package.");
            exit();
        } else {
            $this->info("required package is already installed, continue...");
        }
    }
}
