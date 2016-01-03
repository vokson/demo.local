<?php

namespace Classes\Factory\Export\BinaryData\File;

/**
 * Class to keep description of document
 *
 * @author Noskov Alexey
 */
class DocumentDescription {

    public $number; // Document's number
    public $version; // Document's version
    public $offset; // Document's offset
    public $count; // Document's count
    public $body; // Document's body

    /*
     * Compare two objects
     * 
     * @param DocumentDescription $a Object 1
     * @param DocumentDescription $a Object 2
     * 
     * @return int
     */

    static public function compareByNumber($a, $b) {
        if ($a->number == $b->number) {
            return 0;
        }
        return ($a->number < $b->number) ? -1 : 1;
    }

}
