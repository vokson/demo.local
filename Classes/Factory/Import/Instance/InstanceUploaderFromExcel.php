<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Import\Instance;

/**
 * Base class for uploading array of instances from Excel
 *
 * @author Noskov Alexey
 */
class InstanceUploaderFromExcel extends InstanceUploader {
    /*
     * Upload array of instance from file
     * 
     * @param string $path path to file
     * @param \Classes\Instance\Instance $instanse class of instances stored in file
     * 
     * @return \Classes\Instance\Instance[]  array of instances
     */

    public function upload($path, $instanceOfClass) {
        //Read Excel file
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray();

        //Prepare return array
        $array = array();

        //Prepare class name for instance
        $className = get_class($instanceOfClass);

        //Start to read from 2nd row
        if (count($sheetData) < 2) {
            return $array;
        }

        for ($row = 1; $row < count($sheetData); $row++) {
            if ($sheetData[$row][0] != "*") {
                //Create new object
                $instance = new $className;
                // Cycle for all properties  
                $isRowOK = TRUE; // Flag for current row
                for ($col = 0; $col < count($sheetData[$row]); $col++) {

                    // Header cell must NOT be empty
                    if (!is_null($sheetData[0][$col])) {

                        // Get Name
                        $name = $sheetData[0][$col];

                        // Check if property is mandatory
                        $isNameMandatory = FALSE;
                        if ($name[strlen($name) - 1] == '*') {
//                            echo "[$row] $name is MANDATORY<br/>";
                            $isNameMandatory = TRUE;
                            $name = substr($name, 0, -1);
                        }

                        // Get Value
                        $value = $sheetData[$row][$col];

                        // Check Value
                        if ($isNameMandatory && is_null($value)) {
                            $isRowOK = FALSE;
                        }

                        // If current cell IS NULL (empty) change to ""
                        if (is_null($value)) {
                            $value = '';
                        }
//                        echo "[" . $row . "][" . $col . "] Name = " . $name . "  Value = " . $value . "<br/>";
//                        
                        // Property name validation
                        if ($instance->validateProperty($name, $propertyClass)) {
                            // Set property
                            try {

                                $instance->setProperty($name, new $propertyClass($value));
                            } catch (Exception $e) {
                                echo "Exception: " . $e->getMessage() . "</br>";
                            }
                        }
                    }
                }

                // Add object to array
                if ($isRowOK) {
                    $array[] = $instance;
                } else {
                    echo basename($path) . " [" . ($row + 1) . "] Row is NOT correct<br/>";
                }
            }
        }

        return $array;
    }

}
