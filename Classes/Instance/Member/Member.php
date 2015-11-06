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
class Member extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'id' => '\Classes\Value\IntValue' ,
        'name' => '\Classes\Value\StringValue',
        'betaAngle' => '\Classes\Value\FloatValue',
        'section' => '\Classes\Value\StringValue'
    );
    
    /*
     * PRINT
     */
    public function servicePrint() {

        $uin = $this->getUin();
        
        $id = $name = $betaAngle =$section = NULL;
        
        if (isset($this->properties['id'])) {
            $id = $this->properties['id']->get();
        }
        
        if (isset($this->properties['name'])) {
            $name = $this->properties['name']->get();
        }
        
        if (isset($this->properties['betaAngle'])) {
            $betaAngle = $this->properties['betaAngle']->get();
        }
        
        if (isset($this->properties['section'])) {
            $section = $this->properties['section']->get();
        }

        echo $uin . " => |$id| [$betaAngle] $section *$name* <br/>";
    }
}
