<?php

namespace Classes\Factory\Export\BinaryData\Document\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
class LoadCaseDocWriter_1 extends \Classes\Factory\Export\BinaryData\Document\DocumentWriter {

    public function __construct() {
        $this->number = 40;
        $this->version = 1;
    }

    /*
     * Prepare binary data of object
     * 
     * @param string $uin Object's uin
     * @param \Classes\Instance\LoadCase\LoadCase $object
     * 
     * @return string Binary data of encoded object
     */

    protected function add($uin, $object) {

        $data = '';

        // ID
        $data .= pack('I', $object->getProperty('id')->get());

        // NAME
        $name = '';
        $data .= pack('S', $this->prepareString($object->getProperty('name')->get(), $name));
        $data .= $name;

        // DESCRIPTION
        $description = '';
        $data .= pack('S', $this->prepareString($object->getProperty('description')->get(), $description));
        $data .= $description;

        // MASS MATRIX COEFFICIENTS
        $connection = \Classes\Factory\Model\Model::getMassMatrixTable()->getConnection($uin);
        if (is_null($connection)) {
            // Write count of coefficients = 0
            $data .= pack('S', 0);
        } else {

            // Write count of coefficients
            $data .= pack('S', count($connection->get()));
            
            // Write coefficients
            foreach ($connection->get() as $loadCaseID => $factor) {
                $data .= pack('S', $loadCaseID);
                $data .= pack('d', $factor);
            }
        }

        return $data;
    }

}
