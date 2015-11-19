<?php

namespace Classes\Factory\Export;

/**
 * Class of Export Factory
 *
 * @author Noskov Alexey
 */
class Scad21ExportFactory extends ExportFactory {
    
    // Array for text lines
    private static $txt = array();
    // Collections
    private static $nodes;
    private static $members;
    private static $restraints;
    private static $loadCases;
    private static $massMatrixTable;
    
    
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @return void
     */
    public static function export($path) {
        self::$nodes = \Classes\Factory\Model\Model::getNodes();
        self::$members = \Classes\Factory\Model\Model::getMembers();
        self::$restraints = \Classes\Factory\Model\Model::getRestraintTable()->getTable();
        self::$loadCases = \Classes\Factory\Model\Model::getLoadCases();
        self::$massMatrixTable = \Classes\Factory\Model\Model::getMassMatrixTable();
        
        
        // START
        self::$txt[] =  '#include "stdafx.h"';
        self::$txt[] =  '#include "Model.h"';
        self::$txt[] =  'Model::Model() {';
        
       //EXPORT
       self::nodesExport();
       self::memberExport();
       self::restraintExport();
       self::loadCaseExport();
        
        self::$txt[] =  '};';
        // FINISH
        file_put_contents($path, implode("\r\n", self::$txt));
    }
    
    /*
     * Export of nodes
     * 
     * @return void
     */
    private function nodesExport() {
        self::$txt[] = "// +++ NODES +++";
     
        foreach (self::$nodes as $object) {
         
            $id = $object->getProperty('id')->get();
            $name = '"'.$object->getProperty('name')->get().'"';
            
            $x = $object->getProperty('x')->get();
            $y = $object->getProperty('y')->get();
            $z = $object->getProperty('z')->get();
            
            self::$txt[] = "this->nodes.push_back(Node($id, $name, $x, $y, $z));";
        }
    }
    
    /*
     * Export of restraints
     * 
     * @return void
     */
    private function restraintExport() {
        self::$txt[] = "// +++ RESTRAINTS +++";
        foreach (self::$restraints as $nodeUin => $connection) {
            // this->members.push_back(Restraint(1, 32));
            
            $id = self::$nodes[$nodeUin]->getProperty('id')->get();
            
            // Get RESTRAINT
            $restraint = 0;
            if ($connection instanceof \Classes\Factory\Connection\RestraintConnection) {
                $restraint = $connection->get();
            }
            
            self::$txt[] = "this->restraints.push_back(Restraint($id, $restraint));";
        }
    }
    
    /*
     * Export of load cases
     * 
     * @return void
     */
    private function loadCaseExport() {
        self::$txt[] = "// +++ LOAD CASES +++";
        self::$txt[] = "std::vector <double> massMatrixVector;";
        
        foreach (self::$loadCases as $uin => $case) {
            
            $id = $case->getProperty('id')->get();
            $name = $case->getProperty('name')->get();
            $description = $case->getProperty('description')->get();
            
            // Get Masses
            $connection = self::$massMatrixTable->getConnection($uin);
            if (is_null($connection)) {
                self::$txt[] = "this->loadCases.push_back(LoadCase($id, \"$name\", \"$description\" ));";
            } else {
                // Clear vector
                self::$txt[] = "massMatrixVector.clear();";
                
                // Find max id in array
                $massArray = $connection->get();
                $maxID = max(array_keys($massArray));
                // Fill vector with coefficients
                for ($i=0; $i <= $maxID; $i++) {
                    
                    $coefficient = 0;
                    if (isset($massArray[$i])) {
                        $coefficient = $massArray[$i];
                    }
                    
                    self::$txt[] = "massMatrixVector.push_back($coefficient);";
                }
                self::$txt[] = "this->loadCases.push_back(LoadCase($id,\"$name\", \"$description\", massMatrixVector));";
            }
        }
    }
    
    /*
     * Export of members
     * 
     * @return void
     */
    private function memberExport() {
        self::$txt[] = "// +++ MEMBERS +++";
        foreach (self::$members as $object) {
            // this->members.push_back(Member(1, "1st element", 1, 2, 32, 63, 90, "STZ RUSSIAN p_wide_h 18 TMP 1.2e-005"));
            
            $id = $object->getProperty('id')->get();
            $name = '"'.$object->getProperty('name')->get().'"';
            $betaAngle = $object->getProperty('betaAngle')->get();
            $section = '"'.$object->getProperty('section')->get().'"';
            
            //Get NODES ID
            $objectConnections = \Classes\Factory\Model\Model::getHashTable()->getConnection($object->getUin());
            $nodeUins = array_keys($objectConnections);
            
            $node1 = self::$nodes[$nodeUins[0]]->getProperty('id')->get();
            $node2 = self::$nodes[$nodeUins[1]]->getProperty('id')->get();
            
            // Get PINS
            $pins = array_values($objectConnections);
            $pin1 = $pin2 = 0;
            if ($pins[0] instanceof \Classes\Factory\Connection\PinConnection) {
                $pin1 = $pins[0]->get();
            }
            if ($pins[1] instanceof \Classes\Factory\Connection\PinConnection) {
                $pin2 = $pins[1]->get();
            }
            
            self::$txt[] = "this->members.push_back(Member($id, $name, $node1, $node2, $pin1, $pin2, $betaAngle, $section));";
        }
    }
}
