<?php
return
[
    [
        'stub' => __DIR__.'/test.stub',
        'output' => __DIR__.'/Results/first.generated',
        'replace' => [
            '{{value}}' => 'first file first value',
            '{{value2}}' => 'first file second value'
        ]
    ],
    [
        'stub' => __DIR__.'/test.stub',
        'output' => __DIR__.'/Results/second.generated',
        'replace' => [
            '{{value}}' => 'second file first value',
            '{{value2}}' => 'second file second value'
        ]
    ]
];
