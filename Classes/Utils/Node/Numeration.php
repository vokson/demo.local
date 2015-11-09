<?php

namespace Classes\Utils\Node;

/**
 * Class for numeration of elements
 *
 * @author Noskov Alexey
 */
class Numeration {
    
    /*
      * Do new numberation for nodes from 1
      */
     static function numerateFromOne () {
         
         $nodes = \Classes\Factory\Model\Model::getNodes();
         
         $id = 1;
         foreach ($nodes as $node) {
             $node->setProperty('id', new \Classes\Value\IntValue($id));
             $id++;
         }
     }
}
