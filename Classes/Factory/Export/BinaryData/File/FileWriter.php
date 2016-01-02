<?php

namespace Classes\Factory\Export\BinaryData\File;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */
abstract class FileWriter {
    protected $version; // Version of document
    
    
    /*
     * Get body of file 
     * 
     * @return string
     */
    abstract public function getBody();
    
     /*
     * Export of nodes
     * 
     * @param \Classes\Instance\Node\Node[] $nodes Array of nodes to export
     * 
     * @return bool SUCCESS
     */
    abstract public function nodesExport($nodes);
}
