<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class RestraintConnectionDocumentWriterVersion_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {
    
    public function __construct() {
        $this->number = 30;
        $this->version = 1;
    }
    
    /*
     * Prepare binary data of object
     * 
     * @param string $uin Uin of node to apply restraint
     * @param Classes\Factory\Connection\SixFreedomConnection\RestraintConnection $object
     * 
     * @return string Binary data of encoded object
     */
    protected function add($uin, $object) {
        $data = '';
        
        // ID
        $id = \Classes\Factory\Model\Model::getNodes()[$uin]->getProperty('id')->get();
        $data .= pack('I', $id);
        
        // RESTRAINT
        $data .= pack('S', $object->get());
        
        return $data;
    }
}
