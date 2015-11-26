<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Node;

/**
 * Class for Node Instance
 *
 * @author Noskov Alexey
 */
class Node extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'id' => '\Classes\Value\IntValue' ,
        'name' => '\Classes\Value\StringValue',
        'x' => '\Classes\Value\FloatValue',
        'y' => '\Classes\Value\FloatValue',
        'z' => '\Classes\Value\FloatValue'
    );
    
    public function __construct() {
        parent::__construct();
        $this->setProperty('name', new \Classes\Value\StringValue(''));
    }
    
    /*
     * Comparison of two Nodes. Return
     * a < b = -1
     * a == b = 0
     * a > b = 1
     * 
     * @param Classes\Instance\Node\Node $a Point 1
     * @param Classes\Instance\Node\Node $b Point 2
     * @param double $tolerance Value of tolerance btw Point's coordinates
     * 
     * return int 
     */
    static function compare($a, $b, $tolerance) {
        
        $dx = $a->getProperty('x')->get() - $b->getProperty('x')->get();
        $dy = $a->getProperty('y')->get() - $b->getProperty('y')->get();
        $dz = $a->getProperty('z')->get() - $b->getProperty('z')->get();
       
        if ($dx > $tolerance) {return 1;}
        if ($dx < -$tolerance) {return -1;}
        
        // if |DX| <= $tolerance
        if ($dy > $tolerance) {return 1;}
        if ($dy < -$tolerance) {return -1;}
        
        // if |DX| && |DY| <= $tolerance
        if ($dz > $tolerance) {return 1;}
        if ($dz < -$tolerance) {return -1;}
        
        // if |DX| && |DY| && |DZ| <= $tolerance
        return 0;
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {

        $uin = $this->getUin();
        
        $id = $name = $x =$y = $z = NULL;
        
        if (isset($this->properties['id'])) {
            $id = $this->properties['id']->get();
        }
        
        if (isset($this->properties['name'])) {
            $name = $this->properties['name']->get();
        }
        
        if (isset($this->properties['x'])) {
            $x = $this->properties['x']->get();
        }
        
        if (isset($this->properties['y'])) {
            $y = $this->properties['y']->get();
        }
        
        if (isset($this->properties['z'])) {
            $z = $this->properties['z']->get();
        }
        
        echo $uin . " => |$id| ($x, $y, $z) *$name* <br/>";
    }
    
}
