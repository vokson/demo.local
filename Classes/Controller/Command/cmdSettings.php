<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdSettings extends Command {
    function doExecute(\controller\Request $request) {
        $request->addFeedback(\util\Utils::createViewName("settings"));
        include \util\Utils::createViewName('index');
    }
}
