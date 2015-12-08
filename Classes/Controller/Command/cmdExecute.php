<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdExecute extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {
        
        // Получаем переменные
        // Экранируем специальные символы
        $commandArray = filter_input(INPUT_POST, 'commands', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $commandArray = $request->getProperty('commands');
        
        // if Commands array is empty -> cancel
        if (empty($commandArray)) {
            return;
        }
        
        // Create command resolver
        $commandResolver = new \Classes\Controller\Command\CommandResolver();

        foreach ($commandArray as $command) {
            // Check command
            $command = trim(preg_replace("/\s{2,}/", " ", $command));
            
            // Create command request
            $commandRequest = new \Classes\Controller\Controller\Request();
            
            if ($command == "COMBINE DOUBLE NODES") {
                $commandRequest->setProperty('cmd', 'CombineDoubleNodes');
            }
            
            if ($command == "DIVIDE ALL MEMBERS BY EXISTING NODES") {
                $commandRequest->setProperty('cmd', 'DivideAllMemberByExistingNodes');
            }
            
            if (preg_match('/IMPORT\s+STEEL\s+MEMBERS\s+FROM\s+"(?P<filename>.*)"/i', $command, $matches)) {
                $commandRequest->setProperty('cmd', 'ImportSteelMembers');
                $commandRequest->setProperty('filename', $matches['filename']);
            }
            
            $cmd = $commandResolver->getCommand($commandRequest);
            $cmd->execute($commandRequest);
        }

             
//            $request->addFeedback($collection);
//            include \util\Utils::createViewName('find');
    }

}
