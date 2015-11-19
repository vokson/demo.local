<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\LoadCase;

/**
 * Class for Node Load
 *
 * @author Noskov Alexey
 */
class LoadCase extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'id' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'description' => '\Classes\Value\StringValue',
        'masses' => '\Classes\Value\StringValue'
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
        
        if (isset($this->properties['id'])) {
            $id = $this->properties['id']->get();
        }
        
        if (isset($this->properties['name'])) {
            $name = $this->properties['name']->get();
        }
        
        if (isset($this->properties['description'])) {
            $description = $this->properties['description']->get();
        }
        
        if (isset($this->properties['masses'])) {
            $masses = $this->properties['masses']->get();
        }

        echo $uin . " => |$id| $masses $description *$name* <br/>";
    }
}
