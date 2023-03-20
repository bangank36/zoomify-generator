<?php

namespace ZoomImage;

require 'vendor/autoload.php';

// use Imagick;
use DanielKm\Zoomify\Zoomify;

class ExtendZoomify extends Zoomify
{
    // Add a new property to the config
    protected $tileFormat = 'zoomify';

    public function __construct(array $config = null)
    {
        // Assign the new property from the config array
        if (isset($config['tileFormat'])) {
            $this->tileFormat = $config['tileFormat'];
        }

        parent::__construct($config);

        // Add support for the new processor
        if ($this->processor == 'ExtendedVips') {
            require_once __DIR__ . DIRECTORY_SEPARATOR . '/src/ZoomifyVips.php';
            $processor = new ZoomifyVips();
            if (!$processor->getVipsPath()) {
                throw new \Exception('Vips path is not available.');
            }
        }
    }
}
