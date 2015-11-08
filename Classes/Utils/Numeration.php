<?php

namespace Classes\Utils;

/**
 * Class for numeration of elements
 *
 * @author Noskov Alexey
 */
class Numeration {
    
    /*
      * Do new numberation for nodes from 1
      */
     static function numerateNodes () {
         
         $nodes = \Classes\Factory\Model\Model::getNodes();
         
         $id = 1;
         foreach ($nodes as $node) {
             $node->setProperty('id', new \Classes\Value\IntValue($id));
             $id++;
         }
     }
     
     /*
      * Do new numberation for members from 1
      */
     static function numerateMembers () {
         
         $members = \Classes\Factory\Model\Model::getMembers();
         
         $id = 1;
         foreach ($members as $member) {
             $member->setProperty('id', new \Classes\Value\IntValue($id));
             $id++;
         }
     }
}
