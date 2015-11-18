<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Load\Node;

/**
 * Class for Node Load
 *
 * @author Noskov Alexey
 */
class NodeLoad extends \Classes\Instance\Load\Load {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'loadCase' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'direction' => '\Classes\Value\IntValue',
        'value' => '\Classes\Value\FloatValue'
    );
    
    public function __construct() {
        parent::__construct();
        $this->setProperty('name', new \Classes\Value\StringValue(''));
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {
        $uin = $this->getUin();
        
        $loadCase = $this->getProperty('loadCase')->get();
        $name = $this->getProperty('name')->get();
        $direction = $this->getProperty('direction')->get();
        $value = $this->getProperty('value')->get();
        
        echo $uin . " => $loadCase NC |$direction| $value *$name*<br/>";
    }
}
