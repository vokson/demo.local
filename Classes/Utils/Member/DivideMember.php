<?php

namespace Classes\Utils\Member;

/**
 * Class for division of member
 *
 * @author Noskov Alexey
 */
class DivideMember {
    
    /*
     * Divide member in two parts by Node
     * 
     * @param string $memberUin Uin of member
     * @param string $nodeUin Uin of node
     * 
     * @return bool Success or not
     */
    public static function divideMemberByNode($memberUin, $nodeUin) {
        // Get nodes and members
        $nodes = \Classes\Factory\Model\Model::getNodes();
        $members = \Classes\Factory\Model\Model::getMembers();
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        
        //Check are node and member exist or not
        if (!isset($nodes[$nodeUin]) || !isset($members[$memberUin])) {
            return FALSE;
        }
        
        // Get 2nd connection from oldMember
        $hash = $hashTable->getConnection($memberUin);
        
        $uin2 = array_keys($hash)[1];
        $con2 = array_values($hash)[1];
        
        // Create zero Pin connection
        $zeroPin = new \Classes\Factory\Connection\PinConnection;
        
        // Remove 2nd connection from oldMember
        $hashTable->removeConnection($memberUin, $uin2);
        // Set 2nd connection to oldMember
        $hashTable->setConnection($memberUin, $nodeUin, $zeroPin);
        
        // Create newMember same as oldMember
        $newMember = $members[$memberUin];
        $newMember->newUin();
        
        // Set 1st connection to newMember
        $hashTable->setConnection($newMember->getUin(), $nodeUin, $zeroPin);
        // Set 2nd connection to newMember
        $hashTable->setConnection($newMember->getUin(), $uin2, $con2);
        
        return TRUE;
    }
    
    /*
     * Divide one member by existing nodes
     * 
     * @param string $memberUin Uin of member
     * 
     * @return bool Success or not
     */
    public static function divideMemberByExistingNodes($memberUin) {
        // Get nodes and members
        $nodes = \Classes\Factory\Model\Model::getNodes();
        $members = \Classes\Factory\Model\Model::getMembers();
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        
        
        //Check is member exist or not
        if (!isset($members[$memberUin])) {
            return FALSE;
        }
        
        // Get coordinates of members' ends
        $hash = $hashTable->getConnection($memberUin);
        
        $node1 = array_keys($hash)[0];
        $point1 = new \Classes\Utils\AbstractInstance\Point(
                    $node1->getProperty('x')->get(),
                    $node1->getProperty('y')->get(),
                    $node1->getProperty('z')->get()
                    );
        
        $node2 = array_keys($hash)[1];
        $point2 = new \Classes\Utils\AbstractInstance\Point(
                    $node2->getProperty('x')->get(),
                    $node2->getProperty('y')->get(),
                    $node2->getProperty('z')->get()
                    );
        //Create Line
        $line = new \Classes\Utils\AbstractInstance\Line($point1, $point2);
        
        foreach ($nodes as $node) {
            // Get $node coordinates
            $pointNode = new \Classes\Utils\AbstractInstance\Point(
                    $node->getProperty('x')->get(),
                    $node->getProperty('y')->get(),
                    $node->getProperty('z')->get()
                    );
            
            // RECURSION CHECK
        }
    }
}
