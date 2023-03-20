<?php

namespace ZoomImage;

use DanielKm\Zoomify\ZoomifyVips;

class ExtendZoomifyVips extends ZoomifyVips
{
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
        $this->_imageFilename = realpath($filepath);
        $this->filepath = realpath($filepath);
        $this->destinationDir = $destinationDir;
        $result = $this->createDataContainer();
        if (!$result) {
            trigger_error('Output directory already exists.', E_USER_WARNING);
            return false;
        }

        $vipsTileLayout;
        $vipsTileOutput;

        switch($this->config['tileLayout']) {
            case 'zoomify':
            default: 
                $vipsTileLayout = 'zoomify';
                $vipsTileOutput = escapeshellarg($this->_saveToLocation);
                break;
            case 'deepzoom':
                $vipsTileLayout = 'dz';
                $vipsTileOutput = escapeshellarg($this->_saveToLocation).'/output';
                break;
        }

        $command = sprintf(
            '%s dzsave %s %s --layout %s --suffix %s --overlap %s --tile-size %s --background "0 0 0" --properties',
            'vips',
            escapeshellarg($this->filepath),
            $vipsTileOutput,
            $vipsTileLayout,
            escapeshellarg('.' . $this->tileFormat . '[Q=' . (int) $this->tileQuality . ']'),
            (int) $this->tileOverlap,
            (int) $this->tileSize
        );
        $result = $this->execute($command);
        if ($result === false) {
            return false;
        }

        // For an undetermined reason, the vips xml may be saved in a sub-folder
        // on some servers.
        $filevips = $this->_saveToLocation . '/' . basename($this->_saveToLocation) . '/vips-properties.xml';
        if (file_exists($filevips)) {
            rename($filevips, $this->_saveToLocation . '/vips-properties.xml');
            rmdir($this->_saveToLocation . '/' . basename($this->_saveToLocation));
        }
        return true;
    }

    /**
     * Helper to get the command line tool vips.
     *
     * @return string
     */
    public function getVipsPath()
    {
        return 'vips';
    }
}
