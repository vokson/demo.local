<?php

namespace Classes\Factory\Export\BinaryData\Document;

/**
 * Class to create Document Writer
 *
 * @author Noskov Alexey
 */
class DocumentWriterFactory {
    
    private static $maxAvailableVersion  = array(
        'Node' => 1,
        'Member' => 1,
        'Restraint' => 1,
        'Load' => 1,
        'LoadCase' => 1
    );
    
    private static $docWriterNames  = array(
        'Node' => 'Node',
        'Member' => 'Member',
        'RestraintConnection' => 'Restraint',
        'NodeLoad' => 'Load',
        'ConcenratedMemberLoad' => 'Load',
        'DistributedMemberLoad' => 'Load',
        'LoadCase' => 'LoadCase'
    );
    
    /*
     * Create Document Writer class
     * 
     * @param string $class Class name of objects to be written by Document Writer
     * @param int $version Number of version
     * 
     * @return DocumentWriter
     * @return NULL
     */
    static public function make($class, $version = NULL) {
        
        if (!isset(self::$docWriterNames[$class])) {
            return NULL;
        }
        
        $type = self::$docWriterNames[$class];
        
        if (is_null($version) || $version > self::$maxAvailableVersion[$type]) {
            $version = self::$maxAvailableVersion[$type];
        }
        
        $className = '\Classes\Factory\Export\BinaryData\Document\Version\\' . $type.'DocWriter_' . $version;
        
        return new $className();
    }
    
}
