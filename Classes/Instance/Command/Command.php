<?php

namespace Classes\Instance\Command;

/**
 * Class for Command
 *
 * @author Noskov Alexey
 */
class Command extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'command' => '\Classes\Value\StringValue',
        'object' => '\Classes\Value\StringValue',
        'format' => '\Classes\Value\StringValue',
        'file' => '\Classes\Value\StringValue'
    );
    
    public function __construct() {
        parent::__construct();
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {
        $uin = $this->getUin();
        
        if (isset($this->properties['command'])) {
            $command = $this->properties['command']->get();
        }
        
        if (isset($this->properties['object'])) {
            $object = $this->properties['object']->get();
        }
        
        if (isset($this->properties['format'])) {
            $format = $this->properties['format']->get();
        }
        
        if (isset($this->properties['file'])) {
            $file = $this->properties['file']->get();
        }

        echo $uin . " => " . $command . " " . $object . " FROM " . $format .
                " FILE " . $file . "<br/>";
    }
}
