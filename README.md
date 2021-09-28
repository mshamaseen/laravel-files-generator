# laravel-files-generator
Generate any kind of file/s from stubs with only a single command, literally, ANY KIND.

## Quickstart

To generate a **single file:** 

From command line:
```php
php artisan generate:stub pathToStub.stub pathToOutput.extension --replace='key 1' --with='value 1' --replace="key 2" --with='value 2'
```

On runtime:

```php
FilesGenerator::stub('PathToYourStubFile.extension')
        ->replace('string to be replaced','The replacement value')
        ->output('outputPath.extension');
```

To generate **multiple files:**

From command line:
```php
php artisan generate:config 'pathToYourConfig/configFileName.php'
```

On runtime:

```php
FilesGenerator::fromConfigFile('configPath.php');
```

## Installation

Run:

```composer
composer require shamaseen/laravel-files-generator
```

Publish the package config file

```php
php artisan vendor:publish --provider='Shamaseen\Generator\GeneratorServiceProvider'
```

## Configuration files

To generate multiple files from config you will need to create a config file first, a config file is a php file that **MUST** return an array, either one-dimensional array or multi-dimensional array, depends on how many files you want to generate.

for example, to generate 2 files from 2 stubs with a single command, first create your conf:

```php
<?php
return 
[
    [
        'stub' => 'first.stub',
        'output' => 'first.generated',
        'replace' => [
            '{% to be replaced 1 %}' => 'value',
            '{% to be replaced 2 %}' => 'value'
        ]
    ],
    [
        'stub' => 'second.stub',
        'output' => 'second.generated',
        'replace' => [
            '{% to be replaced 1 %}' => 'value',
        ]
    ]
];
```

now run this command to generate the two files:
```php
php artisan generate:config 'pathToYourConfig/configFileName.php'
```

## License
laravel files generator is a free software distributed under the terms of the **MIT** license.
