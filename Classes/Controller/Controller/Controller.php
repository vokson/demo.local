<?php

namespace Classes\Controller\Controller;
/**
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class Controller {
    /**
     *
     * @var ApplicationHelper Configuration settings for application 
     */
    private $applicationHelper;
    
    
    private function __construct() { }
    
    /**
     * Usefull method for application's runing
     */
    static function run () {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }
    
    /**
     * Initialize object of class ApplicationHelper
     */
    function init () {
        mb_internal_encoding('UTF-8');
        
        $this->applicationHelper = ApplicationHelper::instance();
        //Читаем файл конфигурации
        $this->applicationHelper->init();
    }
    
    /**
     * Use class CommandResolver to receive object of class Command
     * and execute one
     */
    function handleRequest() {
        $request = new Request();
        $cmd_r = new \Classes\Controller\Command\CommandResolver();
        $cmd = $cmd_r->getCommand ($request);
        $cmd->execute($request);
    }
    
    /**
     * Exception handler.
     */
    public function handleException(\Exception $ex) {
        $request = new Request();
        $request->addFeedback($ex);
        
        if ($ex instanceof \Classes\Controller\Exception\NotFoundException) {
            $request->setProperty('cmd', '404');
        } else {
            $request->setProperty('cmd', '500');
        }
        $cmd_r = new \Classes\Controller\Command\CommandResolver();
        $cmd = $cmd_r->getCommand ($request);
        $cmd->execute($request);
        
    }
}
