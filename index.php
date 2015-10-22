<?php

spl_autoload_extensions(".php");
spl_autoload_register();

try {

    $array = array();

    for ($i = 0; $i < 1000; $i++) {
        $node = new \Classes\Instance\Node\Node();
        
        $node->setProperty('x', new \Classes\Value\FloatValue((float) rand(0, 100)));
        $node->setProperty('y', new \Classes\Value\FloatValue((float) rand(0, 100)));
        $node->setProperty('z', new \Classes\Value\FloatValue((float) rand(0, 100)));
        $node->setProperty('tag', new \Classes\Value\StringValue('Node ' . $i));
        $node->setProperty('id', new \Classes\Value\IntValue($i));

        $uin = $node->getUin();
        $array[$uin] = $node;
    }

    
    //Serialize the array.
    $serialized = serialize($array);
//    var_dump($serialized);

    //Save the serialized array to a text file.
    file_put_contents('serialized.txt', $serialized);

    //Retrieve the serialized string.
    $fileContents = file_get_contents('serialized.txt');

    //Unserialize the string back into an array.
    $arrayUnserialized = unserialize($fileContents);

    //End result.
    var_dump($arrayUnserialized);
    
    $node = $arrayUnserialized[$uin];
    echo "<br/>";
    echo "NODE No." . $node->getProperty('id')->get()." TAG = ".$node->getProperty('tag')->get()."<br/>";
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "</br>";
}

