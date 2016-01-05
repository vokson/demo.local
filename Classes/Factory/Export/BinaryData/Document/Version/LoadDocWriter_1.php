<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class LoadDocWriter_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {

    public function __construct() {
        $this->number = 50;
        $this->version = 1;
    }

    /*
     * Prepare binary data of object
     * 
     * @param string $uin Object's uin
     * @param \Classes\Instance\Load\Load $object
     * 
     * @return string Binary data of encoded object
     */

    protected function add($uin, $object) {
        // Load information array
        $loadInfoArray = array();

        // Get information about Instance where Load is connected
        $connectionArray = \Classes\Factory\Model\Model::getLoadTable()->getConnection($uin);
        
        // COORDINATE SYSTEM
        $coordinateSystem = reset($connectionArray)->get();
        
        // TARGET UIN
        $instanceTargetUin = key($connectionArray);
        
        // NODE LOAD
        if ($object instanceof \Classes\Instance\Load\Node\NodeLoad) {
            $id = \Classes\Factory\Model\Model::getNodes()[$instanceTargetUin]->getProperty('id')->get();
            $loadType = 0;
            $loadInfoArray[] = $object->getProperty('value')->get();
        }
        
        // CONCENTRATED MEMBER LOAD
        if ($object instanceof \Classes\Instance\Load\Member\ConcenratedMemberLoad) {
            
            $id = \Classes\Factory\Model\Model::getMembers()[$instanceTargetUin]->getProperty('id')->get();
            $loadType = 1;

            $loadInfoArray[] = $object->getProperty('value')->get();
            $loadInfoArray[] = $object->getProperty('position')->get();
        }
        
        // DISTRIBUTED MEMBER LOAD
        if ($object instanceof \Classes\Instance\Load\Member\DistributedMemberLoad) {
            
            $id = \Classes\Factory\Model\Model::getMembers()[$instanceTargetUin]->getProperty('id')->get();
            $loadType = 2;

            $loadInfoArray[] = $object->getProperty('value1')->get();
            $loadInfoArray[] = $object->getProperty('position1')->get();
            $loadInfoArray[] = $object->getProperty('value2')->get();
            $loadInfoArray[] = $object->getProperty('position2')->get();
        }
        
        $data = '';
        // LOAD CASE
        $data .= pack('S', $object->getProperty('loadCase')->get()); 
        // ID
        $data .= pack('I', $id);
        // LOAD TYPE
        $data .= pack('S', $loadType);
        // COORDINATE SYSTEM
        $data .= pack('S', $coordinateSystem);
        // DIRECTION
        $data .= pack('S', $object->getProperty('direction')->get()); 
        
        // LOAD INFORMATION
        $data .= pack('S', count($loadInfoArray));
        foreach ($loadInfoArray as $value) {
            $data .= pack('d', $value);
        }
        
        return $data;
    }

}
