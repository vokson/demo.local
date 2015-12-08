<?php

namespace Classes\Controller\Command;

/**
 * Description of Command
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
abstract class Command {
    /**
     * Guarantees that __construct will be without params
     */
    final function __construct() { }
    
    /**
     * Execute doExecute method in subclass
     */
 function execute (\Classes\Controller\Controller\Request $request) {
     $this->doExecute ($request);
 }
    
 abstract function doExecute (\Classes\Controller\Controller\Request $request);
}
