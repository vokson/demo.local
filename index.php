<?php

spl_autoload_extensions(".php");
spl_autoload_register();

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';



try {
    $inputFileName = './Source/Excel/Frame_01.xlsx';
//    $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
//    $sheetData = $objPHPExcel->getActiveSheet()->toArray();
//    var_dump($sheetData);
//
//    echo '<hr />';
//
    $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
    $class = new \Classes\Instance\Member\SteelMember();
    $array = $uploadFactory->upload($inputFileName, $class);
    
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
        $member->setProperty('id',$commonMember->getProperty('id'));
        $member->setProperty('betaAngle',$commonMember->getProperty('betaAngle'));
        
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
    
    // Print
    Classes\Factory\Model\Model::servicePrint();
    
    echo '<hr />';
    
//    foreach ($nodes as $object ) {
//        Classes\Factory\Model\Model::addInstance($object);
//    }
//    
//    Classes\Factory\Export\Scad21ExportFactory::export("Model.cpp");
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}

