<?php
//error_reporting(0);
include "GET_USERSTAMP.php";
include "CONNECTION.php";
include "COMMON.php";


mysqli_report(MYSQLI_REPORT_STRICT);
try{

    $err_msg=get_error_msg('61');

        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID AND UA.UA_TERMINATE IS NULL ORDER BY MP_MNAME ASC");

    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];

        $sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUB from USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' ORDER BY MP_MSUB ASC");

        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=$row["MP_MSUB"];
            $sub_sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUBMENU,MP_MFILENAME,MP_SCRIPT_FLAG FROM USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' AND MP_MSUB='$URSC_sub_menu_row[$j]'  ORDER BY MP_MSUBMENU ASC");
            $URSC_sub_sub_menu_row_data=array();
            $script_flag=array();
            $file_name=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){

                $script_flag[]=$row["MP_SCRIPT_FLAG"];
                $file_name[]=$row["MP_MFILENAME"];
                if($row["MP_MSUBMENU"]==null||$row["MP_MSUBMENU"]=="")continue;
                $URSC_sub_sub_menu_row_data[]=$row["MP_MSUBMENU"];

            }
            $URSC_script_flag[]=$script_flag;
            $URSRC_filename[]=$file_name;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }

        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }

if(count($URSC_Main_menu_array)!=0){
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array,$URSC_script_flag,$URSRC_filename);    // $final = array($URSC_sub_menu_array,$URSC_sub_sub_menu_array,$URSC_sub_sub_menu_data_array);
}
    else{

        $final_values=array($URSC_Main_menu_array,$err_msg);
    }
    $_SESSION['menus']=$final_values;
//    mysqli_close($con);
    echo JSON_ENCODE($final_values);
}
catch (mysqli_sql_exception $e) {


    echo $e->getMessage();

}

?>