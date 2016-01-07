<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class NodeDocWriter_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {
    
    public function __construct() {
        $this->number = 10;
        $this->version = 1;
    }
    
    /*
     * Prepare binary data of object
     * 
     * @param string $uin Object's uin
     * @param \Classes\Instance\Node\Node $object
     * 
     * @return string Binary data of encoded object
     */
    protected function add($uin, $object) {
        
        $data = '';
        
        // ID
        $data .= pack('I', $object->getProperty('id')->get());
        
        // COORDINATES
        $data .= pack('d', $object->getProperty('x')->get());
        $data .= pack('d', $object->getProperty('y')->get());
        $data .= pack('d', $object->getProperty('z')->get());
        
        // NAME
        $name = '';
        $data .= pack('S', $this->prepareString($object->getProperty('name')->get(), $name));
        $data .= $name;
        
        return $data;
    }
}
