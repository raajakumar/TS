<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.03-SD:17/11/2014 ED:18/11/2014,TRACKER NO:93,Updated Mutiple String replaced function fr mail sending details
//VER 0.02-SD:14/11/2014 ED:14/11/2014,TRACKER NO:79,Changed check bx name
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:79
//*********************************************************************************************************//
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING INITIAL DATAS
    if($_REQUEST['option']=="INITIAL_DATAS"){
        $EMP_ENTRY_errmsg=get_error_msg_arry();
        $get_loginid_array=array();
        $get_loginid_array_result=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' AND ULD_LOGINID IN (SELECT ULD_LOGINID FROM USER_LOGIN_DETAILS WHERE ULD_ID NOT IN (SELECT ULD_ID FROM EMPLOYEE_DETAILS)) ORDER BY ULD_LOGINID");
        while($row=mysqli_fetch_array($get_loginid_array_result))
        {
            $get_loginid_array[]=$row["ULD_LOGINID"];
        }
        $EMP_ENTRY_errmsg=get_error_msg_arry();
        $final_values=array($EMP_ENTRY_errmsg,$get_loginid_array);
        echo json_encode($final_values);
    }
//FUNCTION FOR TO SAVE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="EMPLOYDETAILS_SAVE"){
        $EMP_ENTRY_loginid=$_POST['EMP_ENTRY_tb_loginid'];
        $EMP_ENTRY_firstname=$_POST['EMP_ENTRY_tb_firstname'];
        $EMP_ENTRY_lastname=$_POST['EMP_ENTRY_tb_lastname'];
        $EMP_ENTRY_dob=$_POST['EMP_ENTRY_tb_dob'];
        $EMP_ENTRY_finaldob = date('Y-m-d',strtotime($EMP_ENTRY_dob));
        $EMP_ENTRY_designation=$_POST['EMP_ENTRY_tb_designation'];
        $EMP_ENTRY_Mobileno=$_POST['EMP_ENTRY_tb_permobile'];
        $EMP_ENTRY_kinname=$_POST['EMP_ENTRY_tb_kinname'];
        $EMP_ENTRY_relationhd=$_POST['EMP_ENTRY_tb_relationhd'];
        $EMP_ENTRY_mobile=$_POST['EMP_ENTRY_tb_mobile'];
        $EMP_ENTRY_laptopno=$_POST['EMP_ENTRY_tb_laptopno'];
        $EMP_ENTRY_chrgrno=$_POST['EMP_ENTRY_tb_chargerno'];
        $EMP_ENTRY_bag=$_POST['EMP_ENTRY_chk_bag'];
        if($EMP_ENTRY_bag=='on')
        {
            $EMP_ENTRY_bag= 'X';
            $bag='YES';
        }
        else
        {
            $EMP_ENTRY_bag='';
            $bag='NO';
        }
        $EMP_ENTRY_mouse=$_POST['EMP_ENTRY_chk_mouse'];
        if($EMP_ENTRY_mouse=='on')
        {
            $EMP_ENTRY_mouse= 'X';
            $mouse='YES';
        }
        else
        {
            $EMP_ENTRY_mouse='';
            $mouse='NO';
        }
        $EMP_ENTRY_dooracess=$_POST['EMP_ENTRY_chk_dracess'];
        if($EMP_ENTRY_dooracess=='on')
        {
            $EMP_ENTRY_dooracess= 'X';
            $dooraccess='YES';
        }
        else
        {
            $EMP_ENTRY_dooracess='';
            $dooraccess='NO';
        }
        $EMP_ENTRY_idcard=$_POST['EMP_ENTRY_chk_idcrd'];
        if($EMP_ENTRY_idcard=='on')
        {
            $EMP_ENTRY_idcard= 'X';
            $idcard='YES';
        }
        else
        {
            $EMP_ENTRY_idcard='';
            $idcard='NO';
        }
        $EMP_ENTRY_headset=$_POST['EMP_ENTRY_chk_headset'];
        if($EMP_ENTRY_headset=='on')
        {
            $EMP_ENTRY_headset= 'X';
            $headset='YES';
        }
        else
        {
            $EMP_ENTRY_headset='';
            $headset='NO';
        }
        //SP CALLING FOR SAV PART
        $result = $con->query("CALL SP_TS_EMPLOYEE_AND_COMPANY_PROPERTIES_DETAILS_INSERT('$EMP_ENTRY_loginid','$EMP_ENTRY_firstname','$EMP_ENTRY_lastname','$EMP_ENTRY_finaldob','$EMP_ENTRY_designation','$EMP_ENTRY_Mobileno','$EMP_ENTRY_kinname','$EMP_ENTRY_relationhd','$EMP_ENTRY_mobile','$EMP_ENTRY_laptopno','$EMP_ENTRY_chrgrno','$EMP_ENTRY_bag','$EMP_ENTRY_mouse','$EMP_ENTRY_dooracess','$EMP_ENTRY_idcard','$EMP_ENTRY_headset','$USERSTAMP',@EMP_INSERT_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @EMP_INSERT_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@EMP_INSERT_FLAG'];
        //RETURN FLAG ONE MEANS SEND MAIL
        if ($return_flag==1){
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
//STRING REPLACE FUNCTION
            $email_body;
            $body_msg =explode(",", $body);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $email_body.=$body_msg[$i].'<br><br>';
            }
            $replace= array("[LOGINID]", "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]");
            $str_replaced  = array($EMP_ENTRY_loginid,$EMP_ENTRY_firstname, $EMP_ENTRY_lastname, $EMP_ENTRY_finaldob,$EMP_ENTRY_designation,$EMP_ENTRY_Mobileno,$EMP_ENTRY_kinname,$EMP_ENTRY_relationhd,$EMP_ENTRY_mobile,$EMP_ENTRY_laptopno,$EMP_ENTRY_chrgrno,$bag,$mouse,$dooraccess,$idcard,$headset);
            $newphrase = str_replace($replace, $str_replaced, $email_body);
            //SENDING MAIL OPTIONS
            $mail_options = [
                "sender" => $admin,
                "to" => $admin,//$EMP_ENTRY_loginid,
                "cc" => 'safiyullah.mohideen@ssomens.com',//$admin,
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
        echo $return_flag;
    }
}
//GET ERR MSG
function get_error_msg_arry(){
    global $con;
    $errormessages=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (1,2,69,70,71,72)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessages[]=$row["EMC_DATA"];
    }
    return $errormessages;
}
?>