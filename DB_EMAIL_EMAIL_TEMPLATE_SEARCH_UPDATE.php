<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE SEARCH/UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:99
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    //FUNCTION TO FETCHING EMAIL TEMPLATE SCRIPT NAME,ERROR MESSAGE FROM SQL TABLE
    if($_REQUEST['option']=="INITIAL_DATAS"){
        // GET ERR MSG
        $ET_SRC_UPD_DEL_errmsg=get_error_msg('56,87,88,89');
        $ET_SRC_UPD_DEL_emltemp = mysqli_query($con,"SELECT ET_ID,ET_EMAIL_SCRIPT FROM EMAIL_TEMPLATE ORDER BY ET_EMAIL_SCRIPT");
        $ET_SRC_UPD_DEL_script_object=array();
        while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_emltemp)){
            $ET_SRC_UPD_DEL_script_object[]=array($row["ET_EMAIL_SCRIPT"],$row["ET_ID"]);
        }
        $ET_SRC_UPD_DEL_final_values=array($ET_SRC_UPD_DEL_script_object,$ET_SRC_UPD_DEL_errmsg);
        echo JSON_ENCODE($ET_SRC_UPD_DEL_final_values);
    }
    //FUNCTION FOR SHOW THE DATA IN TABLE
    if($_REQUEST['option']=="EMAIL_TEMPLATE_DETAILS"){
        $ET_SRC_UPD_DEL_scriptname=$_POST['ET_SRC_UPD_DEL_lb_scriptname'];
        $ET_SRC_UPD_DEL_flextbl= mysqli_query($con,"SELECT DATE_FORMAT(ETD.ETD_TIMESTAMP, '%d-%m-%Y %h:%m:%s') AS TIMESTAMP,ETD.ETD_EMAIL_SUBJECT,ETD.ETD_EMAIL_BODY,ULD.ULD_LOGINID,ETD.ETD_ID FROM EMAIL_TEMPLATE_DETAILS ETD,USER_LOGIN_DETAILS ULD WHERE ETD.ULD_ID=ULD.ULD_ID AND ETD.ET_ID='$ET_SRC_UPD_DEL_scriptname'");
        $ET_SRC_UPD_DEL_values=array();
        while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_flextbl)){
            $ET_SRC_UPD_DEL_subject=$row["ETD_EMAIL_SUBJECT"];
            $ET_SRC_UPD_DEL_body=$row["ETD_EMAIL_BODY"];
            $ET_SRC_UPD_DEL_userstamp=$row["ULD_LOGINID"];
            $ET_SRC_UPD_DEL_timestamp=$row["TIMESTAMP"];
            $ET_SRC_UPD_DEL_el_id=$row['ETD_ID'];
            $final_values=(object) ['id'=>$ET_SRC_UPD_DEL_el_id,'ET_SRC_UPD_DEL_subject' =>$ET_SRC_UPD_DEL_subject,'ET_SRC_UPD_DEL_body' =>$ET_SRC_UPD_DEL_body,'ET_SRC_UPD_DEL_userstamp'=>$ET_SRC_UPD_DEL_userstamp,'ET_SRC_UPD_DEL_timestamp'=>$ET_SRC_UPD_DEL_timestamp];
            $ET_SRC_UPD_DEL_values[]=$final_values;
        }
        echo JSON_ENCODE($ET_SRC_UPD_DEL_values);
    }
    //UPDATE DATA FOR EMAIL TEMPLATE TABLE
    if($_REQUEST['option']=="EMAIL_TEMPLATE_UPDATE"){
        $ET_SRC_UPD_DEL_el_id=$_POST['ET_SRC_UPD_DEL_rd_flxtbl'];
        $ET_SRC_UPD_DEL_subject=$_POST['ET_SRC_UPD_DEL_ta_updsubject'];
        $ET_SRC_UPD_DEL_subject= $con->real_escape_string($ET_SRC_UPD_DEL_subject);
        $ET_SRC_UPD_DEL_body=$_POST['ET_SRC_UPD_DEL_ta_updbody'];
        $ET_SRC_UPD_DEL_body= $con->real_escape_string($ET_SRC_UPD_DEL_body);
        $sql="UPDATE EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_SUBJECT='$ET_SRC_UPD_DEL_subject',ETD_EMAIL_BODY='$ET_SRC_UPD_DEL_body',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE ETD_ID='$ET_SRC_UPD_DEL_el_id' ";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
}
?>