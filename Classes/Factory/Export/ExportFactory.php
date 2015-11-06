<?php

namespace Classes\Factory\Export;

/**
 * Class of Export Factory
 *
 * @author Noskov Alexey
 */
abstract class ExportFactory {
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @return void
     */
    abstract static public function export($path);
}
