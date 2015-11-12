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
class Constraint extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'x' => '\Classes\Value\FloatValue',
        'y' => '\Classes\Value\FloatValue',
        'z' => '\Classes\Value\FloatValue',
        'fix' => '\Classes\Value\StringValue'
    );
    
/*
     * PRINT
     */
    public function servicePrint() {
        // TODO
    }    
}
