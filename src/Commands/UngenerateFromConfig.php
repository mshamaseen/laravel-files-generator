<?php

namespace Shamaseen\Generator\Commands;

use Exception;
use Illuminate\Console\Command;
use Shamaseen\Generator\Ungenerator;

class UngenerateFromConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ungenerate:config {config-path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove files generated before from a config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(Ungenerator $generator)
    {
        $generator->fromConfigFile($this->argument('config-path'));
    }
}
