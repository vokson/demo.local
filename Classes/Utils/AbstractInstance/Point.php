<?php


namespace Classes\Utils\AbstractInstance;

/**
 * Class of point
 *
 * @author Noskov Alexey
 */
class Point {
    /*
     * Coordinates
     * 
     * @param double $x
     * @param double $y
     * @param double $z
     */
    public $x, $y, $z;
    
    public function __construct($x, $y, $z) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
