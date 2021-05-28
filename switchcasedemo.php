<?php

$car="Hyundai";
$model="Turki";

switch($car){

    case "verna" :  switch($model) { 
            // case 1 of nested switch
            case  "japan": 
            echo "THis is verna car from japan";
            break;

     case  "malasaya": 
                echo "THis is verna car from malasiya and highly imported";
                break;

        }
    break;

    //---------------------------------------------
    case "Hyundai" : switch($model) {

     case "USA" : echo "THis is hyundai car fro USA"; 
        break;
   
    case "Turki" : echo "This is hyundai car from Turki";
        break;
        }

    break;

}