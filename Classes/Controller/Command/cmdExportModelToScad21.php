<?php

namespace Classes\Controller\Command;

/**
 * EXPORT MODEL TO SCAD21 FORMAT FILE
 *
 */
class cmdExportModelToScad21 extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('MODEL_EXPORT');

        // Получаем переменные
        $filename = $request->getProperty('filename');
        $path = \Classes\Controller\Util\Utils::tempPath() 
                . DIRECTORY_SEPARATOR . $filename;

        // NUMERATION
        \Classes\Utils\Member\Numeration::numerateFromOne();
        \Classes\Utils\Node\Numeration::numerateFromOne();

        // EXPORT
        \Classes\Factory\Export\Scad21ExportFactory::export($path);

        \Classes\Utils\Timer\Timer::stop('MODEL_EXPORT');

        $request->addFeedback(\Classes\Utils\Timer\Timer::show('MODEL_EXPORT'));
        include \Classes\Controller\Util\Utils::createViewName('exportModelToScad21');
    }

}