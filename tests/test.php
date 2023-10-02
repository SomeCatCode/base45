<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload


$string = "Hello World!";
$base45String = \SomeCatCode\Base45::encode($string);
echo $base45String . PHP_EOL;
$plainText = \SomeCatCode\Base45::decode($base45String);
echo $plainText . PHP_EOL;
