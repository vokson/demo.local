<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace base;

/**
 * Description of ApplicationRegistry
 *
 * @author vokson
 */
class ApplicationRegistry extends Registry {

    private static $instance;
    private $freezedir = "data";
    private $values = array();
    private $mtimes = array();

    private function __construct() {
        
    }

    /**
     * Create object of class
     * 
     * @return ApplicationRegistry
     */
    static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Read values from file
     * 
     * @param type $key
     * @return null
     */
    protected function get($key) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $this->freezedir . DIRECTORY_SEPARATOR . $key;
//        echo var_dump(file_exists($path));
        if (file_exists($path)) {
            clearstatcache();
            $mtime = filemtime($path);
            if (!isset($this->mtimes[$key])) {
                $this->mtimes[$key] = 0;
            }
            if ($mtime > $this->mtimes[$key]) {
                $data = file_get_contents($path);
                $this->mtimes[$key] = $mtime;
                return ($this->values[$key] = unserialize($data));
            }
        }

        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return NULL;
    }

    /**
     * Save values to file
     * 
     * @param type $key
     * @param type $val
     */
    protected function set($key, $val) {
//         $userinfo = posix_getpwuid(posix_getuid());
//        echo 'USER = ' . $userinfo['name']; //выведет имя пользователя, от которого работает PHP
        
        $this->values[$key] = $val;
        $path = __DIR__ . DIRECTORY_SEPARATOR . $this->freezedir . DIRECTORY_SEPARATOR . $key;
//        echo $path;
        
        file_put_contents($path, serialize($val));
        
       
//        
//        chmod($path, 0777);
        $this->mtimes[$key] = time();
    }
    
    static function getProject() {
        return self::instance()->get('project');
    }

    static function setProject($val) {
        return self::instance()->set('project', $val);
    }
    
    static function getAdminIP() {
        return self::instance()->get('admin_ip');
    }

    static function setAdminIP($val) {
        return self::instance()->set('admin_ip', $val);
    }

    static function getDSN() {
        return self::instance()->get('dsn');
    }

    static function setDSN($val) {
        return self::instance()->set('dsn', $val);
    }
    
    static function getDrawing() {
        return self::instance()->get('drawing');
    }

    static function setDrawing($val) {
        return self::instance()->set('drawing', $val);
    }
    
}
