<?php

namespace Classes\Controller\Command;

/**
 * UPLOAD PARAMETER MEMBERS FROM EXCEL FILE
 *
 */
class cmdImportParameterMembersFromExcel extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('PARAMETER_MEMBER_UPLOAD');
        
        // Получаем переменные
        $filename = $request->getProperty('filename');
        
        $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
        $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\Member\ParameterMember());

        foreach ($objectArray as &$object) {
            \Classes\Factory\Model\Addition\ParameterMemberAddition::add($object);
        }
        \Classes\Utils\Timer\Timer::stop('PARAMETER_MEMBER_UPLOAD');
        
        $request->addFeedback(\Classes\Utils\Timer\Timer::show('PARAMETER_MEMBER_UPLOAD'));
        include \Classes\Controller\Util\Utils::createViewName('importParameterMembersFromExcel');
    }
}
    