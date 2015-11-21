<?php

spl_autoload_extensions(".php");
spl_autoload_register();

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';


try {
    // UPLOAD STEEL MEMBERS
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $steelMemberArray = $uploadFactory->upload('./Source/Excel/Frame_01.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    foreach ($steelMemberArray as &$object) {
        Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
    }
    
    // DELETE DOUBLE NODES
    Classes\Utils\Node\DoubleNodes::combineAll();
    
    // DIVIDE MEMBERS BY NODES
    \Classes\Utils\Member\DivideMember::divideAllMembersByExistingNodes();
    
    // UPLOAD CONSTRAINTS
    $constraintArray = $uploadFactory->upload('./Source/Excel/Constraint_01.xlsx',
            new \Classes\Instance\Node\Constraint);
    
    foreach ($constraintArray as $object) {
        Classes\Factory\Model\Addition\ConstraintAddition::add($object);
    }
    
    // UPLOAD LOAD CASES
    $loadCaseArray = $uploadFactory->upload('./Source/Excel/Load_Cases_01.xlsx',
            new \Classes\Instance\LoadCase\LoadCase);
    
    foreach ($loadCaseArray as $object) {
        Classes\Factory\Model\Addition\LoadCaseAddition::add($object);
    }
    
    // UPLOAD LOADS
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/Loads_01.xlsx',
            new \Classes\Instance\Load\Member\CommonMemberLoad);
    
    
    foreach ($memberLoadArray as $object) {
        $notFoundObjects = NULL;
        $isFound = Classes\Factory\Model\Addition\MemberLoadAddition::add($object, $notFoundObjects);
        
        $name = $object->getProperty('name')->get();
        if (!$isFound) {
            echo "LOAD $name IS NOT FOUND<br/>";
        } else {
            echo "LOAD $name IS FOUND<br/>";
        }
    }
    
    // NUMERATION
    \Classes\Utils\Member\Numeration::numerateFromOne();
    \Classes\Utils\Node\Numeration::numerateFromOne();
   
    // Print
    Classes\Factory\Model\Model::servicePrint();
    
    echo '<hr />';
    
    // WRITE C++ FILE
    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}

