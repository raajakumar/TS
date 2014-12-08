<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL:SEARCH/UPDATE/DELETE*********************************************//
//DONE BY:LALITHA
//VER 0.02-SD:17/11/2014 ED:18/11/2014,TRACKER NO:93,Updated Mutiple String replaced function fr mail sending details
//VER 0.01-INITIAL VERSION, SD:07/10/2014 ED:10/10/2014,TRACKER NO:93
//*********************************************************************************************************//
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING INITIAL DATAS OF EMP NAME ND ERR MSG
    if($_REQUEST['option']=="INITIAL_DATAS"){
        $EMPSRC_UPD_DEL_errmsg=get_error_msg_arry();
        $EMPSRC_UPD_DEL_employeename_array=array();
        $EMPSRC_UPD_DEL_empname_result=mysqli_query($con,"SELECT DISTINCT EMP_FIRST_NAME,EMP_LAST_NAME FROM EMPLOYEE_DETAILS order by EMP_FIRST_NAME ");
        while($row=mysqli_fetch_array($EMPSRC_UPD_DEL_empname_result)){
            $EMPSRC_UPD_DEL_empfrstname=$row["EMP_FIRST_NAME"];
            $EMPSRC_UPD_DEL_empname=$row["EMP_LAST_NAME"];
            $EMPSRC_UPD_DEL_employeename_array[]=array($EMPSRC_UPD_DEL_empfrstname.'_'.$EMPSRC_UPD_DEL_empname);
        }
        $EMPSRC_UPD_DEL_final_values=array($EMPSRC_UPD_DEL_employeename_array,$EMPSRC_UPD_DEL_errmsg);
        echo json_encode($EMPSRC_UPD_DEL_final_values);
    }
