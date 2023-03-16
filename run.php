<?php
require 'vendor/autoload.php';

// use Imagick;
use DanielKm\Zoomify\Zoomify;
use DanielKm\Zoomify\ZoomifyFactory;

// Set the source image path and the destination folder for the tiles
$source = __DIR__ . '/input/pexels-max-rahubovskiy-5997992.jpg';
$destination = 'output';

// Set your configuration options
$config = [
    // Add your config options here
];

// Setup the Zoomify library
$factory = new ZoomifyFactory();
$zoomify = $factory($config);

// Process the source file and save tiles in the destination folder
$result = $zoomify->process($source, $destination);

// Print the result
echo "Zoomify processing result: " . ($result ? 'Success' : 'Failed') . "\n";
