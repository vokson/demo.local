<?php

spl_autoload_extensions(".php");
spl_autoload_register();

$arrayA = array(1,2,3,4,5,6,7);
$arrayB = array(4,5,6,7,8,9,10);

$a = new \Classes\Listbox\Listbox;
$b = new \Classes\Listbox\Listbox;

foreach ($arrayA as $e) {
    $a->add($e);
}
foreach ($arrayB as $e) {
    $b->add($e);
}

$actionCollection = \Classes\Factory\Model\Model::getListBoxActionCollection();
// REGISTER
$actionCollection->registerListbox($a);

//CREATE ACTION
$act1 = new Classes\Listbox\Action\DeleteListboxAction(3);
$actionCollection->addAction($act1);

// REGISTER
$actionCollection->registerListbox($b);


//PRINT
$actionCollection->servicePrint();
echo "<br/>";

//PRINT
echo "A: (" . implode(",", $a->get()) . ")<br/>";
echo "B: (" . implode(",", $b->get()) . ")<br/>";
echo "<br/>";

//PRINT
$actionCollection->servicePrint();
echo "<br/>";

//CREATE ACTION
$actionCollection->addAction(new Classes\Listbox\Action\DeleteListboxAction(8));
$actionCollection->addAction(new Classes\Listbox\Action\DeleteListboxAction(9));
$actionCollection->addAction(new Classes\Listbox\Action\DeleteListboxAction(10));

//PRINT
echo "A: (" . implode(",", $a->get()) . ")<br/>";
echo "B: (" . implode(",", $b->get()) . ")<br/>";
echo "<br/>";

//CREATE ACTION
$actionCollection->addAction(new Classes\Listbox\Action\DeleteListboxAction(99));

//PRINT
$actionCollection->servicePrint();
echo "<br/>";


