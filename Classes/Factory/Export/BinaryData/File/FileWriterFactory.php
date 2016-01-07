<?php

namespace Classes\Factory\Export\BinaryData\File;

/**
 * Class to create File Writer
 *
 * @author Noskov Alexey
 */
class FileWriterFactory {
    
    const maxAvailableVersion = 1;
    
    /*
     * Create File Writer class
     * 
     * @param int $version Number of version
     */
    static public function make($version = NULL) {
        if (is_null($version) || $version > FileWriterFactory::maxAvailableVersion) {
            $version = FileWriterFactory::maxAvailableVersion;
        }
        
        $class = '\Classes\Factory\Export\BinaryData\File\Version\FileWriterVersion_' . $version;
        
        return new $class();
    }
    
}
