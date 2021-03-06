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
    $steelMemberArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_SM.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    
    foreach ($steelMemberArray as &$object) {
        Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
    }
     
     // UPLOAD NEW STEEL MEMBERS
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $steelMemberArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_SM_new.xlsx',
            new \Classes\Instance\Member\SteelMember);
    
    
    foreach ($steelMemberArray as &$object) {
        Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
    }
     Classes\Utils\Timer\Timer::stop('STEEL_MEMBER_UPLOAD');
   
    // UPLOAD RC MEMBERS
     
    Classes\Utils\Timer\Timer::start('RC_MEMBER_UPLOAD');
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $rcMemberArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_RC.xlsx',
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
    $loadCaseArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_Load_Cases.xlsx',
            new \Classes\Instance\LoadCase\LoadCase);
    
    foreach ($loadCaseArray as $object) {
        Classes\Factory\Model\Addition\LoadCaseAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('LOAD_CASES_UPLOAD');
    
    // UPLOAD LOADS
    Classes\Utils\Timer\Timer::start('LOADS_UPLOAD');
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_Loads.xlsx',
            new \Classes\Instance\Load\Member\CommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\MemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
    echo "<br/>";
    
    // CONCENTRATED LOADS UPLOAD
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_WO_Loads.xlsx',
            new \Classes\Instance\Load\Member\ConcentratedCommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\ConcentratedMemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
    
    // UPLOAD WIND LOADS
    Classes\Utils\Timer\Timer::start('LOADS_UPLOAD');
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_Wind_Load.xlsx',
            new \Classes\Instance\Load\Member\CommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\MemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
    
    // UPLOAD NEW LOADS
    Classes\Utils\Timer\Timer::start('LOADS_UPLOAD');
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_Loads_new.xlsx',
            new \Classes\Instance\Load\Member\CommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\MemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
    
    // CONCENTRATED NEW LOADS UPLOAD
    $memberLoadArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_WO_Loads_new.xlsx',
            new \Classes\Instance\Load\Member\ConcentratedCommonMemberLoad);
    
    $notFoundObjects = Classes\Factory\Model\Addition\ConcentratedMemberLoadAddition::add($memberLoadArray);
    foreach ($notFoundObjects as $object) {
        $name = $object->getProperty('name')->get();
        echo "LOAD $name IS NOT FOUND<br/>";
    }
   
    
    Classes\Utils\Timer\Timer::stop('LOADS_UPLOAD');
    
    // UPLOAD CONSTRAINTS
    Classes\Utils\Timer\Timer::start('LOAD_CONSTRAINTS');
    $constraintArray = $uploadFactory->upload('./Source/Excel/TAF2_PR-205b/PR-205b_Constraint.xlsx',
            new \Classes\Instance\Node\Constraint);
    
    foreach ($constraintArray as $object) {
        Classes\Factory\Model\Addition\ConstraintAddition::add($object);
    }
    Classes\Utils\Timer\Timer::stop('LOAD_CONSTRAINTS');
     
     echo "<br/>";
    
    // NUMERATION
    \Classes\Utils\Member\Numeration::numerateFromOne();
    \Classes\Utils\Node\Numeration::numerateFromOne();
   
    // Print
    Classes\Factory\Model\Model::servicePrint();
    Classes\Utils\Timer\Timer::servicePrint();
    
    echo '<hr />';
    
    // WRITE C++ FILE
    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    // WRITE JSON FILE
    Classes\Factory\Export\JSONExportFactory::export("Model.json");
    // WRITE BINARY FILE
    Classes\Factory\Export\BinaryDataExportFactory::export("Model.bin");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}