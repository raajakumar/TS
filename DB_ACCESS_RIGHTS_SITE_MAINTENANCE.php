<?php
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    global $con;
    //TREE VIEW
    if($_REQUEST['option']=="SITE_MAINTENANCE"){
        $USR_SITE_errmsg=get_error_msg_arry();
        $USR_SITE_select_usermenudetails=mysqli_query($con,"SELECT MP_ID FROM MENU_PROFILE WHERE MP_SCRIPT_FLAG IS NOT NULL");
        while($row=mysqli_fetch_array($USR_SITE_select_usermenudetails))
        {
            $USR_SITE_usermenudetails_array[]=$row["MP_ID"];
        }
        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE WHERE MP_ID NOT IN(1,2,3,4) ORDER BY MP_MNAME ASC");
        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];
            $sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUB , MP.MP_ID FROM MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC");
            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
                $sub_sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP.MP_ID,MP_MSUBMENU FROM MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL ORDER BY MP_MSUBMENU ASC");
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
        $USR_SITE_menuarray=array($URSC_Main_menu_array,$URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
        $final_values=array($USR_SITE_menuarray,$USR_SITE_usermenudetails_array,$USR_SITE_errmsg);
        echo json_encode($final_values);
    }
    //FUNCTION FOR TO UPDATE THE MENU PROFILE
    if($_REQUEST['option']=="USR_SITE_update"){
        $USR_SITE_menu=$_POST['menu'];
        $USR_SITE_menuid;
        $USR_SITE_sub_submenu=$_POST['Sub_menu1'];
        $USR_SITE_submenu=$_POST['Sub_menu'];
        $id;
        if($USR_SITE_menu==NULL && $USR_SITE_submenu==NULL && $USR_SITE_sub_submenu==NULL && $id==$id)
        {
            $id='null';
            $result = $con->query("CALL SP_TS_ACCESS_RIGHTS_SITE_MAINTENANCE($id)");
            echo $result;
        }
        else
        {
            $USR_SITE_sub_submenu_array=array();
            $submenu_array=array();
            $menu_array=array();
            $sub_menu_menus=array();
            $length=count($USR_SITE_submenu);
            $sub_menu1_length=count($USR_SITE_sub_submenu);
            $projectid;
            $ids;
            $flag=0;
            for($i=0;$i<$length;$i++){
                if (!(preg_match('/&&/',$USR_SITE_submenu[$i])))
                {
                    $sub_menu_menus[]=$USR_SITE_submenu[$i];
                }
            }
            if($sub_menu1_length!=0){
                for($j=0;$j<$sub_menu1_length;$j++){
                    $sub_menu_menus[]=$USR_SITE_sub_submenu[$j];
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
            $result = $con->query("CALL SP_TS_ACCESS_RIGHTS_SITE_MAINTENANCE('$id')");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            echo $result;
        }
    }
}
//GET ERR MSG
function get_error_msg_arry(){
    global $con;
    $errormessages=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (64,65)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessages[]=$row["EMC_DATA"];
    }
    return $errormessages;
}
?>