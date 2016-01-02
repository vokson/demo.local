<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class NodeDocumentWriterVersion_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {
    
    public function __construct() {
        $this->number = 10;
        $this->version = 1;
    }
    
    /*
     * Prepare binary data of object
     * 
     * @param \Classes\Instance\Node\Node $objects
     * 
     * @return string Binary data of encoded object
     */
    protected function add($object) {
        
        $data = '';
        
        $data .= pack('I', $object->getProperty('id')->get());
        
        $data .= pack('d', $object->getProperty('x')->get());
        $data .= pack('d', $object->getProperty('y')->get());
        $data .= pack('d', $object->getProperty('z')->get());
        
        return $data;
    }
}
