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
     * @return bool FALSE If not successful
     * @return string Uin of new member
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
        $newMember = clone $members[$memberUin];
        echo "OLD MEMBER<br/>";
        var_dump($members[$memberUin]);
        echo "<br/>";
        
        echo "OLD UIN = $memberUin, NEW UIN = ".$newMember->getUin()."<br/>";
        
        $newMember->newUin();
        
        echo "OLD UIN = $memberUin, NEW UIN = ".$newMember->getUin()."<br/>";
        echo "<br/>";
        
        //Add member to Model
        \Classes\Factory\Model\Model::addInstance($newMember);
        
        // Set 1st connection to newMember
        $hashTable->setConnection($newMember->getUin(), $nodeUin, $zeroPin);
        // Set 2nd connection to newMember
        $hashTable->setConnection($newMember->getUin(), $uin2, $con2);
        
        return $newMember->getUin();
    }
    
    /*
     * Divide one member by existing nodes
     * 
     * @param string $memberUin Uin of member
     * @param Classes\Instance\Node\Node $nodes Array of nodes
     * 
     * @return bool Success or not
     */
    public static function divideMemberByNodes($memberUin, $nodes) {
        // Get nodes and members
        $allNodes = \Classes\Factory\Model\Model::getNodes();
        $members = \Classes\Factory\Model\Model::getMembers();
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        
        
        //Check is member exist or not
        if (!isset($members[$memberUin])) {
            return FALSE;
        }
        
        // Get coordinates of members' ends
        $hash = $hashTable->getConnection($memberUin);
        
        $node1 = $allNodes[array_keys($hash)[0]];
        $point1 = new \Classes\Utils\AbstractInstance\Point(
                    $node1->getProperty('x')->get(),
                    $node1->getProperty('y')->get(),
                    $node1->getProperty('z')->get()
                    );
        
        $node2 = $allNodes[array_keys($hash)[1]];
        $point2 = new \Classes\Utils\AbstractInstance\Point(
                    $node2->getProperty('x')->get(),
                    $node2->getProperty('y')->get(),
                    $node2->getProperty('z')->get()
                    );
        //Create Line
        $line = new \Classes\Utils\AbstractInstance\Line($point1, $point2);
        
        foreach ($nodes as &$node) {
            // UIN
            $nodeUin = $node->getUin();
            
            // Get $node coordinates
            $pointNode = new \Classes\Utils\AbstractInstance\Point(
                    $node->getProperty('x')->get(),
                    $node->getProperty('y')->get(),
                    $node->getProperty('z')->get()
                    );
            
            // Delete node from array
            unset($node);
            
            // If point is inside line
            if (\Classes\Utils\Math\Points::isPointOnLine($pointNode, $line) == 3) {
                
                // Divide Member
                $newMemberUin = self::divideMemberByNode($memberUin, $nodeUin);
                
                //Start to divide new member by remaining nodes
                self::divideMemberByNodes($newMemberUin, $nodes);
            }
        }
    }
    
    /*
     * Divide all members by existing nodes
     * 
     * @return bool Success or not
     */
    public static function divideAllMembersByExistingNodes() {
        // Get nodes and members
        $nodes = \Classes\Factory\Model\Model::getNodes();
        $members = \Classes\Factory\Model\Model::getMembers();
        
        foreach ($members as $member) {
            self::divideMemberByNodes($member->getUin(), $nodes);
        }
    }
}
