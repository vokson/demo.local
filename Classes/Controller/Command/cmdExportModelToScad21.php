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

        // NUMERATION
        \Classes\Utils\Member\Numeration::numerateFromOne();
        \Classes\Utils\Node\Numeration::numerateFromOne();

        // EXPORT
        \Classes\Factory\Export\Scad21ExportFactory::export($filename);

        \Classes\Utils\Timer\Timer::stop('MODEL_EXPORT');

        $request->addFeedback(\Classes\Utils\Timer\Timer::show('MODEL_EXPORT'));
        include \Classes\Controller\Util\Utils::createViewName('exportModelToScad21');
    }

}
//// FINISH
//        header("Content-Disposition: attachment; filename=$filename");
//        header("Content-Type: application/octet-stream");
//        echo implode("\r\n", self::$txt);