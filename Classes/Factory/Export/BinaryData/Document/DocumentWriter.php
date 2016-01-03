<?php

namespace Classes\Factory\Export\BinaryData\Document;

/**
 * Description of Document Writer
 *
 * @author Noskov Alexey
 */
abstract class DocumentWriter {
    protected $number; // Number of document
    protected $version; // Version of document
    private $body; // Body of document
    
    
    /*
     * Set body of document
     * 
     * @param mixed[] $objects Array of objects to export
     * 
     * @return void
     */
    public function encode($objects) {
        // Prepare body
        $body = '';
        
        // Write body header
        $body .= pack('S', $this->number);
        $body .= pack('S', $this->version);
        $body .= pack('I', count($objects));
        
        // Write objects one by one
        foreach ($objects as $object) {
            $body .= $this->add($object);
        }
        
        $this->body = $body;
    }
    
    /*
     * Get body of document
     * 
     * @return string Binary data
     */
    public function getBody() {
        return $this->body;
    }
    
    /* Get document number
     * 
     * @return int Doc's number
     */
    public function getDocumentNumber() {
        return $this->number;
    }
    
    /* Get document version
     * 
     * @return int Doc's version
     */
    public function getDocumentVersion() {
        return $this->version;
    }
    
     /*
     * Prepare binary data of object
     * 
     * @param mixed[] $object Objects to add
     * 
     * @return string Binary data of encoded object
     */
    abstract protected function add($object);
    
    
    
}
