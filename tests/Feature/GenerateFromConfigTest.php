<?php

use Shamaseen\Generator\Tests\TestCase;
use Shamaseen\Generator\Ungenerator;

class GenerateFromConfigTest extends TestCase
{
    private string $configPath;
    private array $configs;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->configPath = __DIR__."/../test_config.php";
        $this->configs = require(__DIR__."/../test_config.php");
    }

    /**
     * @throws Exception
     */
    public function test_run_time()
    {
        $generator = new \Shamaseen\Generator\Generator();
        $generator->fromConfigFile($this->configPath);

        $this->checkFiles();
    }

    /**
     * @throws Exception
     */
    public function test_ungenerate_run_time()
    {
        $ungenerator = new Ungenerator();
        $ungenerator->fromConfigFile($this->configPath);

        $this->assertFileDoesNotExist($this->configs[0]['output']);
        $this->assertFileDoesNotExist($this->configs[1]['output']);
    }

    public function test_command_line()
    {
        $this->artisan("generate:config ".$this->configPath)
            ->assertExitCode(0);

        $this->checkFiles();
    }

    /**
     * @throws Exception
     */
    public function test_ungenerate_command_line()
    {
        $this->artisan("ungenerate:config ".$this->configPath)
            ->assertExitCode(0);

        $this->assertFileDoesNotExist($this->configs[0]['output']);
        $this->assertFileDoesNotExist($this->configs[1]['output']);
    }

    private function checkFiles()
    {
        $this->assertFileExists($this->configs[0]['output']);
        $this->assertStringEqualsFile(
            $this->configs[0]['output'],
            "This is only a testing stub file, this first file first value should be changed to 'changed!' and this first file second value should be changed to 'double check'\n"
        );

        $this->assertFileExists($this->configs[1]['output']);
        $this->assertStringEqualsFile(
            $this->configs[1]['output'],
            "This is only a testing stub file, this second file first value should be changed to 'changed!' and this second file second value should be changed to 'double check'\n"
        );
    }
}
