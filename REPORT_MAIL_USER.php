<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************REPORT MAIL USER TRIGGER *************************************//
//DONE BY:SAFIYULLAH
//VER 0.03,SD:06/11/2014 ED:06/11/2014,tracker:74,DESC:checked onduty date also to send remainder
//VER 0.02,SD:24/10/2014 ED:24/10/2014,TRACKER NO:82,DESC:update subject and body to get from email template
//VER 0.01-INITIAL VERSION, SD:16/09/2014 ED:08/10/2014,TRACKER NO:82
//*********************************************************************************************************//-->

<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
date_default_timezone_set('Asia/Kolkata');
$get_active_user=array();
$get_active_user=get_active_login_id();//GET ALL ACTIVE LOGIN ID
$currentdate=date("Y-m-d");//CURRENT DATE
$Current_day=date('l');//CURRENT DAY
$check_ph=Check_public_holiday($currentdate);//CHECK CURRENT DATE IS IN PUBLIC HOLIDAY
$check_onduty=check_onduty($currentdate);//CHECK CURRENT DATE IS IN ONDUTY
$get_login_id=array();
$get_login_id=get_login_id($currentdate);//GET WHO ARE ALL ENTERED REPORT FOR CURRENT DATE
$ph_array=get_public_holiday();// GET ALL PUBLIC HOLIDAY
$onduty_array=get_onduty();//GET ALL ONDUTY
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=5";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
if($row=mysqli_fetch_array($admin_rs)){
    $admin=$row["ULD_LOGINID"];//get admin
}

if($Current_day!='Sunday'){
    if($check_ph==0 && $check_onduty==0){
        $final_array=array_diff($get_active_user,$get_login_id);
        $final_array=array_values($final_array);
        $array_length=count($final_array);
        for($i=0;$i<$array_length;$i++){
            $names=$final_array[$i];
            $username = strtoupper(substr($names, 0, strpos($names, '@')));
            if(substr($username, 0, strpos($username, '.'))){

                $username = strtoupper(substr($username, 0, strpos($username, '.')));

            }
            else{
                $username=$username;
            }
            $get_uldid=get_uldid($names);
            $get_joindate=get_joindate($get_uldid);
            $sub=str_replace("[MAILID_USERNAME]","$username",$body);
            $message='<body>'.'<br>'.'<h> '.$sub.'</h>'.'<br>'.'<br>'.'<table border=1  width=250 ><thead  bgcolor=#6495ed style=color:white><tr  align=center  height=2px ><td >REPORT DATE</td></tr></thead>';
            $select_last_report="SELECT UARD_DATE FROM USER_ADMIN_REPORT_DETAILS UARD,USER_LOGIN_DETAILS ULD WHERE  UARD.ULD_ID=(SELECT ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$names') ORDER by UARD.UARD_DATE desc LIMIT 1";
            $select_last_report_rs=mysqli_query($con,$select_last_report);
            while($row=mysqli_fetch_array($select_last_report_rs)){
                $last_date=$row["UARD_DATE"];
            }
            $final_date_array=array();
            if($last_date!=$currentdate){
                for($k=1;$k<=30;$k++){
                    $final_date=date('Y-m-d', strtotime($last_date.  + $k.' days'));
                    if($final_date<=$currentdate){
                        if($final_date>=$get_joindate){
                        $day=date('l', strtotime($final_date));
                        if($day!="Sunday")
                            $final_date_array[]=($final_date);
                        }
                    }
                }
                $date_array=array_diff($final_date_array,$ph_array);
                $date_array=array_values($date_array);
                $date_array=array_diff($date_array,$onduty_array);
                $date_array=array_values($date_array);
                $num_count=count($date_array);
                for($j=0;$j<count($date_array);$j++){
                    $message=$message. "<tr><td align=center>".date('l jS  F Y ',strtotime($date_array[$j]))."</td></tr>";
                }
                $message=$message."</table>";

                if($num_count>0){
                $mail_options = [
                    "sender" => $admin,
                    "to" => $names,
                    "subject" => $mail_subject,
                    "htmlBody" => $message
                ];
                try {
                    $message = new Message($mail_options);
                    $message->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }

            }
            }

        }
    }
}