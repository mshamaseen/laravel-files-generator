<?php

namespace Shamaseen\Generator\Commands;

use Illuminate\Console\Command;
use Shamaseen\Generator\Generator;

class GenerateFromStub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:stub {stub} {output} {--replace=*?} {--with=*?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a file from stub';

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
        $replace = (array) $this->option('replace');
        $with = (array) $this->option('with');

        $generator->stub($this->argument('stub'));

        if(count($replace) !== count($with))
            throw new \Exception('Replace options count should be equal to the with options count');

        for ($i = 0; $i < count($replace); $i++)
        {
            $generator->replace($replace[$i],$with[$i]);
        }

        $generator->output($this->argument('output'));
    }
}
