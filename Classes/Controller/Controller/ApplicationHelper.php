<?php

namespace Classes\Controller\Controller;

/**
 * Configuration settings for application in cache
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */

class ApplicationHelper {
    private static $instance;
    
    private function __construct() {}
    
    static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Check ApplicationRegistry to make sure confifuration settings have
     * been already loaded or not
     * 
     * @return void
     */
    function init($path = NULL) {
        if (!$path) {
            $path = \Classes\Controller\Util\Utils::configPath();
        }
        $this->getOptions(realpath($path));
    }
    
    /**
     * Read configuration file
     */
    private function getOptions($path) {
        \Classes\Controller\Util\Utils::ensure (file_exists($path),
                "Configuration file isn't found'");
        
        $options = parse_ini_file($path, true);
        \Classes\Controller\Util\Utils::ensure (is_array($options),
                "Configuration file is damaged");
        
        $coordinateTolerance = $options['coordinate_tolerance'];
        \Classes\Controller\Util\Utils::ensure ($coordinateTolerance,
                "There is no [coordinate_tolerance] option in configuration file");
        \Classes\Factory\Model\Model::$coordinateTolerance = $coordinateTolerance;
        
        $epsilon = $options['epsilon'];
        \Classes\Controller\Util\Utils::ensure ($epsilon,
                "There is no [epsilon] option in configuration file");
        \Classes\Utils\Math\Constant::$epsilon = $epsilon;
        
        $timeLimit = $options['time_limit'];
        \Classes\Controller\Util\Utils::ensure ($timeLimit,
                "There is no [time_limit] option in configuration file");
        set_time_limit($timeLimit);
    }
    
    
}
