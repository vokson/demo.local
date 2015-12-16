<?php

namespace Classes\Controller\Util;

/**
 * Miscellaneous utility methods.
 */
final class Utils {

    /**
     * Create path & filename for View
     * 
     * @param type $page
     * @return string
     */
    public static function createViewName($page) {
        return 'Classes/Controller/View/' . $page . '.phtml';
    }

    /**
     * Return path to configuration file
     * 
     * @return string
     */
    public static function configPath() {
        return realpath(__DIR__ . "/../../../Config/config.ini");
    }
    
     /**
     * Return path to TEMP folder
     * 
     * @return string
     */
    public static function tempPath() {
        return "Temp";
    }

    /**
     * Usefull method to check statement and throw exception
     * 
     * @param type $expr
     * @param type $message
     * @throws \Exception
     */
    public static function ensure($expr, $message) {
        if (!$expr) {
            throw new \Exception($message);
        }
    }

}
