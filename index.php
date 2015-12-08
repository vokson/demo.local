<?php
spl_autoload_extensions(".php");
spl_autoload_register();

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';

\Classes\Controller\Controller\Controller::run();