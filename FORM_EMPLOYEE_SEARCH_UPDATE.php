<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL:SEARCH/UPDATE/DELETE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:07/10/2014 ED:10/10/2014,TRACKER NO:93
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var EMPSRC_UPD_DEL_loginid=[];
var value_array=[];
var EMPSRC_UPD_DEL_errorAarray=[];
$('#EMPSRC_UPD_DEL_btn_search').hide();
//START DOCUMENT READY FUNCTION
$(document).ready(function(){
    $(".preloader").show();
    $('#EMPSRC_UPD_DEL_btn_search').hide();
    $("#EMPSRC_UPD_DEL_btn_update").attr("disabled","disabled");
    //CALLING FUNCTION FOR EMPLOYEENAME LISTBX
    EMPSRC_UPD_DEL_employeename_listbx()
    //SUCCESS FUNCTION FOR LOADING EMPLOYEE NAME LISTBX
    function EMPSRC_UPD_DEL_employeename_listbx()
    {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                value_array=JSON.parse(xmlhttp.responseText);
                // EMP_ENTRY_errorAarray=value_array[0];
                EMPSRC_UPD_DEL_loginid=value_array[0];
                EMPSRC_UPD_DEL_errorAarray=value_array[1];
                if(EMPSRC_UPD_DEL_loginid!='')
                {
                    //GET EMPLOYEE NAME
                    var EMPSRC_UPD_DEL_login_list='<option>SELECT</option>';
                    for (var i=0;i<EMPSRC_UPD_DEL_loginid.length;i++) {
                        var EMPSRC_UPD_DEL_employeenameconcat=EMPSRC_UPD_DEL_loginid[i].toString().split("_");
                        EMPSRC_UPD_DEL_login_list += '<option value="' + EMPSRC_UPD_DEL_loginid[i]  + '">' +EMPSRC_UPD_DEL_employeenameconcat[0]+" "+EMPSRC_UPD_DEL_employeenameconcat[1] +  '</option>';
                    }
                    $('#EMPSRC_UPD_DEL_lb_empname').html(EMPSRC_UPD_DEL_login_list);
                    $('#EMPSRC_UPD_DEL_lbl_empname').show();
                    $('#EMPSRC_UPD_DEL_lb_empname').show();
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_lbl_empname').hide();
                    $('#EMPSRC_UPD_DEL_lbl_noempname').text(EMPSRC_UPD_DEL_errorAarray[5]).show()
                    $('#EMPSRC_UPD_DEL_lb_empname').hide();
                }
                $(".title_alpha").prop("title",EMPSRC_UPD_DEL_errorAarray[0]);
                $(".title_nos").prop("title",EMPSRC_UPD_DEL_errorAarray[1]);
            }
        }
        var option="INITIAL_DATAS";
        xmlhttp.open("GET","DB_EMPLOYEE_SEARCH_UPDATE.do?option="+option);
        xmlhttp.send();
    }
    //DO VALIDATION START
    $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
    $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
    $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
    $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
//DO VALIDATION END
    //SET DOB DATEPICKER
    var EMPSRC_UPD_DEL_d = new Date();
    var EMPSRC_UPD_DEL_year = EMPSRC_UPD_DEL_d.getFullYear() - 18;
    EMPSRC_UPD_DEL_d.setFullYear(EMPSRC_UPD_DEL_year);
    $('#EMPSRC_UPD_DEL_tb_dob').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true,
            yearRange: '1920:' + EMPSRC_UPD_DEL_year + '',
            defaultDate: EMPSRC_UPD_DEL_d
        });
    var EMPSRC_UPD_DEL_pass_changedmonth=new Date(EMPSRC_UPD_DEL_d.setFullYear(EMPSRC_UPD_DEL_year));
    $('#EMPSRC_UPD_DEL_tb_dob').datepicker("option","maxDate",EMPSRC_UPD_DEL_pass_changedmonth);
