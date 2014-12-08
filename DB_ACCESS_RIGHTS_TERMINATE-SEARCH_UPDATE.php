<?php

set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google/appengine/api/mail/Message.php';
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
//error_reporting(0);
use google\appengine\api\mail\Message;
include "CONNECTION.php";
include "COMMON.php";
include "GET_USERSTAMP.php";
include "CONFIG.php";
//echo 'inside';
//get uld_id
function getULD_ID_from_ULD_LOGINID1($ULD_LOGINID){
    global $con;
    $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$ULD_LOGINID."'";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $ULD_ID=$row["ULD_ID"];
    }

    return $ULD_ID;
}
if(isset($_REQUEST))
{

    $userstamp=$UserStamp;
    if($_REQUEST['option']=='TERMINATIONLB')
    {
        $active_emp=get_active_login_id();
        echo json_encode( $active_emp);
    }

    else if($_REQUEST['option']=='REJOINLB')
    {

        $active_nonemp=get_nonactive_login_id();
        echo json_encode($active_nonemp);

    }

    else if($_REQUEST['option']=='SEARCHLB')

    {
        $active_nonemp=get_nonactive_login_id();
        echo json_encode($active_nonemp);

    }
    else if($_REQUEST['option']=='FETCH')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT UA_REASON,UA_END_DATE FROM USER_ACCESS where ULD_ID =(select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$loginid_result."')";
        $loginsearch_fetchingdata= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URT_SRC_enddate=$row["UA_END_DATE"];
            $URT_SRC_reason=$row["UA_REASON"];
            $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
            $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason);
        }
        echo json_encode($URT_SRC_values);
    }
    else if($_REQUEST['option']=='GETDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $ULD_ID=getULD_ID_from_ULD_LOGINID1($loginid_result);
        $query= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%d-%m-%Y') as UA_JOIN_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$ULD_ID AND UA_TERMINATE IS NULL)AND ULD_ID=$ULD_ID";
        $joindate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($joindate_data)){
            $mindate=$row["UA_JOIN_DATE"];
        }
        echo $mindate;
    }
    else if($_REQUEST['option']=='GETENDDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $ULD_ID=getULD_ID_from_ULD_LOGINID1($loginid_result);
        $query= "SELECT  DATE_FORMAT(UA_END_DATE,'%d-%m-%Y') as UA_END_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$ULD_ID AND UA_TERMINATE IS NOT NULL)AND ULD_ID=$ULD_ID";
        $enddate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($enddate_data)){
            $mindate=$row["UA_END_DATE"];
        }
        echo $mindate;

    }
    else if($_REQUEST['option']=='UPDATE')
    {
        $reason_update=$_REQUEST['URT_SRC_ta_nreasonupdate'];
        $loggin=$_REQUEST['URT_SRC_lb_nloginupdate'];
        $date=$_REQUEST['URT_SRC_tb_ndatepickerupdate'];
        $enddate = date("Y-m-d",strtotime($date));
        $sql="UPDATE USER_ACCESS SET UA_END_DATE='$enddate',UA_REASON='$reason_update',UA_USERSTAMP='$userstamp' where ULD_ID=(SELECT ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='".$loggin."')";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='REJOIN')
    {
        $loggin=$_REQUEST['URT_SRC_lb_nloginrejoin'];
        $role_access = $_REQUEST['URT_SRC_radio_nrole'];
        $final_radioval=str_replace("_"," ",$role_access);
        $date=$_REQUEST['URT_SRC_tb_ndatepickerrejoin'];
        $emp_type=$_REQUEST['URSRC_lb_selectemptype'];
        $joindate = date("Y-m-d",strtotime($date));
//        echo "CALL SP_TS_LOGIN_CREATION_INSERT('$loggin','$final_radioval','$joindate','$userstamp','$emp_type',@success_flag)";

        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT('$loggin','$final_radioval','$joindate','$userstamp','$emp_type',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        if($flag==1){
            $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
            $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
            $admin_rs=mysqli_query($con,$select_admin);
            $sadmin_rs=mysqli_query($con,$select_sadmin);
            if($row=mysqli_fetch_array($admin_rs)){
                $admin=$row["ULD_LOGINID"];//get admin
            }
            if($row=mysqli_fetch_array($sadmin_rs)){
                $sadmin=$row["ULD_LOGINID"];//get super admin
            }
            $select_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=4");
            if($row=mysqli_fetch_array($select_link)){
                $site_link=$row["URC_DATA"];
            }
            $select_ss_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
            if($row=mysqli_fetch_array($select_ss_link)){
                $ss_link=$row["URC_DATA"];
            }

            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }
            $loginid_name = strtoupper(substr($loggin, 0, strpos($loggin, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loggin'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }
            $fileId=$ss_fileid;
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $email_body;
            $body_msg =explode(",", $body);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $email_body.=$body_msg[$i].'<br><br>';
            }
            $replace= array("[LOGINID]", "[LINK]","[SSLINK]", "[VLINK]");
            $str_replaced  = array($loggin,$site_link, $ss_link, 'https://www.youtube.com/watch?v=u3Vr4lfdTa8&feature=youtu.be');
            $final_message = str_replace($replace, $str_replaced, $email_body);
            $mail_options = [
                "sender" =>'safiyullah.mohideen@ssomens.com',//$admin,
                "to" => $loggin,
                "cc"=>'safiyullah.mohideen@ssomens.com',// $admin,
                "subject" => $mail_subject,
                "htmlBody" => $final_message
            ];
            try {
                $message = new Message($mail_options);
                $message->send();
            } catch (\InvalidArgumentException $e) {
                echo $e;
            }


            URSRC_calendar_create($loggin,$fileId,$loginid_name,$URSC_uld_id,$joindate,$calenderid,'REJOIN DATE','REJOIN');



        }
        echo $flag;
    }
    else if($_REQUEST['option']=='TERMINATE')
    {
//$loggin='safiyullah84@gmail.com';
        $reason_termin=$_POST['URT_SRC_ta_nreasontermination'];
        $loggin=$_POST['URT_SRC_lb_nloginterminate'];
        $date=$_POST['URT_SRC_tb_ndatepickertermination'];
        $enddate = date("Y-m-d",strtotime($date));
        $result = $con->query("CALL SP_TS_LOGIN_TERMINATE_SAVE('$loggin','$enddate','$reason_termin','$userstamp',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag=$result['@success_flag'];
        if($flag==1){


            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }
            $loginid_name = strtoupper(substr($loggin, 0, strpos($loggin, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loggin'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }
            $fileId=$ss_fileid;

            URSRC_calendar_create($loggin,$fileId,$loginid_name,$URSC_uld_id,$enddate,$calenderid,'TERMINATE DATE','TERMINATE');
        }
        echo $flag;
    }

}
function URSRC_calendar_create($loggin,$fileId,$loginid_name,$URSC_uld_id,$finaldate,$calenderid,$status,$form){

    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
    $drive = new Google_Client();
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
if($form=='TERMINATE'){
    try {
        $permissions = $service->permissions->listPermissions($fileId);
        $return_value= $permissions->getItems();
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
    foreach ($return_value as $key => $value) {
        if ($value->emailAddress==$loggin) {
            $permission_id=$value->id;
        }
    }
    try {
        $service->permissions->delete($fileId, $permission_id);

    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}
    else{

        $value=$loggin;
        $type='user';
        $role='reader';
        $email=$loggin;

        $newPermission = new Google_Service_Drive_Permission();
        $newPermission->setValue($value);
        $newPermission->setType($type);
        $newPermission->setRole($role);
        $newPermission->setEmailAddress($email);
        try {
            $service->permissions->insert($fileId, $newPermission);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

    }
    $cal = new Google_Service_Calendar($drive);
    $event = new Google_Service_Calendar_Event();
    $event->setsummary($loginid_name.'  '.$status);
    $event->setDescription($URSC_uld_id);
    $start = new Google_Service_Calendar_EventDateTime();
    $start->setDate($finaldate);//setDate('2014-11-18');
    $event->setStart($start);
    $event->setEnd($start);
    $createdEvent = $cal->events->insert($calenderid, $event);




}