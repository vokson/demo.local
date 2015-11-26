<?php

spl_autoload_extensions(".php");
spl_autoload_register();

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';

set_time_limit(36000);

try {
    
    // UPLOAD STEEL MEMBERS
    Classes\Utils\Timer\Timer::start('STEEL_MEMBER_UPLOAD');
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
//    $steelMemberArray = $uploadFactory->upload('./Source/Excel/Test/Member750.xlsx',
    $steelMemberArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/Steel_Members_01.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    foreach ($steelMemberArray as &$object) {
        Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('STEEL_MEMBER_UPLOAD');
    
    // UPLOAD RC MEMBERS
//    Classes\Utils\Timer\Timer::start('RC_MEMBER_UPLOAD');
//    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
//    $rcMemberArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/RC_Members_01.xlsx',
//            new \Classes\Instance\Member\ParameterMember);
//    
//    foreach ($rcMemberArray as &$object) {
//        Classes\Factory\Model\Addition\ParameterMemberAddition::add($object);
//    }
//    Classes\Utils\Timer\Timer::stop('RC_MEMBER_UPLOAD');
    
    // SORT NODES
    \Classes\Utils\Timer\Timer::start('SORT_NODES');
    \Classes\Factory\Model\Model::sortNodes();
    \Classes\Utils\Timer\Timer::start('SORT_NODES');
    
    // DELETE DOUBLE NODES
    \Classes\Utils\Timer\Timer::start('COMBINE_DOUBLE_NODES');
    \Classes\Utils\Node\DoubleNodes::combineAll();
    \Classes\Utils\Timer\Timer::stop('COMBINE_DOUBLE_NODES');
    
    // DIVIDE MEMBERS BY NODES
    \Classes\Utils\Timer\Timer::start('DIVIDE_ALL_MEMBERS');
    \Classes\Utils\Member\DivideMember::divideAllMembersByExistingNodes();
    \Classes\Utils\Timer\Timer::stop('DIVIDE_ALL_MEMBERS');
    
    // UPLOAD CONSTRAINTS
//    $constraintArray = $uploadFactory->upload('./Source/Excel/Constraint_01.xlsx',
//            new \Classes\Instance\Node\Constraint);
//    
//    foreach ($constraintArray as $object) {
//        Classes\Factory\Model\Addition\ConstraintAddition::add($object);
//    }
    
    // UPLOAD LOAD CASES
//    $loadCaseArray = $uploadFactory->upload('./Source/Excel/Load_Cases_01.xlsx',
//            new \Classes\Instance\LoadCase\LoadCase);
//    
//    foreach ($loadCaseArray as $object) {
//        Classes\Factory\Model\Addition\LoadCaseAddition::add($object);
//    }
    
    // UPLOAD LOADS
    
//    $memberLoadArray = $uploadFactory->upload('./Source/Excel/Loads_01.xlsx',
//            new \Classes\Instance\Load\Member\CommonMemberLoad);
//    
//    
//    foreach ($memberLoadArray as $object) {
//        $notFoundObjects = NULL;
//        $isFound = Classes\Factory\Model\Addition\MemberLoadAddition::add($object, $notFoundObjects);
//        
//        $name = $object->getProperty('name')->get();
//        if (!$isFound) {
//            echo "LOAD $name IS NOT FOUND<br/>";
//            var_dump($notFoundObjects);
//        }
//    }
//    echo "<br/>";
     
    
    // NUMERATION
    \Classes\Utils\Member\Numeration::numerateFromOne();
    \Classes\Utils\Node\Numeration::numerateFromOne();
   
    // Print
    Classes\Factory\Model\Model::servicePrint();
    Classes\Utils\Timer\Timer::servicePrint();
    
    echo '<hr />';
    
    // WRITE C++ FILE
    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}

