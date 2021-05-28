<?php
interface A {
    function employee($age,$name);
}

interface B{
    function employeesal($salary);

    
}
 interface C{
   function employeecompany($companyname);
 }

class Dimplements implements A,B{
    function employee($age,$name){
        echo $age . "  " . $name;
    }
    function employeesal($salary=6000){
        echo $salary;
    }
}

$Dobj = new Dimplements();
echo $Dobj-> employee(24,"VInod");
echo $Dobj->employeesal();