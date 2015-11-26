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
     * Change OLD member => Keep 1st node. Move 2nd node to middle
     * Create NEW member => Set 1st node as middle. Set 2nd node as end of old member
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

        // TODO isDivided
        
        //Check are node and member exist or not
        if (!isset($nodes[$nodeUin]) || !isset($members[$memberUin])) {
            return FALSE;
        }
        
        // Check if member is divided
         if ($members[$memberUin]->getProperty('isDivided')->get() == FALSE) {
            return FALSE;
        }
        
        // Get 2nd connection from oldMember
        $hash = $hashTable->getConnection($memberUin);

        $uin2 = array_keys($hash)[1];
        $con2 = array_values($hash)[1];

        // Create zero Pin connection
        $zeroPin = new \Classes\Factory\Connection\SixFreedomConnection\PinConnection;

        // Remove 2nd connection from oldMember
        $hashTable->removeConnection($memberUin, $uin2);
        // Set 2nd connection to oldMember
        $hashTable->setConnection($memberUin, $nodeUin, $zeroPin);

        // Create newMember same as oldMember
        $newMember = clone $members[$memberUin];

//        echo "<br/>";
//        echo "DIVIDE MEMBER $memberUin<br/>";
//        echo "BY NODE $nodeUin<br/>";
//        echo "NEW MEMBER " . $newMember->getUin() . "<br/>";
//        echo "<br/>";

        //Add member to Model
        \Classes\Factory\Model\Model::addInstance($newMember);

        // Set 1st connection to newMember
        $hashTable->setConnection($newMember->getUin(), $nodeUin, $zeroPin);
        // Set 2nd connection to newMember
        $hashTable->setConnection($newMember->getUin(), $uin2, $con2);
        
        // Add information about operation in ListBoxCollection
        $actionCollection = \Classes\Factory\Model\Model::getMemberActionCollection();
        $actionCollection->addAction(new \Classes\Listbox\Action\ReplaceListboxAction(
                $memberUin, array($memberUin, $newMember->getUin()))
        );

        return $newMember->getUin();
    }

    
     /*
     * Divide all members by existing nodes
     * 
     * @return bool Success or not
     */

    public static function divideAllMembersByExistingNodes() {
        // SORT NODES
        \Classes\Utils\Timer\Timer::start('SORT_NODES');
        \Classes\Factory\Model\Model::sortNodes();
        \Classes\Utils\Timer\Timer::start('SORT_NODES');
        
        // Get nodes and members
        $nodes = \Classes\Factory\Model\Model::getNodes();
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        
        // Array [uin] => [0 - 1st point, 1 - 2nd point]
        $actualMemberUins = array();

        foreach ($nodes as $node) {
            
            // Get $node coordinates
            $point = \Classes\Utils\AbstractInstance\Point::createFromNode($node);
            
            $nodeUin = $node->getUin();
            $connections = $hashTable->getConnection($nodeUin);
            
//            echo "NODE (" . $node->getProperty('x')->get() . ", " .
//                            $node->getProperty('y')->get() . ", " .
//                            $node->getProperty('z')->get() . ")   ";
//            
//            echo "ACTUAL MEMBERS COUNT = " . count($actualMemberUins) . "<br/>";
//            echo "CONNECTIONS: " . implode('; ', array_keys($connections)) . "<br/>";
            
            //Change $actualMemberUins array
            if (count($connections) > 0) {
                
                foreach ($connections as $uin => $c) {
                    // Here $uin is uin of member connected with node
                    
                    if (isset($actualMemberUins[$uin])) {
                        // If member is already included in $actualMemberUins -> delete it
                        unset($actualMemberUins[$uin]);
                    } else {
                        // If member is NOT included in $actualMemberUins -> add it
                        $keys = array_keys($hashTable->getConnection($uin));
                        $endNumber = array_search($nodeUin, $keys);
                        $actualMemberUins[$uin] = $endNumber;
                    }
                }
                    
            }
            
            // Divide $actualMemberUins by $node
            foreach ($actualMemberUins as $memberUin => $endNumber) {
                
                // Get coordinates of members' ends
                $memberEndUins = array_keys($hashTable->getConnection($memberUin));
                $node1 = $nodes[$memberEndUins[0]];
                $node2 = $nodes[$memberEndUins[1]];
                $line = \Classes\Utils\AbstractInstance\Line::createFromTwoNodes($node1, $node2);
                
                // If point is inside line
                if (\Classes\Utils\Math\Points::isPointOnLine($point, $line) == 3) {
                
                    // Divide member by node
                    $newMemberUin = self::divideMemberByNode($memberUin, $nodeUin);
                    // If intersection found AND member's begin before sweep line
                    if ($newMemberUin != FALSE && $endNumber == 0) {
                        // Delete old member from actual array
                        unset($actualMemberUins[$memberUin]);
                        // Add new member to actual array (member's begin also before sweep line)
                        $actualMemberUins[$newMemberUin] = 0;
                    }
                    // If intersection found AND member's begin after sweep line
                    // it's necessary to do nothing, because divided member is
                    // still located after sweep line
                }
            }
        }
        
        
    }

}
