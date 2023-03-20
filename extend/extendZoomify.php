<?php

namespace ZoomImage;

// use Imagick;
use DanielKm\Zoomify\Zoomify;
use ZoomImage\ExtendZoomify;

class ExtendZoomify extends Zoomify
{
    // Add a new property to the config
    protected $tileLayout = 'zoomify';

    public function __construct(array $config = null)
    {
        if (is_null($config)) {
            $config = [];
        }

        $this->config = $config;
        if (isset($config['processor'])) {
            $this->processor = $config['processor'];
        }

        // Assign the new property from the config array
        if (isset($config['tileLayout'])) {
            $this->tileLayout = $config['tileLayout'];
        }
        
        // Handle the new processor case first
        if (isset($config['processor']) && $config['processor'] == 'ExtendVips') {
            require_once __DIR__ . DIRECTORY_SEPARATOR . '/ExtendZoomifyVips.php';
            $processor = new ExtendZoomifyVips();
            if (!$processor->getVipsPath()) {
                throw new \Exception('Vips path is not available.');
            }
        } else {
            // Call the parent constructor for other processors
            parent::__construct($config);
        }
    }

    /**
     * Zoomify the specified image and store it in the destination dir.
     *
     * Check to be sure the file hasn't been converted already.
     *
     * @param string $filepath The path to the image.
     * @param string $destinationDir The directory where to store the tiles.
     * @return bool
     */
    public function process($filepath, $destinationDir = '')
    {
        // If the processor is not 'ExtendVips', call the parent process method
        if ($this->processor != 'ExtendVips') {
            try {
                $result = parent::process($filepath, $destinationDir);
            } catch (\Exception $e) {
                // Handle the exception as needed
                // For example, you can throw a custom exception or log the error message
                throw new \Exception('Error during processing: ' . $e->getMessage());
            }
            return $result;
        } else {
            // If the processor is 'ExtendVips', use the custom processor
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'ExtendZoomifyVips.php';
            $processor = new ExtendZoomifyVips($this->config);
            $result = $processor->process($filepath, $destinationDir);
            return $result;
        }
    }

}
