<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Import\Instance;

/**
 * Base class for uploading array of instances
 *
 * @author Noskov Alexey
 */
abstract class InstanceUploader {
    
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @param \Classes\Instance\Instance $instanse class of instances stored in file
     * 
     * @return \Classes\Instance\Instance[]  array of instances
     */
    abstract public function upload($path, $instance);
}
