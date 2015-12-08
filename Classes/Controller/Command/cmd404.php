<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmd404 extends Command {
    function doExecute(\controller\Request $request) {
        
        include \util\Utils::createViewName('404');
    }
}
