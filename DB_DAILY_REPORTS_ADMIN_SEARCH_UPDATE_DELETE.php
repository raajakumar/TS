<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){

    include "CONNECTION.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    if($_REQUEST["option"]=="login_id"){
        $login_id=$_REQUEST['login_id'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
        while($row=mysqli_fetch_array($uld_id)){
            $ADM_uld_id=$row["ULD_ID"];
        }
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ADM_uld_id' ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["UARD_DATE"];
            $admin_min_date = date('d-m-Y',strtotime($admin_searchmin_date_value));
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ADM_uld_id' ");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["UARD_DATE"];
            $admin_max_date= date('d-m-Y',strtotime($admin_searchmax_date_value));
        }
        $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ADM_uld_id' ");
        while($row=mysqli_fetch_array($min_date)){
            $mindate_array=$row["UA_JOIN_DATE"];
            $min_date = date('d-m-Y',strtotime($mindate_array));
        }
        $finalvalue=array($admin_min_date,$admin_max_date,$min_date);
        echo JSON_ENCODE($finalvalue);

    }
    $ure_values=array();
    if($_REQUEST['option']=='DATERANGE')
    {
        $sdate = $_REQUEST['start_date'];
        $edate = $_REQUEST['end_date'];
        $active_loginid=$_REQUEST['actionloginid'];
        $startdate = date('Y-m-d',strtotime($sdate));
        $enddate = date('Y-m-d',strtotime($edate));
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$active_loginid'");
        while($row=mysqli_fetch_array($uld_id)){
            $ure_uld_id=$row["ULD_ID"];
        }
        $date= mysqli_query($con,"SELECT UARD_ID,UARD_REPORT,UARD_REASON,UARD_DATE,b.AC_DATA as UARD_PERMISSION, c.AC_DATA as UARD_ATTENDANCE,UARD.UARD_PDID,G.AC_DATA AS UARD_AM_SESSION,H.AC_DATA AS UARD_PM_SESSION,I.ULD_LOGINID AS ULD_ID,DATE_FORMAT(CONVERT_TZ(UARD.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS UARD_TIMESTAMP,UARD_BANDWIDTH,ULD.ULD_LOGINID as UARD_USERSTAMP_ID,ABSENT_FLAG FROM USER_ADMIN_REPORT_DETAILS UARD
LEFT JOIN ATTENDANCE_CONFIGURATION b ON b.AC_ID=UARD.UARD_PERMISSION
left JOIN ATTENDANCE_CONFIGURATION c on c.AC_ID=UARD.UARD_ATTENDANCE
LEFT JOIN ATTENDANCE_CONFIGURATION G ON G.AC_ID=UARD.UARD_AM_SESSION
LEFT JOIN ATTENDANCE_CONFIGURATION H ON H.AC_ID=UARD.UARD_PM_SESSION
LEFT JOIN USER_LOGIN_DETAILS I ON I.ULD_ID=UARD.ULD_ID
LEFT JOIN USER_LOGIN_DETAILS ULD ON ULD.ULD_ID=UARD.UARD_USERSTAMP_ID
where UARD_DATE BETWEEN '$startdate' AND '$enddate' and UARD.ULD_ID='$ure_uld_id' ORDER BY UARD.UARD_DATE ");
        while($row=mysqli_fetch_array($date)){
            $ure_id=$row["UARD_ID"];
            $uredate=$row["UARD_DATE"];
            $ure_date = date('d-m-Y',strtotime($uredate));
            $ure_reprt=$row["UARD_REPORT"];
            $ure_userstamp=$row["ULD_ID"];
            $ure_timestamp=$row["UARD_TIMESTAMP"];
            $userstamp=$row["UARD_USERSTAMP_ID"];
            $ure_reason_txt=$row["UARD_REASON"];
            $ure_permission=$row["UARD_PERMISSION"];
            $ure_attendance=$row["UARD_ATTENDANCE"];
            $ure_pdid=$row["UARD_PDID"];
            $ure_bandwidth=$row["UARD_BANDWIDTH"];
            $ure_morningsession=$row["UARD_AM_SESSION"];
            $ure_afternoonsession=$row["UARD_PM_SESSION"];
            $ure_flag=$row["ABSENT_FLAG"];
            //STRING REPLACED
            if($ure_reprt!=null){
                $ure_report='';
                $body_msg =explode("\n", $ure_reprt);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $ure_report.=$body_msg[$i].'<br>';
                }
            }
            else{
                $ure_report=null;
            }
            if($ure_reason_txt!=null){
                $ure_reason='';
                $URE_reason_msg =explode("\n", $ure_reason_txt);
                $length=count($URE_reason_msg);
                for($i=0;$i<=$length;$i++){
                    $ure_reason.=$URE_reason_msg[$i].'<br>';
                }
            }
            else{
                $ure_reason=null;
            }
            $final_values=(object) ['id'=>$ure_id,'date' => $ure_date,'report' =>$ure_report,'report1' =>$ure_reprt,'userstamp'=> $ure_userstamp,'timestamp'=>$ure_timestamp,'reason'=>$ure_reason,'reason1'=>$ure_reason_txt,'permission'=>$ure_permission,'attendance'=>$ure_attendance,'pdid'=>$ure_pdid,'morningsession'=>$ure_morningsession,'afternoonsession'=>$ure_afternoonsession,'bandwidth'=>$ure_bandwidth,'user_stamp'=>$userstamp,'flag'=>$ure_flag];
            $ure_values[]=$final_values;
        }
        echo json_encode($ure_values);
    }
    if($_REQUEST['option']=='ALLDATE')
    {
        $alldate = $_REQUEST['alldate'];
        $empdate = date('Y-m-d',strtotime($alldate));
        $sql=mysqli_query($con,"SELECT DISTINCT AC.AC_DATA,A.UARD_REPORT,A.UARD_REASON,B.ULD_LOGINID,C.ULD_LOGINID as USERSTAMP,DATE_FORMAT(CONVERT_TZ(A.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as UARD_TIMESTAMP FROM USER_ADMIN_REPORT_DETAILS A
            INNER JOIN USER_LOGIN_DETAILS B on A.ULD_ID=B.ULD_ID INNER JOIN USER_LOGIN_DETAILS C on A.UARD_USERSTAMP_ID=C.ULD_ID
              INNER JOIN USER_ACCESS D LEFT join ATTENDANCE_CONFIGURATION AC ON A.UARD_PERMISSION=AC.AC_ID  where A.UARD_DATE='$empdate' and D.UA_TERMINATE IS null order by ULD_LOGINID");
        while($row=mysqli_fetch_array($sql)){
            $adm_reprt=$row["UARD_REPORT"];
            $adm_userstamp=$row["USERSTAMP"];
            $adm_timestamp=$row["UARD_TIMESTAMP"];
            $adm_loginid=$row["ULD_LOGINID"];
            $adm_reason_txt=$row["UARD_REASON"];
            $ure_permission=$row["AC_DATA"];
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
            if($adm_reason_txt!=null){
                $adm_reason='';
                $URE_reason_msg =explode("\n", $adm_reason_txt);
                $length=count($URE_reason_msg);
                for($i=0;$i<=$length;$i++){
                    $adm_reason.=$URE_reason_msg[$i].'<br>';
                }
            }
            else{
                $adm_reason=null;
            }
            $all_values=(object) ['admreason'=>$adm_reason,'admreport' =>$adm_report,'permission'=>$ure_permission,'admuserstamp'=> $adm_userstamp,'admtimestamp'=>$adm_timestamp,'admlogin'=>$adm_loginid];
            $ure_values[]=$all_values;
        }
        echo json_encode($ure_values);
    }
    if($_REQUEST['option']=='ADMIN REPORT SEARCH/UPDATE')
    {
        $date = $_POST['ASRC_UPD_DEL_ta_reportdate'];
        $id=$_POST['ASRC_UPD_DEL_rd_flxtbl'];
        $attendance=$_POST['ASRC_UPD_DEL_lb_attendance'];
        $perm_time=$_POST['ASRC_UPD_DEL_lb_timing'];
        $reason=$_POST['ASRC_UPD_DEL_ta_reason'];
        $report=$_POST['ASRC_UPD_DEL_ta_report'];
        $bandwidth=$_POST['ASRC_UPD_DEL_tb_band'];
        $ampm=$_POST['ASRC_UPD_DEL_lb_ampm'];
        $project=$_POST['checkbox'];
        $login_id=$_POST['ASRC_UPD_DEL_lb_loginid'];
        $flag_abs=$_POST['flag'];
        $finaldate = date('Y-m-d',strtotime($date));
        $length=count($project);
        if($flag_abs=='on'){

            $flag_absent='X';

        }
        else{
            $flag_absent='';

        }
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

        if($perm_time=='SELECT')
        {
            $perm_time='';
        }
        else
        {
            $perm_time=$perm_time;
        }
        $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($urc_id)){
            $ADM_urc_id=$row["URC_ID"];
        }
        $userstamp_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($userstamp_id)){
            $ADM_userstamp_id=$row["ULD_ID"];
        }

        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
        while($row=mysqli_fetch_array($uld_id)){
            $ADM_uld_id=$row["ULD_ID"];
        }
        $present=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='1'");
        while($row=mysqli_fetch_array($present)){
            $ADM_present_data=$row["AC_DATA"];
        }
        $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
        while($row=mysqli_fetch_array($absent)){
            $ADM_absent_data=$row["AC_DATA"];
        }
        $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
        while($row=mysqli_fetch_array($onduty)){
            $ADM_onduty_data=$row["AC_DATA"];
        }
