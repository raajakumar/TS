<?php


set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google/appengine/api/mail/Message.php';
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
include "CONNECTION.php";
include "COMMON.php";
include "GET_USERSTAMP.php";
include "CONFIG.php";
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){
$USERSTAMP=$UserStamp;

    global $con;
    //ALREADY EXISTS FUNCTION FOR LOGIN ID
    if($_REQUEST['option']=="check_login_id"){
        $loginid=$_GET['URSRC_login_id'];
        $sql="select * from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $URSRC_already_exist_flag=1;
        }
        else{
            $URSRC_already_exist_flag=0;
        }
        $rcname_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION ORDER BY RC_NAME");
        $get_rcname_array=array();
        while($row=mysqli_fetch_array($rcname_result)){
            $get_rcname_array[]=$row["RC_NAME"];
        }
        $URSRC_final_array=array();
        $URSRC_role_array=array();
        $URSRC_role_array=$get_rcname_array;
        $URSRC_final_array=array($URSRC_already_exist_flag,$URSRC_role_array);
        echo json_encode($URSRC_final_array);
    }
//LOGIN CREWTION SAVE PART
    if($_REQUEST['option']=="loginsave")
    {
        $loginid=$_POST['URSRC_tb_loginid'];
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $role_accessradiovalue = $_REQUEST['radio_checked'];
        $final_radioval=str_replace("_"," ",$role_accessradiovalue);
        $date=$_POST['URSRC_tb_joindate'];
        $finaldate = date('Y-m-d',strtotime($date));
        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT('$loginid','$final_radioval','$finaldate','$USERSTAMP','$emp_type',@success_flag)");
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

            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }

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
            $fileId=$ss_fileid;
            $value=$loginid;
            $type='user';
            $role='reader';
            $email=$loginid;

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

            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
            URSRC_calendar_create($loginid_name,$URSC_uld_id,$finaldate,$calenderid);

//            $cal = new Google_Service_Calendar($drive);
//            $event = new Google_Service_Calendar_Event();
//            $event->setsummary($loginid_name.'  '.'JOIN DATE');
//            $event->setDescription($URSC_uld_id);
//            $start = new Google_Service_Calendar_EventDateTime();
//            $start->setDate($finaldate);//setDate('2014-11-18');
//            $event->setStart($start);
//            $event->setEnd($start);
//            $createdEvent = $cal->events->insert($calenderid, $event);
            $email_body;
            $body_msg =explode(",", $body);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $email_body.=$body_msg[$i].'<br><br>';
            }
            $replace= array("[LOGINID]", "[LINK]","[SSLINK]", "[VLINK]");
            $str_replaced  = array($loginid,$site_link, $ss_link, 'https://www.youtube.com/watch?v=u3Vr4lfdTa8&feature=youtu.be');
            $final_message = str_replace($replace, $str_replaced, $email_body);
           $mail_options = [
                "sender" =>'safiyullah.mohideen@ssomens.com',//$admin,
                "to" => $loginid,
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

            }




        echo $flag;
    }
    //FETCHING LOGIN DETAILS
    if($_REQUEST['option']=="loginfetch")
    {
        $loginid_result = $_REQUEST['URSRC_login_id'];
        $loginsearch_fetchingdata= mysqli_query($con,"SELECT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA FROM USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,ROLE_CREATION RC where UA.UA_EMP_TYPE=URC1.URC_ID and ULD.ULD_ID=UA.ULD_ID and URC.URC_ID=RC.URC_ID and RC.RC_ID=UA.RC_ID and ULD_LOGINID='$loginid_result' and UA.UA_REC_VER=(select max(UA_REC_VER) from USER_ACCESS UA,USER_LOGIN_DETAILS ULD where ULD.ULD_ID=UA.ULD_ID and ULD_LOGINID='$loginid_result' and UA_JOIN is not null) ORDER BY RC_NAME");
        $URSRC_values=array();
        $rolecreation_result = mysqli_query($con,"SELECT * FROM ROLE_CREATION");
        $get_rolecreation_array=array();
        while($row=mysqli_fetch_array($rolecreation_result)){
            $get_rolecreation_array[]= $row["RC_NAME"];
        }
        $final_values=array();
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URSRC_joindate=$row["UA_JOIN_DATE"];
            $join_date=date('d-m-Y',strtotime($URSRC_joindate));
            $URSRC_rcname=$row["RC_NAME"];
            $URSRC_EMP_TYPE=$row['URC_DATA'];
            $final_values=(object)['joindate'=>$join_date,'rcname' => $URSRC_rcname,'emp_type'=>$URSRC_EMP_TYPE];
        }
        $URSRC_values[]=array($final_values,$get_rolecreation_array);
        echo json_encode($URSRC_values);
    }
    if($_REQUEST['option']=="login_db"){
        $rcname_result=mysqli_query($con,"SELECT ULD_LOGINID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID ORDER BY ULD_LOGINID");
        $get_rcname_array=array();
        while($row=mysqli_fetch_array($rcname_result)){
            $get_rcname_array[]=$row["ULD_LOGINID"];
        }
        echo json_encode($get_rcname_array);
    }
