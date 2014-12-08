<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************REPORT MAIL TRIGGER *************************************//
//DONE BY:LALITHA
//VER 0.05,SD:24/11/2014 ED:24/11/2014,TRACKER NO:74,DESC:Implemented If reason means updated Onduty/Absent with checked condition nd changed query also(selected am/pm session)
//VER 0.04,SD:22/11/2014 ED:22/11/2014,TRACKER NO:74,DESC:Updated date concat with subject in mail option
//VER 0.03,SD:20/11/2014 ED:20/11/2014,TRACKER NO:74,DESC:Updated Showned permission details in flex tble nd also changed flxtbl query,Updated to showned point by point line fr report nd reason,Removed unwanted br tags,Updated hrs fr permission
//DONE BY:SAFIYULLAH
//VER 0.02,SD:24/10/2014 ED:24/10/2014,TRACKER NO:82,DESC:update subject and body to get from email template
//VER 0.01-INITIAL VERSION, SD:16/09/2014 ED:08/10/2014,TRACKER NO:82
//*********************************************************************************************************//-->
<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
$currentdate=date("Y-m-d");//CURRENT DATE
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=4";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
if($row=mysqli_fetch_array($admin_rs)){
    $admin=$row["ULD_LOGINID"];//get admin
}
if($row=mysqli_fetch_array($sadmin_rs)){
    $sadmin=$row["ULD_LOGINID"];//get super admin
}
$admin_name = substr($admin, 0, strpos($admin, '.'));
$sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
$spladminname=$admin_name.'/'.$sadmin_name;
$spladminname=strtoupper($spladminname);
$sub=str_replace("[SADMIN]","$spladminname",$body);
$sub=str_replace("[DATE]",date("d-m-Y"),$sub);
$message='<html><body>'.'<br>'.'<h> '.$sub.'</h>'.'<br>'.'<br>'.'<table border=1  width=1300 ><thead  bgcolor=#6495ed style=color:white><tr  align="center"  height=2px ><td><b>LOGIN ID</b></td><td><b>REPORT</b></td><td width=200><b>USERSTAMP</b></td><td  width=200 nowrap><b>TIMESTAMP</b></td></tr></thead>';
$query="SELECT DISTINCT AC.AC_DATA,A.UARD_REPORT,A.UARD_REASON,A.ABSENT_FLAG,G.AC_DATA AS UARD_AM_SESSION,H.AC_DATA AS UARD_PM_SESSION,B.ULD_LOGINID,C.ULD_LOGINID as USERSTAMP,DATE_FORMAT(CONVERT_TZ(A.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as UARD_TIMESTAMP FROM USER_ADMIN_REPORT_DETAILS A
INNER JOIN USER_LOGIN_DETAILS B on A.ULD_ID=B.ULD_ID INNER JOIN USER_LOGIN_DETAILS C on A.UARD_USERSTAMP_ID=C.ULD_ID
             INNER JOIN USER_ACCESS D LEFT join ATTENDANCE_CONFIGURATION AC ON A.UARD_PERMISSION=AC.AC_ID LEFT JOIN ATTENDANCE_CONFIGURATION G ON G.AC_ID=A.UARD_AM_SESSION
LEFT JOIN ATTENDANCE_CONFIGURATION H ON H.AC_ID=A.UARD_PM_SESSION where A.UARD_DATE='$currentdate' and D.UA_TERMINATE IS null order by ULD_LOGINID";
$sql=mysqli_query($con,$query);
$row=mysqli_num_rows($sql);
$x=$row;
if($x>0){
    while($row=mysqli_fetch_array($sql)){
        $adm_reprt=$row["UARD_REPORT"];
        $adm_userstamp=$row["USERSTAMP"];
        $adm_timestamp=$row["UARD_TIMESTAMP"];
        $adm_loginid=$row["ULD_LOGINID"];
        $ure_reason_txt=$row["UARD_REASON"];
        $adm_permission=$row["AC_DATA"];
        $adm_absentflag=$row["ABSENT_FLAG"];
        $ure_morningsession=$row["UARD_AM_SESSION"];
        $ure_afternoonsession=$row["UARD_PM_SESSION"];
        //STRING REPLACED
        if($adm_reprt!=null){
            $adm_report='';
            $body_msg =explode("\n", $adm_reprt);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $adm_report.=$body_msg[$i].'<br>';
            }
        }
        else{
            $adm_report=null;
        }
        if($ure_reason_txt!=null){
            $adm_reason='';
            $URE_reason_msg =explode("\n", $ure_reason_txt);
            $length=count($URE_reason_msg);
            for($i=0;$i<=$length;$i++){
                $adm_reason.=$URE_reason_msg[$i].'<br>';
            }
        }
        else{
            $adm_reason=null;
        }
        if($adm_report==null){

            if($adm_absentflag=='X')
            {
                $final_report='REASON:'.$adm_reason;
            }
            else
            {
                $final_report='REASON:'.$ure_morningsession.':'.$adm_reason;
            }
        }
        else if($adm_reason==null){
            if($adm_permission!=null)
            {
                $final_report=$adm_report.'<br>'.'PERMISSION:'.$adm_permission.'hrs';
            }
            else
            {
                $final_report=$adm_report;
            }
        }
        else{
            if($ure_morningsession=='PRESENT'){
                $ure_after_mrg=$ure_afternoonsession;
            }
            else
            {
                $ure_after_mrg=$ure_morningsession;
            }
            if($adm_permission!=null){
                $final_report=$adm_report.'<br>'.'REASON:'.$ure_after_mrg.':'.$adm_reason.'PERMISSION:'.$adm_permission .'hrs';
            }
            else{
                $final_report=$adm_report.'<br>'.'REASON:'.$ure_after_mrg.':'.$adm_reason;
            }
        }
        $message=$message. "<tr><td >".$adm_loginid."</td><td >".$final_report."</td><td >".$adm_userstamp."</td><td nowrap>".$adm_timestamp."</td></tr>";
    }
    $message=$message."</table></body></html>";
    $REP_subject_date=$mail_subject.' - '.date("d/m/Y");
    // SENDING MAIL OPTIONS
    $mail_options = [
        "sender" => $admin,
        "to" => $admin,
        "cc"=>'safiyullah.mohideen@ssomens.com',
//        "cc"=>$sadmin,
        "subject" => $REP_subject_date,
        "htmlBody" => $message
    ];
    try {
        $message = new Message($mail_options);
        $message->send();
    } catch (\InvalidArgumentException $e) {
        echo $e;
    }
}
