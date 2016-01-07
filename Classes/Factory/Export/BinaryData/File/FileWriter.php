<?php

namespace Classes\Factory\Export\BinaryData\File;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
abstract class FileWriter {

    protected $version; // Version of document

    /*
     * Get body of file 
     * 
     * @return string
     */

    abstract public function getBody();

    /*
     * Export of objects
     * 
     * @param mixed[] $nodes Array of objects to export
     * 
     * @return bool SUCCESS
     */

    abstract public function export($nodes);

    /*
     * Get class name of object without namespace
     * 
     * @param mixed $obj Object
     * 
     * @return string Class name without namespace
     */

    protected function getClassName($obj) {
    $classname = get_class($obj);

    if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
        $classname = $matches[1];
    }

    return $classname;
}

}
