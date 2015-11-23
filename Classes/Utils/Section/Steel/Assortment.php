<?php

namespace Classes\Utils\Section\Steel;

/**
 * Class make string naem of section type
 *
 * @author Noskov Alexey
 */
 class Assortment {
     
     /*
      * Make name of steel profile section
      * 
      * @param string var Variable for name
      * @param string name Name of section
      * 
      * @return bool Success or NOT
      */
     function steelProfile (&$var, $name) {
//         echo "NAME = ".$name."<br/>";
         
         if (isset($this->collection[$name])) {
             $var = $this->collection[$name];
             return TRUE;
         } else {
             $var = NULL;
             return FALSE;
         }
     }
}
