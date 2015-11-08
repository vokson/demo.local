<?php

namespace Classes\Factory\Export;

/**
 * Class of Export Factory
 *
 * @author Noskov Alexey
 */
class Scad21ExportFactory extends ExportFactory {
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @return void
     */
    public static function export($path) {
        $txt = array();
        // START
        $txt[] =  '#include "stdafx.h"';
        $txt[] =  '#include "Model.h"';
        $txt[] =  'Model::Model() {';
        
        $txt[] = "// +++ NODES +++";
        $nodes = \Classes\Factory\Model\Model::getNodes();
        foreach ($nodes as $uin => $object) {
            // this->nodes.push_back(Node(1,"Node 1",0,3,0));
            
            $id = $object->getProperty('id')->get();
            $name = '"'.$object->getProperty('name')->get().'"';
            
            $x = $object->getProperty('x')->get();
            $y = $object->getProperty('y')->get();
            $z = $object->getProperty('z')->get();
            
            $txt[] = "this->nodes.push_back(Node($id, $name, $x, $y, $z));";
        }
        
        $txt[] = "// +++ MEMBERS +++";
        $members = \Classes\Factory\Model\Model::getMembers();
        foreach ($members as $uin => $object) {
            // this->members.push_back(Member(1, "1st element", 1, 2, 32, 63, 90, "STZ RUSSIAN p_wide_h 18 TMP 1.2e-005"));
            
            $id = $object->getProperty('id')->get();
            $name = '"'.$object->getProperty('name')->get().'"';
            $betaAngle = $object->getProperty('betaAngle')->get();
            $section = '"'.$object->getProperty('section')->get().'"';
            
            //Get NODES ID
            $objectConnections = \Classes\Factory\Model\Model::getHashTable()->getConnection($object->getUin());
            $nodeUins = array_keys($objectConnections);
            
            $node1 = $nodes[$nodeUins[0]]->getProperty('id')->get();
            $node2 = $nodes[$nodeUins[1]]->getProperty('id')->get();
            
            // Get PINS
            $pins = array_values($objectConnections);
            $pin1 = $pins[0]->get();
            $pin2 = $pins[1]->get();
            
            $txt[] = "this->members.push_back(Member($id, $name, $node1, $node2, $pin1, $pin2, $betaAngle, $section));";
        }
        
        $txt[] =  '};';
        // FINISH
        file_put_contents($path, implode("\r\n", $txt));
    }
}
