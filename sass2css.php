#!/usr/bin/env php
<?php
array_shift($argv);
$formatters = [
  'compact' => '\Leafo\ScssPhp\Formatter\Compact',
  'compressed' => '\Leafo\ScssPhp\Formatter\Compressed',
  'crunched' => '\Leafo\ScssPhp\Formatter\Crunched',
  'expanded' => '\Leafo\ScssPhp\Formatter\Expanded',
  'nested' => '\Leafo\ScssPhp\Formatter\Nested',
];
$defaultFormatter = 'crunched';

$options = [
  '--verbose' => false,
  '--formatter' => $defaultFormatter,
];
foreach ($argv as $pn) {
  $parts = explode('=', $pn);
  $pn = trim($parts[0]);
  if (in_array($pn, array_keys($options))) {
    $options[$pn] = (isset($parts[1]) ? trim($parts[1]) : true);
  }
}

list($src, $tgt) = $argv;
$autoloadCandidates = [
  __DIR__ . '/vendor/autoload.php',
  __DIR__ . '/../../autoload.php',
];

$ready = false;
foreach ($autoloadCandidates as $autoload) {
  if (is_readable($autoload)) {
    include $autoload;
    $ready = true;
    if ($options['--verbose'] === true) {
      echo "Using autoload: {$autoload}\n";
    }
    break;
  }
}
if (!$ready) {
  throw new \Exception('Unable to find autoload.php');
}

use Leafo\ScssPhp\Compiler;
$scss = new Compiler();
$scss->setImportPaths([$src]);
$scss->setFormatter($formatters[$options['--formatter']]);

$files = scandir($src);
foreach ($files as $inputFile) {
  $fullSourceFile = "{$src}/{$inputFile}";
  if (in_array($inputFile, ['.', '..']) || is_dir($fullSourceFile)) continue;
  if (is_readable($fullSourceFile)) {
    $outputBaseName = str_replace('.scss', '.min.css', $inputFile);
    $outputFilename = "{$tgt}/{$outputBaseName}";
    if ($options['--verbose'] === true) {
      echo "\tCompiling: {$inputFile} as {$outputBaseName}...\n";
    }
    $output = $scss->compile('@import "'. $inputFile .'";');
    file_put_contents($outputFilename, $output);
  }
}
