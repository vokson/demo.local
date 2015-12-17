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

    /*
     * Get filename of temporary file on server side
     * 
     * @param string $originalName Original filename
     * @param string $tempName Temporary filename will be placed here
     * 
     * @return bool Is file found or not?
     */

    public static function getTempFileName($originalName, &$tempName) {
        // Get index in files array, where file is stored
        $index = array_search($originalName, $_FILES['file']['name']);

        // If there isn't file in files
        if ($index === FALSE) {
            return FALSE;
        }
        // Read command file. Get temp filename
        $tempName = $_FILES['file']['tmp_name'][$index];
        return TRUE;
    }

}
