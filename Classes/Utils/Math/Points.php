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
     * @param Classes\Utils\AbstractInstance\Point $point1
     * @param Classes\Utils\AbstractInstance\Point $point2
     * 
     * @return bool
     */
    public static function isPointSame($point1, $point2) {
        
        if (sqrt(pow($point1->x-$point2->x, 2)
                + pow($point1->y-$point2->y, 2)
                + pow($point1->z-$point2->z, 2))
                <= \Classes\Factory\Model\Model::coordinateTolerance) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*
     * Check if point is laying on line
     * Point (x,y,z) - Line (x1,y1,z1)(x2,y2,z2)
     * 
     * px = (x-x2)/(x1-x2)
     * py = (y-y2)/(y1-y2)
     * pz = (z-z2)/(z1-z2)
     * 
     * If (0 <= px == py == pz <= 1) => Point is on Line
     * 0, 1 - ends
     *      
     * @param Classes\Utils\AbstractInstance\Point $point
     * @param Classes\Utils\AbstractInstance\Line $line
     * 
     * @return int
     * 0 - ТОЧКА НЕ НА ПРЯМОЙ
     * 1 - ТОЧКА НА ПРЯМОЙ
     * 2 - ТОЧКА НА КОНЦЕ ОТРЕЗКА
     * 3 - ТОЧКА ВНУТРИ ОТРЕЗКА
     */
    public static function isPointOnLine($point, $line) {
        
        $px = ($point->x - $line->point2->x) / ($line->point1->x - $line->point2->x);
        $py = ($point->y - $line->point2->y) / ($line->point1->y - $line->point2->y);
        
        if (!Constant::isNumbersEqual($px, $py)) {
            return 0;
        }
        
        $pz = ($point->z - $line->point2->z) / ($line->point1->z - $line->point2->z);
        
        if (!Constant::isNumbersEqual($px, $pz)) {
            return 0;
        }
        
        // Check ends or not
        if (Constant::isNumbersEqual($px, 0) || Constant::isNumbersEqual($px, 1)) {
            return 2;
        }
        
        // Check inside or not
        if ($px > 0 && $px < 1) {
            return 3;
        }
        
        // Point is on long line
        return 1;
    }
}
