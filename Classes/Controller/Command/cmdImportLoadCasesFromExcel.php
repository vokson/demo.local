<?php

namespace Classes\Controller\Command;

/**
 * UPLOAD LOAD CASES FROM EXCEL FILE
 *
 */
class cmdImportLoadCasesFromExcel extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('LOAD_CASE_UPLOAD');
        
        // Получаем переменные
        $filename = $request->getProperty('filename');
        
        $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
        $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\LoadCase\LoadCase);

        foreach ($objectArray as &$object) {
             \Classes\Factory\Model\Addition\LoadCaseAddition::add($object);
        
        }
        \Classes\Utils\Timer\Timer::stop('LOAD_CASE_UPLOAD');
        
        $request->addFeedback(\Classes\Utils\Timer\Timer::show('LOAD_CASE_UPLOAD'));
        include \Classes\Controller\Util\Utils::createViewName('importLoadCasesFromExcel');
    }
}
    