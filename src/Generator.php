<?php

namespace Shamaseen\Generator;

use Exception;

class Generator
{
    private ?string $content = null;
    private ?string $basePath = null;

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
     * Get the base path from the config file or the cache if available
     * @param $path
     * @return string
     */
    private function basePath($path): string
    {
        if (!$this->basePath) {
            $this->basePath = config('generator.base_path', base_path());
        }

        // if the base still falsy after setting the config then just return the path
        return $this->basePath ? $this->basePath."/".$path : $path;
    }

    /**
     * Replace strings in the generated file
     *
     * @param $variable
     * @param $value
     * @return $this
     */
    public function replace($variable, $value): Generator
    {
        $this->content = str_replace($variable, $value, $this->content);
        return $this;
    }

    /**
     * Specify the stub file, this is the first method to be run
     * @param $stubPath
     * @return $this
     */
    public function stub($stubPath): Generator
    {
        $this->content = $this->getStub($stubPath);
        return $this;
    }

    /**
     * Validate the required parameters on a custom configuration
     * @param $config
     * @throws Exception
     */
    private function validateRequiredConfigs($config)
    {
        if (!array_key_exists('stub', $config)) {
            throw new Exception('stub is a required key in the generator configuration');
        }

        if (!array_key_exists('output', $config)) {
            throw new Exception('stub is a required key in the generator configuration');
        }
    }

    /**
     * Generate the files from a config file
     *
     * @param $configPath
     * @throws Exception
     */
    public function fromConfigFile($configPath)
    {
        $configs = require $configPath;

        //if not multidimensional array then make it multi
        if (!isset($configs[0]) || !is_array($configs[0])) {
            $configs = [$configs];
        }

        foreach ($configs as $config) {
            $this->validateRequiredConfigs($config);

            $stub = $this->stub($config['stub']);

            if (isset($config['replace'])) {
                foreach ($config['replace'] as $toReplace => $value) {
                    $stub->replace($toReplace, $value);
                }
            }

            $stub->output($config['output']);
        }
    }

    /**
     * Set the output of single generated file
     * @param $path
     * @return bool
     */
    public function output($path): bool
    {
        if ($this->fileForceContents($this->basePath($path), $this->content) === false) {
            return false;
        }

        return true;
    }

    /**
     * Generate the folder with the file
     *
     * @param $dir
     * @param $contents
     * @return false|int
     */
    private function fileForceContents($dir, $contents)
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';

        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }

        return file_put_contents("$dir/$file", $contents);
    }
}
