<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace command;

/**
 * Description of cmdZip
 *
 * @author vokson
 */
class cmdZipCreate extends Command {

    function doExecute(\controller\Request $request) {

        //Максимальное время работы скрипта
        set_time_limit(\base\ApplicationRegistry::getDrawing()['max_download_drw_count']);
        
        //Получаем переменные
        //Проверяем входящие данные на тип URL
        $drawings = array ();
//        var_dump($_POST['drawings']);
        for ($i=0; $i<count($_POST['drawings']);$i++) {
            $path = urldecode(filter_var($_POST['drawings'][$i],FILTER_SANITIZE_STRING));
            $path = parse_url($path)['path'];
//            var_dump($path);
            if (isset($path) && is_string($path)) {
                $drawings[] = $path;
            }
        }
        
        if (is_array($drawings) && (count($drawings) > 0)) {
            //Если массив принят успешно, архивируем файлы
            //Число файлов в случае удачи или
            //FALSE в случае неудачи
            $result = \util\Archivator::makeZip($drawings);
            if ($result === FALSE) {
               $request->addFeedback(FALSE); 
            } else {
                $request->addFeedback(TRUE); 
                $request->addFeedback($result); 
            }
            
        } else {
            $request->addFeedback(FALSE);
        }
        
        include \util\Utils::createViewName('zip_create');
    }

}
