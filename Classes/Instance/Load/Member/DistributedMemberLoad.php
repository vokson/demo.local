<?php

namespace Classes\Instance\Load\Member;

/**
 * Class for Distributed Member Load
 *
 * @author Noskov Alexey
 */
class DistributedMemberLoad extends \Classes\Instance\Load\Load {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'loadCase' => '\Classes\Value\IntValue',
        'name' => '\Classes\Value\StringValue',
        'direction' => '\Classes\Value\IntValue',
        'value1' => '\Classes\Value\FloatValue',
        'position1' => '\Classes\Value\FloatValue',
        'value2' => '\Classes\Value\FloatValue',
        'position2' => '\Classes\Value\FloatValue'
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
        $value1 = $this->getProperty('value1')->get();
        $position1 = $this->getProperty('position1')->get();
        $value2 = $this->getProperty('value2')->get();
        $position2 = $this->getProperty('position2')->get();
        
        echo $uin . " => $loadCase NC |$direction| $position1, $value1, $position2, $value2 *$name*<br/>";
    }
}
