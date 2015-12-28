<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Group\SteelMember;

/**
 * Class for check group of steel members
 *
 * @author Noskov Alexey
 */
class SteelMemberCheckGroup extends \Classes\Instance\Group\Group {
    /*
     * $param string[] valid property names
     */

    protected $validPropertyNames = array(
        'listbox' => '\Classes\Listbox\Listbox',
        'name' => '\Classes\Value\StringValue',
        'steel' => '\Classes\Value\StringValue',
        'Ry' => '\Classes\Value\FloatValue',
        'gammaC' => '\Classes\Value\FloatValue',
        'muXZ' => '\Classes\Value\FloatValue',
        'muXY' => '\Classes\Value\FloatValue',
        'FC' => '\Classes\Value\IntValue'
    );

    public function __construct() {
        parent::__construct();
        $this->setProperty('name', new \Classes\Value\StringValue(''));
    }

    /*
     * PRINT
     */

    public function servicePrint() {
        // Get members
        $members = \Classes\Factory\Model\Model::getMembers();
        
        $uin = $this->getUin();

        $name = $this->getProperty('name')->get();
        $steel = $this->getProperty('steel')->get();
        $Ry = $this->getProperty('Ry')->get();
        $gammaC = $this->getProperty('gammaC')->get();
        $muXZ = $this->getProperty('muXZ')->get();
        $muXY = $this->getProperty('muXY')->get();
        $FC = $this->getProperty('FC')->get();

        $list = $this->getProperty('listbox')->get();

        echo $uin . " => *$name* $steel($Ry) |$gammaC| $muXZ, $muXY - $FC<br/>";
        foreach ($list as $memberUin) {
            $memberId = $members[$memberUin]->getProperty('id')->get();
            echo " * $memberUin - $memberId <br/>";
        }
    }

}
