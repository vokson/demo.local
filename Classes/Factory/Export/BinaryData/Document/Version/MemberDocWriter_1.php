<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class MemberDocWriter_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {
    
    public function __construct() {
        $this->number = 20;
        $this->version = 1;
    }
    
    /*
     * Prepare binary data of object
     * 
     * @param string $uin Object's uin
     * @param \Classes\Instance\Member\Member $object
     * 
     * @return string Binary data of encoded object
     */
    protected function add($uin, $object) {
        
        $data = '';
        
        // ID
        $data .= pack('I', $object->getProperty('id')->get());
        
        // NODES
        $objectConnections = \Classes\Factory\Model\Model::getHashTable()->getConnection($uin);
        $nodeUins = array_keys($objectConnections);
        $idNode1 = \Classes\Factory\Model\Model::getNodes()[$nodeUins[0]]->getProperty('id')->get();
        $idNode2 = \Classes\Factory\Model\Model::getNodes()[$nodeUins[1]]->getProperty('id')->get();
        
        $data .=pack('I', $idNode1);
        $data .=pack('I', $idNode2);
        
        // PINS
        $pins = array_values($objectConnections);
        $pin1 = 0; $pin2 = 0;
        if ($pins[0] instanceof \Classes\Factory\Connection\SixFreedomConnection\PinConnection) {
            $pin1 = $pins[0]->get();
        }
        if ($pins[1] instanceof \Classes\Factory\Connection\SixFreedomConnection\PinConnection) {
            $pin2 = $pins[1]->get();
        }
        
        $data .=pack('S', $pin1);
        $data .=pack('S', $pin2);
        
        // BETA ANGLE
        $data .= pack('s', $object->getProperty('betaAngle')->get());
        
        // SECTION
        $section = '';
        $data .= pack('S', $this->prepareString($object->getProperty('section')->get(), $section));
        $data .= $section;

        // NAME
        $name = '';
        $data .= pack('S', $this->prepareString($object->getProperty('name')->get(), $name));
        $data .= $name;
        
        return $data;
    }
}
