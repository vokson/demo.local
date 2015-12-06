<?php

$ctkFile=file('D:\Lira Soft\Lira 9.6\LWork\Simple piperack_Rev03.hvm.DCF.rez');
$txtFile[] = "{13/";

$isInsideAssignedMember = FALSE;

foreach($ctkFile as $row)
{
   
   if ($row == "\r\n") {
       $isInsideAssignedMember = FALSE;
   }
   
   if ($isInsideAssignedMember) {
       echo $row."<br/>";
       $rowArray = preg_split( "/([\s]+[=]+[\s]*|[\s]{2,})/", $row, NULL,  PREG_SPLIT_NO_EMPTY );
       
       $txtFile[] = "[" . $rowArray[0] . "]";
       $txtFile[] = "Section = " . $rowArray[2];
       $txtFile[] = "File = " . $rowArray[4];
       $txtFile[] = "Shape = " . $rowArray[6];
       $txtFile[] = "";
   }
   
   if (strpos($row, "[Assigned sections]") !== FALSE) {
       $isInsideAssignedMember = TRUE;
   }
   
}
$txtFile[] = "}";


// Read model TXT file
$modelFile = file_get_contents('D:\Lira Soft\Lira 9.6\LData\Simple piperack_Rev03.txt');
// Find 13 document
$begin13 = strpos($modelFile, "{13/");
$before13 = substr($modelFile, 0, $begin13);

$end13 = strpos($modelFile, "}", $begin13 + 3);
$after13 = substr($modelFile, $end13 + 1);

//file_put_contents("before.txt", $before13);
//file_put_contents("after.txt", $after13);

file_put_contents('D:\Lira Soft\Lira 9.6\LData\Simple piperack_Rev03.txt', $before13 . implode("\r\n", $txtFile) . $after13);


