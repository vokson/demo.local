<?php
namespace Classes\Controller\Command;

/**
 * Interpretation of request
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class CommandResolver {
    private static $base_cmd;
    private static $default_cmd;
    
    /**
     * Prepare defauld settings for this class
     */
    function __construct() {
        if (! self::$base_cmd) {
            self::$base_cmd = new \ReflectionClass("\Classes\Controller\Command\Command");
            self::$default_cmd = new cmdIndex();
        }
        
    }
    /**
     * Search file and class inside the file for command
     * 
     * @param \Classes\Controller\Controller\Request $request Request from user
     * @return ReflectionClass
     */
    function getCommand(\Classes\Controller\Controller\Request $request) {
        $cmd = 'cmd' . $request->getProperty('cmd');
        $sep = DIRECTORY_SEPARATOR;
        if (!$cmd) {
            return self::$default_cmd;
        }
        $cmd = str_replace(array('.',$sep), '', $cmd);
        $filepath = "Classes{$sep}Controller{$sep}Command{$sep}{$cmd}.php";
        $classname = "\\Classes\\Controller\\Command\\$cmd";
        if (file_exists($filepath)) {
            @require_once "$filepath";
            if (class_exists($classname)) {
               $cmd_class = new \ReflectionClass($classname);
               if ($cmd_class->isSubclassOf(self::$base_cmd)) {
                   return $cmd_class->newInstance();
               } else {
                   $request->addFeedback("Object Command from command '$cmd' isn't found'");
               }
            }
        }
        $request->addFeedback("Command '$cmd' isn't found");
        return clone self::$default_cmd;
        
    }
}
