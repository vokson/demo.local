<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdDownloadTempFiles extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {
        // get all file names
        $files = glob(\Classes\Controller\Util\Utils::tempPath() . '/*');
        
        foreach ($files as $file) {
            $request->addFeedback($file);
        }
        
        include \Classes\Controller\Util\Utils::createViewName('downloadTempFiles');
    }

}
