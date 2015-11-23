<?php

namespace Classes\Utils\Section\Steel;

/**
 * Class make string naem of section type
 *
 * @author Noskov Alexey
 */
 class SectionType {
     
     /*
      * Make name of steel profile section
      * 
      * @param string var Variable for name
      * @param string assortment Name of sortament
      * @param string name Name of section
      * 
      * @return bool Success or NOT
      */
     static function steelProfile (&$var, $assortment, $name) {
//         echo "ASSORTMENT = ".$assortment."   NAME = ".$name."<br/>";
        $var = NULL; 
         
        switch ($assortment) {
            case "ASCHM 20-93":
                $assortment = new Russian\ASCHM_2093;
                break;
            case "GOST 26020":
                $assortment = new Russian\GOST_26020;
                break;
            case "GOST 10704":
                $assortment = new Russian\GOST_10704;
                break;
            case "GOST 8240":
                $assortment = new Russian\GOST_8240;
                break;
            case "GOST 8509":
                $assortment = new Russian\GOST_8509;
                break;
            case "GOST 8510":
                $assortment = new Russian\GOST_8510;
                break;
            case "GOST 8239":
                $assortment = new Russian\GOST_8239;
                break;
            case "GOST 19425":
                $assortment = new Russian\GOST_19425;
                break;
            case "GOST 30245":
                $assortment = new Russian\GOST_30245;
                break;
            // If there is no necessary assortment
            default:
                return FALSE;
        }
        
        $isFound = $assortment->steelProfile($var, $name);
        // If there is no necessary profile in assortment
        if (!$isFound) {
            return FALSE;
        }
        
        // If there is necessary profile in assortment
        $var .= " TMP 1.2e-005";
        return TRUE;
         
     }
}
