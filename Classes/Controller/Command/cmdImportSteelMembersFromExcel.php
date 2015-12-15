<?php

namespace Classes\Controller\Command;

/**
 * UPLOAD STEEL MEMBERS FROM EXCEL FILE
 *
 */
class cmdImportSteelMembersFromExcel extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('STEEL_MEMBER_UPLOAD');
        
        // Получаем переменные
        $filename = $request->getProperty('filename');
        
        $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
        $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\Member\SteelMember);

        foreach ($objectArray as &$object) {
            \Classes\Factory\Model\Addition\SteelMemberAddition::add($object);
        }

        \Classes\Utils\Timer\Timer::stop('STEEL_MEMBER_UPLOAD');
        
        $request->addFeedback(\Classes\Utils\Timer\Timer::show('STEEL_MEMBER_UPLOAD'));
        include \Classes\Controller\Util\Utils::createViewName('importSteelMembersFromExcel');
    }
}
    