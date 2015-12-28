<?php

spl_autoload_extensions(".php");
spl_autoload_register();

$arrayA = array(1,2,3,4,5,6,7);
$arrayB = array(4,5,6,7,8,9,10);

$a = new \Classes\Listbox\Listbox(\Classes\Factory\Model\Model::getMemberActionCollection());
$b = new \Classes\Listbox\Listbox(\Classes\Factory\Model\Model::getMemberActionCollection());

foreach ($arrayA as $e) {
    $a->add($e);
}
foreach ($arrayB as $e) {
    $b->add($e);
}

$actionCollection = \Classes\Factory\Model\Model::getMemberActionCollection();

//CREATE ACTION
$actionCollection->addAction(new \Classes\Listbox\Action\DeleteListboxAction(3));

//PRINT
echo "A: (" . implode(",", $a->get()) . ")<br/>";
echo "B: (" . implode(",", $b->get()) . ")<br/>";
echo "<br/>";

//PRINT
$actionCollection->servicePrint();
echo "<br/>";

//CREATE ACTION
$actionCollection->addAction(new \Classes\Listbox\Action\ReplaceListboxAction(4, array(1,9,13,15)));

//PRINT
echo "A: (" . implode(",", $a->get()) . ")<br/>";
echo "B: (" . implode(",", $b->get()) . ")<br/>";
echo "<br/>";

//CREATE ACTION
$actionCollection->addAction(new \Classes\Listbox\Action\DeleteListboxAction(99));

//PRINT
$actionCollection->servicePrint();
echo "<br/>";


