<?php
error_reporting(0);
if(isset($_REQUEST))
{
    include "CONNECTION.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    if($_REQUEST['option']=='SUBMIT')
    {
//        date_default_timezone_set("Europe/London");
        $rep_entryreport = $_REQUEST['AWRE_SRC_ta_enterreport'];
        $date=$_REQUEST['AWRE_SRC_tb_selectdate'];
        $rep_entrydate = date("Y-m-d",strtotime($date));
        $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='$USERSTAMP'";
        $result=mysqli_query($con,$query);
        while($row=mysqli_fetch_array($result)){
            $rep_getuld_id=$row["ULD_ID"];
        }
        $sql="INSERT INTO ADMIN_WEEKLY_REPORT_DETAILS (AWRD_REPORT,AWRD_DATE,ULD_ID)VALUES('$rep_entryreport','$rep_entrydate','$rep_getuld_id')";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='CHECK')
    {
        $date_array=array();
        $sql="SELECT * FROM ADMIN_WEEKLY_REPORT_DETAILS ";
        $sql_result= mysqli_query($con,$sql);
        while($row=mysqli_fetch_array($sql_result)){
            $date_array[]=$row["AWRD_DATE"];
        }
        echo JSON_ENCODE($date_array);
    }
}
?>