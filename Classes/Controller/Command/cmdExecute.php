<?php

namespace Classes\Controller\Command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdExecute extends Command {


    function doExecute(\Classes\Controller\Controller\Request $request) {
        try {
            // Check command file
            $filename = $this->getTempFileName("constructor.xlsx");

            if ($filename !== FALSE) {
                // Upload command file
                $uploadFactory = new \Classes\Factory\Import\Instance\InstanceUploaderFromExcel();
                $objectArray = $uploadFactory->upload($filename, new \Classes\Instance\Command\Command);

                foreach ($objectArray as &$object) {
                    $this->doCommand($object);
                }
            }
        } catch (Exception $e) {
            $request->addFeedback("Exception: " . $e->getMessage() . "</br>");
            include \util\Utils::createViewName('execute');
        }
        
        $this->servicePrint();
    }
    
    /*
     * Print service information
     */
    
    private function servicePrint() {
         // Create command resolver
        $commandResolver = new \Classes\Controller\Command\CommandResolver();
        // Create command request
        $commandRequest = new \Classes\Controller\Controller\Request();
        
        $commandRequest->setProperty('cmd', 'ServicePrint');
        
        $cmd = $commandResolver->getCommand($commandRequest);
        $cmd->execute($commandRequest);
    }

    /*
     * Get filename of temporary file on server side
     * 
     * @param string $name Original filename
     * 
     * @return string Temporary filename
     * @return bool FALSE if not found
     */

    private function getTempFileName($name) {
        // Get index in files array, where file is stored
        $index = array_search($name, $_FILES['file']['name']);

        // If there isn't file in files
        if ($index === FALSE) {
            throw new \Classes\Controller\Exception\FileNotFoundException("File $name is NOT found.");
        } else {
            // Read command file. Get temp filename
            return $_FILES['file']['tmp_name'][$index];
        }
    }

    /*
     * Execute command
     * 
     * @param \Classes\Instance\Command\Command $object Command Object
     */

    private function doCommand($cmdObject) {
        // Create command resolver
        $commandResolver = new \Classes\Controller\Command\CommandResolver();
        // Create command request
        $commandRequest = new \Classes\Controller\Controller\Request();

        $command = trim($cmdObject->getProperty('command')->get());
        $object = trim($cmdObject->getProperty('object')->get());

        // Try to find command
        if ($command == "COMBINE" && $object == "DOUBLE NODES") {
            $commandRequest->setProperty('cmd', 'CombineDoubleNodes');
        }

        if ($command == "DIVIDE" && $object == "ALL MEMBERS") {
            $commandRequest->setProperty('cmd', 'DivideAllMemberByExistingNodes');
        }

        if ($command == "IMPORT" || $command == "EXPORT") {
            $format = trim($cmdObject->getProperty('format')->get());
            $file = trim($cmdObject->getProperty('file')->get());
        }
        
        if ($command == "IMPORT" && $format == "EXCEL") {
           // Check filename
            $filename = $this->getTempFileName($file);
            if ($filename === FALSE) {
                return;
            } else {
                $commandRequest->setProperty('filename', $filename);
            } 
        
            if ($object == "STEEL MEMBER") {
                $commandRequest->setProperty('cmd', 'ImportSteelMembersFromExcel');
            }

            if ($object == "PARAMETER MEMBER") {
                $commandRequest->setProperty('cmd', 'ImportParameterMembersFromExcel');
            }

            if ($object == "CONSTRAINT") {
                $commandRequest->setProperty('cmd', 'ImportConstraintsFromExcel');
            }

            if ($object == "LOAD CASE") {
                $commandRequest->setProperty('cmd', 'ImportLoadCasesFromExcel');
            }

            if ($object == "COMMON MEMBER LOAD") {
                $commandRequest->setProperty('cmd', 'ImportCommonMemberLoadsFromExcel');
            }
        }
        
        if ($command == "EXPORT" ) {
            $commandRequest->setProperty('filename', $file);
            
            if ($object == "MODEL" && $format == "SCAD21") {
                $commandRequest->setProperty('cmd', 'ExportModelToScad21');
            }
        }
        
        if (is_null($commandRequest->getProperty('cmd'))) {
            throw new \Classes\Controller\Exception\CommandNotFoundException("Command *" . $cmdObject->servicePrint() . "* is NOT found.");
        }

        $cmd = $commandResolver->getCommand($commandRequest);
        $cmd->execute($commandRequest);
    }

}
