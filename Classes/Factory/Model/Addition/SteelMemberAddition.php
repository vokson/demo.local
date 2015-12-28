<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition SteelMember to Model
 *
 * @author Noskov Alexey
 */
class SteelMemberAddition {
   
    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Member\SteelMember $object
     */
    public static function add($object) {
        
        // NODE 1
        $node1 = new \Classes\Instance\Node\Node();
        $node1->setProperty('x', $object->getProperty('x1'));
        $node1->setProperty('y', $object->getProperty('y1'));
        $node1->setProperty('z', $object->getProperty('z1'));
        
        // NODE 2
        $node2 = new \Classes\Instance\Node\Node();
        $node2->setProperty('x', $object->getProperty('x2'));
        $node2->setProperty('y', $object->getProperty('y2'));
        $node2->setProperty('z', $object->getProperty('z2'));
        
        // MEMBER
        $member = new \Classes\Instance\Member\Member();
        $member->setProperty('betaAngle',$object->getProperty('betaAngle'));
        $member->setProperty('isDivided',$object->getProperty('isDivided'));
        $member->setProperty('name',$object->getProperty('name'));
        
        // SECTION
        $sectionString = NULL;
        if (\Classes\Utils\Section\Steel\SectionType::steelProfile($sectionString,
                $object->getProperty('sectionType')->get(),
                $object->getProperty('sectionName')->get())) {
            
            $section = new \Classes\Value\StringValue($sectionString);
//            var_dump($sectionString);
            $member->setProperty('section', $section);
        }

        // ADD TO MODEL
         \Classes\Factory\Model\Model::addInstance($node1);
         \Classes\Factory\Model\Model::addInstance($node2);
         \Classes\Factory\Model\Model::addInstance($member);
                 
        // PINS
        $pin1 = new \Classes\Factory\Connection\SixFreedomConnection\PinConnection($object->getProperty('pin1')->get());
        $pin2 = new \Classes\Factory\Connection\SixFreedomConnection\PinConnection($object->getProperty('pin2')->get());
        
        // ADD TO HASH TABLE
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        
        $hashTable->setConnection($node1->getUin(), $member->getUin(), $pin1);
        $hashTable->setConnection($node2->getUin(), $member->getUin(), $pin2);
        
        // MAKE CHECK GROUP FOR STEEL MEMBER
        $group = new \Classes\Instance\Group\SteelMember\SteelMemberCheckGroup();
        $group->setProperty('steel',$object->getProperty('steel'));
        $group->setProperty('Ry',$object->getProperty('Ry'));
        $group->setProperty('muXZ',$object->getProperty('muXZ'));
        $group->setProperty('muXY',$object->getProperty('muXY'));
        $group->setProperty('gammaC',$object->getProperty('gammaC'));
        $group->setProperty('FC',$object->getProperty('FC'));
        $group->setProperty('name',$object->getProperty('name'));
        
        // CREATE LISTBOX
        $memberList = new \Classes\Listbox\Listbox(\Classes\Factory\Model\Model::getMemberActionCollection());
        $memberList->add($member->getUin());
        
        // ADD LISTBOX TO GROUP
        $group->setProperty('listbox',$memberList);
        
        // ADD GROUP TO MODEL
         \Classes\Factory\Model\Model::addInstance($group);
    }
}
