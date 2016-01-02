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
        $nodes = \Classes\Factory\Model\Model::getNodes();
//        self::$members = \Classes\Factory\Model\Model::getMembers();
//        self::$restraints = \Classes\Factory\Model\Model::getRestraintTable()->getTable();
//        self::$loadCases = \Classes\Factory\Model\Model::getLoadCases();
//        self::$loads = \Classes\Factory\Model\Model::getLoads();
//        self::$massMatrixTable = \Classes\Factory\Model\Model::getMassMatrixTable();
//        self::$loadTable = \Classes\Factory\Model\Model::getLoadTable();
//        
        
        // GET FILE WRITER
        $fileWriter = BinaryData\File\FileWriterFactory::make();
        
        // EXPORT
        $fileWriter->nodesExport($nodes);
        
        // WRITE
        file_put_contents($path, $fileWriter->getBody());
    }
    
}
