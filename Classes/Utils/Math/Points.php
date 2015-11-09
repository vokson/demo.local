<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Utils\Math;

/**
 * Math function for points
 *
 * @author Noskov Alexey
 */
class Points {
    
    /*
     * Check if coordinates of two points are same
     * 
     * @return bool
     */
    public static function isPointSame($x1, $y1, $z1, $x2, $y2, $z2) {
        
        if (sqrt(pow($x1-$x2, 2) + pow($y1-$y2, 2) + pow($z1-$z2, 2))
                <= \Classes\Factory\Model\Model::coordinateTolerance) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
