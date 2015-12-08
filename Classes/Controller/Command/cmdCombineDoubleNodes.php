<?php

namespace Classes\Controller\Command;

/**
 * DELETE DOUBLE NODES FROM MODEL
 *
 */
class cmdCombineDoubleNodes extends Command {

    function doExecute(\Classes\Controller\Controller\Request $request) {

        \Classes\Utils\Timer\Timer::start('COMBINE_DOUBLE_NODES');
        
        \Classes\Utils\Node\DoubleNodes::combineAll(array(Classes\Factory\Model\Model::getHashTable()));

        \Classes\Utils\Timer\Timer::stop('COMBINE_DOUBLE_NODES');
    }

}
