<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdUploadExcelConstructorFile extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        $request->addFeedback('UploadExcelConstructorFile');
        
        // Получаем переменные
        $originName = $request->getProperty('filename');

        // Check command file
        $tempName = NULL;
        if (\Classes\Controller\Util\Utils::getTempFileName($originName, $tempName)) {
            // Upload command file
            $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
            $array = $uploadFactory->upload($tempName, new \Classes\Instance\Command\Command);

            foreach ($array as $e) {
                $command = $e->getProperty('command');
                $object = $e->getProperty('object');
                $format = $e->getProperty('format');
                $file = $e->getProperty('file');
                
                // Prepare empty command row
                $row = array();
                
                if (!is_null($command)) {
                  $row['command'] = $command->get();
                }
                if (!is_null($object)) {
                  $row['object'] = $object->get();
                }
                if (!is_null($format)) {
                  $row['format'] = $format->get();
                }
                if (!is_null($file)) {
                  $row['file'] = $file->get();
                }
                
                $request->addFeedback($row);
            }
        }

        include \Classes\Controller\Util\Utils::createViewName('uploadExcelConstructorFile');
    }
}
