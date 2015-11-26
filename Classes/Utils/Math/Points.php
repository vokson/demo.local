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

        if ((abs($point1->x - $point2->x) <= \Classes\Factory\Model\Model::coordinateTolerance) &&
                (abs($point1->y - $point2->y) <= \Classes\Factory\Model\Model::coordinateTolerance) &&
                (abs($point1->z - $point2->z) <= \Classes\Factory\Model\Model::coordinateTolerance)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Get distance between two points

     * @param Classes\Utils\AbstractInstance\Point $point1
     * @param Classes\Utils\AbstractInstance\Point $point2
     * 
     * @return bool
     */

    public static function twoPointsDistance($point1, $point2) {
        return sqrt(
                pow($point1->x - $point2->x, 2) + pow($point1->y - $point2->y, 2) + pow($point1->z - $point2->z, 2)
        );
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
     * 1 - ТОЧКА НА ПРЯМОЙ, НО НЕ НА ОТРЕЗКЕ
     * 2 - ТОЧКА НА КОНЦЕ ОТРЕЗКА
     * 3 - ТОЧКА ВНУТРИ ОТРЕЗКА
     * 4 - ТОЧКА И ОБА КОНЦА - ЭТО ОДНА ТОЧКА
     */

    public static function isPointOnLine($point, $line) {

        $dx = $line->point1->x - $line->point2->x;
        $dy = $line->point1->y - $line->point2->y;
        $dz = $line->point1->z - $line->point2->z;

        // Create array of p
        $p = array();
        if (!Constant::isNumbersEqual($dx, 0)) {
            $p[] = ($point->x - $line->point2->x) / $dx;
        } else {
            // X coordinate of all point must be same
            if (!Constant::isNumbersEqual($point->x, $line->point1->x)) {
                return 0;
            }
        }
        if (!Constant::isNumbersEqual($dy, 0)) {
            $p[] = ($point->y - $line->point2->y) / $dy;
        } else {
            // Y coordinate of all point must be same
            if (!Constant::isNumbersEqual($point->y, $line->point1->y)) {
                return 0;
            }
        }

        if (!Constant::isNumbersEqual($dz, 0)) {
            $p[] = ($point->z - $line->point2->z) / $dz;
        } else {
            // Z coordinate of all point must be same
            if (!Constant::isNumbersEqual($point->z, $line->point1->z)) {
                return 0;
            }
        }

        // CASE 4
        if (count($p) == 0) {
            return 4;
        }

        // All members in p array must be same
        $isEqual = TRUE;
        if (count($p) > 1) {
            for ($i = 1; $i < count($p); $i++) {
                if (!Constant::isNumbersEqual($p[$i], $p[0])) {
                    $isEqual = FALSE;
                }
            }
        }

        // CASE 0
        if (!$isEqual) {
            return 0;
        }

        // CASE 2
        if (Constant::isNumbersEqual($p[0], 0) || Constant::isNumbersEqual($p[0], 1)) {
            return 2;
        }

        // CASE 3
        if ($p[0] > 0 && $p[0] < 1) {
            return 3;
        }

        // CASE 1
        return 1;
    }

}
