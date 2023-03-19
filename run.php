<?php
require 'vendor/autoload.php';

// use Imagick;
use DanielKm\Zoomify\Zoomify;
use DanielKm\Zoomify\ZoomifyFactory;

// Set your configuration options
$globalConfig = [
    // Add your config options here
    'titleType' => 'zoomify',
    'tileSize' => 256,
    'tileOverlap' => 0,
    'tileQuality' => 100,
    'destinationRemove' => true,
    'processor' => 'GD'
];

// Define an $images array containing the image configurations.
$images = [
    [
        'name' => 'pexels-max-rahubovskiy-5997992',
        'source' => 'input/pexels-max-rahubovskiy-5997992.jpg',
        'destination' => 'output/'.'pexels-max-rahubovskiy-5997992',
        'config' => [ /* image specific configs */
            'tileSize' => 256
        ],
        'enable' => true,
    ]
];

// Set the source image path and the destination folder for the tiles
$source = __DIR__ . '/input/pexels-max-rahubovskiy-5997992.jpg';
$destination = 'output';

// Set your configuration options
$config = [
    // Add your config options here
    "destinationRemove" => true,
];

// Setup the Zoomify library
$factory = new ZoomifyFactory();

// Loop through the $images array and process each image
foreach ($images as $image) {
    if ($image['enable']) {
        // Merge global config with image specific config
        $config = array_merge($globalConfig, $image['config']);

        $source = $image['source'];
        $destination = $image['destination'].'--'.$config['titleType'];

        // Setup the Zoomify library with the merged config
        $zoomify = $factory($config);

        // Process the source file and save tiles in the destination folder
        $result = $zoomify->process($source, $destination);
    }
}
