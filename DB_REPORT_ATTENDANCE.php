<?php
include "CONNECTION.php";
include "GET_USERSTAMP.php";
include "COMMON.php";


if($_REQUEST["option"]=="search_option"){
//    mysqli_connect($con);
    $loginid=get_active_login_id();
    $err_msg=get_error_msg('18');
    $select_query="SELECT * FROM REPORT_CONFIGURATION WHERE RC_ID IN (1,2,6) ORDER BY RC_DATA";
    $result=mysqli_query($con,$select_query);

    $get_report_array=array();
    while($row=mysqli_fetch_array($result)){
        $get_report_array[]=array($row["RC_DATA"],$row["RC_ID"]);
    }

    $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmin_date)){
        $admin_searchmin_date_value=$row["UARD_DATE"];
        $min_date = $admin_searchmin_date_value;//date('d-m-Y',strtotime($admin_searchmin_date_value));
    }
    $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmax_date)){
        $admin_searchmax_date_value=$row["UARD_DATE"];
        $max_date= $admin_searchmax_date_value;//date('d-m-Y',strtotime($admin_searchmax_date_value));
    }

    $final_array=array($loginid,$err_msg,$get_report_array,$min_date,$max_date);
//    mysqli_close($con);
    echo JSON_ENCODE($final_array);

}
if($_REQUEST["option"]=="login_id"){
    $login_id=$_REQUEST['login_id'];
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
    while($row=mysqli_fetch_array($uld_id)){
        $uld_id=$row["ULD_ID"];
    }
    $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$uld_id' ");
    while($row=mysqli_fetch_array($admin_searchmin_date)){
        $admin_searchmin_date_value=$row["UARD_DATE"];
        $admin_min_date =$admin_searchmin_date_value;// date('F-Y',strtotime($admin_searchmin_date_value));
    }
    $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$uld_id' ");
    while($row=mysqli_fetch_array($admin_searchmax_date)){
        $admin_searchmax_date_value=$row["UARD_DATE"];
        $admin_max_date= $admin_searchmax_date_value;//date('F-Y',strtotime($admin_searchmax_date_value));
    }

    $finalvalue=array($admin_min_date,$admin_max_date);
//    mysqli_close($con);
    echo JSON_ENCODE($finalvalue);

}





if($_REQUEST["option"]=="6"){



    $date=$_REQUEST["date"];
    $result = $con->query("CALL SP_TS_REPORT_COUNT_ABSENT_FLAG('$date','$UserStamp',@TEMP_USER_ABSENT_COUNT)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
    $select_data="select * from $temp_table_name order by UNAME ";
    $select_data_rs=mysqli_query($con,$select_data);
    $row=mysqli_num_rows($select_data_rs);
    $x=$row;
    $values_array=array();
    while($row=mysqli_fetch_array($select_data_rs)){
        $name=$row['UNAME'];
        $count_value=$row['ABSENT_COUNT'];
        $final_values=array('name'=>$name,'absent_count' => $count_value);
        $values_array[]=$final_values;
    }
    $drop_query="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//    mysqli_close($con);
    echo   JSON_ENCODE($values_array);}

if($_REQUEST["option"]=="2")
{
    $date=$_REQUEST["date"];
    $result = $con->query("CALL SP_TS_ATTENDANCE_CALCULATION ('$date','','$UserStamp',@TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS,@WORKING_DAYS)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
    $total_days=$result['@TOTAL_DAYS'];
    $select_data="select * from $temp_table_name order by LOGINID asc ";
    $select_data_rs=mysqli_query($con,$select_data);
    $row=mysqli_num_rows($select_data_rs);
    $x=$row;
    $values_array=array();
    while($row=mysqli_fetch_array($select_data_rs)){
        $total_working_days=$row['NO_OF_DAYS'];
        $no_of_present=$row['NO_OF_PRESENT'];
        $no_of_absent=$row['NO_OF_ABSENT'];
        $no_of_onduty=$row['NO_OF_ONDUTY'];
        $permission=$row['PERMISSION_HRS'];
        $login_id=$row['LOGINID'];
        $final_values=array('total_days'=>$total_days,'total_working_days'=>$total_working_days,'present_count' => $no_of_present,'absent_count' => $no_of_absent,'onduty_count' => $no_of_onduty,'permission_count' => $permission,'loginid' => $login_id);
        $values_array[]=$final_values;
    }
    $drop_query="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//    mysqli_close($con);
    echo   JSON_ENCODE($values_array);
}
if($_REQUEST["option"]=="1")
{
    $date=$_REQUEST["date"];
    $loginid=$_REQUEST["loginid"];
    $result = $con->query("CALL SP_TS_ATTENDANCE_CALCULATION ('$date','$loginid','$UserStamp',@TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS,@WORKING_DAYS)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS,@WORKING_DAYS');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
    $today_no_days=$result['@TOTAL_DAYS'];
    $working_day=$result['@WORKING_DAYS'];
    $select_data="select * from $temp_table_name";
    $select_data_rs=mysqli_query($con,$select_data);
    $row=mysqli_num_rows($select_data_rs);
    $x=$row;
    $values_array=array();
    while($row=mysqli_fetch_array($select_data_rs)){
        $report_date=$row['REPORT_DATE'];
        $present=$row['PRESENT'];
        $absent=$row['ABSENT'];
        $onduty=$row['ONDUTY'];
        $permission=$row['PERMISSION_HRS'];
        $final_values=array('today_no_days'=>$today_no_days,'working_day'=>$working_day,'reportdate'=>$report_date,'presents' => $present,'absents' => $absent,'ondutys' => $onduty,'permission_counts' => $permission);
        $values_array[]=$final_values;
    }
    $drop_query="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//    mysqli_close($con);
    echo JSON_ENCODE($values_array);
}






