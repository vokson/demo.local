<?php

namespace Classes\Factory\Export\BinaryData\File\Version;

/**
 * Description of FileWriter
 *
 * @author Noskov Alexey
 */

class FileWriterVersion_1 extends \Classes\Factory\Export\BinaryData\File\FileWriter {
    private $documents; // Array of documents
    
    public function __construct() {
        $this->documents = array();
        $this->version = 1;
    }
    
    public function getBody() {
        $data = '*MODEL_DATA*';
        $data .= pack('S', $this->version);
        
        // Sort documents by number
        usort($this->documents, '\Classes\Factory\Export\BinaryData\File\DocumentDescription::compareByNumber');
        
        $totalCount = 0;
        for ($i=0; $i < count($this->documents); $i++) {
            $totalCount += strlen($this->documents[$i]->body);
        }
        
        // Write offset of docs' description
        $data .= pack('I', strlen($data) + $totalCount + 4);
        
        // Write each document
        for ($i=0; $i < count($this->documents); $i++) {
            $this->documents[$i]->offset = strlen($data);
            $this->documents[$i]->count = strlen($this->documents[$i]->body);
            
            $data .= $this->documents[$i]->body;
        }
        
        // Write doc's description
        for ($i=0; $i < count($this->documents); $i++) {
            $data .= pack('S', $this->documents[$i]->number);
            $data .= pack('S', $this->documents[$i]->version);
            $data .= pack('I', $this->documents[$i]->offset);
            $data .= pack('I', $this->documents[$i]->count);
        }
        
        // Two zero bytes in the end
        $data .= "\x00\x00";
        
        return $data;
    }
    
    /*
     * Create document description
     * 
     * @param \Classes\Factory\Export\BinaryData\DocumentWriter $doc Object of document
     * 
     * @return \Classes\Factory\Export\BinaryData\File\DocumentDescription
     */
    private function createDocumentDescription($docWriter) {
        $object = new \Classes\Factory\Export\BinaryData\File\DocumentDescription();
        
        $object->number = $docWriter->getDocumentNumber();
        $object->version = $docWriter->getDocumentVersion();
        $object->body = $docWriter->getBody();
        
        return $object;
    }

    /*
     * Export nodes
     * 
     * @param \Classes\Instance\Node\Node[] $objects
     */
    public function nodesExport($objects) {
        $docWriter = \Classes\Factory\Export\BinaryData\Document\DocumentWriterFactory::make('Node');
        $docWriter->encode($objects);
        
        $docDescription = $this->createDocumentDescription($docWriter);
        $this->documents[] = $docDescription;
    }
    
    /*
     * Export members
     * 
     * @param \Classes\Instance\Member\Member[] $objects
     */
     public function membersExport($objects) {
        $docWriter = \Classes\Factory\Export\BinaryData\Document\DocumentWriterFactory::make('Member');
        $docWriter->encode($objects);
        
        $docDescription = $this->createDocumentDescription($docWriter);
        $this->documents[] = $docDescription;
    }
}
