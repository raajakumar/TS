<?php
error_reporting(0);
include "CONNECTION.php";
date_default_timezone_set('Asia/Kolkata');
//FUNCTION TO CHECK PUBLIC HOLIDAY
function Check_public_holiday($currentdate){

    global $con;
    $sql="SELECT * FROM PUBLIC_HOLIDAY WHERE  PH_DATE='$currentdate'";
    $sql_result= mysqli_query($con,$sql);
    $check_ph=mysqli_num_rows($sql_result);
    return $check_ph;

}
//FUNCTION TO CHECK ONDUTY
function check_onduty($currentdate){
    global $con;
    $check_onduty_select="SELECT * FROM ONDUTY_ENTRY_DETAILS WHERE OED_DATE='$currentdate'";
    $check_onduty_rs=mysqli_query($con,$check_onduty_select);
    $check_onduty=mysqli_num_rows($check_onduty_rs);
    return $check_onduty;

}
//FUNCTION TO RETURN LOGIN ID WHO'S REPORT ENTERED FOR GIVEN DATE
function get_login_id($currentdate){
    global $con;

    $active_user_array=array();
    $select_active_user="SELECT ULD.ULD_LOGINID FROM USER_ADMIN_REPORT_DETAILS UARD,USER_LOGIN_DETAILS ULD WHERE UARD.UARD_DATE='$currentdate' and UARD.ULD_ID=ULD.ULD_ID ";
    $active_user=mysqli_query($con,$select_active_user);
    while($row=mysqli_fetch_array($active_user)){
        $active_user_array[]=$row["ULD_LOGINID"];
    }

    return $active_user_array;
}
//FUNCTION TO RETURN PUBLIC HOLIDAY
function get_public_holiday(){

    global $con;
    $ph_array=array();
    $select_ph="select * from PUBLIC_HOLIDAY";
    $select_ph_rs=mysqli_query($con,$select_ph);
    while($row=mysqli_fetch_array($select_ph_rs)){
        $ph_array[]=$row["PH_DATE"];
    }
    return  $ph_array;
}


//FUNCTION TO RETURN ONDUTY
function get_onduty(){

    global $con;
    $onduty_array=array();
    $select_onduty="select * from ONDUTY_ENTRY_DETAILS";
    $select_onduty_rs=mysqli_query($con,$select_onduty);
    while($row=mysqli_fetch_array($select_onduty_rs)){
        $onduty_array[]=$row["OED_DATE"];
    }
    return  $onduty_array;
}

//GET ACTIVE LOGIN ID;
function get_active_login_id(){
    global $con;
    $loginid=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $login_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $login_array[]=$row["ULD_LOGINID"];
    }
    return $login_array;
}

function get_joindate($ure_uld_id){
    global $con;
    $min_date=mysqli_query($con,"SELECT MAX(UA_JOIN_DATE) as UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ure_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
    }
    return  $mindate_array;
}

function get_uldid($name){
    global $con;
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$name'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    return $ure_uld_id;
}