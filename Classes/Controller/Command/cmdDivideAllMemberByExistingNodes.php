<?php

namespace Classes\Controller\Command;

/**
 * DIVIDE ALL MEMBERS BY EXISTING NODES
 *
 */
class cmdDivideAllMemberByExistingNodes extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {
        
        \Classes\Utils\Timer\Timer::start('DIVIDE_ALL_MEMBERS');
        
        \Classes\Utils\Member\DivideMember::divideAllMembersByExistingNodes();
        
        \Classes\Utils\Timer\Timer::stop('DIVIDE_ALL_MEMBERS');
    }

}