// for present radio button
        if($attendance=="1")
        {
            $report;
            $uard_morning_session=$ADM_present_data;
            $uard_afternoon_session =$ADM_present_data;
            $projectid;
            $reason='';
            $bandwidth;
        }
//  for onduty radio button
        if($attendance=="OD")
        {
            if($ampm=="AM")
            {
                $uard_morning_session =$ADM_onduty_data;
                $uard_afternoon_session =$ADM_present_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="PM")
            {
                $uard_morning_session =$ADM_present_data;
                $uard_afternoon_session =$ADM_onduty_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="FULLDAY")
            {

                $reason;
                $uard_morning_session=$ADM_onduty_data;
                $uard_afternoon_session =$ADM_onduty_data;
                $report='';
                $bandwidth=0;
                $projectid='';
            }
        }
// for absent radio button
        if($attendance=="0")
        {
            if($ampm=="AM")
            {
                $uard_morning_session =$ADM_absent_data;
                $uard_afternoon_session =$ADM_present_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="PM")
            {
                $uard_morning_session =$ADM_present_data;
                $uard_afternoon_session =$ADM_absent_data;
                $reason;
                $projectid;
                $report;
                $bandwidth;
            }
            elseif($ampm=="FULLDAY")
            {

                $reason;
                $uard_morning_session=$ADM_absent_data;
                $uard_afternoon_session =$ADM_absent_data;
                $report='';
                $bandwidth=0;
                $projectid='';
            }

        }
        if($attendance=="1")
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =5 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ADM_attendance=$row["AC_DATA"];
            }
        }
        if(($attendance=="0") && (($ampm=="AM") || ($ampm=="PM")))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =4 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ADM_attendance=$row["AC_DATA"];
            }
        }
        elseif(($attendance=="0") && ($ampm=="FULLDAY"))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ADM_attendance=$row["AC_DATA"];
            }
        }
        if(($attendance=="OD") && (($ampm=="AM") || ($ampm=="PM")))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =8 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ADM_attendance=$row["AC_DATA"];
            }
        }
        elseif(($attendance=="OD") && ($ampm=="FULLDAY"))
        {
            $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
            while($row=mysqli_fetch_array($attend)){
                $ADM_attendance=$row["AC_DATA"];
            }
        }
