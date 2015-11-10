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

}
