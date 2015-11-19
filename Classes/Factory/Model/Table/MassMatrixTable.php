<?php

namespace Classes\Factory\Model\Table;

/**
 * Class to store depencies between instancies
 *
 * @author Noskov Alexey
 */
class MassMatrixTable extends OneDimensionalTable {
    
    /*
     * Print Table
     */
    public function servicePrint() {
        foreach ($this->table as $uin => $connection) {
            echo $uin . " = " . json_encode($connection->get()) . "<br/>";
        }
    }

}
