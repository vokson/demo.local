<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Node;

/**
 * Class for Node Instance
 *
 * @author Noskov Alexey
 */
class Node extends \Classes\Instance\Instance {
   /*
     * $param string[] valid property names
     */
    protected $validPropertyNames = array(
        'id' => '',
        'tag' => '',
        'x' => '',
        'y' => '',
        'z' => ''
    );
}
