<?php

namespace Shamaseen\Generator;

use Exception;

class Ungenerator
{
    /**
     * @var string|null
     */
    private ?string $basePath = null;

    public function __construct()
    {

    }

    /**
     * Get the base path from the config file or the cache if available
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
     * Remove an output
     * @param $filePath
     * @return bool
     */
    public function output($filePath): bool
    {
        return unlink($this->basePath($filePath));
    }

    /**
     * Validate the required parameters on a custom configuration
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
     * Generate the files from a config file
     *
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

            $this->output($config['output']);
        }
    }
}
