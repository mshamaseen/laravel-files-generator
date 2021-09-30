<?php

use JohnDoe\BlogPackage\Tests\TestCase;

class GenerateFromConfigTest extends TestCase
{
    /**
     * @throws Exception
     */
    function test_run_time()
    {
        $generator = new \Shamaseen\Generator\Generator();
        $generator->fromConfigFile(__DIR__."/../test_config.php");

        $this->checkFiles();
    }

    function test_command_line(){
        $this->artisan("generate:config ".__DIR__."/../test_config.php")
            ->assertExitCode(0);

        $this->checkFiles();
    }

    private function checkFiles()
    {
        $this->assertFileExists(__DIR__."/../Results/first.generated");
        $this->assertStringEqualsFile(__DIR__."/../Results/first.generated",
            "This is only a testing stub file, this first file first value should be changed to 'changed!' and this first file second value should be changed to 'double check'\n");

        $this->assertFileExists(__DIR__."/../Results/second.generated");
        $this->assertStringEqualsFile(__DIR__."/../Results/second.generated",
            "This is only a testing stub file, this second file first value should be changed to 'changed!' and this second file second value should be changed to 'double check'\n");

        //remove the file after testing
        unlink(__DIR__."/../Results/first.generated");
        unlink(__DIR__."/../Results/second.generated");
    }
}
