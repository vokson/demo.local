<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdIndex extends Command {
    function doExecute(\Classes\Controller\Controller\Request $request) {
        $request->addFeedback(\Classes\Controller\Util\Utils::createViewName("body"));
        include \Classes\Controller\Util\Utils::createViewName('index');
    }
}
