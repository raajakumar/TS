<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "GET_USERSTAMP.php";
    include "COMMON.php";
    $timeZoneFormat=getTimezone();
    $USERSTAMP=$UserStamp;
    if($_REQUEST["option"]=="DATE")
    {
        $date=$_REQUEST['date_change'];

        $ure_date=date('Y-m-d',strtotime($date));

        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $ure_uld_id=$row["ULD_ID"];
        }
        $sql="SELECT * FROM USER_ADMIN_REPORT_DETAILS WHERE ULD_ID='$ure_uld_id' AND UARD_DATE='$ure_date'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);

        $x=$row;
        if($x > 0)
        {
            $flag=1;
        }
        else{
            $flag=0;
        }
        echo $flag;
    }
    if($_REQUEST['option']=='SEARCH')
    {
        $sdate =$_REQUEST['start_date'];
        $edate =$_REQUEST['end_date'];
        $startdate = date('Y-m-d',strtotime($sdate));//echo $startdate;
        $enddate = date('Y-m-d',strtotime($edate));//echo $enddate;
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $ure_uld_id=$row["ULD_ID"];
        }
        $ure_values=array();
//        DATE_FORMAT(CONVERT_TZ(ETD.ETD_TIMESTAMP,"+timeZoneFormat+"),'%d-%m-%Y %T')
        $date= mysqli_query($con,"SELECT UARD_ID,UARD_REPORT,UARD_REASON,UARD_DATE,b.AC_DATA as UARD_PERMISSION, c.AC_DATA as UARD_ATTENDANCE,UARD.UARD_PDID,G.AC_DATA AS UARD_AM_SESSION,H.AC_DATA AS UARD_PM_SESSION,I.ULD_LOGINID AS ULD_ID,DATE_FORMAT(CONVERT_TZ(UARD.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS UARD_TIMESTAMP,UARD_BANDWIDTH FROM USER_ADMIN_REPORT_DETAILS UARD
LEFT JOIN ATTENDANCE_CONFIGURATION b ON b.AC_ID=UARD.UARD_PERMISSION
left JOIN ATTENDANCE_CONFIGURATION c on c.AC_ID=UARD.UARD_ATTENDANCE
LEFT JOIN ATTENDANCE_CONFIGURATION G ON G.AC_ID=UARD.UARD_AM_SESSION
LEFT JOIN ATTENDANCE_CONFIGURATION H ON H.AC_ID=UARD.UARD_PM_SESSION
LEFT JOIN USER_LOGIN_DETAILS I ON I.ULD_ID=UARD.ULD_ID
where UARD_DATE BETWEEN '$startdate' AND '$enddate' AND UARD.ULD_ID='$ure_uld_id' ORDER BY UARD.UARD_DATE");
        while($row=mysqli_fetch_array($date)){
            $ure_id=$row["UARD_ID"];
            $ure_date=$row["UARD_DATE"];
            $ure_date = date('d-m-Y',strtotime($ure_date));
            $ure_report=$row["UARD_REPORT"];
            $ure_userstamp=$row["ULD_ID"];
            $ure_timestamp=$row["UARD_TIMESTAMP"];
            $ure_reason=$row["UARD_REASON"];
            $ure_permission=$row["UARD_PERMISSION"];
            $ure_attendance=$row["UARD_ATTENDANCE"];
            $ure_pdid=$row["UARD_PDID"];
            $ure_morningsession=$row["UARD_AM_SESSION"];
            $ure_afternoonsession=$row["UARD_PM_SESSION"];
            $ure_bandwidth=$row['UARD_BANDWIDTH'];
            if($ure_report!=null){
                $email_body='';
                $body_msg =explode("\n", $ure_report);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $email_body.=$body_msg[$i].'<br>';
//                    if($i<$length)
//                        $email_body.='<br>';
                }
            }
            else{

                $email_body=null;
            }
            if($ure_reason!=null){
                $URE_reasontxt='';
                $URE_reason_msg =explode("\n", $ure_reason);
                $length=count($URE_reason_msg);
                for($i=0;$i<=$length;$i++){
                    $URE_reasontxt.=$URE_reason_msg[$i];
                    if($i!=$length)
                        $URE_reasontxt.='<br>';
                }
            }
            else{

                $URE_reasontxt=null;
            }
            $final_values=(object) ['id'=>$ure_id,'date' => $ure_date,'report' =>$email_body,'report1'=>$ure_report,'userstamp'=> $ure_userstamp,'timestamp'=>$ure_timestamp,'reason'=>$URE_reasontxt,'reason1'=>$ure_reason,'permission'=>$ure_permission,'attendance'=>$ure_attendance,'pdid'=>$ure_pdid,'morningsession'=>$ure_morningsession,'afternoonsession'=>$ure_afternoonsession,'bandwidth'=>$ure_bandwidth];
            $ure_values[]=$final_values;
        }
        echo JSON_ENCODE($ure_values);
    }
    if($_REQUEST['option']=='UPDATE')
    {
        $date = $_POST['USRC_UPD_tb_date'];
        $id=$_POST['USRC_UPD_rd_flxtbl'];
        $attendance=$_POST['USRC_UPD_lb_attendance'];
        $perm_time=$_POST['USRC_UPD_lb_timing'];
        $session=$_POST['USRC_UPD_lbl_session'];
        $project=$_POST['USRC_UPD_selproject'];
        $reason=$_POST['USRC_UPD_ta_reason'];
        $report=$_POST['USRC_UPD_ta_report'];
        $bandwidth=$_POST['USRC_UPD_tb_band'];
        $ampm=$_POST['USRC_UPD_lb_ampm'];
        $project=$_POST['checkbox'];
        $finaldate = date('Y-m-d',strtotime($date));
        if($perm_time=='SELECT')
        {
            $perm_time='';
        }
        else
        {
            $perm_time=$perm_time;
        }
        $length=count($project);
        $projectid;
        for($i=0;$i<$length;$i++){
            if($i==0){
                $projectid=$project[$i];
            }
            else{
                $projectid=$projectid .",".$project[$i];
            }
        }
        $projectid;
        $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($urc_id)){
            $ure_urc_id=$row["URC_ID"];
        }
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $ure_uld_id=$row["ULD_ID"];
        }
        $present=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='1'");
        while($row=mysqli_fetch_array($present)){
            $ure_present_data=$row["AC_DATA"];
        }
        $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
        while($row=mysqli_fetch_array($absent)){
            $ure_absent_data=$row["AC_DATA"];
        }
        $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
        while($row=mysqli_fetch_array($onduty)){
            $ure_onduty_data=$row["AC_DATA"];
        }
