### Info
- These extended classed are used to update the Vips processor for Zoomify.php, the new processor `ExtendVips` can be used for process which accepts the `tileFormat` config property
- There are some issues with the implementation though ( due to my limitation on PHP ), but with the new changes, the run.php can now accept new image configs to create different tileSize ( GD has problem with > 256px tiles ) and tileLayout ( default on zoomify )
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

#### Note for dz layout
- In this layout, to make sure the files are generated into the correct folder, we should add the output name like: {out_folder}/output, this will create a output.dzi and output_files folder to the directory

#### Example
```
// Set your configuration options
$config = [
    'tileLayout' => 'deepzoom'
    'tileSize' => 512,
    'tileOverlap' => 0,
    'tileQuality' => 100,
    'destinationRemove' => true,
    'processor' => 'Vips'
];
```

