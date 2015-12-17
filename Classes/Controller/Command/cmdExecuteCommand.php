<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdExecuteCommand extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {
        // Получаем переменные
        $folderName = $request->getProperty('timestamp');
        $command = $request->getProperty('command');
        $object = $request->getProperty('object');
        $format = $request->getProperty('format');
        $file = $request->getProperty('file');
        
        $request->addFeedback((bool)rand(0,1));
        $request->addFeedback(rand(10,100));
        
        include \Classes\Controller\Util\Utils::createViewName('executeCommand');
    }

}
