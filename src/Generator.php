<?php

namespace Shamaseen\Generator;

use Exception;

class Generator
{
    private $content = null;
    /**
     * @var string
     */
    private $basePath = null;

    public function __construct()
    {

    }

    /**
     * Get stub content to generate needed file.
     *
     * @param string $stubPath
     * @return false|string
     */
    private function getStub(string $stubPath)
    {
        return file_get_contents($this->basePath($stubPath));
    }

    /**
     * @param $path
     * @return string
     */
    private function basePath($path): string
    {
        if(!$this->basePath)
            $this->basePath = config('generator.base_path');

        return $this->basePath."/".$path;
    }

    /**
     * @param $variable
     * @param $value
     * @return $this
     */
    public function replace($variable,$value): Generator
    {
        $this->content = str_replace($variable,$value,$this->content);
        return $this;
    }

    /**
     * @param $stubPath
     * @return $this
     */
    public function stub($stubPath): Generator
    {
        $this->content = $this->getStub($stubPath);
        return $this;
    }

    /**
     * @param $config
     * @throws Exception
     */
    private function validateRequiredConfigs($config)
    {
        if(!array_key_exists('stub',$config))
            throw new Exception('stub is a required key in the generator configuration');

        if(!array_key_exists('output',$config))
            throw new Exception('stub is a required key in the generator configuration');
    }

    /**
     * @param $configPath
     * @throws Exception
     */
    public function fromConfigFile($configPath)
    {
        $configs = require $configPath;

        //if not multidimensional array then make it multi
        if(!isset($configs[0]) || !is_array($configs[0]))
            $configs = [$configs];

        foreach ($configs as $config)
        {
            $this->validateRequiredConfigs($config);

            $stub = $this->stub($config['stub']);

            if(isset($config['replace']))
            {
                foreach ($config['replace'] as $toReplace => $value)
                {
                    $stub->replace($toReplace,$value);
                }
            }

            $stub->output($config['output']);
        }
    }

    /**
     * @param $path
     * @return bool
     */
    public function output($path): bool
    {
        if($this->file_force_contents($this->basePath($path),$this->content) === false)
            return false;

        return true;
    }

    /**
     * @param $dir
     * @param $contents
     * @return false|int
     */
    private function file_force_contents($dir, $contents){
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';

        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir);

        return file_put_contents("$dir/$file", $contents);
    }
}
