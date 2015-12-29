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
class ConcentratedCommonMemberLoad extends \Classes\Instance\Load\Load {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'loadCase' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'x' => '\Classes\Value\FloatValue',
        'y' => '\Classes\Value\FloatValue',
        'z' => '\Classes\Value\FloatValue',
        'FX' => '\Classes\Value\FloatValue',
        'FY' => '\Classes\Value\FloatValue',
        'FZ' => '\Classes\Value\FloatValue',
        'MX' => '\Classes\Value\FloatValue',
        'MY' => '\Classes\Value\FloatValue',
        'MZ' => '\Classes\Value\FloatValue'
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
