<?php

namespace Classes\Utils\Math;

/**
 * Description of Lines
 *
 * @author Noskov Alexey
 */
class Lines {
    /*
     * Check if two portions on one line
     *      
     * @param Classes\Utils\AbstractInstance\Line $line1 Portion1
     * @param Classes\Utils\AbstractInstance\Line $line2 Portion2
     * 
     * @return bool
     * TRUE - YES
     * FALSE - NO
     */
    public static function isTwoPiecesOnSameLine($line1, $line2) {
        if (Points::isPointOnLine($line1->point1, $line2) > 0 &&
            Points::isPointOnLine($line1->point2, $line2) > 0) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /*
     * Check if two vector on same line have same direction or not
     * 
     * @param \Classes\Utils\AbstractInstance\Line $line1 Portion1
     * @param \Classes\Utils\AbstractInstance\Line $line2 Portion2
     * 
     * @return bool
     * TRUE - YES
     * FALSE - NO
     */
    public static function areTwoVectorHaveSameDirection($line1, $line2) {
        
        $mx1 = Constant::sign($line1->point1->x - $line1->point2->x);
        $mx2 = Constant::sign($line2->point1->x - $line2->point2->x);
        
        $my1 = Constant::sign($line1->point1->y - $line1->point2->y);
        $my2 = Constant::sign($line2->point1->y - $line2->point2->y);
        
        $mz1 = Constant::sign($line1->point1->z - $line1->point2->z);
        $mz2 = Constant::sign($line2->point1->z - $line2->point2->z);
        
        if ($mx1*$mx2 >= 0 && $my1*$my2 >= 0 && $mz1*$mz2 >= 0) {return TRUE;}
        
        return FALSE;
    }
    
    /*
     * Find overlapping of two lines. Return coordinates of overlapping portion
     * in coordinate's system of line
     * 
     * @param \Classes\Utils\AbstractInstance\Line $line1 Line 1
     * @param \Classes\Utils\AbstractInstance\Line $line2 Line 2
     * @param double[] $overlap1 Array of overlapping in Line 1 coordinates
     * @param double[] $overlap2 Array of overlapping in Line 2 coordinates
     * 
     * 
     * @return bool
     * FALSE - No overlapping
     * TRUE - There is overlapping
     */
    public static function findOverlappingOfTwoLines($line1, $line2, &$overlap1, &$overlap2) {
        $isLine1Reversed = FALSE;
        
        // Check same line or not
        if (!Lines::isTwoPiecesOnSameLine($line1, $line2)) {return FALSE;}
        
        // Check same directions or not
        if (!Lines::areTwoVectorHaveSameDirection($line1, $line2)) {
           $line1->reverse();
           $isLine1Reversed = TRUE;
        }
        
        // Points
        $point1 = $line1->point1; $point2 = $line1->point2; // Line 1
        $point3 = $line2->point1; $point4 = $line2->point2; // Line 2
        
        // Calculate line coordinates from point1
        $x1 = 0;
        $x2 = Points::twoPointsDistance($point1, $point2);
        $x3 = Points::twoPointsDistance($point1, $point3);
        $x4 = Points::twoPointsDistance($point1, $point4);
        
        // Set sign to coordinates
        if (!Lines::areTwoVectorHaveSameDirection($line1,
                new \Classes\Utils\AbstractInstance\Line($point1, $point3))) {
            $x3 *= -1;
        }
        
        // Set sign to coordinates
        if (!Lines::areTwoVectorHaveSameDirection($line1,
                new \Classes\Utils\AbstractInstance\Line($point1, $point4))) {
            $x4 *= -1;
        }
        
        // Overlapping length
        $length = min($x2, $x4) - max($x1, $x3);
        
        // Check if therу is overlapping
        if ($length <= 0) {return FALSE;}
        
        // Create coordinate array
        $arrayToSort = array(
            0 => array('point' => 1, 'value' => $x1),
            1 => array('point' => 2, 'value' => $x2),
            2 => array('point' => 3, 'value' => $x3),
            3 => array('point' => 4, 'value' => $x4),
        );
        
        $sortedArray = Constant::sortArrayByIndex($arrayToSort, 'value');
        
        // Check variant of overlapping
        
        // 1-3-2-4
        if ($sortedArray[0]['point'] == 1 &&
            $sortedArray[1]['point'] == 3 &&
            $sortedArray[2]['point'] == 2 &&
            $sortedArray[3]['point'] == 4) {
        
            $overlap1 = array($x3, $x2);
            $overlap2 = array(0, $x2-$x3);
        }
        
        // 3-1-4-2
        if ($sortedArray[0]['point'] == 3 &&
            $sortedArray[1]['point'] == 1 &&
            $sortedArray[2]['point'] == 4 &&
            $sortedArray[3]['point'] == 2) {
        
            $overlap1 = array(0, $x4);
            $overlap2 = array($x1-$x3, $x4-$x3);
        }
        
        // 1-3-4-2
        if ($sortedArray[0]['point'] == 1 &&
            $sortedArray[1]['point'] == 3 &&
            $sortedArray[2]['point'] == 4 &&
            $sortedArray[3]['point'] == 2) {
        
            $overlap1 = array($x3, $x4);
            $overlap2 = array(0, $x4-$x3);
        }
        
        // 3-1-2-4
        if ($sortedArray[0]['point'] == 3 &&
            $sortedArray[1]['point'] == 1 &&
            $sortedArray[2]['point'] == 2 &&
            $sortedArray[3]['point'] == 4) {
        
            $overlap1 = array(0, $x2);
            $overlap2 = array($x1-$x3, $x2-$x3);
        }
        
        // Reverse overlapping of line1
        if ($isLine1Reversed) {
            // $x2 = Length(line1)
            $begin = $x2 - $overlap1[1];
            $end = $x2 - $overlap1[0];
            $overlap1[0] = $begin;
            $overlap1[1] = $end;
        }
        
        return TRUE;
    }
    
    /*
     * Get Point's coordinates on Line with Offset from Begin
     * 
     * @param \Classes\Utils\AbstractInstance\Line $line Line
     * @param double $offset Offset from begin
     * 
     * @return Classes\Utils\AbstractInstance\Point if $offset is inside ofLine
     * @return FALSE if $offset is outside of Line
     */
    public static function getPointOnLineWithOffset($line, $offset) {
        
        $length = $line->length();
        
        if ( (0 - $offset) > Constant::epsilon || ($offset - $length) > Constant::epsilon) {
            return FALSE;
        }
        
        $x = $line->point1->x + ($line->point2->x - $line->point1->x) * $offset / $length;
        $y = $line->point1->y + ($line->point2->y - $line->point1->y) * $offset / $length;
        $z = $line->point1->z + ($line->point2->z - $line->point1->z) * $offset / $length;
        
        $point = new \Classes\Utils\AbstractInstance\Point($x, $y, $z);
        
        return $point;
            
    }
}
