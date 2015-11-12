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
        $zeroPin = new \Classes\Factory\Connection\PinConnection;

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
//        echo "+++ divideMemberByNodes +++ <br/>";
//        echo "MEMBER = $memberUin<br/>";
//        $serviceArray = array();
//        foreach ($nodes as $node) {
//            $serviceArray[] = $node->getUin();
//        }
//        echo "NODES = " . implode(",", $serviceArray) . "<br/>";

        // Get nodes and members
        $allNodes = \Classes\Factory\Model\Model::getNodes();
        $members = \Classes\Factory\Model\Model::getMembers();
        $hashTable = \Classes\Factory\Model\Model::getHashTable();

        // TODO isDivided
        
        //Check is member exist or not
        if (!isset($members[$memberUin])) {
            return FALSE;
        }
        
        // Check if member is divided
         if ($members[$memberUin]->getProperty('isDivided')->get() == FALSE) {
            return FALSE;
        }

        $isDivided = TRUE;
        foreach ($nodes as $key => $node) {

            // If member has been divided, update Line's points
            if ($isDivided) {
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
                
                // Set flag
                $isDivided = FALSE;
            }

            // Get $node coordinates
            $pointNode = new \Classes\Utils\AbstractInstance\Point(
                    $node->getProperty('x')->get(), $node->getProperty('y')->get(), $node->getProperty('z')->get()
            );

            // Delete node from array
            unset($nodes[$key]);

            // If point is inside line
            if (\Classes\Utils\Math\Points::isPointOnLine($pointNode, $line) == 3) {

                // Divide Member
                $newMemberUin = self::divideMemberByNode($memberUin, $node->getUin());

                //Start to divide new member by remaining nodes
                if (!empty($nodes)) {
                    self::divideMemberByNodes($newMemberUin, $nodes);
                }
                
                $isDivided = TRUE;
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
