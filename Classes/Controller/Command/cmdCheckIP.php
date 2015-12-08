<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdCheckIP extends Command {

    function doExecute(\controller\Request $request) {
        //Определяет IP адресс клиента
         $ip = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_VALIDATE_IP);
         
         if (!$ip) {
             $ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_VALIDATE_IP);
         }
         
         if (!$ip) {
             $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
         }
        
        //Сверяем IP с IP администратора
        if ($ip == \base\ApplicationRegistry::getAdminIP()) {
            $request->addFeedback(TRUE);
        } else {
            $request->addFeedback(FALSE);
        }


        include \util\Utils::createViewName('checkIP');
    }

}
