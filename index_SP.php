<?php
spl_autoload_extensions(".php");
spl_autoload_register();
/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';
set_time_limit(36000);

\Classes\Factory\Model\Model::$coordinateTolerance = 0.01;
\Classes\Utils\Math\Constant::$epsilon = 0.01;

try {
    
    // UPLOAD STEEL MEMBERS
    Classes\Utils\Timer\Timer::start('STEEL_MEMBER_UPLOAD');
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $steelMemberArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/Steel_Members.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    foreach ($steelMemberArray as &$object) {
        Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
    }
     Classes\Utils\Timer\Timer::stop('STEEL_MEMBER_UPLOAD');
   
    
    // UPLOAD RC MEMBERS
     
    Classes\Utils\Timer\Timer::start('RC_MEMBER_UPLOAD');
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $rcMemberArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/RC_Members.xlsx',
            new \Classes\Instance\Member\ParameterMember);
    
    foreach ($rcMemberArray as &$object) {
        Classes\Factory\Model\Addition\ParameterMemberAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('RC_MEMBER_UPLOAD');
    
    
     
    // DELETE DOUBLE NODES
    \Classes\Utils\Timer\Timer::start('COMBINE_DOUBLE_NODES');
    \Classes\Utils\Node\DoubleNodes::combineAll(array(Classes\Factory\Model\Model::getHashTable()));
    
    \Classes\Utils\Timer\Timer::stop('COMBINE_DOUBLE_NODES');
    
    // DIVIDE MEMBERS BY NODES
    \Classes\Utils\Timer\Timer::start('DIVIDE_ALL_MEMBERS');
    \Classes\Utils\Member\DivideMember::divideAllMembersByExistingNodes();
    \Classes\Utils\Timer\Timer::stop('DIVIDE_ALL_MEMBERS');
    
    // UPLOAD LOAD CASES
    Classes\Utils\Timer\Timer::start('LOAD_CASES_UPLOAD');
    $loadCaseArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/Load_Cases.xlsx',
            new \Classes\Instance\LoadCase\LoadCase);
    
    foreach ($loadCaseArray as $object) {
        Classes\Factory\Model\Addition\LoadCaseAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('LOAD_CASES_UPLOAD');
    
    // UPLOAD LOADS
    Classes\Utils\Timer\Timer::start('LOADS_UPLOAD');
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/Loads.xlsx',
            new \Classes\Instance\Load\Member\CommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\MemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
    echo "<br/>";
    
    Classes\Utils\Timer\Timer::stop('LOADS_UPLOAD');
    
    // UPLOAD CONSTRAINTS
    Classes\Utils\Timer\Timer::start('LOAD_CONSTRAINTS');
    $constraintArray = $uploadFactory->upload('./Source/Excel/Simple_Piperack/Constraint.xlsx',
            new \Classes\Instance\Node\Constraint);
    
    foreach ($constraintArray as $object) {
        Classes\Factory\Model\Addition\ConstraintAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('LOAD_CONSTRAINTS');
     
    
    // NUMERATION
    \Classes\Utils\Member\Numeration::numerateFromOne();
    \Classes\Utils\Node\Numeration::numerateFromOne();
   
    // Print
    Classes\Factory\Model\Model::servicePrint();
    Classes\Utils\Timer\Timer::servicePrint();
    
    echo '<hr />';
    
    // WRITE C++ FILE
    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    // WRITE BINARY FILE
    Classes\Factory\Export\BinaryDataExportFactory::export("Model.bin");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}