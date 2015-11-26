<?php


namespace Classes\Utils\AbstractInstance;

/**
 * Class of point
 *
 * @author Noskov Alexey
 */
class Line {
    /*
     * Points of begin and end
     * 
     * @param Classes\Utils\AbstractInstance\Point $point1
     * @param Classes\Utils\AbstractInstance\Point $point2
     */
    public $point1, $point2;
    
    public function __construct($point1, $point2) {
        $this->point1 = $point1;
        $this->point2 = $point2;
    }
    
    /*
     * Create line from two nodes
     * 
     * @param \Classes\Instance\Node\Node $node1 Node 1
     * @param \Classes\Instance\Node\Node $node2 Node 2
     * 
     * return \Classes\Utils\AbstractInstance\Line Line
     */
    public static function createFromTwoNodes($node1, $node2) {

        $line = new self(Point::createFromNode($node1), Point::createFromNode($node2));
    	return $line;
    }
    
    /*
     * Get length
     * 
     * @return double
     */
    public function length() {
       return \Classes\Utils\Math\Points::twoPointsDistance($this->point1, $this->point2);
    }
    
    /* 
     * Reverse line
     */
    public function reverse() {
        $temp = $this->point1;
        $this->point1 = $this->point2;
        $this->point2 = $temp;
    }
    
    
}
