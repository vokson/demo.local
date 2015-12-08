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
class cmdStatistic extends Command {

    function doExecute(\controller\Request $request) {

        include \util\Utils::createViewName('statistic');
    }

}
