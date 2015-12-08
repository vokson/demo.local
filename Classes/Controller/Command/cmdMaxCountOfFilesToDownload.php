<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace command;

/**
 * Description of cmdMaxCountOfFilesToDownload
 *
 * @author vokson
 */
class cmdMaxCountOfFilesToDownload extends Command {

    function doExecute(\controller\Request $request) {

        //Максимальное количество файлов для скачивания
        $request->addFeedback(\base\ApplicationRegistry::getDrawing()['max_download_drw_count']);

        include \util\Utils::createViewName('max_count_of_files_to_download');
    }

}
