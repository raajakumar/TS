<?php
error_reporting(0);
include "CONNECTION.php";
include "GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
date_default_timezone_set('Asia/Kolkata');
global $con;
Is/*somens software developement private lmited....*?

function getTimezone()
{
    return ("'+00:00','+05:30'");
}

//GET ULD_ID
function get_uldid(){
    global $USERSTAMP;
    global $con;
//    mysqli_connect($con);
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
//    mysql_close($con);
    return $ure_uld_id;
}
//GET PERMISSION
function get_permission(){
    global $con;
    $permission_result = mysqli_query($con,"SELECT AC_DATA FROM ATTENDANCE_CONFIGURATION WHERE CGN_ID=6");
    $get_permission_array=array();
    while($row=mysqli_fetch_array($permission_result)){
        $get_permission_array[]= $row["AC_DATA"];
    }
    return $get_permission_array;
}
//FOR GETTING PROJECT ID AND NAME FOR ENTRYFORM
function get_projectentry(){
    global $con;
    $project_result=mysqli_query($con,"SELECT DISTINCT A.PD_ID,A.PD_PROJECT_NAME FROM PROJECT_DETAILS A,PROJECT_STATUS B WHERE B.PC_ID!=3 AND A.PD_ID = B.PD_ID ORDER BY A.PD_ID;");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"],$row["PD_ID"]);
    }
    return $get_project_array;
}

//GET PROJECTS LIST FROM DB
function get_project(){
    global $con;
    $project_result=mysqli_query($con,"SELECT * FROM PROJECT_DETAILS");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"],$row["PD_ID"]);
    }
    return $get_project_array;
}
//GET JOIN DATE FOR SELECTING LOGIN ID;
function get_joindate($ure_uld_id){
    global $con;
    $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ure_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
        $min_date = date('d-m-Y',strtotime($mindate_array));
    }
    return  $min_date;
}
if($_REQUEST["option"]=="user_report_entry"){
//    echo $USERSTAMP;
    $get_permission_array=get_permission();

    $get_project_array=get_projectentry();
    $ure_uld_id=get_uldid();
    $min_date=get_joindate($ure_uld_id);
    $error='3,4,5,6,7,8,16,17,18,67';
    $error_array=get_error_msg($error);
    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array);
    echo JSON_ENCODE($values_array);

}
if($_REQUEST["option"]=="user_search_update"){

    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
    $get_project_array=get_project();
    $error='3,4,5,6,7,8,16,17,18,67,83';
    $error_array=get_error_msg($error);
    $min_date=get_joindate($ure_uld_id);


    $user_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ure_uld_id' ");
    while($row=mysqli_fetch_array($user_searchmin_date)){
        $user_searchmin_date_value=$row["UARD_DATE"];
        $user_searchmin_date_value = date('d-m-Y',strtotime($user_searchmin_date_value));
    }

    $user_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ure_uld_id' ");
    while($row=mysqli_fetch_array($user_searchmax_date)){
        $user_searchmax_date_value=$row["UARD_DATE"];
        $user_searchmax_date_value = date('d-m-Y',strtotime($user_searchmax_date_value));
    }

    $values_array=array($get_permission_array,$get_project_array,$user_searchmin_date_value,$user_searchmax_date_value,$error_array,$min_date);
    echo JSON_ENCODE($values_array);


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
//GET NON ACTIVE LOGIN ID
function get_nonactive_login_id(){
    global $con;
    $activenonemp=mysqli_query($con,"SELECT * from VW_ACCESS_RIGHTS_REJOIN_LOGINID ORDER BY ULD_LOGINID");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=$row["ULD_LOGINID"];
    }
    return $active_nonemp;
}



function get_company_start_date(){

    global $con;
    $comp_sdate=mysqli_query($con,"SELECT * from USER_RIGHTS_CONFIGURATION WHERE URC_ID=11");
    while($row=mysqli_fetch_array($comp_sdate)){
        $comp_startdate=$row["URC_DATA"];
    }
    $comp_startdate = date('d-m-Y',strtotime($comp_startdate));
    return $comp_startdate;



}
if($_REQUEST["option"]=="admin_report_entry")
{
    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
    $get_project_array=get_projectentry();
    $error='3,4,5,6,7,8,16,17,18,67';
    $error_array=get_error_msg($error);
    $min_date=get_joindate($ure_uld_id);
    $login_array=get_active_login_id();
    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array,$login_array);
    echo JSON_ENCODE($values_array);
}
if($_REQUEST["option"]=="admin_search_update")
{
    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
    $get_project_array=get_project();
    $error='3,4,5,6,7,8,16,17,18,67,83';
    $error_array=get_error_msg($error);

//    $min_date=get_joindate($ure_uld_id);
    $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmin_date)){
        $admin_searchmin_date_value=$row["UARD_DATE"];
        $min_date = date('d-m-Y',strtotime($admin_searchmin_date_value));
    }
    $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmax_date)){
        $admin_searchmax_date_value=$row["UARD_DATE"];
        $max_date= date('d-m-Y',strtotime($admin_searchmax_date_value));
    }
    $loginid=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $login_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $login_array[]=$row["ULD_LOGINID"];
    }
    $activeemp=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $active_emp=array();
    while($row=mysqli_fetch_array($activeemp)){
        $active_emp[]=$row["ULD_LOGINID"];
    }
    $activenonemp=mysqli_query($con,"SELECT * from VW_ACCESS_RIGHTS_REJOIN_LOGINID ORDER BY ULD_LOGINID");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=$row["ULD_LOGINID"];
    }

    $onduty_searchmin_date=mysqli_query($con,"SELECT MIN(OED_DATE) as OED_DATE FROM ONDUTY_ENTRY_DETAILS");
    while($row=mysqli_fetch_array($onduty_searchmin_date)){
        $onduty_searchmin_date_value=$row["OED_DATE"];
        $od_mindate = date('d-m-Y',strtotime($onduty_searchmin_date_value));
    }
    $onduty_searchmax_date=mysqli_query($con,"SELECT MAX(OED_DATE) as OED_DATE FROM ONDUTY_ENTRY_DETAILS");
    while($row=mysqli_fetch_array($onduty_searchmax_date)){
        $onduty_searchmax_date_value=$row["OED_DATE"];
        $od_maxdate= date('d-m-Y',strtotime($onduty_searchmax_date_value));
    }

    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array,$login_array,$active_emp,$active_nonemp,$max_date,$od_mindate,$od_maxdate);
    echo JSON_ENCODE($values_array);
}
//GET ERROR MSG
function get_error_msg($str){
    global $con;
    $errormessage=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN ($str)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessage[]=$row["EMC_DATA"];
    }
    return $errormessage;
}


if($_REQUEST["option"]=="USER_RIGHTS_TERMINATE"){
    $str='9,10,11,12,13,14';
    $errormsg_array= get_error_msg($str);
    $role_result=mysqli_query($con,"SELECT  RC_NAME,RC_ID FROM ROLE_CREATION;");
    $get_role_array=array();
    while($row=mysqli_fetch_array($role_result)){
        $get_role_array[]=array($row["RC_ID"],$row["RC_NAME"]);
    }
    $emp_type=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION where URC_ID in (6,7,8) ");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }

    $value_array=array($errormsg_array,$get_role_array,$get_emptype_array);
    echo JSON_ENCODE($value_array);

}
function get_roles(){
    global $con;
    $rolecreation_result = mysqli_query($con,"SELECT * FROM ROLE_CREATION");
    $get_rolecreation_array=array();
    while($row=mysqli_fetch_array($rolecreation_result)){
        $get_rolecreation_array[]= $row["RC_NAME"];
    }

    return  $get_rolecreation_array;
}
if($_REQUEST["option"]=="ACCESS_RIGHTS_SEARCH_UPDATE")
{
    $str='40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60';
    $URSRC_errmsg=get_error_msg($str);

    $get_rolecreation_array=get_roles();
    $project_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION where URC_ID in (1,2,3) ");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=$row["URC_DATA"];
    }
    $emp_type=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION where URC_ID in (6,7,8) ");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }

    $menuname_result=mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,USER_RIGHTS_CONFIGURATION URC");
    $get_menuname_array=array();
    while($row=mysqli_fetch_array($menuname_result)){
        $get_menuname_array[]=$row["MP_MNAME"];

    }
    //BASI ROLE

    $query= "select * from  USER_RIGHTS_CONFIGURATION URC,ROLE_CREATION RC,USER_LOGIN_DETAILS ULD,USER_ACCESS UA where ULD.ULD_ID=UA.ULD_ID and RC.RC_ID=UA.RC_ID and RC.URC_ID=URC.URC_ID and ULD.ULD_LOGINID='".$USERSTAMP."' ORDER BY URC_DATA ASC";
    $URSRC_select_basicrole_result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($URSRC_select_basicrole_result)){
        $URSRC_basicrole=$row["URC_DATA"];

    }
    $URSRC_basicroleid_array_result=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basicrole."' and URC.URC_ID=BRP.URC_ID");
    $URSRC_basicroleid_array=array();
    while($row=mysqli_fetch_array($URSRC_basicroleid_array_result)){
        $URSRC_basicroleid_array[]=($row["BRP_BR_ID"]);
    }
    $get_URSRC_basicrole_profile_array=array();

    for($i=0;$i<count($URSRC_basicroleid_array);$i++){

        $URSRC_basicrole_profile_array_result=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
        while($row=mysqli_fetch_array($URSRC_basicrole_profile_array_result)){
            $get_URSRC_basicrole_profile_array[]=$row["URC_DATA"];
        }
    }
    $get_URSRC_basicrole_profile_array=array_values(array_unique($get_URSRC_basicrole_profile_array));
    $comp_startdate=get_company_start_date();

    $value_array=array($get_rolecreation_array,$get_project_array,$get_menuname_array,$get_URSRC_basicrole_profile_array,$URSRC_errmsg,$get_emptype_array,$comp_startdate);
    echo JSON_ENCODE($value_array);
}

if($_REQUEST["option"]=="EMAIL_TEMPLATE_ENTRY"){
    $error='71,85,86';
    $error_array=get_error_msg($error);
    $values_array=array($error_array);
    echo JSON_ENCODE($values_array);
}

if($_REQUEST['option']=="ADMIN WEEKLY REPORT SEARCH UPDATE"){
    $str='4,17';
    $errormsg_array= get_error_msg($str);
    $value_array=array($errormsg_array);
    echo JSON_ENCODE($value_array);
}
if($_REQUEST['option']=="ADMIN WEEKLY REPORT ENTRY"){
    $str='3,84';
    $errormsg_array= get_error_msg($str);
    $value_array=array($errormsg_array);
    echo JSON_ENCODE($value_array);
}

//echo "<pre>";
//print_r($active_nonemp);
?>
