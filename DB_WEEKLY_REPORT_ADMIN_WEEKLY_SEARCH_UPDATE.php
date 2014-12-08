<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY REPORT SEARCH UPDATE**************************************//
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:20/10/2014 ED:28/10/2014,TRACKER NO:86
//*********************************************************************************************************//
error_reporting(0);
include "CONNECTION.php";
include "GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
if(isset($_REQUEST['option']) && $_REQUEST['option']!=''){
    $actionfunction = $_REQUEST['option'];
    call_user_func($actionfunction,$_REQUEST,$con);
}
//FUNCTION TO SHOW DATATABLE
function showData($data,$con){
    $date = $con->real_escape_string($data['startdate']);
    $startdate = date("Y-m-d",strtotime($date));
    $date = $con->real_escape_string($data['enddate']);
    $enddate = date("Y-m-d",strtotime($date));
    $AWSU_values=array();
    $sql="SELECT AW.AWRD_ID,AW.AWRD_REPORT,AW.AWRD_DATE,DATE_FORMAT(CONVERT_TZ(AW.AWRD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS AWRD_TIMESTAMP,ULD.ULD_LOGINID as ULD_USERSTAMP FROM ADMIN_WEEKLY_REPORT_DETAILS AW JOIN USER_LOGIN_DETAILS ULD on AW.ULD_ID=ULD.ULD_ID  where AW.AWRD_DATE BETWEEN '$startdate' AND '$enddate'";
    $projectfetch= mysqli_query($con, $sql);
    $AWSU_values=false;
    while($row=mysqli_fetch_array($projectfetch)){
        $awsu_report=$row['AWRD_REPORT'];
        $awsu_id=$row['AWRD_ID'];
        $awsu_date=$row['AWRD_DATE'];
        $awsu_userstamp=$row["ULD_USERSTAMP"];
        $awsu_timestamp=$row["AWRD_TIMESTAMP"];
        $AWSU_report_values=array('report'=>$awsu_report,'id'=>$awsu_id,'date'=>$awsu_date,'userstamp'=>$awsu_userstamp,'timestamp'=>$awsu_timestamp);
        $AWSU_values[]=$AWSU_report_values;
    }
    echo JSON_ENCODE($AWSU_values);
}
//FUNCTION TO UPDATE VALUES
function updateData($data,$con){
    global $USERSTAMP;
    $report = $con->real_escape_string($data['report']);
    $editid = $con->real_escape_string($data['editid']);

    $sql = "UPDATE ADMIN_WEEKLY_REPORT_DETAILS SET AWRD_REPORT='$report',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')  WHERE AWRD_ID=$editid";
    if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));
        $flag=0;
    }
    else{
        $flag=1;
    }
    echo $flag;
}
?>