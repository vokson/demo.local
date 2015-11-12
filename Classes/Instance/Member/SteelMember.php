<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Member;

/**
 * Class for Member Instance
 *
 * @author Noskov Alexey
 */
class SteelMember extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'name' => '\Classes\Value\StringValue',
        'x1' => '\Classes\Value\FloatValue',
        'y1' => '\Classes\Value\FloatValue',
        'z1' => '\Classes\Value\FloatValue',
        'x2' => '\Classes\Value\FloatValue',
        'y2' => '\Classes\Value\FloatValue',
        'z2' => '\Classes\Value\FloatValue',
        'pin1' => '\Classes\Value\StringValue',
        'pin2' => '\Classes\Value\StringValue',
        'betaAngle' => '\Classes\Value\IntValue',
        'sectionType' => '\Classes\Value\StringValue',
        'sectionName' => '\Classes\Value\StringValue',
        'isDivided' => '\Classes\Value\BoolValue'
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
