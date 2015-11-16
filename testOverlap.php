<?php

spl_autoload_extensions(".php");
spl_autoload_register();

$p1 = new \Classes\Utils\AbstractInstance\Point(0,0,0);
$p2 = new \Classes\Utils\AbstractInstance\Point(5,0,0);
$p3 = new \Classes\Utils\AbstractInstance\Point(10,0,0);
$p4 = new \Classes\Utils\AbstractInstance\Point(3,0,0);

$line1 = new \Classes\Utils\AbstractInstance\Line($p1, $p2);
$line2 = new \Classes\Utils\AbstractInstance\Line($p3, $p4);

$overlap1 = array(); $overlap2 = array();
$result = Classes\Utils\Math\Lines::findOverlappingOfTwoLines($line1, $line2, $overlap1, $overlap2);

echo "RESULT = ".print_r($result)."<br/>";
var_dump($overlap1);
var_dump($overlap2);


