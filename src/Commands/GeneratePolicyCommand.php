<?php

namespace Jaffran\LaravelTools\Commands;

use Illuminate\Console\Command;
use Jaffran\LaravelTools\Actions\GenerateFileFromStubAction;
use Exception;

class GenerateQueryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-policy {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate policy';

    /**
     * Execute the console command.
     */
    public function handle(GenerateFileFromStubAction $action)
    {
        try {
            $name = $this->argument('name');
            $action->execute('Policy', 'Policy', 'Policies/', $name);
            $this->info("$name has been fully generated!");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
