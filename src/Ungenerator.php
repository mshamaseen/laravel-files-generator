<?php

namespace Shamaseen\Generator;

use Exception;

class Ungenerator
{
    public function __construct()
    {
    }

    /**
     * Get absolute Path from a path.
     *
     * @param $path
     * @return string
     */
    public function absolutePath($path): string
    {
        // if the path is already absolute then return it directly
        return $path[0] === '/' ?
            $path :
            config('generator.base_path', base_path()) . "/" . $path;
    }

    /**
     * Remove an output
     * @param $filePath
     * @return bool
     */
    public function output($filePath): bool
    {
        return unlink($this->absolutePath($filePath));
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

            $this->output($config['output']);
        }
    }
}
