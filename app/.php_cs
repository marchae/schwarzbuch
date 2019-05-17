<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'app',
        'assets',
        'bin',
        'features',
        'gulp',
        'node_modules',
        'var',
        'vendor',
        'web',
    ])
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => [
            'equal' => null,
            'identical' => null,
            'less_and_greater' => null,
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'single'],
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_align' => [
            'align' => 'left',
            'tags' => ['param', 'property', 'return', 'throws', 'type', 'var', 'method']
        ],
        'phpdoc_to_comment' => false,
        'no_superfluous_phpdoc_tags' => true,
    ])
    ->setFinder($finder)
;
