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
