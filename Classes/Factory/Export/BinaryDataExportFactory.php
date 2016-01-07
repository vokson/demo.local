<?php

namespace Classes\Factory\Export;

/**
 * Class of Export Factory
 *
 * @author Noskov Alexey
 */
class BinaryDataExportFactory extends ExportFactory {
    
    /*
     * Export Model to Binary Data Format
     * 
     * @param string $path path to file
     * @return void
     */
    public static function export($path) {
//        self::$restraints = \Classes\Factory\Model\Model::getRestraintTable()->getTable();
//        self::$loadCases = \Classes\Factory\Model\Model::getLoadCases();
//        self::$loads = \Classes\Factory\Model\Model::getLoads();
//        self::$massMatrixTable = \Classes\Factory\Model\Model::getMassMatrixTable();
//        self::$loadTable = \Classes\Factory\Model\Model::getLoadTable();
//        
        
        // GET FILE WRITER
        $fileWriter = BinaryData\File\FileWriterFactory::make();
        
        // EXPORT
        $fileWriter->export(\Classes\Factory\Model\Model::getNodes());
        $fileWriter->export(\Classes\Factory\Model\Model::getMembers());
        $fileWriter->export(\Classes\Factory\Model\Model::getRestraintTable()->getTable());
        $fileWriter->export(\Classes\Factory\Model\Model::getLoadCases());
        $fileWriter->export(\Classes\Factory\Model\Model::getLoads());
        
        
        // WRITE
        file_put_contents($path, $fileWriter->getBody());
    }
    
}
