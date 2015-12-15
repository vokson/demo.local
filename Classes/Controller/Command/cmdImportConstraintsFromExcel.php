<?php

namespace Classes\Controller\Command;

/**
 * UPLOAD CONSTRAINTS FROM EXCEL FILE
 *
 */
class cmdImportConstraintsFromExcel extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('CONSTRAINT_UPLOAD');
        
        // Получаем переменные
        $filename = $request->getProperty('filename');
        
        $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
        $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\Node\Constraint);

        foreach ($objectArray as &$object) {
             \Classes\Factory\Model\Addition\ConstraintAddition::add($object);
        }
        \Classes\Utils\Timer\Timer::stop('CONSTRAINT_UPLOAD');
        
        $request->addFeedback(\Classes\Utils\Timer\Timer::show('CONSTRAINT_UPLOAD'));
        include \Classes\Controller\Util\Utils::createViewName('importConstraintsFromExcel');
    }
}
    