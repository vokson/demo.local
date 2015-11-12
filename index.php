<?php

spl_autoload_extensions(".php");
spl_autoload_register();

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';

try {
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $array = $uploadFactory->upload('./Source/Excel/Frame_01.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    foreach ($array as &$commonMember) {
//        var_dump($commonMember);
//        
        // NODE 1
        $node1 = new \Classes\Instance\Node\Node();
        $node1->setProperty('x', $commonMember->getProperty('x1'));
        $node1->setProperty('y', $commonMember->getProperty('y1'));
        $node1->setProperty('z', $commonMember->getProperty('z1'));
        
        
        // NODE 2
        $node2 = new \Classes\Instance\Node\Node();
        $node2->setProperty('x', $commonMember->getProperty('x2'));
        $node2->setProperty('y', $commonMember->getProperty('y2'));
        $node2->setProperty('z', $commonMember->getProperty('z2'));
        
        // MEMBER
        $member = new \Classes\Instance\Member\Member();
        $member->setProperty('betaAngle',$commonMember->getProperty('betaAngle'));
        $member->setProperty('isDivided',$commonMember->getProperty('isDivided'));
        $member->setProperty('name',$commonMember->getProperty('name'));
        
        // SECTION
        if (\Classes\Utils\SectionType::steelProfile($sectionString,
                $commonMember->getProperty('sectionType')->get(),
                $commonMember->getProperty('sectionName')->get())) {
            
            $section = new \Classes\Value\StringValue($sectionString);
//            var_dump($sectionString);
            $member->setProperty('section', $section);
        }

        // ADD TO MODEL
         Classes\Factory\Model\Model::addInstance($node1);
         Classes\Factory\Model\Model::addInstance($node2);
         Classes\Factory\Model\Model::addInstance($member);
                 
        // PINS
        $pin1 = new \Classes\Factory\Connection\PinConnection($commonMember->getProperty('pin1')->get());
        $pin2 = new \Classes\Factory\Connection\PinConnection($commonMember->getProperty('pin2')->get());
        
        // ADD TO HASH TABLE
        $hashTable = Classes\Factory\Model\Model::getHashTable();
        
        $hashTable->setConnection($node1->getUin(), $member->getUin(), $pin1);
        $hashTable->setConnection($node2->getUin(), $member->getUin(), $pin2);
        
        
    }
    
    // DELETE DOUBLE NODES
    Classes\Utils\Node\DoubleNodes::combineAll();
    
    // DIVIDE MEMBERS BY NODES
    \Classes\Utils\Member\DivideMember::divideAllMembersByExistingNodes();
    
    // APPLY CONSTRAINTS
    $array = $uploadFactory->upload('./Source/Excel/Constraint_01.xlsx',
            new \Classes\Instance\Node\Constraint);
    
    // GET NODES
    $nodes = array_values(Classes\Factory\Model\Model::getNodes());
    
    //GET RESTRAINT TABLE
    $resTable = Classes\Factory\Model\Model::getRestraintTable();
    
    foreach ($array as $constraint) {
        // RESTRAINT
        $restraint = new \Classes\Factory\Connection\RestraintConnection($constraint->getProperty('fix')->get());
        
        // RESTRAINT POINT
        $resPoint = new Classes\Utils\AbstractInstance\Point(
            $constraint->getProperty('x')->get(),
            $constraint->getProperty('y')->get(),
            $constraint->getProperty('z')->get()
        );
        
        // TRY TO FIND NODE FOR RESTRAINT'S APPLICATION
        $isFound = FALSE; $i=0;
        while ($isFound === FALSE && $i < count($nodes)) {
            $node = $nodes[$i];
            
            // NODE POINT
            $nodePoint = new Classes\Utils\AbstractInstance\Point(
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
    
    // NUMERATION
    \Classes\Utils\Member\Numeration::numerateFromOne();
    \Classes\Utils\Node\Numeration::numerateFromOne();
   
    // Print
    Classes\Factory\Model\Model::servicePrint();
    
    echo '<hr />';
    
    
    
    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}

