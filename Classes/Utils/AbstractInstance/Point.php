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
    
    /*
     * Create point from node
     * 
     * @param \Classes\Instance\Node\Node $node Node
     * 
     * return \Classes\Utils\AbstractInstance\Point Point
     */
    public static function createFromNode($node) {
        $point = new self(
                $node->getProperty('x')->get(),
                $node->getProperty('y')->get(),
                $node->getProperty('z')->get()
            );
        
    	return $point;
    }
    
}