// for present radio button
        if($attendance=="1")
        {
            $report;
            $uard_morning_session=$ure_present_data;
            $uard_afternoon_session =$ure_present_data;
            $projectid;
            $reason='';
            $bandwidth;
        }
//  for absent radio button
        if($attendance=="OD")
        {
            if($ampm=="AM")
            {
                $uard_morning_session =$ure_onduty_data;
                $uard_afternoon_session =$ure_present_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="PM")
            {
                $uard_morning_session =$ure_present_data;
                $uard_afternoon_session =$ure_onduty_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="FULLDAY")
            {

                $reason;
                $uard_morning_session=$ure_onduty_data;
                $uard_afternoon_session =$ure_onduty_data;
                $report='';
                $bandwidth=0;
                $projectid='';
            }
        }
// for onduty radio button
        if($attendance=="0")
        {
            if($ampm=="AM")
            {
                $uard_morning_session =$ure_absent_data;
                $uard_afternoon_session =$ure_present_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="PM")
            {
                $uard_morning_session =$ure_present_data;
                $uard_afternoon_session =$ure_absent_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="FULLDAY")
            {
                $reason;
                $uard_morning_session=$ure_absent_data;
                $uard_afternoon_session =$ure_absent_data;
                $report='';
                $bandwidth=0;
                $projectid='';
            }
        }
        if($attendance=="1")
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =5 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ure_attendance=$row["AC_DATA"];
            }
        }
        if(($attendance=="0") && (($ampm=="AM") || ($ampm=="PM")))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =4 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ure_attendance=$row["AC_DATA"];
            }
        }
        elseif(($attendance=="0") && ($ampm=="FULLDAY"))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ure_attendance=$row["AC_DATA"];
            }
        }
        if(($attendance=="OD") && (($ampm=="AM") || ($ampm=="PM")))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =8 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ure_attendance=$row["AC_DATA"];
            }
        }
        elseif(($attendance=="OD") && ($ampm=="FULLDAY"))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ure_attendance=$row["AC_DATA"];
            }
        }
        $report= $con->real_escape_string($report);
        $reason= $con->real_escape_string($reason);
        $result = $con->query("CALL SP_TS_DAILY_REPORT_SEARCH_UPDATE($id,'$report','$reason','$finaldate',$ure_urc_id,'$USERSTAMP','$perm_time','$ure_attendance','$projectid','$uard_morning_session','$uard_afternoon_session',$bandwidth,'$USERSTAMP','',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        if($flag==1)
        {
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
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=7";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $header='<body>'.'<br>'.'<table border=1  width=2000><thead  bgcolor=#6495ed style=color:white><tr bgcolor=#498af3 align=center  height="40" ><th>LOGIN ID</th><th style="max-width:850px; !important;" >OLD VALUE</th><th style="max-width:850px; !important;" >NEW VALUE</th><th>USERSTAMP</th><th>TIMESTAMP</th></tr></thead>';
            $tickler_data= mysqli_query($con,"SELECT ULD.ULD_LOGINID,TH.TH_OLD_VALUE,TH.TH_NEW_VALUE,C.ULD_LOGINID as ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(TH.TH_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as TH_TIMESTAMP FROM TICKLER_HISTORY TH
INNER JOIN USER_LOGIN_DETAILS ULD ON ULD.ULD_ID = TH.ULD_ID
INNER JOIN USER_LOGIN_DETAILS C ON C.ULD_ID=TH.TH_USERSTAMP_ID
WHERE TH.ULD_ID='$ure_uld_id' AND TH.TTIP_ID=2 AND TH.TH_TIMESTAMP=(SELECT MAX(TH_TIMESTAMP) FROM TICKLER_HISTORY WHERE ULD_ID='$ure_uld_id');");
            $row=mysqli_num_rows($tickler_data);
            $x=$row;
            if($x>0){
                while($row=mysqli_fetch_array($tickler_data)){
                    $loginid=$row["ULD_LOGINID"];
                    $old_value=$row["TH_OLD_VALUE"];
                    $old_value=htmlspecialchars($old_value);

                    $old_value_array=explode(",",$old_value);

                    $TH_arroldvalue='';
                    $TH_arrnewvalue='';
                    for($j=0;$j<count($old_value_array);$j++)
                    {
                        if($j==0){
                            $TH_arroldvalue=$old_value_array[$j];
                        }
                        else{
                            $TH_arroldvalue .=' , '.$old_value_array[$j];
                        }
                    }
                    $new_value=$row["TH_NEW_VALUE"];
                    $new_value=htmlspecialchars($new_value);

                    $new_value_array=explode(",",$new_value);
                    for($k=0;$k<count($new_value_array);$k++)
                    {
                        if($k==0){
                            $TH_arrnewvalue=$new_value_array[$k];
                        }
                        else{
                            $TH_arrnewvalue .=' , '.$new_value_array[$k];
                        }
                    }
                    $userstamp=$row["ULD_USERSTAMP"];
                    $timestamp=$row["TH_TIMESTAMP"];
                    $values=$header. "<tr><td nowrap>".$loginid."</td><td style=max-width:850px; !important;>".$TH_arroldvalue."</td><td style=max-width:850px; !important;>".$TH_arrnewvalue."</td><td >".$userstamp."</td><td nowrap>".$timestamp."</td></tr>";
                }
                $sub=str_replace("[LOGINID]","$loginid",$body);
                $sub=$sub.'<br>';
                $mail_options = [
                    "sender" => $admin,
                    "to" => $admin,
                    "cc" => 'safiyullah.mohideen@ssomens.com',
//                    "cc" => $sadmin,
                    "subject" => $mail_subject,
                    "htmlBody" => $sub.$values
                ];
                try {
                    $message = new Message($mail_options);
                    $message->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }
            }
        }
        echo $flag;
    }
}
?>