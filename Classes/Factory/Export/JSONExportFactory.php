<?php

namespace Classes\Factory\Export;

/**
 * Class of Export Factory
 *
 * @author Noskov Alexey
 */
class JSONExportFactory extends ExportFactory {
    
    // Array for text lines
    private static $array;
    // Collections
    private static $members;
    private static $steelMemberCheckGroups;
    
    
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @return void
     */
    public static function export($path) {
        self::$members = \Classes\Factory\Model\Model::getMembers();
        self::$steelMemberCheckGroups = \Classes\Factory\Model\Model::getSteelMemberCheckGroups();
        
        // START
        self::$array = array();
        
       //EXPORT
       self::steelMemberCheckGroupsExport();
        
       file_put_contents($path, json_encode(self::$array));
    }
    
    /*
     * Export of check groups for steel members
     * 
     * @return void
     */
    private function steelMemberCheckGroupsExport() {
        foreach (self::$steelMemberCheckGroups as $object) {
            $group = array();
            $group['steel'] = $object->getProperty('steel')->get();
            $group['Ry'] = $object->getProperty('Ry')->get();
            $group['gammaC'] = $object->getProperty('gammaC')->get();
            $group['muXZ'] = $object->getProperty('muXZ')->get();
            $group['muXY'] = $object->getProperty('muXY')->get();
            $group['FC'] = $object->getProperty('FC')->get();
            $group['name'] = $object->getProperty('name')->get();
            
            // Make list
            $list = array();
            $listbox = $object->getProperty('listbox')->get();
            foreach ($listbox as $uin) {
                $id = self::$members[$uin]->getProperty('id')->get();
                $list[] = $id;
            }
            $group['list'] = $list;
            
            // Add group to array
            self::$array['SteelMemberCheckGroups'][] = $group;
        }
    }
}
