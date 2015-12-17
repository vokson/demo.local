<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdUploadImportFile extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        $request->addFeedback('UploadImportFile');

        // Получаем переменные
        $originName = $request->getProperty('filename');
        $folderName = $request->getProperty('timestamp');

        // Check command file
        $tempName = NULL;
        $is_OK = FALSE;
        if (\Classes\Controller\Util\Utils::getTempFileName($originName, $tempName)) {
            // Copy file to TEMP file
            $is_OK = copy($tempName, \Classes\Controller\Util\Utils::tempPath() .
                    '/' . $folderName .'/' . $originName);
        }
        // File not found
        $request->addFeedback($is_OK);

        include \Classes\Controller\Util\Utils::createViewName('uploadExcelConstructorFile');
    }

    /*
     * Make temp folder
     * 
     * @param string $folderName Name of folder
     */

    private function makeTempFolder($folderName) {
        mkdir(\Classes\Controller\Util\Utils::tempPath() . '/' . $folderName);
    }

}
