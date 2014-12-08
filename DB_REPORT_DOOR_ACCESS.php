<?php
//*********************************************GLOBAL DECLARATION******************************************//
//*********************************************************************************************************//
//*******************************************FILE DESCRIPTION*********************************************//
//****************************************DOOR ACCESS DETAILS*************************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION,SD:04/11/2014 ED:04/11/2014,TRACKER NO:97
//*********************************************************************************************************//
error_reporting(0);
include "CONNECTION.php";
//GETTING ERR MSG
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (15)");
if($row=mysqli_fetch_array($errormsg)){
    $errormessages=$row["EMC_DATA"];
}
//FETCHING DOOR_ACCESS RECORDS
$date= mysqli_query($con,"SELECT VW.ULD_LOGINID,CPD.CPD_DOOR_ACCESS from VW_ACCESS_RIGHTS_TERMINATE_LOGINID VW,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS ED,USER_LOGIN_DETAILS ULD WHERE ED.ULD_ID=ULD.ULD_ID AND CPD.EMP_ID=ED.EMP_ID AND ULD.ULD_LOGINID=VW.ULD_LOGINID order by VW.ULD_LOGINID");
$ure_values=array();
$final_values=array();
while($row=mysqli_fetch_array($date)){
    $DR_ACC_loginid=$row["ULD_LOGINID"];
    $DR_ACC_draccess=$row["CPD_DOOR_ACCESS"];
    $final_values=array('loginid' =>$DR_ACC_loginid,'DR_ACC_draccess' =>$DR_ACC_draccess);
    $ure_values[]=$final_values;
}
$finalvalue=array($ure_values,$errormessages);
echo JSON_ENCODE($finalvalue);
?>