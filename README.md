## zoomify-generator
Use daniel-km/zoomify to generate Zoomify tile images

Origin Readme can be [found here](https://github.com/Daniel-KM/LibraryZoomify/blob/master/README.md), there is lack of explanation of the config object though, I will address them later

## Get started
### Instal dependencies (macOS)
1. Install php: `brew install php`
2. Install composer: `brew install composer`
3. Install dependencies: `composer install`
### Config object 
| Property           | Default Value | Value Type | Description | Accepted Values |
|--------------------|---------------|------------|---|-------------------|
| processor          | 'GD'          | string     | The image processing library to use | 'GD', 'Imagick', 'ImageMagick', 'Vips'  |
| filepath           | None          | string     | The path to the input image   | Any valid file path to an image file (e.g., 'input/image-path') |
| destinationDir     | None          | string     | The path to the destination directory where the tiles will be saved | Any valid directory path (e.g., 'output-path')  |
| destinationRemove  | false         | boolean    | Whether to remove existing content in the destination directory before processing  | true, false |
| dirMode            | 0755          | int        | The file system mode (permissions) for created directories  | Any valid Unix file system mode (e.g., 0755, 0775, 0777)  |
| tileSize           | 256           | int        | The size of the tiles in pixels  | Any positive integer value (e.g., 256, 512, 1024)  |
| tileOverlap        | 0             | int        | The overlap of tiles in pixels   | Any non-negative integer value (e.g., 0, 1, 2)     |
| tileFormat         | 'jpg'         | string     | The format of the output tiles   | 'jpg', 'png', 'gif', or any other supported image format by the selected processor           |
| tileQuality        | 85            | int        | The quality of the output tiles (only applicable for lossy formats like JPEG)                         | Any integer value between 1 and 100 (inclusive), where 1 is the lowest quality (highest compression) and 100 is the highest quality (lowest compression) |
| * tileLayout        | 'zoomiy'            | string        | Supported output layout for libvips  | allowed: dz, zoomify, google, iiif, iiif3 |  
### Generated files on default config
```
// Setup the Zoomify library.
$zoomify = new ExtendZoomify($config);

// Process the source file and save tiles in the destination folder
$result = $zoomify->process($source, $destination);
```
### Modify the main `run.php` to run against a list of images
https://github.com/bangank36/zoomify-generator/blob/9bfc96d33fddc3d6e05f7e8a73096eca8378700a/run.php#L116
