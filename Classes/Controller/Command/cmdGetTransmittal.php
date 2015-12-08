<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdGetTransmittal extends Command {

    function doExecute(\controller\Request $request) {
        
        //Запускаем таймер
        \util\Timer::start('GLOBAL');
//        \util\Timer::start('preparation');
        
        $this->request = $request;

        //Получаем имя трансмитталла
        $this->transmit = filter_input(INPUT_POST, 'transmit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($this->transmit)) {
            exit;
        }
        //читаем корневую директорию сервера
        $this->absolute_folder_path = \util\Explorer::getNameOfFolderWithTransmittal($this->transmit);
        if ($this->absolute_folder_path === FALSE) {
            exit;
        }
        
        //Считаем время выполнения скрипта
//        \util\Timer::show('preparation','Preparation = ');
        
        //Запускаем таймер
//        \util\Timer::start('readXLS');

        //Читаем файл XLS
        $this->readXLS();
        //Считаем время выполнения скрипта
//        \util\Timer::show('readXLS', 'readXLS = ');
        
        //Запускаем таймер
//        \util\Timer::start('checkFiles');

        if (!isset($this->feedback['error'])) {
            //Проверяем наличие файлов для всех документов
            $this->checkFilesInCollection($request);
        }
        
        //Считаем время выполнения скрипта
//        \util\Timer::show('checkFiles', 'checkFiles = ');
        
        
        //Запускаем таймер
//        \util\Timer::start('feedback');
        //Выводим шаблон
        $request->addFeedback($this->feedback);
        $request->addFeedback($this->transmit);
        include \util\Utils::createViewName('get_transmittal');
    }

    /**
     * Читает файл XLS
     */
    private function readXLS() {
        //Without extension
        $path_to_xls = $this->absolute_folder_path . DIRECTORY_SEPARATOR . $this->transmit;

        //читаем xls файлы данного трансмиталла
        $xls = new \util\Excel($path_to_xls, $this->transmit);
        if (!$xls->getStatus()->is_OK()) {
            $this->feedback['error'][] = $xls->getStatus();
        } else {

            $xls->read();
            $raw = $xls->getRows();
            //Создаем коллекцию объектов
            $this->collection = \mapper\DocumentPersistenceFactory::getCollection($raw);
            //        var_dump($this->collection);
        }
    }

    /**
     * Создает полный список файлов в папке,
     * стратегии проверки и копирования файлов
     * 
     * @param type $param
     */
    private function makeStrategies() {
        //Создаем список файлов в папке
        $this->file_list = new \util\FileUploader\FileList($this->absolute_folder_path);

        //Создаем стратегии проверки
        $this->checkPdfDwgStrategy = new \util\FileUploader\checkStrategy\checkPdfDwgStrategy($this->file_list);
        $this->checkOnlyPdfStrategy = new \util\FileUploader\checkStrategy\checkOnlyPdfStrategy($this->file_list);

        //Создаем стратегии копирования
        $this->copyPdfDwgStrategy = new \util\FileUploader\copyStrategy\copyPdfDwgStrategy();
        $this->copyOnlyPdfStrategy = new \util\FileUploader\copyStrategy\copyOnlyPdfStrategy();
    }

    function makeFileUploader(\domain\Document &$doc) {
        if ($doc->getIs_DWG() === TRUE) {
            return new \util\FileUploader\FileUploader($doc, $this->checkPdfDwgStrategy, $this->copyPdfDwgStrategy);
        } else {
            return new \util\FileUploader\FileUploader($doc, $this->checkOnlyPdfStrategy, $this->copyOnlyPdfStrategy);
        }
    }

    /**
     * Проверяет все объекты в коллекции
     * 
     * @param \controller\Request $request
     */
    private function checkFilesInCollection(\controller\Request &$request) {
//         echo 'checkFilesInCollection<br/>';
        $is_OK = TRUE;
        $added_doc_count = 0; //Счетчик записанный файлов
        $this->uploaders = array(); //Массив загрузчиков
        
//        \util\Timer::start('makeStrategies');
        //Создаем стратегии
        $this->makeStrategies();
//        \util\Timer::show('makeStrategies', 'makeStrategies = ');

        //Проверяем наличие файлов для всех документов
        //Перебираем объекты в коллекции и проверяем
        foreach ($this->collection as $doc) {

            $file_uploader = $this->makeFileUploader($doc);

            //Добавляем FileUploader к колекции
            $this->uploaders[] = $file_uploader;

//            \util\Timer::start('isDocumentInDatabase');
            //Проверяем наличие документа в базе данных
            $file_uploader->isDocumentInDatabase();
//            \util\Timer::show('isDocumentInDatabase', 'isDocumentInDatabase = ');


            //Если такого документа нет в базе данных, проверяем наличие всех файлов
            if ($file_uploader->getStatus()->is_OK() === TRUE) {
//                \util\Timer::start('FileUploaderCheck');
                $file_uploader->check();
//                \util\Timer::show('FileUploaderCheck', 'FileUploaderCheck = ');
            }
            //Если проверка не прошла
            if ($file_uploader->getStatus()->is_OK() === FALSE) {
                $is_OK = FALSE;
            }
        }

//        echo '<br/>#######################<br/>';
        //Если все документы прошли проверку успешно
        if ($is_OK === TRUE) {
            //Проверяем существует ли таблица с максимальными ревизиями
            \mapper\DocumentMaxRevision::createTableIfNotExist($file_uploader->getFinder());
            
            foreach ($this->uploaders as $file_uploader) {
                // копируем файлы в хранилище
//                \util\Timer::start('FileUploaderCopy');
                $file_uploader->copy();
//                 \util\Timer::show('FileUploaderCopy', 'FileUploaderCopy = ');
//            var_dump($file_uploader);
                if ($file_uploader->getStatus()->is_OK() === TRUE) {
                    //Если файлы скопированы успешно, добавляем запись в базу данных
                    $file_uploader->insert();
                }
                //Если проверка прошла, прибавляем счетчик добавленных 
                if ($file_uploader->getStatus()->is_OK() === TRUE) {
                    $added_doc_count++;
                }
            }
            
        }


        $this->feedback['uploaders'] = $this->uploaders;
        $this->feedback['count'] = $added_doc_count;
    }

}