//LOGIN CREATION UPATE FORM
    if($_REQUEST['option']=="loginupdate")
    {
        $rolename=$_POST['roles1'];
        $rolename=str_replace("_"," ",$rolename);
        $joindate=$_POST['URSRC_tb_joindate'];
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $loginid=$_POST['URSRC_tb_loginidupd'];
        $oldloginid=$_POST['URSRC_lb_loginid'];
        $sql="select * from USER_LOGIN_DETAILS where ULD_LOGINID='$oldloginid'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $ULD_id=$row["ULD_ID"];
        }
        $finaldate = date('Y-m-d',strtotime($joindate));
       $select_last_joindate= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%Y-%m-%d') as UA_JOIN_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID='$ULD_id' AND UA_TERMINATE IS NULL)AND ULD_ID='$ULD_id'";
        $select_last_joindate_result=mysqli_query($con,$select_last_joindate);
        if($row=mysqli_fetch_array($select_last_joindate_result)){

            $lastdate=$row['UA_JOIN_DATE'];


        }
        $result = $con->query("CALL SP_TS_LOGIN_UPDATE($ULD_id,'$loginid','$rolename','$finaldate','$emp_type','$USERSTAMP',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        if($flag==1){
            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }

        if($oldloginid!=$loginid){
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
            if($row=mysqli_fetch_array($uld_id)){
                $ss_link=$row["URC_DATA"];
            }
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
            $str_replaced  = array($loginid,$site_link, $ss_link, 'https://www.youtube.com/watch?v=u3Vr4lfdTa8&feature=youtu.be');
            $final_message = str_replace($replace, $str_replaced, $email_body);
            URSRC_delete_create_calendarevent($ULD_id,$loginid_name,$finaldate);
//            $sub=str_replace("[LOGINID]","$loginid",$body);
//            $final_message=str_replace("[LINK]","$site_link",$sub);

//            $sub="WELCOME ".$loginid.'<br><br><br>'.'USE BELOW LINK TO ENTER YOUR DAILY REPORTS';
//            $final_message=$sub.'<br><br><br>'.$site_link;
            $mail_options = [
                "sender" =>'safiyullah.mohideen@ssomens.com',//$admin,
                "to" => $loginid,
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
        }
            if($lastdate!=$finaldate){

                            URSRC_delete_create_calendarevent($ULD_id,$loginid_name,$finaldate);



            }

        }
        echo $flag;
    }
    //ROLE CREATION ENTRY
    if($_REQUEST['option']=="URSRC_check_role_id"){
        $URSRC_roleid=$_GET['URSRC_roleidval'];
        $sql="SELECT * FROM ROLE_CREATION where RC_NAME='$URSRC_roleid'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $URSRC_already_exist_flag=1;
        }
        else{
            $URSRC_already_exist_flag=0;
        }
        echo ($URSRC_already_exist_flag);
    }
    //TREE VIEW
    if($_REQUEST['option']=="URSRC_tree_views"){
        $role_customemrole_name = $_REQUEST['URSRC_lbrole_srchndupdate'];
        $role_customemrole_name=str_replace("_"," ",$role_customemrole_name);
        $rcname_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION RC,USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=RC.URC_ID and RC_NAME='".$role_customemrole_name."' ORDER BY URC_DATA ");
        while($row=mysqli_fetch_array($rcname_result)){
            $get_urcdata_array=$row["URC_DATA"];
        }
        $mpid_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION RC,USER_MENU_DETAILS  UMD,MENU_PROFILE MP where MP.MP_ID=UMD.MP_ID and UMD.RC_ID=RC.RC_ID and RC_NAME='".$role_customemrole_name."' ");
        $get_mpid_array=array();
        while($row=mysqli_fetch_array($mpid_result)){
            $get_mpid_array[]=$row["MP_ID"];
        }
        $get_urcdata_array=str_replace("_"," ",$get_urcdata_array);
        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' ORDER BY MP_MNAME ASC ");
        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];
            $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
                $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
                $URSC_sub_sub_menu_row=array();
                $URSC_sub_sub_menu_row_data=array();
                while($row=mysqli_fetch_array($sub_sub_menu_data)){
                    $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
                }
                $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
                $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
                $j++;
            }
            $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
            $URSC_sub_menu_array[]=$URSC_sub_menu_row;
            $i++;
        }
        $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
        $role_mpid_array=array($get_urcdata_array,$get_mpid_array,$final_values);
        echo json_encode($role_mpid_array);
    }
    //Role creation save and update & Basic role menu creation save and update
    if($_REQUEST['option']=="rolecreationsave")
    {
        $URSRC_radio_button_select_value = $_REQUEST['URSRC_mainradiobutton'];
        $URSRC_radio_button_select_value=str_replace("_"," ",$URSRC_radio_button_select_value);
        $URSRC_customrolename=$_POST['URSRC_tb_customrole'];
        $URSRC_customrolenameupd=$_POST['URSRC_lb_rolename'];
        $URSRC_basicrole=$_POST['basicroles'];
        $URSRC_basicrole=str_replace("_"," ",$URSRC_basicrole);
        $URSRC_menu=$_POST['menu'];
        $URSRC_menuid;
        $URSRC_sub_submenu=$_POST['Sub_menu1'];
        $URSRC_submenu=$_POST['Sub_menu'];
        $URSRC_sub_submenu_array=array();
        $submenu_array=array();
        $menu_array=array();
        $sub_menu_menus=array();
        $length=count($URSRC_submenu);
        $sub_menu1_length=count($URSRC_sub_submenu);
        $URSRC_checkbox_basicrole=$_POST['URSRC_cb_basicroles1'];
        $URSRC_checkbox_basicrole=str_replace("_"," ",$URSRC_checkbox_basicrole);
        $URSRC_rd_basicrole=$_POST['URSRC_radio_basicroles1'];
        $URSRC_rd_basicrole=str_replace("_"," ",$URSRC_rd_basicrole);
        $projectid;
        $id;
        $ids;
        $flag=0;
        for($i=0;$i<$length;$i++){
            if (!(preg_match('/&&/',$URSRC_submenu[$i])))
            {
                $sub_menu_menus[]=$URSRC_submenu[$i];
            }
        }
        if($sub_menu1_length!=0){
            for($j=0;$j<$sub_menu1_length;$j++){
                $sub_menu_menus[]=$URSRC_sub_submenu[$j];
            }
        }
        for($j=0;$j<count($sub_menu_menus);$j++){
            if($j==0){
                $id=$sub_menu_menus[$j];
            }
            else{
                $id=$id .",".$sub_menu_menus[$j];
            }
        }
        if($URSRC_radio_button_select_value=="ROLE CREATION"){
            $result = $con->query("CALL SP_TS_ROLE_CREATION_INSERT('$URSRC_customrolename','$URSRC_basicrole','$id','$USERSTAMP','timesheet',@ROLE_CRTNINSRTFLAG)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CRTNINSRTFLAG');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CRTNINSRTFLAG'];
            echo $flag;
        }
        else if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"||$URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
            $length=count($URSRC_checkbox_basicrole);
            $URSRC_checkbox_basicrole_array;
            for($i=0;$i<$length;$i++){
                if($i==0){
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole[$i];
                }
                else{
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole_array .",".$URSRC_checkbox_basicrole[$i];
                }
            }
            if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_SAVE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PROFILESAVEFLAG)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PROFILESAVEFLAG');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PROFILESAVEFLAG'];
                echo $flag;
            }
            else if($URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_UPDATE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PRFUPDATE)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PRFUPDATE');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PRFUPDATE'];
                echo $flag;
            }
        }
        else{
            $result = $con->query("CALL SP_TS_ROLE_CREATION_UPDATE('$URSRC_customrolenameupd','$URSRC_basicrole','$id','$USERSTAMP','timesheet',@ROLE_CREATIONUPDATE)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CREATIONUPDATE');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CREATIONUPDATE'];
            echo $flag;
        }
    }
    //BASIC ROLE MENU CREATION URSRC_check_basicrole
    if($_REQUEST['option']=='URSRC_check_basicrolemenu')
    {
        $role=$_REQUEST['URSRC_basicradio_value'];
        $role=str_replace("_"," ",$role);
        $URSRC_select_check_basicrole_menu=mysqli_query($con,"select * from BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='$role'");
        $row=mysqli_num_rows($URSRC_select_check_basicrole_menu);
        $x=$row;
        if($x > 0)
        {
            $URSRC_check_basicrole_menu=0;//TRUE
        }
        else{
            $URSRC_check_basicrole_menu=1;//FALSE
        }
        echo ($URSRC_check_basicrole_menu);
    }
    //FUNCTION to get basic role menus
    if($_REQUEST['option']=="URSRC_loadbasicrole_menu"){
        $URSRC_basicrole_values_array=array();
        $URSRC_basic_roleval=$_REQUEST['URSRC_basicradio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$URSRC_basic_roleval);
        $URSRC_basicrole_menu_array=array();
        $URSRC_basicroleid_array=array();
        $URSRC_select_basicrole_menu= "select * from USER_RIGHTS_CONFIGURATION URC,BASIC_MENU_PROFILE BMP where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."'";
        $URSRC_basicrole_menu_rs=mysqli_query($con,$URSRC_select_basicrole_menu);
        while($row=mysqli_fetch_array($URSRC_basicrole_menu_rs)){
            $URSRC_basicrole_menu_array[]=$row["MP_ID"];
        }
        $select_basicrole_id= "select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basic_roleval."' and URC.URC_ID=BRP.URC_ID";
        $URSRC_basicroleid_rs=mysqli_query($con,$select_basicrole_id);
        while($row=mysqli_fetch_array($URSRC_basicroleid_rs)){
            $URSRC_basicroleid_array=$row["BRP_BR_ID"];
        }
        $URSRC_basicrole_array=array();

        for($i=0;$i<count($URSRC_basicroleid_array);$i++){
            $select_basicrole=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
            while($row=mysqli_fetch_array($select_basicrole)){
                $URSRC_basicrole_array[]=$row["URC_DATA"];
            }
        }
        //UNIQUE FUNCTION
        $URSRC_basicrole_array=array_values(array_unique($URSRC_basicrole_array));
        $value_array=array($URSRC_basicrole_menu_array,$URSRC_basicrole_array);
        $URSRC_basicrole_values_array[]=($value_array);
//        $URSRC_getmenu_folder_values=URSRC_getmenu_folder($URSRC_basic_roleval);
        $URSRC_getmenu_folder_values=  URSRC_getmenubasic_folder();
        $URSRC_basicrole_values_array[]=[$URSRC_getmenu_folder_values,$value_array];
        echo JSON_ENCODE($URSRC_basicrole_values_array);
    }
    //FUNCTION to get role menus
    if($_REQUEST['option']=="URSRC_tree_view"){
        $menunameradiovalues = $_GET['radio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$menunameradiovalues);
        $URSRC_getmenu_folder_values=URSRC_getmenu_folder($URSRC_basic_roleval);
        echo JSON_ENCODE($URSRC_getmenu_folder_values);
    }
    //FUNCTION TO LOAD INITIAL VALUES ROLE LST bX
    if($_REQUEST['option']=="ACCESS_RIGHTS_SEARCH_UPDATE_BASICROLE"){
        $URSRC_role_array=get_roles();
        echo JSON_ENCODE($URSRC_role_array);
    }
}
function URSRC_calendar_create($loginid_name,$URSC_uld_id,$finaldate,$calenderid){

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
                $cal = new Google_Service_Calendar($drive);
            $event = new Google_Service_Calendar_Event();
            $event->setsummary($loginid_name.'  '.'JOIN DATE');
            $event->setDescription($URSC_uld_id);
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDate($finaldate);//setDate('2014-11-18');
            $event->setStart($start);
            $event->setEnd($start);
            $createdEvent = $cal->events->insert($calenderid, $event);




}
function URSRC_delete_create_calendarevent($ULD_id,$loginid_name,$finaldate){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;

    $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
    if($row=mysqli_fetch_array($select_calenderid)){
        $calenderid=$row["URC_DATA"];
    }
    $drive = new Google_Client();
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);

    $cal = new Google_Service_Calendar($drive);
    $service = new Google_Service_Calendar($drive);
    $events = $service->events->listEvents($calenderid);
    while(true) {
        foreach ($events->getItems() as $newevent) {
            $desc=$newevent->getDescription();
            if($desc==$ULD_id){
                $event_id=$newevent->getId();
                $service->events->delete($calenderid,$event_id);
            }
//        echo $event->

        }
        $pageToken = $events->getNextPageToken();
        if ($pageToken) {
            $optParams = array('pageToken' => $pageToken);
            $events = $service->events->listEvents($calenderid, $optParams);
        } else {
            break;
        }
    }
    URSRC_calendar_create($loginid_name,$ULD_id,$finaldate,$calenderid);



}
//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenu_folder($URSRC_basic_roleval){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}

//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenubasic_folder(){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID  ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID  and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID  and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}

?>