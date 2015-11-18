<?php

namespace Classes\Instance\Load\Member;

/**
 * Class for Concentrated Member Load
 *
 * @author Noskov Alexey
 */
class ConcenratedMemberLoad extends \Classes\Instance\Load\Load {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'loadCase' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'direction' => '\Classes\Value\IntValue',
        'value' => '\Classes\Value\FloatValue',
        'position' => '\Classes\Value\FloatValue'
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
        $position = $this->getProperty('position')->get();
        
        echo $uin . " => $loadCase NC |$direction| $position, $value *$name*<br/>";
    }
}
