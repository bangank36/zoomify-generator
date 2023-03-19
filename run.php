<?php
require 'vendor/autoload.php';

// use Imagick;
use DanielKm\Zoomify\Zoomify;
use DanielKm\Zoomify\ZoomifyFactory;

// Set your configuration options
$globalConfig = [
    // Add your config options here
    'tileFormat' => 'zoomify',
    'tileSize' => 256,
    'tileOverlap' => 0,
    'tileQuality' => 100,
    'destinationRemove' => true,
    'processor' => 'GD'
];

function getFolderSizeAndFileCount($dir) {
    $size = 0;
    $count = 0;

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $file) {
        $size += $file->getSize();
        $count++;
    }

    return ['size' => $size, 'count' => $count];
}

function resizeImage($source, $destination, $newWidth) {
    // Get the original image dimensions
    list($width, $height) = getimagesize($source);

    // Calculate the new height based on the aspect ratio
    $newHeight = intval(($newWidth / $width) * $height);

    // Create a new image with the new dimensions
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Get the image extension
    $imageExtension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

    // Load the source image based on the extension
    switch ($imageExtension) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'png':
            $image = imagecreatefrompng($source);
            break;
        case 'gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            throw new Exception('Unsupported image format');
    }

    // Resize the image
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the resized image
    imagejpeg($newImage, $destination, 100);

    // Free memory
    imagedestroy($newImage);
    imagedestroy($image);

    return array(
        'fullWidth' => $newWidth,
        'fullHeight' => $newHeight,
        'originWidth' => $width,
        'originHeight' => $height,
        'fullImage' => "full.jpg",
    );
}

$output = array();

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
        $destination = $image['destination'].'--'.$config['tileFormat'];

        // Setup the Zoomify library with the merged config
        $zoomify = $factory($config);

        // Process the source file and save tiles in the destination folder
        $result = $zoomify->process($source, $destination);

        // ... rest of the code
        $folderInfo = getFolderSizeAndFileCount($destination);
        $folderSize = $folderInfo['size'] / 1048576; // Convert to megabytes
        $fileCount = $folderInfo['count'];

        // Grab the result of all images into JSON file
        $fileOutput = array(
            "image" => $image["name"].'--'.$config['tileFormat'],
            "status" => $result,
            "fileCount" => $fileCount - 1,
            "folderSize" => $folderSize,
            "tileSize" => $config['tileSize'],
            "tileOverlap" => $config['tileOverlap'],
            "format" => $config['tileFormat']
        );

        // Create a resized version of the image
        $resizedDestination = $destination . "/full.jpg";
        $imageInfo = resizeImage($source, $resizedDestination, 2500);
        
        // Concat the imageInfo with output[]
        $fileOutput = array_merge($fileOutput, $imageInfo);

        $output[] = $fileOutput;
    }
}

// Convert the array to a JSON string with pretty formatting
$json = json_encode($output, JSON_PRETTY_PRINT);

// Define the path to the output JSON file
$outputFile = 'output/output.json';

// Write the JSON string to the output file
file_put_contents($outputFile, $json);
