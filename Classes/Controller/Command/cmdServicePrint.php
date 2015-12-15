<?php

namespace Classes\Controller\Command;

/**
 * SERVICE PRINT
 *
 */
class cmdServicePrint extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        include \Classes\Controller\Util\Utils::createViewName('servicePrint');
    }

}
