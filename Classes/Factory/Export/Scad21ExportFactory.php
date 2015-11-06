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
        
        // Write Nodes
        foreach (\Classes\Factory\Model\Model::getNodes() as $object) {
            $s =  'this->nodes.push_back(Node(';
            $s .= $object->getProperty('id')->get();
            $s .= ',';
            $s .= '"'.$object->getProperty('name')->get().'"';
            $s .= ',';
            $s .= $object->getProperty('x')->get();
            $s .= ',';
            $s .= $object->getProperty('y')->get();
            $s .= ',';
            $s .= $object->getProperty('z')->get();
            $s .= '));';
            $txt[] =  $s;
        }
        
        $txt[] =  '};';
        // FINISH
        file_put_contents($path, implode("\r\n", $txt));
    }
}
