<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
date_default_timezone_set('Asia/Kolkata');
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
$currentdate=date("d-m-Y");
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=8";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
$mail_subject=str_replace("[CURRENTDATE]","$currentdate",$mail_subject);

$result = $con->query("CALL SP_DROP_PROD_TEMP_TABLE('TS_PHP','$admin',@FINAL_TEMP_TABLE)");
if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
$select = $con->query('SELECT @FINAL_TEMP_TABLE');
$result = $select->fetch_assoc();
$temp_table_name= $result['@FINAL_TEMP_TABLE'];

if($temp_table_name!=''){
    $sub1=str_replace("[CURRENTDATE]","$currentdate",$body);
    $message1= '<body>'.'<br>'.'<h> '.$sub1.'</h>'.'<br>'.'<br>'.'<table border=1  width=400>><thead  bgcolor=#6495ed style=color:white><tr bgcolor=#498af3 align=center  height="30"><td >TEMP TABLE NAME </td></tr></thead>';

    $select_data="select * from $temp_table_name ";
    $select_data_rs=mysqli_query($con,$select_data);
    $row=mysqli_num_rows($select_data_rs);
    $x=$row;
    if($x>0){
        while($row=mysqli_fetch_array($select_data_rs)){
            $droptablename=$row['DROPTABLENAME'];
            $message1=$message1."<tr><td>".$droptablename."</td></tr>";

        }
                $mail_options = [
            "sender" => $admin,
            "to" => $admin,
             "cc"=> $sadmin,
            "subject" => $mail_subject,
            "htmlBody" => $message1
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