<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************USER SEARCH DETAILS*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:11/10/2014 ED:11/10/2014,TRACKER NO:79
//*********************************************************************************************************//
//error_reporting(0);

    include "CONNECTION.php";
    //GETTING ERR MSG
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (15)");
    if($row=mysqli_fetch_array($errormsg)){
        $errormessages=$row["EMC_DATA"];
    }
//FETCHING USER LOGIN DETAILS RECORDS
    $date= mysqli_query($con,"SELECT ULD.ULD_LOGINID,RC.RC_NAME,UA.UA_REC_VER,URC1.URC_DATA,UA.UA_REASON,UA.UA_USERSTAMP,DATE_FORMAT(UA.UA_JOIN_DATE,'%d-%m-%Y') AS UA_JOIN_DATE,DATE_FORMAT(UA.UA_END_DATE,'%d-%m-%Y') AS UA_END_DATE,DATE_FORMAT(UA.UA_TIMESTAMP , '%d-%m-%Y %h:%m:%s') AS UA_TIMESTAMP FROM USER_LOGIN_DETAILS ULD,ROLE_CREATION RC,USER_ACCESS UA,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1 WHERE UA.UA_EMP_TYPE=URC1.URC_ID AND URC.URC_ID=RC.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND UA.RC_ID=RC.RC_ID ORDER BY ULD.ULD_LOGINID");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $USD_SRC_loginid=$row["ULD_LOGINID"];
        $USD_SRC_rcid=$row["RC_NAME"];
        $USD_SRC_recver=$row["UA_REC_VER"];
        $USD_SRC_joindate=$row["UA_JOIN_DATE"];
        $USD_SRC_enddate=$row["UA_END_DATE"];
        $USD_SRC_reason=$row["UA_REASON"];
        $USD_SRC_emptypes=$row["URC_DATA"];
        $USD_SRC_userstamp=$row["UA_USERSTAMP"];
        $USD_SRC_timestamp=$row["UA_TIMESTAMP"];
        $final_values=array('loginid' =>$USD_SRC_loginid,'rcid' =>$USD_SRC_rcid,'recordver'=>$USD_SRC_recver,'joindate'=>$USD_SRC_joindate,'terminationdate'=>$USD_SRC_enddate,'reasonoftermination'=>$USD_SRC_reason,'emptypes'=>$USD_SRC_emptypes,'userstamp'=>$USD_SRC_userstamp,'timestamp'=>$USD_SRC_timestamp);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);

?>