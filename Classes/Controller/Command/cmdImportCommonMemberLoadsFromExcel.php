<?php

namespace Classes\Controller\Command;

/**
 * UPLOAD COMMON MEMBER LOADS FROM EXCEL FILE
 *
 */
class cmdImportCommonMemberLoadsFromExcel extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('LOADS_UPLOAD');

        // Получаем переменные
        $filename = $request->getProperty('filename');

        $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
        $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\Load\Member\CommonMemberLoad);

        $notFoundObjects = \Classes\Factory\Model\Addition\MemberLoadAddition::add($objectArray);
        $request->addFeedback($notFoundObjects);

        \Classes\Utils\Timer\Timer::stop('LOADS_UPLOAD');
        
        $request->addFeedback(\Classes\Utils\Timer\Timer::show('LOADS_UPLOAD'));
        
        include \Classes\Controller\Util\Utils::createViewName('importCommonMemberLoadsFromExcel');
    }

}
