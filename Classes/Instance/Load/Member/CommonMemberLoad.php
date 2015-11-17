<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Load\Member;

/**
 * Class for Member Instance
 *
 * @author Noskov Alexey
 */
class CommonMemberLoad extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'loadCase' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'type' => '\Classes\Value\StringValue',
        'direction' => '\Classes\Value\StringValue',
        'x1' => '\Classes\Value\FloatValue',
        'y1' => '\Classes\Value\FloatValue',
        'z1' => '\Classes\Value\FloatValue',
        'x2' => '\Classes\Value\FloatValue',
        'y2' => '\Classes\Value\FloatValue',
        'z2' => '\Classes\Value\FloatValue',
        'value1' => '\Classes\Value\StringValue',
        'value2' => '\Classes\Value\StringValue'
    );
    
    public function __construct() {
        parent::__construct();
        $this->setProperty('name', new \Classes\Value\StringValue(''));
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {
        // TODO
    }
}
