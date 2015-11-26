<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Utils\Math;

/**
 * DMath constants
 *
 * @author Noskov Alexey
 */
class Constant {

    // Tolerance for double numbers comparison
    const epsilon = 0.001;

    /*
     * Check $a == $b or not
     * 
     * @param double $a
     * @param double $и
     * 
     * @return bool Equal or Not
     */

    public static function isNumbersEqual($a, $b) {
        if (abs($a - $b) < self::epsilon) {
            return TRUE;
        }

        return FALSE;
    }

    /*
     * Get sign of number
     * 
     * @param mixed $number Number
     * 
     * @return int
     * 1 - Positive
     * 0 - Zero
     * -1 - Negative
     */

    public static function sign($number) {
        return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
    }

    /*
     * Sort array by index
     * 
     * @param mixed[] $array
     * @param string $on Name of index
     * @param int $order Sort flag
     * 
     * @return mixed[] Sorted array
     */
    static function sortArrayByIndex($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            $i = 0;
            foreach ($sortable_array as $k => $v) {
                $new_array[$i] = $array[$k];
                $i++;
            }
        }

        return $new_array;
    }

    
    /*
     * Get string coordinate
     * 
     * @param double $value Value
     * @param int $precision Presicion
     * 
     * @return string String with value rounded for necessary precision
     */
    static function stringValue($value, $precision) {
        if ($value > 0) {
            $intPart = floor($value/\Classes\Factory\Model\Model::coordinateTolerance);
        } else {
            $intPart = ceil($value/\Classes\Factory\Model\Model::coordinateTolerance);
        }
        
        return sprintf("%." . $precision . "f", $intPart * \Classes\Factory\Model\Model::coordinateTolerance);
    }
}
