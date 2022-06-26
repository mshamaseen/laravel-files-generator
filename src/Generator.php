<?php

namespace Shamaseen\Generator;

use Exception;
use Illuminate\Support\Str;

class Generator
{
    private ?string $content = null;

    public function __construct()
    {
    }

    /**
     * Get stub content to generate needed file.
     *
     * @return false|string
     */
    private function getStub(string $stubPath)
    {
        return file_get_contents($stubPath);
    }

    public function isWindowsAbsolutePath(string $path): bool
    {
        $pathObj = Str::of($path);
        if (!$pathObj->contains(':\\')) {
            return false;
        }

        // if path before doesn't have any \ then it is absolute
        return !$pathObj->before(':\\')->contains('\\');
    }

    public function isWindows()
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    public function isAbsolutePath(string $path)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->isWindowsAbsolutePath($path);
        }

        return '/' === $path[0];
    }

    public function windowsPathToForwardSlash(string $path)
    {
        if ($this->isWindows()) {
            return Str::replace('\\', '/', $path);
        }

        return $path;
    }

    /**
     * Get absolute Path from a path.
     *
     * @param $path
     */
    public function absolutePath($path): string
    {
        // if the path is already absolute then return it directly
        return $this->windowsPathToForwardSlash(
            $this->isAbsolutePath($path) ?
            $path :
            config('generator.base_path', base_path()).'/'.$path
        );
    }

    /**
     * Replace strings in the generated file.
     *
     * @param $variable
     * @param $value
     *
     * @return $this
     */
    public function replace($variable, $value): Generator
    {
        $this->content = str_replace($variable, $value, $this->content);

        return $this;
    }

    /**
     * Specify the stub file, this is the first method to be run.
     *
     * @param $stubPath
     *
     * @return $this
     */
    public function stub($stubPath): Generator
    {
        $this->content = $this->getStub($this->absolutePath($stubPath));

        return $this;
    }

    /**
     * Validate the required parameters on a custom configuration.
     *
     * @param $config
     *
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
     * Generate the files from a config file.
     *
     * @param $configPath
     *
     * @throws Exception
     */
    public function fromConfigFile($configPath)
    {
        $configs = require $configPath;

        // if not multidimensional array then make it multi
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
     * Set the output of single generated file.
     */
    public function output(string $path): bool
    {
        if (false === $this->fileForceContents($path, $this->content)) {
            return false;
        }

        return true;
    }

    /**
     * Generate the folder with the file.
     *
     * @param $dir
     * @param $contents
     *
     * @return false|int
     */
    private function fileForceContents($dir, $contents)
    {
        $absoluteDir = $this->absolutePath($dir);
        $parts = explode('/', $absoluteDir);

        $file = array_pop($parts);
        $finalDir = '';

        foreach ($parts as $part) {
            if (!is_dir($finalDir .= "$part/")) {
                mkdir($finalDir);
            }
        }

        return file_put_contents($finalDir.$file, $contents);
    }
}
