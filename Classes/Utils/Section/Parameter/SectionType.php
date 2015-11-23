<?php

namespace Classes\Utils\Section\Parameter;

/**
 * Class make string name of section type
 *
 * @author Noskov Alexey
 */
class SectionType {
    /*
     * Make name of steel profile section
     * 
     * @param string $var Variable for name
     * @param double $E Young Modulus
     * @param double $nu Poisson Ratio
     * @param double $density Density
     * @param string $tmp Linear Expansion Coefficient
     * @param string $type Section Type
     * @param string $properties Properties
     * 
     * @return bool Success or NOT
     */

    static function parameterSectionName(&$var, $E, $nu, $density, $tmp, $type, $properties) {
        $var = NULL;

        //Get array of properties
        $propArray = array_diff(array_map('trim', explode(";", $properties)), array(""));

        switch ($type) {
            case "REC":
                if (count($propArray) != 2) {
                    return FALSE;
                }
                $section = "S0";
                break;
            case "T-UP":
                if (count($propArray) != 4) {
                    return FALSE;
                }
                $section = "S1";
                break;
            case "T-DOWN":
                if (count($propArray) != 4) {
                    return FALSE;
                }
                $section = "S2";
                break;
            case "TUBE":
                if (count($propArray) != 2) {
                    return FALSE;
                }
                $section = "S6";
                break;
            // If there is no necessary assortment
            default:
                return FALSE;
        }

        // If there is necessary profile in assortment
        $var .= "$section $E ".implode(' ', $propArray)." NU $nu RO $density TMP $tmp";

        return TRUE;
    }

}
