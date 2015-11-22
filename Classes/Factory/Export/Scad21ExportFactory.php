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
    private static $loads;
    private static $massMatrixTable;
    private static $loadTable;
    
    
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
        self::$loads = \Classes\Factory\Model\Model::getLoads();
        self::$massMatrixTable = \Classes\Factory\Model\Model::getMassMatrixTable();
        self::$loadTable = \Classes\Factory\Model\Model::getLoadTable();
        
        
        // START
        self::$txt[] =  '#include "stdafx.h"';
        self::$txt[] =  '#include "Model.h"';
        self::$txt[] =  'Model::Model() {';
        
       //EXPORT
       self::nodesExport();
       self::memberExport();
       self::restraintExport();
       self::loadCaseExport();
       self::loadExport();
        
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
            if ($connection instanceof \Classes\Factory\Connection\SixFreedomConnection\RestraintConnection) {
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
     * Export of loads
     * 
     * @return void
     */
    private function loadExport() {
        self::$txt[] = "// +++ LOADS +++";
        self::$txt[] = "std::vector <double> loadVector;";
        
        foreach (self::$loads as $loadUin => $load) {
            
            // Clear vector
            self::$txt[] = "loadVector.clear();";
            
            $loadCase = $load->getProperty('loadCase')->get();
            $loadDirection = $load->getProperty('direction')->get();
            
            $connectArray = self::$loadTable->getConnection($loadUin);
            $instanceTargetUin = array_keys($connectArray)[0];
            $coordinateSystem = array_values($connectArray)[0];
            
            // NODE LOAD
            if ($load instanceof \Classes\Instance\Load\Node\NodeLoad) {
                 self::$txt[] = "// Nodal Load";
                
                $id = self::$nodes[$instanceTargetUin]->getProperty('id')->get();
                $loadType = 0;
                $value = $load->getProperty('value')->get();
                
                //Fill vector with load data
                self::$txt[] = "loadVector.push_back($value);";
            }
            
            // CONCENTRATED MEMBER LOAD
            if ($load instanceof \Classes\Instance\Load\Member\ConcenratedMemberLoad) {
                self::$txt[] = "// Concentrated Member Load";
                
                $id = self::$members[$instanceTargetUin]->getProperty('id')->get();
                
                if ($coordinateSystem instanceof \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem) {
                    $loadType = 15;
                }
                if ($coordinateSystem instanceof \Classes\Factory\Connection\LoadConnection\LocalCoordinateSystem) {
                    $loadType = 5;
                }
                
                $value = $load->getProperty('value')->get();
                $position = $load->getProperty('position')->get();
                
                //Fill vector with load data
                self::$txt[] = "loadVector.push_back($value);";
                self::$txt[] = "loadVector.push_back($position);";
            }
            
            // DISTRIBUTED MEMBER LOAD
            if ($load instanceof \Classes\Instance\Load\Member\DistributedMemberLoad) {
                self::$txt[] = "// Distributed Member Load";
                
                $id = self::$members[$instanceTargetUin]->getProperty('id')->get();
                
                if ($coordinateSystem instanceof \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem) {
                    $loadType = 17;
                }
                if ($coordinateSystem instanceof \Classes\Factory\Connection\LoadConnection\LocalCoordinateSystem) {
                    $loadType = 7;
                }
                
                $value1 = $load->getProperty('value1')->get();
                $value2 = $load->getProperty('value2')->get();
                $position1 = $load->getProperty('position1')->get();
                $position2 = $load->getProperty('position2')->get();
                
                //Fill vector with load data
                self::$txt[] = "loadVector.push_back($value1);";
                self::$txt[] = "loadVector.push_back($position1);";
                self::$txt[] = "loadVector.push_back($value2);";
                self::$txt[] = "loadVector.push_back($position2);";
            }
            
            self::$txt[] = "this->loads.push_back(Load($loadCase, $id, $loadType, $loadDirection, loadVector));";
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
            if ($pins[0] instanceof \Classes\Factory\Connection\SixFreedomConnection\PinConnection) {
                $pin1 = $pins[0]->get();
            }
            if ($pins[1] instanceof \Classes\Factory\Connection\SixFreedomConnection\PinConnection) {
                $pin2 = $pins[1]->get();
            }
            
            self::$txt[] = "this->members.push_back(Member($id, $name, $node1, $node2, $pin1, $pin2, $betaAngle, $section));";
        }
    }
}
