<?php

require 'spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

// we can use this Reader\Xlsx class
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$getxlsxdata= $reader->load('sample.xlsx');

$d=$getxlsxdata->getSheet(0)->toArray();
echo count($d) . "<br>";
$xlsxdata=$getxlsxdata->getActiveSheet()->toArray();

$j=1;
$arrayvalue=array();
foreach($xlsxdata as $arrayvalue){
    echo $arrayvalue ."<br>";
    $j++;
}
//we can use this IOFactory::load
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('sample.xlsx');
$sheetDatas = $spreadsheet->getActiveSheet()->toArray();

$i=1;


// foreach($sheetData as $t){
//     // echo  $t[0] . "<br>" ;
//     // echo  $t[1] . "<br>";
//     echo $t . "\n";
//     $i++;
// }

// $items=count($sheetData);
for($num= 0; $num< sizeof($sheetDatas); $num +=1){
    echo $sheetDatas[$num][0] . "   " . $sheetDatas[$num][1] . "    " .$sheetDatas[$num][2] . "<br>";
}
//-------------------------------------------------------

