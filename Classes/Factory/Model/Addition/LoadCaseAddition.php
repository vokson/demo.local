<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Load Case to Model
 *
 * @author Noskov Alexey
 */
class LoadCaseAddition {
   
    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\LoadCase\LoadCase $object
     */
    public static function add($object) {
        // ADD LOAD CASE
        \Classes\Factory\Model\Model::addInstance($object);
    
        //GET MASS MATRIX TABLE
        $massTable = \Classes\Factory\Model\Model::getMassMatrixTable();
        
        $masses = json_decode($object->getProperty('masses')->get(), TRUE);
        
       if (!empty($masses)) {
            $connection = new \Classes\Factory\Connection\MassMatrixConnection\MassMatrixConnection($masses);
            $massTable->setConnection($object->getUin(), $connection);
        }
       
    }
}
