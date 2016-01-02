<?php

namespace Classes\Factory\Export\BinaryData\Document;

/**
 * Class to create Document Writer
 *
 * @author Noskov Alexey
 */
class DocumentWriterFactory {
    
    private static $maxAvailableVersion  = array(
        'Node' => 1
    );
    
    /*
     * Create Document Writer class
     * 
     * @param string $type Type of document to create
     * @param int $version Number of version
     * 
     * @return DocumentWriter
     * @return NULL
     */
    static public function make($type, $version = NULL) {
        if (!isset(self::$maxAvailableVersion[$type])) {
            return NULL;
        }
        
        if (is_null($version) || $version > self::$maxAvailableVersion[$type]) {
            $version = self::$maxAvailableVersion[$type];
        }
        
        $className = '\Classes\Factory\Export\BinaryData\Document\Version\\' . $type.'DocumentWriterVersion_' . $version;
        
        return new $className();
    }
    
}
