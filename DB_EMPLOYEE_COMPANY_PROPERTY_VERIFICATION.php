<?php
//*******************************************FILE DESCRIPTION*************************************************//
//***********************************************COMPANY PROPERTY VERFICATION*******************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:03/11/2014 ED:04/11/2014,TRACKER NO:97
//************************************************************************************************************//

require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    // FETCHING ERROR MESSAGE FROM SQL TABLE
    if($_REQUEST['option']=="INITIAL_DATAS"){
        // GET ERR MSG
        $CPVD_errmsg=get_error_msg('3,7,15');
//        $CPVD_final_values=array($CPVD_errmsg);
        $CPVD_act_employee=get_active_login_id();
    $final_array=array($CPVD_errmsg,$CPVD_act_employee);
        echo JSON_ENCODE($final_array);
    }
    //FETCHING COMPANY PROPERTIES DETAILS
    if($_REQUEST['option']=="COMPANY_PROPERTY")
    {
        $CPVD_loginid=$_REQUEST['CPVD_lb_loginid'];
        $CPVD_cmpny_prop=mysqli_query($con,"select CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER from EMPLOYEE_DETAILS ED JOIN COMPANY_PROPERTIES_DETAILS CPD on CPD.EMP_ID = ED.EMP_ID JOIN USER_LOGIN_DETAILS ULD ON ULD.ULD_ID = ED.ULD_ID where ULD.ULD_LOGINID ='$CPVD_loginid'");
        while($row=mysqli_fetch_array($CPVD_cmpny_prop)){
            $CPVD_lap_no=$row["CPD_LAPTOP_NUMBER"];
            $CPVD_charger_no=$row["CPD_CHARGER_NUMBER"];
        }
        $CPVD_cmpny_values=array('CPVD_lap_no'=>$CPVD_lap_no,'CPVD_charger_no'=>$CPVD_charger_no);
        $values_array[]=$CPVD_cmpny_values;
        echo JSON_ENCODE($values_array);
    }
    //FUNCTION FOR TO SAVE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="CMPNY_PROPETIES_SAVE"){
        $CPVD_lb_loginid=$_POST['CPVD_lb_loginid'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$CPVD_lb_loginid'");
        while($row=mysqli_fetch_array($uld_id)){
            $CPVD_uld_id=$row["ULD_ID"];
        }
        $emp_id=mysqli_query($con,"select EMP_ID from EMPLOYEE_DETAILS where ULD_ID='$CPVD_uld_id'");
        while($row=mysqli_fetch_array($emp_id)){
            $CPVD_emp_id=$row["EMP_ID"];
        }
        //COMPANY PROPTY ID
        $cpd_id=mysqli_query($con,"select CPD_ID from COMPANY_PROPERTIES_DETAILS where EMP_ID='$CPVD_emp_id'");
        while($row=mysqli_fetch_array($cpd_id)){
            $CPVD_cpd_id=$row["CPD_ID"];
        }
        //COMPANY PROP VERIFY LOGIN ID
        $CPVD_lb_chckdby=$_POST['CPVD_lb_chckdby'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$CPVD_lb_chckdby'");
        while($row=mysqli_fetch_array($uld_id)){
            $CPVD_uld_ids=$row["ULD_ID"];
        }
        $CPVD_ta_reason=$_POST['CPVD_ta_reason'];
        $CPVD_ta_reason= $con->real_escape_string($CPVD_ta_reason);
        $EMP_ENTRY_dob=$_POST['EMP_ENTRY_tb_dob'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $CPVD_uld_id=$row["ULD_ID"];
        }
        $sql="INSERT INTO COMPANY_PROP_VERIFY_DETAILS (EMP_ID,CPD_ID,CPVD_VERIFIED_BY,CPVD_COMMENTS,ULD_ID) VALUES ('$CPVD_emp_id','$CPVD_cpd_id','$CPVD_uld_ids','$CPVD_ta_reason','$CPVD_uld_id')";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }

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
        $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=9";
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
            $replace= array("[SADMIN]", "[LOGINID]","[CHECKEDBYID]", "[COMMENTS]");
            $str_replaced  = array($spladminname,$CPVD_lb_loginid, $CPVD_lb_chckdby,$CPVD_ta_reason);
            $main_body = str_replace($replace, $str_replaced, $email_body);
//        $main_body=str_replace("[SADMIN]","$spladminname",$body);
//        $main_body=str_replace("[LOGINID]","$CPVD_lb_loginid",$main_body);
//        $main_body=str_replace("[CHECKEDBYID]","$CPVD_lb_chckdby",$main_body);
//        $main_body=str_replace("[COMMENTS]","$CPVD_ta_reason",$main_body);


            $mail_options = [
                "sender" => $admin,
                "to" => $admin,
                "cc" => 'safiyullah.mohideen@ssomens.com',
//                "cc" => $sadmin,
                "subject" => $mail_subject,
                "htmlBody" => $main_body
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
if(isset($_REQUEST['option']) && $_REQUEST['option']!=''){
    $actionfunction = $_REQUEST['option'];
    call_user_func($actionfunction,$_REQUEST,$con);
}
//GET ACTIVE LOGIN ID
function showData($data,$con){
    $sql = "SELECT VW.ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID VW,EMPLOYEE_DETAILS ED,USER_LOGIN_DETAILS ULD WHERE ED.ULD_ID=ULD.ULD_ID AND ED.EMP_ID NOT IN (SELECT EMP_ID FROM  COMPANY_PROP_VERIFY_DETAILS) AND ULD.ULD_LOGINID=VW.ULD_LOGINID";
    $data = $con->query($sql);
    $str='<select name="dynamic_data">';
    $str.='<option>SELECT</option>';
    if($data->num_rows>0){
        while( $row = $data->fetch_array(MYSQLI_ASSOC)){
            $str .= '<option value="' .$row['ULD_LOGINID']. '">' .$row['ULD_LOGINID']. '</option>';
        }
    }
    echo $str;
}
?>