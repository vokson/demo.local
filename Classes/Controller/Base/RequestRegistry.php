<?php

namespace Classes\Controller\Base;

/**
 * Description of RequestRegistry
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class RequestRegistry extends Registry {
    private $values = array ();
    private static $instance;
    
    private function __construct() { }
    
 static function instance() {
     if (!isset(self::$instance)) {
         self::$instance = new self();
     }
     return self::$instance;
 }
 
 protected function get($key) {
     if (isset($this->values[$key])) {
         return $this->values[$key];
     }
     return NULL;
 }
 
 protected function set($key, $val) {
     $this->values[$key] = $val;
 }
 
 static function getRequest() {
     return self::instance()->get('request');
 }
 
 static function setRequest(\Classes\Controller\Controller\Request $request) {
     return self::instance()->set('request', $request);
 }
}
