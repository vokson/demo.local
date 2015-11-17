<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Utils\Load;

/**
 * Class make int value from load direction string name
 *
 * @author Noskov Alexey
 */
 class LoadDirection {
     
     /*
      * Create int value
      * 
      * @param string var Variable for int
      * @param string name Load's direction
      * 
      * @return bool Success or NOT
      */
     static function get (&$var, $direction) {
//         echo "DIRECTION = ".$direction."<br/>";
         
         if (isset(self::$collection[$direction])) {
             $var = self::$collection[$direction];
             return TRUE;
         } else {
             $var = 0;
             return FALSE;
         }
         
     }
         
    static private $collection = array(
        "FX" => 1, "Fx" => 1, "fx" => 1,
        "FY" => 2, "Fy" => 2, "fy" => 2,
        "FZ" => 3, "Fz" => 3, "fz" => 3,
        "MX" => 4, "Mx" => 4, "mx" => 4,
        "MY" => 5, "My" => 5, "my" => 5,
        "MZ" => 6, "Mz" => 6, "mz" => 6
     );
}
