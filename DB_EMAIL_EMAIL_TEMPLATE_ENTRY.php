<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE ENTRY*********************************************//
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
    //FUNCTION FOR ALREADY EXIST FOR SCRIPT NAME
    if($_REQUEST['option']=="ET_ENTRY_already_result"){
        $ET_ENTRY_scriptname=$_REQUEST['ET_ENTRY_scriptname'];
        $sql="SELECT ET_EMAIL_SCRIPT FROM EMAIL_TEMPLATE WHERE ET_EMAIL_SCRIPT='$ET_ENTRY_scriptname'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $ET_ENTRY_chkscriptname=1;
        }
        else{
            $ET_ENTRY_chkscriptname=0;
        }
        echo ($ET_ENTRY_chkscriptname);
    }
    //FUNCTION FOR TO SAVE THE EMAIL TEMPLATE
    if($_REQUEST['option']=="ET_ENTRY_insert"){
        $ET_ENTRY_scriptname=$_POST['ET_ENTRY_tb_scriptname'];
        $ET_ENTRY_subject=$_POST['ET_ENTRY_ta_subject'];
        $ET_ENTRY_subject= $con->real_escape_string($ET_ENTRY_subject);
        $ET_ENTRY_body=$_POST['ET_ENTRY_ta_body'];
        $ET_ENTRY_body= $con->real_escape_string($ET_ENTRY_body);
        $result = $con->query("CALL SP_TS_EMAIL_TEMPLATE_INSERT('$ET_ENTRY_scriptname','$ET_ENTRY_subject','$ET_ENTRY_body','$USERSTAMP',@EMAILINSERT_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @EMAILINSERT_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@EMAILINSERT_FLAG'];
        echo $return_flag;
    }
}
?>