//FUNCTION FOR TO UPDATE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="EMPLOYDETAILS_FLEXTBLE"){
        $EMPSRC_UPD_DEL_employeename=$_POST['EMPSRC_UPD_DEL_lb_empname'];
        $EMPSRC_UPD_DEL_splitempname =explode("_", $EMPSRC_UPD_DEL_employeename);
        $EMPSRC_UPD_DEL_firstname = $EMPSRC_UPD_DEL_splitempname[0];
        $EMPSRC_UPD_DEL_lastname = $EMPSRC_UPD_DEL_splitempname[1];
        $EMPSRC_UPD_DEL_flextbl= mysqli_query($con,"SELECT DISTINCT EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,EMP.EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,EMP.EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP FROM EMPLOYEE_DETAILS EMP left join COMPANY_PROPERTIES_DETAILS CPD on EMP.EMP_ID=CPD.EMP_ID,USER_LOGIN_DETAILS ULD  WHERE  (EMP.EMP_FIRST_NAME ='$EMPSRC_UPD_DEL_firstname' AND EMP.EMP_LAST_NAME ='$EMPSRC_UPD_DEL_lastname') AND  EMP.EMP_USERSTAMP_ID=ULD.ULD_ID ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        $ure_values=array();
        while($row=mysqli_fetch_array($EMPSRC_UPD_DEL_flextbl)){
            $EMPSRC_UPD_DEL_empfrstname=$row["EMP_FIRST_NAME"];
            $EMPSRC_UPD_DEL_emplastname=$row["EMP_LAST_NAME"];
            $EMPSRC_UPD_DEL_dob=$row["EMP_DOB"];
            $EMPSRC_UPD_DEL_desig=$row["EMP_DESIGNATION"];
            $EMPSRC_UPD_DEL_mblno=$row["EMP_MOBILE_NUMBER"];
            $EMPSRC_UPD_DEL_kinname=$row["EMP_NEXT_KIN_NAME"];
            $EMPSRC_UPD_DEL_reltnhd=$row["EMP_RELATIONHOOD"];
            $EMPSRC_UPD_DEL_altmoblno=$row["EMP_ALT_MOBILE_NO"];
            $EMPSRC_UPD_DEL_lapno=$row["CPD_LAPTOP_NUMBER"];
            $EMPSRC_UPD_DEL_chrgrno=$row["CPD_CHARGER_NUMBER"];
            $EMPSRC_UPD_DEL_bagno=$row["CPD_LAPTOP_BAG"];
            $EMPSRC_UPD_DEL_mouse=$row["CPD_MOUSE"];
            $EMPSRC_UPD_DEL_draccs=$row["CPD_DOOR_ACCESS"];
            $EMPSRC_UPD_DEL_idcrd=$row["CPD_ID_CARD"];
            $EMPSRC_UPD_DEL_userstamp=$row["ULD_LOGINID"];
            $EMPSRC_UPD_DEL_hdset=$row["CPD_HEADSET"];
            $EMPSRC_UPD_DEL_timestmp=$row["EMP_TIMESTAMP"];
            $EMPSRC_UPD_DEL_id=$row['EMP_ID'];
            $final_values=(object) ['id'=>$EMPSRC_UPD_DEL_id,'EMPSRC_UPD_DEL_employeefirstname' =>$EMPSRC_UPD_DEL_empfrstname,'EMPSRC_UPD_DEL_employeelastname' =>$EMPSRC_UPD_DEL_emplastname,'EMPSRC_UPD_DEL_dteofbrth'=>$EMPSRC_UPD_DEL_dob,'EMPSRC_UPD_DEL_designation'=>$EMPSRC_UPD_DEL_desig,'EMPSRC_UPD_DEL_mobileno'=>$EMPSRC_UPD_DEL_mblno,'EMPSRC_UPD_DEL__kin_name'=>$EMPSRC_UPD_DEL_kinname,'EMPSRC_UPD_DEL_relation_hood'=>$EMPSRC_UPD_DEL_reltnhd,'EMPSRC_UPD_DEL_alt_mbelno'=>$EMPSRC_UPD_DEL_altmoblno,'EMPSRC_UPD_DEL_lap_no'=>$EMPSRC_UPD_DEL_lapno,'EMPSRC_UPD_DEL_charger_no'=>$EMPSRC_UPD_DEL_chrgrno,'EMPSRC_UPD_DEL_bag_no'=>$EMPSRC_UPD_DEL_bagno,'EMPSRC_UPD_DEL_mouse'=>$EMPSRC_UPD_DEL_mouse,'EMPSRC_UPD_DEL_dooraccess'=>$EMPSRC_UPD_DEL_draccs,'EMPSRC_UPD_DEL_id_card'=>$EMPSRC_UPD_DEL_idcrd,'EMPSRC_UPD_DEL_headset'=>$EMPSRC_UPD_DEL_hdset,'$EMPSRC_UPD_DEL_user_stamp'=>$EMPSRC_UPD_DEL_userstamp,'EMPSRC_UPD_DEL_timestamp'=>$EMPSRC_UPD_DEL_timestmp];
            $ure_values[]=$final_values;
        }
        echo JSON_ENCODE($ure_values);
    }
    //FUNCTION FOR TO UPDATE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="EMPLOYDETAILS_UPDATE"){
        $EMP_ENTRY_radioid=$_POST['EMPSRC_UPD_DEL_rd_flxtbl'];
        $EMPSRC_UPD_DEL_firstname=$_POST['EMPSRC_UPD_DEL_tb_firstname'];
        $EMPSRC_UPD_DEL_lastname=$_POST['EMPSRC_UPD_DEL_tb_lastname'];
        $EMPSRC_UPD_DEL_concat=$EMPSRC_UPD_DEL_firstname.' '.$EMPSRC_UPD_DEL_lastname;
        $EMPSRC_UPD_DEL_dob=$_POST['EMPSRC_UPD_DEL_tb_dob'];
        $EMPSRC_UPD_DEL_finaldob = date('Y-m-d',strtotime($EMPSRC_UPD_DEL_dob));
        $EMPSRC_UPD_DEL_designation=$_POST['EMPSRC_UPD_DEL_tb_designation'];
        $EMPSRC_UPD_DEL_Mobileno=$_POST['EMPSRC_UPD_DEL_tb_permobile'];
        $EMPSRC_UPD_DEL_kinname=$_POST['EMPSRC_UPD_DEL_tb_kinname'];
        $EMPSRC_UPD_DEL_relationhd=$_POST['EMPSRC_UPD_DEL_tb_relationhd'];
        $EMPSRC_UPD_DEL_mobile=$_POST['EMPSRC_UPD_DEL_tb_mobile'];
        $EMPSRC_UPD_DEL_laptopno=$_POST['EMPSRC_UPD_DEL_tb_laptopno'];
        $EEMPSRC_UPD_DEL_chrgrno=$_POST['EMPSRC_UPD_DEL_tb_chargerno'];
        $EMPSRC_UPD_DEL_bag=$_POST['EMPSRC_UPD_DEL_chk_bag'];
        if($EMPSRC_UPD_DEL_bag=='on')
        {
            $EMPSRC_UPD_DEL_bag= 'X';
            $bag='YES';
        }
        else
        {
            $EMPSRC_UPD_DEL_bag='';
            $bag='NO';
        }
        $EMPSRC_UPD_DEL_mouse=$_POST['EMPSRC_UPD_DEL_chk_mouse'];
        if($EMPSRC_UPD_DEL_mouse=='on')
        {
            $EMPSRC_UPD_DEL_mouse= 'X';
            $mouse='YES';
        }
        else
        {
            $EMPSRC_UPD_DEL_mouse='';
            $mouse='NO';
        }
        $EMPSRC_UPD_DEL_dooracess=$_POST['EMPSRC_UPD_DEL_chk_dracess'];
        if($EMPSRC_UPD_DEL_dooracess=='on')
        {
            $EMPSRC_UPD_DEL_dooracess= 'X';
            $dooraccess='YES';
        }
        else
        {
            $EMPSRC_UPD_DEL_dooracess='';
            $dooraccess='NO';
        }
        $EMPSRC_UPD_DEL_idcard=$_POST['EMPSRC_UPD_DEL_chk_idcrd'];
        if($EMPSRC_UPD_DEL_idcard=='on')
        {
            $EMPSRC_UPD_DEL_idcard= 'X';
            $idcard='YES';
        }
        else
        {
            $EMPSRC_UPD_DEL_idcard='';
            $idcard='NO';
        }
        $EMPSRC_UPD_DEL_headset=$_POST['EMPSRC_UPD_DEL_chk_headset'];
        if($EMPSRC_UPD_DEL_headset=='on')
        {
            $EMPSRC_UPD_DEL_headset= 'X';
            $headset='YES';
        }
        else
        {
            $EMPSRC_UPD_DEL_headset='';
            $headset='NO';
        }
        $result = $con->query("CALL SP_TS_EMPLOYEE_DETAILS_SEARCH_UPDATE($EMP_ENTRY_radioid,'$EMPSRC_UPD_DEL_firstname','$EMPSRC_UPD_DEL_lastname','$EMPSRC_UPD_DEL_finaldob','$EMPSRC_UPD_DEL_designation','$EMPSRC_UPD_DEL_Mobileno','$EMPSRC_UPD_DEL_kinname','$EMPSRC_UPD_DEL_relationhd','$EMPSRC_UPD_DEL_mobile','$EMPSRC_UPD_DEL_laptopno','$EEMPSRC_UPD_DEL_chrgrno','$EMPSRC_UPD_DEL_bag','$EMPSRC_UPD_DEL_mouse','$EMPSRC_UPD_DEL_dooracess','$EMPSRC_UPD_DEL_idcard','$EMPSRC_UPD_DEL_headset','$USERSTAMP',@EMP_UPDATE_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @EMP_UPDATE_FLAG');
        $result = $select->fetch_assoc();
        $flag= $result['@EMP_UPDATE_FLAG'];
        if ($flag==1){
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
            $admin_name = substr($admin, 0, strpos($admin, '.'));
            $sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
            $spladminname=$admin_name.'/'.$sadmin_name;
            $spladminname=strtoupper($spladminname);
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=10";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $select_loginid="select * from EMPLOYEE_DETAILS ED,USER_LOGIN_DETAILS ULD WHERE ED.ULD_ID = ULD.ULD_ID AND ED.EMP_ID='$EMP_ENTRY_radioid'";
            $select_loginid_rs=mysqli_query($con,$select_loginid);
            if($row=mysqli_fetch_array($select_loginid_rs)){
                $loginid=$row["ULD_LOGINID"];

            }

//STRING REPLACE FUNCTION
            $email_body;
            $body_msg =explode(",", $body);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $email_body.=$body_msg[$i].'<br><br>';
            }
            $replace= array("[LOGINID]", "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]");
            $str_replaced  = array($EMPSRC_UPD_DEL_concat,$EMPSRC_UPD_DEL_firstname, $EMPSRC_UPD_DEL_lastname, $EMPSRC_UPD_DEL_finaldob,$EMPSRC_UPD_DEL_designation,$EMPSRC_UPD_DEL_Mobileno,$EMPSRC_UPD_DEL_kinname,$EMPSRC_UPD_DEL_relationhd,$EMPSRC_UPD_DEL_mobile,$EMPSRC_UPD_DEL_laptopno,$EEMPSRC_UPD_DEL_chrgrno,$bag,$mouse,$dooraccess,$idcard,$headset);
            $newphrase = str_replace($replace, $str_replaced, $email_body);
            //SENDING MAIL OPTIONS
            $mail_options = [
                "sender" => $admin,
                "to" => $admin,
                "cc" => 'safiyullah.mohideen@ssomens.com',
//                "cc" => $sadmin,
                "subject" => $mail_subject,
                "htmlBody" => $newphrase
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
}
//GET ERR MSG
function get_error_msg_arry(){
    global $con;
    $errormessages=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (1,2,17,70,76,77,78)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessages[]=$row["EMC_DATA"];
    }
    return $errormessages;
}
?>