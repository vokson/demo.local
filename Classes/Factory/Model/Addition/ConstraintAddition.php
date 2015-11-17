<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Constraint to Model
 *
 * @author Noskov Alexey
 */
class ConstraintAddition {
   
    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Node\Constraint $object
     */
    public static function add($object) {
        // GET NODES
        $nodes = array_values(\Classes\Factory\Model\Model::getNodes());
    
        //GET RESTRAINT TABLE
        $resTable = \Classes\Factory\Model\Model::getRestraintTable();
        
        // RESTRAINT
        $restraint = new \Classes\Factory\Connection\RestraintConnection($object->getProperty('fix')->get());
        
        // RESTRAINT POINT
        $resPoint = new \Classes\Utils\AbstractInstance\Point(
            $object->getProperty('x')->get(),
            $object->getProperty('y')->get(),
            $object->getProperty('z')->get()
        );
        
        // TRY TO FIND NODE FOR RESTRAINT'S APPLICATION
        $isFound = FALSE; $i=0;
        while ($isFound === FALSE && $i < count($nodes)) {
            $node = $nodes[$i];
            
            // NODE POINT
            $nodePoint = new \Classes\Utils\AbstractInstance\Point(
                $node->getProperty('x')->get(),
                $node->getProperty('y')->get(),
                $node->getProperty('z')->get()
            );
            
            if (\Classes\Utils\Math\Points::isPointSame($nodePoint, $resPoint)) {
                // ADD CONNECTION
                $resTable->setConnection($node->getUin(), $restraint);
                $isFound = TRUE;
            }
            
            $i++;
        }
    }
}