//END DATE PICKER FUNCTION
    //CHANGE FUNCTION FOR LOGIN ID
    var EMPSRC_UPD_DEL_empfirstname;
    var EMPSRC_UPD_DEL_emplastname;
    var EMPSRC_UPD_DEL_dob;
    var EMPSRC_UPD_DEL_desig;
    var EMPSRC_UPD_DEL_mblno;
    var EMPSRC_UPD_DEL_kinname;
    var EMPSRC_UPD_DEL_reltnhd;
    var EMPSRC_UPD_DEL_altmoblno;
    var EMPSRC_UPD_DEL_lapno;
    var EMPSRC_UPD_DEL_chrgrno;
    var EMPSRC_UPD_DEL_bagno;
    var EMPSRC_UPD_DEL_mouse;
    var EMPSRC_UPD_DEL_draccs;
    var EMPSRC_UPD_DEL_idcrd;
    var EMPSRC_UPD_DEL_hdset;
    var EMPSRC_UPD_DEL_userstamp;
    var EMPSRC_UPD_DEL_timestmp;
    var id;
    var values_array=[];
    // var EMPSRC_UPD_DEL_table_value='';
    $(document).on('change','#EMPSRC_UPD_DEL_lb_empname',function(){
        $(".preloader").show();
        $('#EMPSRC_UPD_DEL_btn_search').hide();
        $('#EMPSRC_UPD_DEL_table_updateform').hide();
        $('#EMPSRC_UPD_DEL_tble_htmltable').hide();
        var EMPSRC_UPD_DEL_employeename = $("#EMPSRC_UPD_DEL_lb_empname").val();
        if(EMPSRC_UPD_DEL_employeename=='SELECT')
        {
            $(".preloader").hide();
            $('#EMPSRC_UPD_DEL_tble_htmltable').hide();
            $('#EMPSRC_UPD_DEL_div_htmltable').hide();
            $('#EMPSRC_UPD_DEL_lbl_htmltablemsg').hide();
        }
        else
        {
            EMPSRC_UPD_DEL_flextable()
            $('#EMPSRC_UPD_DEL_tble_htmltable').show();
        }
    });
    //RESPONSE FUNCTION FOR FLEXTABLE SHOWING
    function EMPSRC_UPD_DEL_flextable(){
        $(".preloader").show();
        var EMPSRC_UPD_DEL_employname=$("#EMPSRC_UPD_DEL_lb_empname").val();
        var formElement = document.getElementById("EMPSRC_UPD_DEL_form_employeename");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {

                values_array=JSON.parse(xmlhttp.responseText);
                $(".preloader").hide();

                if(values_array.length!=0)
                {
                    var EMPSRC_UPD_DEL_table_header='<tr><th></th><th style="min-width:200px; !important;">FIRST NAME</th><th  style="min-width:200px; !important;">LAST NAME</th><th  nowrap>DOB</th><th style="min-width:120px; !important;">DESIGNATION</th><th  width="100px">PERSONNAL MOBILE</th><th  style="min-width:190px; !important;">KIN NAME</th><th style="min-width:120px; !important;">RELATION HOOD</th><th>ALT MOBILE</th><th style="min-width:100px; !important;">LAPTOP NO</th><th style="min-width:100px; !important;">CHARGER NO</th><th style="min-width:20px; !important;">LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEADSET</th><th>USERSTAMP</th><th style="min-width:140px; !important;">TIMESTAMP</th></tr>'
                    $('#EMPSRC_UPD_DEL_tble_htmltable').html(EMPSRC_UPD_DEL_table_header);
                    values_array=values_array;
                    if(values_array.length > 10){var px = '1000px'}
                    else
                    {
                        var x = values_array.length*50+50;
                        if(x <=100){var px = '200px'}
                        else{
                            var px = x+"px" }
                    }
                    if(values_array.length == 1) {var px ="95px"}
                    $('#EMPSRC_UPD_DEL_flexdata_result').css('height',px)
                    $('#EMPSRC_UPD_DEL_flexdata_result').show();
                    for(var j=0;j<values_array.length;j++){
                        EMPSRC_UPD_DEL_empfirstname=values_array[j].EMPSRC_UPD_DEL_employeefirstname;
                        EMPSRC_UPD_DEL_emplastname=values_array[j].EMPSRC_UPD_DEL_employeelastname;
                        EMPSRC_UPD_DEL_dob=values_array[j].EMPSRC_UPD_DEL_dteofbrth;
                        EMPSRC_UPD_DEL_desig=values_array[j].EMPSRC_UPD_DEL_designation;
                        EMPSRC_UPD_DEL_mblno=values_array[j].EMPSRC_UPD_DEL_mobileno;
                        EMPSRC_UPD_DEL_kinname=values_array[j].EMPSRC_UPD_DEL__kin_name;
                        EMPSRC_UPD_DEL_reltnhd=values_array[j].EMPSRC_UPD_DEL_relation_hood;
                        EMPSRC_UPD_DEL_altmoblno=values_array[j].EMPSRC_UPD_DEL_alt_mbelno;
                        EMPSRC_UPD_DEL_lapno=values_array[j].EMPSRC_UPD_DEL_lap_no;
                        if((EMPSRC_UPD_DEL_lapno=='null')||(EMPSRC_UPD_DEL_lapno==undefined))
                        {
                            EMPSRC_UPD_DEL_lapno='';
                        }
                        EMPSRC_UPD_DEL_chrgrno=values_array[j].EMPSRC_UPD_DEL_charger_no;
                        if((EMPSRC_UPD_DEL_chrgrno=='null')||(EMPSRC_UPD_DEL_chrgrno==undefined))
                        {
                            EMPSRC_UPD_DEL_chrgrno='';
                        }
                        EMPSRC_UPD_DEL_bagno=values_array[j].EMPSRC_UPD_DEL_bag_no;
                        if((EMPSRC_UPD_DEL_bagno=='null')||(EMPSRC_UPD_DEL_bagno==undefined))
                        {
                            EMPSRC_UPD_DEL_bagno='';
                        }
                        EMPSRC_UPD_DEL_mouse=values_array[j].EMPSRC_UPD_DEL_mouse;
                        if((EMPSRC_UPD_DEL_mouse=='null')||(EMPSRC_UPD_DEL_mouse==undefined))
                        {
                            EMPSRC_UPD_DEL_mouse='';
                        }
                        EMPSRC_UPD_DEL_draccs=values_array[j].EMPSRC_UPD_DEL_dooraccess;
                        if((EMPSRC_UPD_DEL_draccs=='null')||(EMPSRC_UPD_DEL_draccs==undefined))
                        {
                            EMPSRC_UPD_DEL_draccs='';
                        }
                        EMPSRC_UPD_DEL_idcrd=values_array[j].EMPSRC_UPD_DEL_id_card;
                        if((EMPSRC_UPD_DEL_idcrd=='null')||(EMPSRC_UPD_DEL_idcrd==undefined))
                        {
                            EMPSRC_UPD_DEL_idcrd='';
                        }
                        EMPSRC_UPD_DEL_hdset=values_array[j].EMPSRC_UPD_DEL_headset;
                        if((EMPSRC_UPD_DEL_hdset=='null')||(EMPSRC_UPD_DEL_hdset==undefined))
                        {
                            EMPSRC_UPD_DEL_hdset='';
                        }
                        EMPSRC_UPD_DEL_userstamp=values_array[j].$EMPSRC_UPD_DEL_user_stamp;
                        EMPSRC_UPD_DEL_timestmp=values_array[j].EMPSRC_UPD_DEL_timestamp;
                        id=values_array[j].id;
                        EMPSRC_UPD_DEL_table_value='<tbody><tr><td><input type="radio" name="EMPSRC_UPD_DEL_rd_flxtbl" class="EMPSRC_UPD_DEL_radio" id='+id+'  value='+id+' ></td><td>'+EMPSRC_UPD_DEL_empfirstname+'</td><td>'+EMPSRC_UPD_DEL_emplastname+'</td><td nowrap>'+EMPSRC_UPD_DEL_dob+'</td><td>'+EMPSRC_UPD_DEL_desig+'</td><td>'+EMPSRC_UPD_DEL_mblno+'</td><td>'+EMPSRC_UPD_DEL_kinname+'</td><td>'+EMPSRC_UPD_DEL_reltnhd+'</td><td>'+EMPSRC_UPD_DEL_altmoblno+'</td><td>'+EMPSRC_UPD_DEL_lapno+'</td><td>'+EMPSRC_UPD_DEL_chrgrno+'</td><td>'+EMPSRC_UPD_DEL_bagno+'</td><td>'+EMPSRC_UPD_DEL_mouse+'</td><td>'+EMPSRC_UPD_DEL_draccs+'</td><td>'+EMPSRC_UPD_DEL_idcrd+'</td><td>'+EMPSRC_UPD_DEL_hdset+'</td><td>'+EMPSRC_UPD_DEL_userstamp+'</td><td>'+EMPSRC_UPD_DEL_timestmp+'</td></tr>';
                        $('#EMPSRC_UPD_DEL_tble_htmltable').append(EMPSRC_UPD_DEL_table_value);
                    }
                    EMPSRC_UPD_DEL_employname_errmsg=EMPSRC_UPD_DEL_employname.replace("_"," ")
                    var EMPSRC_UPD_DEL_errmsg=EMPSRC_UPD_DEL_errorAarray[6].replace('[NAME]',EMPSRC_UPD_DEL_employname_errmsg);
                    $('#EMPSRC_UPD_DEL_lbl_htmltablemsg').text(EMPSRC_UPD_DEL_errmsg).show();
                }
            }
        }
        var choice="EMPLOYDETAILS_FLEXTBLE"
        xmlhttp.open("POST","DB_EMPLOYEE_SEARCH_UPDATE.do?&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    }
//RADIO CLICK FUNCTION
    $(document).on('click','.EMPSRC_UPD_DEL_radio',function(){
        $('#EMPSRC_UPD_DEL_btn_search').show();
        $("#EMPSRC_UPD_DEL_btn_search").removeAttr("disabled","disabled");
        $('#EMPSRC_UPD_DEL_table_updateform').hide();
    });
    var values_array=[];
    //CLICK FUNCTION FOR SEARCH BTN
    $(document).on('click','#EMPSRC_UPD_DEL_btn_search',function(){
        $('#EMPSRC_UPD_DEL_table_updateform').show();
        $('#EMP_ENTRY_table_others').show();
        $("#EMPSRC_UPD_DEL_btn_search").attr("disabled","disabled");
        $("#EMPSRC_UPD_DEL_btn_update").attr("disabled","disabled");
        $('#EMPSRC_UPD_DEL_lbl_validnumber1').hide();
        $('#EMPSRC_UPD_DEL_lbl_validnumber').hide();
        var SRC_UPD_idradiovalue=$('input:radio[name=EMPSRC_UPD_DEL_rd_flxtbl]:checked').attr('id');
        for(var j=0;j<values_array.length;j++){
            var id=values_array[j].id;
            var EMPSRC_UPD_DEL_empfrstnme=values_array[j].EMPSRC_UPD_DEL_employeefirstname;
            var EMPSRC_UPD_DEL_emplstnme=values_array[j].EMPSRC_UPD_DEL_employeelastname;
            var EMPSRC_UPD_DEL_dob=values_array[j].EMPSRC_UPD_DEL_dteofbrth;
            var EMPSRC_UPD_DEL_design=values_array[j].EMPSRC_UPD_DEL_designation;
            var EMPSRC_UPD_DEL_mblno=values_array[j].EMPSRC_UPD_DEL_mobileno;
            var EMPSRC_UPD_DEL__knname=values_array[j].EMPSRC_UPD_DEL__kin_name;
            var EMPSRC_UPD_DEL_reltnhood=values_array[j].EMPSRC_UPD_DEL_relation_hood;
            var EMPSRC_UPD_DEL_altmblno=values_array[j].EMPSRC_UPD_DEL_alt_mbelno;
            var EMPSRC_UPD_DEL_lapno=values_array[j].EMPSRC_UPD_DEL_lap_no;
            var EMPSRC_UPD_DEL_chrgrno=values_array[j].EMPSRC_UPD_DEL_charger_no;
            var EMPSRC_UPD_DEL_bagno=values_array[j].EMPSRC_UPD_DEL_bag_no;
            var EMPSRC_UPD_DEL_mouses=values_array[j].EMPSRC_UPD_DEL_mouse;
            var EMPSRC_UPD_DEL_door_access=values_array[j].EMPSRC_UPD_DEL_dooraccess;
            var EMPSRC_UPD_DEL_idcard=values_array[j].EMPSRC_UPD_DEL_id_card;
            var EMPSRC_UPD_DEL_head_set=values_array[j].EMPSRC_UPD_DEL_headset;
            if(id==SRC_UPD_idradiovalue)
            {
                var EMPSRC_UPD_DEL_employeefrstnames=(EMPSRC_UPD_DEL_empfrstnme).length+8;
                $('#EMPSRC_UPD_DEL_tb_firstname').attr("size",EMPSRC_UPD_DEL_employeefrstnames);
                $('#EMPSRC_UPD_DEL_tb_firstname').val(EMPSRC_UPD_DEL_empfrstnme).show();
                $('#EMPSRC_UPD_DEL_tb_firstnamehide').val(EMPSRC_UPD_DEL_empfrstnme);
                var EMPSRC_UPD_DEL_employlstnmes=(EMPSRC_UPD_DEL_emplstnme).length+8;
                $('#EMPSRC_UPD_DEL_tb_lastname').attr("size",EMPSRC_UPD_DEL_employlstnmes);
                $('#EMPSRC_UPD_DEL_tb_lastname').val(EMPSRC_UPD_DEL_emplstnme).show();
                $('#EMPSRC_UPD_DEL_tb_lastnamehide').val(EMPSRC_UPD_DEL_emplstnme);
                $('#EMPSRC_UPD_DEL_tb_dob').val(EMPSRC_UPD_DEL_dob).show();
                var EMPSRC_UPD_DEL_designations=(EMPSRC_UPD_DEL_design).length+10;
                $('#EMPSRC_UPD_DEL_tb_designation').attr("size",EMPSRC_UPD_DEL_designations);
                $('#EMPSRC_UPD_DEL_tb_designation').val(EMPSRC_UPD_DEL_design).show();
                $('#EMPSRC_UPD_DEL_tb_permobile').val(EMPSRC_UPD_DEL_mblno).show();
                var EMPSRC_UPD_DEL_knnames=(EMPSRC_UPD_DEL__knname).length+7;
                $('#EMPSRC_UPD_DEL_tb_kinname').attr("size",EMPSRC_UPD_DEL_knnames);
                $('#EMPSRC_UPD_DEL_tb_kinname').val(EMPSRC_UPD_DEL__knname).show();
                $('#EMPSRC_UPD_DEL_tb_relationhd').val(EMPSRC_UPD_DEL_reltnhood).show();
                $('#EMPSRC_UPD_DEL_tb_mobile').val(EMPSRC_UPD_DEL_altmblno).show();
                var EMPSRC_UPD_DEL_laptopnumbers=(EMPSRC_UPD_DEL_lapno).length+7;
                $('#EMP_ENTRY_tb_laptopno').attr("size",EMPSRC_UPD_DEL_laptopnumbers);
                $('#EMP_ENTRY_tb_laptopno').val(EMPSRC_UPD_DEL_lapno).show();
                var EMPSRC_UPD_DEL_chargernos=(EMPSRC_UPD_DEL_chrgrno).length+7;
                $('#EMP_ENTRY_tb_chargerno').attr("size",EMPSRC_UPD_DEL_chargernos);
                $('#EMP_ENTRY_tb_chargerno').val(EMPSRC_UPD_DEL_chrgrno).show();
                if(EMPSRC_UPD_DEL_bagno=='X')
                {
                    $('#EMPSRC_UPD_DEL_chk_bag').attr('checked',true);
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_chk_bag').attr('checked',false);
                }
                if(EMPSRC_UPD_DEL_mouses=='X')
                {
                    $('#EMPSRC_UPD_DEL_chk_mouse').attr('checked',true);
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_chk_mouse').attr('checked',false);
                }
                if(EMPSRC_UPD_DEL_door_access=='X')
                {
                    $('#EMPSRC_UPD_DEL_chk_dracess').attr('checked',true);
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_chk_dracess').attr('checked',false);
                }
                if(EMPSRC_UPD_DEL_idcard=='X')
                {
                    $('#EMPSRC_UPD_DEL_chk_idcrd').attr('checked',true);
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_chk_idcrd').attr('checked',false);
                }
                if(EMPSRC_UPD_DEL_head_set=='X')
                {
                    $('#EMPSRC_UPD_DEL_chk_headset').attr('checked',true);
                }
                else
                {
                    $('#EMPSRC_UPD_DEL_chk_headset').attr('checked',false);
                }
            }
        }
    });
    //EMPLOYEE UPDATE BUTTON VALIDATION
    $(document).on('change','#EMPSRC_UPD_DEL_form_employeename',function(){
        var EMPSRC_UPD_DEL_Firstname= $("#EMPSRC_UPD_DEL_tb_firstname").val();
        var EMPSRC_UPD_DEL_Lastname =$("#EMPSRC_UPD_DEL_tb_lastname").val();
        var EMPSRC_UPD_DEL_tb_dob=$('#EMPSRC_UPD_DEL_tb_dob').val();
        var EMPSRC_UPD_DEL_empdesig =$("#EMPSRC_UPD_DEL_tb_designation").val();
        var EMPSRC_UPD_DEL_Mobileno = $("#EMPSRC_UPD_DEL_tb_permobile").val();
        var EMPSRC_UPD_DEL_kinname = $("#EMPSRC_UPD_DEL_tb_kinname").val();
        var EMPSRC_UPD_DEL_relationhd = $("#EMPSRC_UPD_DEL_tb_relationhd").val();
        var EMPSRC_UPD_DEL_mobile= $("#EMPSRC_UPD_DEL_tb_mobile").val();
        if((EMPSRC_UPD_DEL_Firstname!='') && (EMPSRC_UPD_DEL_Lastname!='' ) && (EMPSRC_UPD_DEL_tb_dob!='' ) && (EMPSRC_UPD_DEL_empdesig!='' )&&( EMPSRC_UPD_DEL_Mobileno!='' && (parseInt($('#EMPSRC_UPD_DEL_tb_permobile').val())!=0)) &&( EMPSRC_UPD_DEL_mobile!='' && (parseInt($('#EMPSRC_UPD_DEL_tb_mobile').val())!=0)) && (EMPSRC_UPD_DEL_kinname!='')&& (EMPSRC_UPD_DEL_relationhd!='' )&& (EMPSRC_UPD_DEL_Mobileno.length>=10)&&(EMPSRC_UPD_DEL_mobile.length>=10 ))
        {
            $("#EMPSRC_UPD_DEL_btn_update").removeAttr("disabled");
        }
        else
        {
            $("#EMPSRC_UPD_DEL_btn_update").attr("disabled","disabled");
        }
    });
    //BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
    $(document).on('blur','.valid',function(){
        var EMPSRC_UPD_DEL_Mobileno=$(this).attr("id");
        var EMPSRC_UPD_DEL_Mobilenoval=$(this).val();
        if(EMPSRC_UPD_DEL_Mobilenoval.length==10)
        {
            if(EMPSRC_UPD_DEL_Mobileno=='EMPSRC_UPD_DEL_tb_permobile')
                $('#EMPSRC_UPD_DEL_lbl_validnumber').hide();
            else
                $('#EMPSRC_UPD_DEL_lbl_validnumber1').hide();
        }
        else
        {
            if(EMPSRC_UPD_DEL_Mobileno=='EMPSRC_UPD_DEL_tb_permobile')
                $('#EMPSRC_UPD_DEL_lbl_validnumber').text(EMPSRC_UPD_DEL_errorAarray[3]).show();
            else
                $('#EMPSRC_UPD_DEL_lbl_validnumber1').text(EMPSRC_UPD_DEL_errorAarray[3]).show();
        }
    });
    //CLICK EVENT FOR UPDATE BUTTON
    $(document).on('click','#EMPSRC_UPD_DEL_btn_update',function(){
        $(".preloader").show();
        var EMPSRC_UPD_DEL_firstname=$('#EMPSRC_UPD_DEL_tb_firstname').val();
        var EMPSRC_UPD_DEL_lastname=$('#EMPSRC_UPD_DEL_tb_lastname').val();
        var EMPSRC_UPD_DEL_firstnamehide=$('#EMPSRC_UPD_DEL_tb_firstnamehide').val();
        var EMPSRC_UPD_DEL_lastnamehide=$('#EMPSRC_UPD_DEL_tb_lastnamehide').val();
        var formElement = document.getElementById("EMPSRC_UPD_DEL_form_employeename");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    if(EMPSRC_UPD_DEL_firstnamehide!=EMPSRC_UPD_DEL_firstname || EMPSRC_UPD_DEL_lastnamehide!=EMPSRC_UPD_DEL_lastname)
                    {
                        $(".preloader").hide();
                        $('#EMPSRC_UPD_DEL_tble_htmltable').hide();
                        $('#EMPSRC_UPD_DEL_btn_search').hide();
                        //CALLING FUNCTION FOR EMPLOYEENAME LISTBX
                        EMPSRC_UPD_DEL_employeename_listbx()
                        $('#EMPSRC_UPD_DEL_lbl_htmltablemsg').hide();
                    }
                    else
                    {
                        $(".preloader").hide();
                        //CALLING FUNCTION FLEX TABLE FOR AFTER UPDATION RECORDS SHOWN
                        EMPSRC_UPD_DEL_flextable()
                    }
                    $('#EMPSRC_UPD_DEL_table_updateform').hide();
                    $('#EMP_ENTRY_table_others').hide();
                    $("#EMPSRC_UPD_DEL_btn_update").attr("disabled","disabled");
                    $('#EMPSRC_UPD_DEL_btn_search').hide();
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE DETAIL:SEARCH/UPDATE/DELETE",msgcontent:EMPSRC_UPD_DEL_errorAarray[4]}});
                }
                else
                {
                    $(".preloader").hide();
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE DETAIL:SEARCH/UPDATE/DELETE",msgcontent:EMPSRC_UPD_DEL_errorAarray[2]}});
                }
            }
        }
        var choice="EMPLOYDETAILS_UPDATE"
        xmlhttp.open("POST","DB_EMPLOYEE_SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
//CLICK EVENT FUCNTION FOR RESET
    $('#EMPSRC_UPD_DEL_btn_reset').click(function()
    {
        $(".preloader").show();
        EMPSRC_UPD_DEL_employeedetailrset()
    });
//RESET ALL THE ELEMENT//
    function EMPSRC_UPD_DEL_employeedetailrset()
    {
        $(".preloader").hide();
        $('.sizefix').prop("size","20");
        $('#EMPSRC_UPD_DEL_tb_firstname').val('');
        $('#EMPSRC_UPD_DEL_tb_lastname').val('');
        $('#EMPSRC_UPD_DEL_tb_dob').val('');
        $('#EMPSRC_UPD_DEL_tb_designation').val('');
        $('#EMPSRC_UPD_DEL_tb_permobile').val('');
        $('#EMPSRC_UPD_DEL_tb_kinname').val('');
        $('#EMPSRC_UPD_DEL_tb_relationhd').val('');
        $('#EMPSRC_UPD_DEL_tb_mobile').val('');
        $('#EMP_ENTRY_tb_laptopno').val('');
        $('#EMP_ENTRY_tb_chargerno').val('');
        $('#EMPSRC_UPD_DEL_tb_email').val('');
        $("#EMPSRC_UPD_DEL_btn_update").attr("disabled", "disabled");
        $('input[name=EMPSRC_UPD_DEL_chk_bag]').attr('checked',false);
        $('input[name=EMPSRC_UPD_DEL_chk_mouse]').attr('checked',false);
        $('input[name=EMPSRC_UPD_DEL_chk_dracess]').attr('checked',false);
        $('input[name=EMPSRC_UPD_DEL_chk_idcrd]').attr('checked',false);
        $('input[name=EMPSRC_UPD_DEL_chk_headset]').attr('checked',false);
        $('#EMPSRC_UPD_DEL_lbl_validnumber1').hide();
        $('#EMPSRC_UPD_DEL_lbl_validnumber').hide();
    }
});
//END DOCUMENT READY FUNCTION
</script
    <!--SCRIPT TAG END-->
    <!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>EMPLOYEE DETAIL:SEARCH/UPDATE/DELETE</h3><p></div></div>
    <form  name="EMPSRC_UPD_DEL_form_employeename" id="EMPSRC_UPD_DEL_form_employeename" class="content">
        <table id="EMPSRC_UPD_DEL_table_employeetbl" style="width:500px"  >
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_empname" id="EMPSRC_UPD_DEL_lbl_empname">EMPLOYEE NAME <em>*</em></label></td>
                <td><select name="EMPSRC_UPD_DEL_lb_empname" id="EMPSRC_UPD_DEL_lb_empname">
                    </select></td>
                <td><div><label id="EMPSRC_UPD_DEL_lbl_noempname" name="EMPSRC_UPD_DEL_lbl_noempname" class="errormsg"></label></div></td>
            </tr>
        </table>
        <tr>
            <td ><label class="errormsg" id="EMPSRC_UPD_DEL_nodataerrormsg" hidden></label></td>
        </tr>
        <tr><td><label  id='EMPSRC_UPD_DEL_lbl_htmltablemsg' class="srctitle" hidden></label></td></tr>

        <div id ="EMPSRC_UPD_DEL_flexdata_result" style="height:3000px;width:2230px;overflow:auto;">
            <table id="EMPSRC_UPD_DEL_tble_htmltable" border="1"   class="srcresult"  width="2200">
            </table>
        </div>
        <table>
            <tr>
                <td><input class="btn" type="button"  id="EMPSRC_UPD_DEL_btn_search" name="EMPSRC_UPD_DEL_btn_search" value="SEARCH" hidden /></td>
            </tr>
        </table>
        <table id ="EMPSRC_UPD_DEL_table_updateform" hidden >
            <tr>
                <td><label class="srctitle"  name="EMPSRC_UPD_DEL_lbl_personnaldtls" id="EMPSRC_UPD_DEL_lbl_personnaldtls">PERSONNAL DETAILS</label></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_firstname" id="EMPSRC_UPD_DEL_lbl_firstname">FIRST NAME <em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_firstname" id="EMPSRC_UPD_DEL_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha"></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_firstnamehide" id="EMPSRC_UPD_DEL_tb_firstnamehide" hidden></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_lastname" id="EMPSRC_UPD_DEL_lbl_lastname">LAST NAME <em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_lastname" id="EMPSRC_UPD_DEL_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha"></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_lastnamehide" id="EMPSRC_UPD_DEL_tb_lastnamehide" hidden></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_dob" id="EMPSRC_UPD_DEL_lbl_dob">DATE OF BIRTH<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_dob" id="EMPSRC_UPD_DEL_tb_dob" class="datepickerdob datemandtry" style="width:75px;"></td>
            </tr>
            <tr>
                <td><label name="EMPSRC_UPD_DEL_lbl_empdesig" id="EMPSRC_UPD_DEL_lbl_empdesig">DESIGNATION<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_designation" id="EMPSRC_UPD_DEL_tb_designation" maxlength='50' class="alphanumericuppercse sizefix"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_permobile" id="EMPSRC_UPD_DEL_lbl_permobile">PERSONAL MOBILE<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_permobile" id="EMPSRC_UPD_DEL_tb_permobile"  maxlength='10' class="mobileno title_nos valid" style="width:75px" ></td>
                <td><div><label id="EMPSRC_UPD_DEL_lbl_validnumber" name="EMPSRC_UPD_DEL_lbl_validnumber" class="errormsg"></label></div></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_kinname" id="EMPSRC_UPD_DEL_lbl_kinname">NEXT KIN NAME<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_kinname" id="EMPSRC_UPD_DEL_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_relationhd" id="EMPSRC_UPD_DEL_lbl_relationhd">RELATION HOOD<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_relationhd" id="EMPSRC_UPD_DEL_tb_relationhd" maxlength='30' class="autosizealph sizefix title_alpha" ></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_mobile" id="EMP_ENTRY_lbl_mobile">MOBILE NO<em>*</em></label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_mobile" id="EMPSRC_UPD_DEL_tb_mobile" class="mobileno title_nos valid" maxlength='10' style="width:75px"></td><td><label hidden name="EMP_ENTRY_lbl_errmsg" id="EMP_ENTRY_lbl_errmsg" class="errormsg"></label></td>
                <td><div><label id="EMPSRC_UPD_DEL_lbl_validnumber1" name="EMPSRC_UPD_DEL_lbl_validnumber1" class="errormsg"></label></div></td>
            </tr>
            <tr>
                <td><label class="srctitle"  name="EMP_ENTRY_lbl_others" id="EMP_ENTRY_lbl_others">OTHERS</label></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_laptopno" id="EMP_ENTRY_lbl_laptopno">LAPTOP NUMBER</label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_laptopno" id="EMP_ENTRY_tb_laptopno" maxlength='30' class="alphanumeric sizefix"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMPSRC_UPD_DEL_lbl_laptopno" id="EMP_ENTRY_lbl_laptopno">CHARGER NO</label></td>
                <td><input type="text" name="EMPSRC_UPD_DEL_tb_chargerno" id="EMP_ENTRY_tb_chargerno" maxlength='70' class="alphanumeric sizefix"></td>
            </tr>
            <tr><td></td><td>
                    <table id="EMP_ENTRY_table_others" style="width:200px" hidden>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMPSRC_UPD_DEL_chk_bag" id="EMPSRC_UPD_DEL_chk_bag" class="">
                                <label name="EMPSRC_UPD_DEL_lbl_laptopbag" id="EMPSRC_UPD_DEL_lbl_laptopbag">LAPTOP BAG</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMPSRC_UPD_DEL_chk_mouse" id="EMPSRC_UPD_DEL_chk_mouse" class="">
                                <label name="EMPSRC_UPD_DEL_lbl_mouse" id="EMPSRC_UPD_DEL_lbl_mouse">MOUSE</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMPSRC_UPD_DEL_chk_dracess" id="EMPSRC_UPD_DEL_chk_dracess"  class="">
                                <label name="EMPSRC_UPD_DEL_lbl_dracess" id="EMPSRC_UPD_DEL_lbl_dracess">DOOR ACCESS</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMPSRC_UPD_DEL_chk_idcrd" id="EMPSRC_UPD_DEL_chk_idcrd" class="">
                                <label name="EMPSRC_UPD_DEL_lbl_idcrd" id="EMPSRC_UPD_DEL_lbl_idcrd">ID CARD</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMPSRC_UPD_DEL_chk_headset" id="EMPSRC_UPD_DEL_chk_headset" class="">
                                <label name="EMPSRC_UPD_DEL_lbl_headset" id="EMPSRC_UPD_DEL_lbl_headset">HEAD SET</label>
                            </td>
                        </tr>
                    </table></td></tr>
            <tr>
                <td  align="right"><input class="btn" type="button"  id="EMPSRC_UPD_DEL_btn_update" name="SAVE" value="UPDATE" disabled hidden /></td>
                <td align="left"><input type="button" class="btn" name="EMPSRC_UPD_DEL_btn_reset" id="EMPSRC_UPD_DEL_btn_reset" value="RESET"></td>
            </tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->