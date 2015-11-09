<?php

namespace Classes\Utils\Member;

/**
 * Class for numeration of elements
 *
 * @author Noskov Alexey
 */
class Numeration {
    
     /*
      * Do new numberation for members from 1
      */
     static function numerateFromOne () {
         
         $members = \Classes\Factory\Model\Model::getMembers();
         
         $id = 1;
         foreach ($members as $member) {
             $member->setProperty('id', new \Classes\Value\IntValue($id));
             $id++;
         }
     }
}