//        $report=htmlspecialchars($report, ENT_QUOTES);
//        $reason=htmlspecialchars($reason, ENT_QUOTES);
        $report= $con->real_escape_string($report);
        $reason= $con->real_escape_string($reason);
        $result = $con->query("CALL SP_TS_DAILY_REPORT_SEARCH_UPDATE($id,'$report','$reason','$finaldate',$ADM_urc_id,'$login_id','$perm_time','$ADM_attendance','$projectid','$uard_morning_session','$uard_afternoon_session',$bandwidth,'$USERSTAMP','$flag_absent',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
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
        if($flag==1)
        {
            $header='<body>'.'<br>'.'<table border=1  width=2000><thead  bgcolor=#6495ed style=color:white><tr bgcolor=#498af3 align=center  height="40" ><th>LOGIN ID</th><th style="max-width:850px; !important;" >OLD VALUE</th><th style="max-width:850px; !important;" >NEW VALUE</th><th>USERSTAMP</th><th>TIMESTAMP</th></tr></thead>';
            $tickler_data= mysqli_query($con,"SELECT ULD.ULD_LOGINID,TH.TH_OLD_VALUE,TH.TH_NEW_VALUE,C.ULD_LOGINID as ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(TH.TH_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as TH_TIMESTAMP FROM TICKLER_HISTORY TH
INNER JOIN USER_LOGIN_DETAILS ULD ON ULD.ULD_ID = TH.ULD_ID
INNER JOIN USER_LOGIN_DETAILS C ON C.ULD_ID=TH.TH_USERSTAMP_ID
WHERE TH.ULD_ID='$ADM_uld_id' AND TH.TTIP_ID=2 AND TH.TH_TIMESTAMP=(SELECT MAX(TH_TIMESTAMP) FROM TICKLER_HISTORY WHERE ULD_ID='$ADM_uld_id');");
            $row=mysqli_num_rows($tickler_data);
            $x=$row;
            if($x>0){
                while($row=mysqli_fetch_array($tickler_data)){
                    $loginid=$row["ULD_LOGINID"];
                    $old_value=$row["TH_OLD_VALUE"];
                    $old_value=$con->real_escape_string($old_value);
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
                    $values=$header. "<tr><td>".$loginid."</td><td style=max-width:850px; !important;>".$TH_arroldvalue."</td><td style=max-width:850px; !important;>".$TH_arrnewvalue."</td><td >".$userstamp."</td><td nowrap>".$timestamp."</td></tr>";
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
    if($_REQUEST['option']=='DELETE')
    {
        $id = $_REQUEST['del_id'];
        $tabid= mysqli_query($con,"SELECT TTIP_ID FROM TICKLER_TABID_PROFILE WHERE TTIP_DATA='USER_ADMIN_REPORT_DETAILS'");
        while($row=mysqli_fetch_array($tabid)){
            $tab_id=$row["TTIP_ID"];
        }
        $result = $con->query("CALL SP_TS_SINGLE_TABLE_ROW_DELETION($tab_id,$id,'$USERSTAMP',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        echo $flag;
    }
    if($_REQUEST['option']=='ONDUTY')
    {
        $sdate = $_REQUEST['sdate'];
        $edate=$_REQUEST['edate'];
        $odstartdate = date('Y-m-d',strtotime($sdate));
        $odenddate=date('Y-m-d',strtotime($edate));
        $sql=mysqli_query($con,"SELECT DISTINCT A.OED_ID,A.OED_DATE,A.OED_DESCRIPTION,B.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(A.OED_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T')as OED_TIMESTAMP FROM ONDUTY_ENTRY_DETAILS A
            INNER JOIN USER_LOGIN_DETAILS B on A.ULD_ID=B.ULD_ID
            INNER JOIN USER_ACCESS C where A.OED_DATE between '$odstartdate' and '$odenddate' and C.UA_TERMINATE IS null");
        while($row=mysqli_fetch_array($sql)){
            $ondutyid=$row["OED_ID"];
            $oddate=$row["OED_DATE"];
            $ondutydate=date('d-m-Y',strtotime($oddate));
            $ondutydes=$row["OED_DESCRIPTION"];
            $userstamp=$row["ULD_LOGINID"];
            $timestamp=$row["OED_TIMESTAMP"];
            $all_values=(object) ['id' =>$ondutyid,'description'=> $ondutydes,'userstamp'=>$userstamp,'timestamp'=>$timestamp,'date'=>$ondutydate];
            $ure_values[]=$all_values;
        }
        echo json_encode($ure_values);
    }
    if($_REQUEST["option"]=="ONDUTY REPORT SEARCH/UPDATE")
    {
        $ondutydate=$_POST['ASRC_UPD_DEL_tb_oddte'];
        $ondutydes=$_POST['ASRC_UPD_DEL_ta_des'];
        $id=$_POST['ASRC_UPD_DEL_rd_tbl'];
        $oddate = date('Y-m-d',strtotime($ondutydate));
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $ADM_uld_id=$row["ULD_ID"];
        }
        $ondutydes= $con->real_escape_string($ondutydes);
        $sql="UPDATE ONDUTY_ENTRY_DETAILS SET OED_DESCRIPTION='$ondutydes',ULD_ID='$ADM_uld_id' WHERE OED_ID='$id'";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));

            $flag="Record not saved";
        }
        else
        {
            $msg= mysqli_query($con,"select EMC_DATA from ERROR_MESSAGE_CONFIGURATION where EMC_ID='4'");
            while($row=mysqli_fetch_array($msg)){
                $flag_msg=$row["EMC_DATA"];
            }
        }
        $flag= $flag_msg;
        echo $flag;
    }
    if($_REQUEST["option"]=="DATE"){
        $login_id=$_REQUEST['login_id'];
        $date=$_REQUEST['reportdate'];
        $ADM_reportdate=date('Y-m-d',strtotime($date));
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
        while($row=mysqli_fetch_array($uld_id)){
            $ADM_uld_id=$row["ULD_ID"];
        }
        $sql="SELECT * FROM USER_ADMIN_REPORT_DETAILS WHERE ULD_ID='$ADM_uld_id' AND UARD_DATE='$ADM_reportdate'";
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
}
?>