<?php
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR INITIAL LISTBX
    if($_REQUEST['option']=="common")
    {
// GET ERR MSG
        $REP_BND_errmsg=get_error_msg("15,18,83");
// REPORT CONFIGURATION LIST
        $REP_BND_report_config = mysqli_query($con,"SELECT * FROM REPORT_CONFIGURATION WHERE CGN_ID=11");
        $REP_BND_rprtconfiglist=array();
        while($row=mysqli_fetch_array($REP_BND_report_config)){
            $REP_BND_rprtconfiglist[]=array($row["RC_DATA"],$row["RC_ID"]);
        }
// ACTIVE EMPLOYEE LIST
        $REP_BND_active_emp=get_active_login_id();
// NONACTIVE EMPLOYEE LIST
        $REP_BND_active_nonemp=get_nonactive_login_id();
        $REP_BND_final_values=array($REP_BND_rprtconfiglist,$REP_BND_active_emp,$REP_BND_active_nonemp,$REP_BND_errmsg);
        echo JSON_ENCODE($REP_BND_final_values);
    }
//SETTING MIN ND MAX DATE FOR DATE PICKER WITH LOGIN ID OPTION
    if($_REQUEST["option"]=="minmax_dtewth_loginid"){
        $login_id=$_REQUEST['REP_BND_loginid'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
        while($row=mysqli_fetch_array($uld_id)){
            $ADM_uld_id=$row["ULD_ID"];
        }
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ADM_uld_id' ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["UARD_DATE"];
            $admin_min_date = $admin_searchmin_date_value;
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ADM_uld_id' ");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["UARD_DATE"];
            $admin_max_date= $admin_searchmax_date_value;
        }
        $finalvalue=array($admin_min_date,$admin_max_date);
        echo JSON_ENCODE($finalvalue);
    }
    //SETTING MIN ND MAX DATE FOR DATE PICKER WITH BANDWIDTH BY MONTH OPTION
    if($_REQUEST["option"]=="minmax_dtewth_monthyr"){
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS ORDER BY UARD_DATE ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["UARD_DATE"];
            $admin_min_date = $admin_searchmin_date_value;
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS ORDER BY UARD_DATE ");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["UARD_DATE"];
            $admin_max_date= $admin_searchmax_date_value;
        }
        $finalvalue=array($admin_min_date,$admin_max_date);
        echo JSON_ENCODE($finalvalue);
    }
    //FETCHING DATA TABLE FRM DB FOR ACTIVE ND NON ACTIVE EMP BANDWIDTH DETAILS
    if($_REQUEST['option']=="REP_BND_loginid_searchoption")
    {
        $REP_BND_loginid=$_REQUEST['REP_BND_loginid'];
        $REP_BND_monthyr=$_REQUEST['REP_BND_monthyear'];
        $result = $con->query("CALL SP_TS_REPORT_BANDWIDTH_CALCULATION('$REP_BND_monthyr','$REP_BND_loginid','$USERSTAMP',@TEMP_BANDWIDTH_CALCULATION)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_BANDWIDTH_CALCULATION');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_BANDWIDTH_CALCULATION'];
        $REP_BND_values=array();
        $sqlquery=mysqli_query($con,"SELECT * FROM $temp_table_name WHERE REPORT_DATE !='TOTAL'");
        $sqlquery_total=mysqli_query($con,"SELECT * FROM $temp_table_name WHERE REPORT_DATE ='TOTAL'");
        $values=false;
        if($row=mysqli_fetch_array($sqlquery_total))
        {
            $REP_BND_rptdte=$row['REPORT_DATE'];
            $REP_BND_bndwdth=$row['BANDWIDTH_MB'];
            $REP_BND_total=array('REP_BND_rptdte'=>$REP_BND_rptdte,'REP_BND_bndwdth'=>$REP_BND_bndwdth);

        }
        while($row=mysqli_fetch_array($sqlquery)){
            $REP_BND_rptdte=$row['REPORT_DATE'];
            $REP_BND_bndwdth=$row['BANDWIDTH_MB'];
            $REP_BND_values=array('REP_BND_rptdte'=>$REP_BND_rptdte,'REP_BND_bndwdth'=>$REP_BND_bndwdth);
            $values_array[]=$REP_BND_values;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        $values=array($values_array,$REP_BND_total);
        echo JSON_ENCODE($values);
    }
    //FETCHING DATA TABLE FRM DB FOR MONTH ND YEAR BW RECORDS
    if($_REQUEST['option']=="REP_BND_monthyear_searchoption")
    {
        $REP_BND_mnthyrval=$_REQUEST['REP_BND_db_selectmnth'];
        $REP_BND_loginid="null";
        $result = $con->query("CALL SP_TS_REPORT_BANDWIDTH_CALCULATION('$REP_BND_mnthyrval',$REP_BND_loginid,'$USERSTAMP',@TEMP_BANDWIDTH_CALCULATION)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_BANDWIDTH_CALCULATION');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_BANDWIDTH_CALCULATION'];
        $REP_BND_values=array();
        $REP_BND_total;
        $sqlquery=mysqli_query($con,"SELECT * FROM $temp_table_name WHERE LOGINID !='TOTAL'");
        $sqlquery_total=mysqli_query($con,"SELECT * FROM $temp_table_name WHERE LOGINID ='TOTAL'");
        $values=false;
        if($row=mysqli_fetch_array($sqlquery_total))
        {
            $REP_BND_lgnid=$row['LOGINID'];
            $REP_BND_bndwdth=$row['BANDWIDTH_MB'];
            $REP_BND_total=array('REP_BND_lgnid'=>$REP_BND_lgnid,'REP_BND_bndwdth'=>$REP_BND_bndwdth);

        }
        while($row=mysqli_fetch_array($sqlquery)){
            $REP_BND_lgnid=$row['LOGINID'];
            $REP_BND_bndwdth=$row['BANDWIDTH_MB'];
            $REP_BND_values=array('REP_BND_lgnid'=>$REP_BND_lgnid,'REP_BND_bndwdth'=>$REP_BND_bndwdth);
            $values_array[]=$REP_BND_values;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        $values=array($values_array,$REP_BND_total);
        echo JSON_ENCODE($values);
    }
}
?>