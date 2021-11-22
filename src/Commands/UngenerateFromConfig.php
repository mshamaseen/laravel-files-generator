<?php

namespace Shamaseen\Generator\Commands;

use Illuminate\Console\Command;
use Shamaseen\Generator\Generator;

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
    protected $description = 'Generate files from config';

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
     */
    public function handle(Generator $generator)
    {
        $generator->fromConfigFile($this->argument('config-path'));
    }
}
