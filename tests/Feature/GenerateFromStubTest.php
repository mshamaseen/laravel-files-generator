<?php

use JohnDoe\BlogPackage\Tests\TestCase;

class GenerateFromStubTest extends TestCase
{
    function test_run_time()
    {
        $generator = new \Shamaseen\Generator\Generator();
        $generator->stub(__DIR__."/../test.stub")
            ->replace('{{value}}','changed!')
            ->replace('{{value2}}','double check')
            ->output(__DIR__."/../Results/test.generated");

        $this->assertFileExists(__DIR__."/../Results/test.generated");

        $this->assertStringEqualsFile(__DIR__."/../Results/test.generated",
            "This is only a testing stub file, this changed! should be changed to 'changed!' and this double check should be changed to 'double check'\n");

        //remove the file after testing
        unlink(__DIR__."/../Results/test.generated");
    }

    function test_command_line(){
        $this->artisan("generate:stub ".__DIR__."/../test.stub ".__DIR__."/../Results/test.generated --replace='{{value}}' --with='changed!' --replace='{{value2}}' --with='double check'")
            ->assertExitCode(0);

        $this->assertFileExists(__DIR__.'/../Results/test.generated');

        $this->assertStringEqualsFile(__DIR__."/../Results/test.generated",
            "This is only a testing stub file, this changed! should be changed to 'changed!' and this double check should be changed to 'double check'\n");

        unlink(__DIR__."/../Results/test.generated");
    }
}
