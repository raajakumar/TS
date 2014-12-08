<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ABSENT MAIL TRIGGER *************************************//
//DONE BY:SAFIYULLAH
//VER 0.03, SD:29/10/2014 ED:29/10/2014,TRACKER NO:74,DESC:updated the query to show data in order.
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
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=2";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
if($Current_day!='Sunday'){
    if($check_ph==0 && $check_onduty==0){
        $result = $con->query("CALL SP_TS_REPORT_AUTO_ABSENT_INSERT('$admin',@TEMP_ABSENT_USER)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_ABSENT_USER');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_ABSENT_USER'];
        if($temp_table_name!=''){
        $admin_name = substr($admin, 0, strpos($admin, '.'));
        $sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
        $spladminname=$admin_name.'/'.$sadmin_name;
            $spladminname=strtoupper($spladminname);
        $select_data="select * from $temp_table_name ORDER BY EMPLOYEE_NAME ";
        $select_data_rs=mysqli_query($con,$select_data);
        $row=mysqli_num_rows($select_data_rs);
        $x=$row;
        if($x>0){
            $sub1=str_replace("[SADMIN]","$spladminname",$body);
            $message1= '<body>'.'<br>'.'<h> '.$sub1.'</h>'.'<br>'.'<br>'.'<table border=1  width=700><thead  bgcolor=#6495ed style=color:white><tr  align=center  height=2px><td >EMPLOYEE NAME </td><td >REPORT DATE</td></tr></thead>';
            while($row=mysqli_fetch_array($select_data_rs)){

                $employee_name=$row['EMPLOYEE_NAME'];
                if(substr($employee_name, 0, strpos($employee_name, '@'))){
                    $username = strtoupper(substr($employee_name, 0, strpos($employee_name, '@')));
                }
                else{
                    $username= $employee_name;

                }
                $message1=$message1."<tr><td>".$username."</td><td>".$row['REPORT_DATE']."</td></tr>";
            }
            echo "</table>";
            $mail_options = [
                "sender" => $admin,
                "to" => $admin,
                "cc"=>$sadmin,
                "subject" => $mail_subject,
                "htmlBody" =>$message1
            ];
            try {
                $message = new Message($mail_options);
                $message->send();
            } catch (\InvalidArgumentException $e) {
                echo $e;
            }
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);

    }
    }


}