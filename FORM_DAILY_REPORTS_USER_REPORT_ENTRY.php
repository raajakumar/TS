<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS USER REPORT ENTRY **************************************//
//DONE BY:SASIKALA
//VER 0.02 SD:17/10/2014 ED 18/10/2014,TRACKER NO:74,DESC:DID PERMISSION AS MANDATORY AND BUTTON VALIDATION
//VER 0.01-INITIAL VERSION, SD:08/08/2014 ED:01/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//READY FUNCTION START
$(document).ready(function(){
    $('textarea').autogrow({onInitialize: true});
    $('#URE_btn_submit').hide();
    $('#URE_btn_save').hide();
    var permission_array=[];
    var project_array=[];
    var min_date;
    var err_msg=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            permission_array=value_array[0];
            project_array=value_array[1];
            min_date=value_array[2];
            err_msg=value_array[3];
            var mindatesplit=min_date.split('-');
            var maxdate=new Date();
            var month=maxdate.getMonth()+1;
            var year=maxdate.getFullYear();
            var date=maxdate.getDate();
            var max_date = new Date(year,month,date);
            var datepicker_maxdate=new Date(Date.parse(max_date));
            $('#URE_tb_date').datepicker("option","maxDate",datepicker_maxdate);
            $('#URE_tb_date').datepicker("option","minDate",min_date);
            $('#URE_ta_fromdate').datepicker("option","maxDate",datepicker_maxdate);
            $('#URE_ta_fromdate').datepicker("option","minDate",min_date);
        }
    }
    var option="user_report_entry";
    xmlhttp.open("GET","COMMON.do?option="+option);
    xmlhttp.send();
    //DATE PICKER FUNCTION
    $('#URE_tb_date').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //JQUERY LIB VALIDATION START
    $("#URE_tb_band").prop("title","NUMBERS ONLY");
    $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
    //JQUERY LIB VALIDATION END
    $(document).on('change','#URE_tb_date',function(){
        $(".preloader").show();
        var reportdate=$('#URE_tb_date').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msgalert=xmlhttp.responseText;
                if(msgalert==1)
                {
                    var msg=err_msg[3].toString().replace("[DATE]",reportdate);
                    UARD_clear()
                    $("#URE_tb_date").val('');
                    $('#URE_tble_attendence').hide();
                    $('#URE_lbl_errmsg').text(msg).show();
                }
                else
                {
                    UARD_clear()
                    $('#URE_tble_attendence').val('SELECT').show();
                    $('#URE_lbl_errmsg').hide();
                    $('#URE_lb_attendance').prop('selectedIndex',0);
                }
            }
        }
        var option="DATE";
        xmlhttp.open("GET","DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?date_change="+reportdate+"&option="+option);
        xmlhttp.send();
    });
    //CHANGE EVENT FOR ATTENDANCE
    $('#URE_lb_attendance').change(function(){
        $('#URE_tble_frstsel_projectlistbx').html('');
        $('#URE_btn_submit').attr('disabled','disabled');
        $('#URE_tble_reasonlbltxtarea').html('');
        if($('#URE_lb_attendance').val()=='SELECT')
        {
            $('#URE_lbl_permission').hide();
            $('#URE_rd_permission').hide();
            $('#URE_rd_nopermission').hide();
            $('#URE_lbl_nopermission').hide();
            $('#URE_lb_timing').hide();
            $('#URE_tble_enterthereport').html('');
            $('#URE_tble_projectlistbx').hide();
            $('#URE_tble_bandwidth').html('');
            $('#URE_lbl_session').hide();
            $('#URE_lb_ampm').hide();
            $('#URE_btn_submit').hide();
        }
        else if($('#URE_lb_attendance').val()=='1')
        {
            $('#URE_tble_enterthereport,#URE_ta_reason,#URE_tble_bandwidth').html('');
            $('#URE_rd_permission').attr('checked',false);
            $('#URE_rd_nopermission').attr("checked",false);
            $('#URE_lb_timing').hide();
            $('#URE_lbl_permission').show();
            $('#URE_rd_permission').show();
            $('#URE_rd_nopermission').show();
            $('#URE_lbl_nopermission').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<permission_array.length;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#URE_lb_timing').html(permission_list);
            $('#URE_lbl_session').hide();
            $('#URE_lb_ampm').hide();
            $('#URE_tble_projectlistbx').show();
            projectlist();
            URE_report();
            URE_tble_bandwidth();
            $('#URE_btn_submit').hide();
            $('#URE_rd_permission').removeAttr("disabled");
            $('#URE_rd_nopermission').removeAttr("disabled");
            $('#URE_lbl_errmsg').hide();
        }
        else if($('#URE_lb_attendance').val()=='0')
        {
            $('#URE_rd_permission').attr('checked',false);
            $('#URE_rd_nopermission').attr("checked",false);
            $('#URE_lb_timing').hide();
            $('#URE_lbl_permission').show();
            $('#URE_rd_permission').show();
            $('#URE_rd_nopermission').show();
            $('#URE_lbl_nopermission').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<4;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#URE_lb_timing').html(permission_list);
            $('#URE_lbl_session').show();
            $('#URE_lb_ampm').val('SELECT').show();
            $('#URE_tble_projectlistbx').hide();
            $('#URE_tble_reasonlbltxtarea').html('');
            $('#URE_tble_enterthereport').html('');
            $('#URE_tble_bandwidth').html('');
            $('#URE_btn_submit').hide();
            $('#URE_rd_permission').attr('disabled','disabled');
            $('#URE_rd_nopermission').attr('disabled','disabled');
            $('#URE_lbl_errmsg').hide();
        }
        else if($('#URE_lb_attendance').val()=='OD')
        {
            $('#URE_rd_permission').attr('checked',false);
            $('#URE_rd_nopermission').attr("checked",false);
            $('#URE_lb_timing').hide();
            $('#URE_lbl_permission').show();
            $('#URE_rd_permission').show();
            $('#URE_rd_nopermission').show();
            $('#URE_lbl_nopermission').show();
            $('#URE_lbl_session').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<4;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#URE_lb_timing').html(permission_list);
            $('#URE_lb_ampm').val('SELECT').show();
            $('#URE_tble_projectlistbx').hide();
            $('#URE_tble_reasonlbltxtarea').html('');
            $('#URE_tble_enterthereport').html('');
            $('#URE_tble_bandwidth').html('');
            $('#URE_btn_submit').hide();
            $('#URE_rd_permission').attr('disabled','disabled');
            $('#URE_rd_nopermission').attr('disabled','disabled');
            $('#URE_lbl_errmsg').hide();
        }
    });
// CLICK EVENT FOR PERMISSION RADIO BUTN
    $(document).on('click','#URE_rd_permission',function()
    {
        if($('#URE_rd_permission').attr("checked","checked"))
        {
            $('#URE_lb_timing').val('SELECT').show();
        }
        else
        {
            $('#URE_lb_timing').hide();
            $('#URE_lb_timing').prop('selectedIndex',0);
        }
    });
// CLICK EVENT FOR NOPERMISSION RADIO BUTN
    $(document).on('click','#URE_rd_nopermission',function()
    {
        $('#URE_lb_timing').hide();
        $('#URE_lb_timing').prop('selectedIndex',0);

    });
//FUNCTION FOR FORM CLEAR
    function UARD_clear(){
        $('#URE_tble_attendence').hide();
        $('#URE_lb_attendance').prop('selectedIndex',0);
        $('#URE_tble_reasonlbltxtarea').html('');
        $('#URE_tble_frstsel_projectlistbx').html('');
        $('#URE_tble_enterthereport').html('');
        $('#URE_tble_bandwidth').html('');
        $('#URE_btn_submit').html('');
        $('#URE_lbl_session').hide();
        $('#URE_chk_permission').hide();
        $('#URE_lbl_permission').hide();
        $('#URE_rd_permission').hide();
        $('#URE_lbl_permission').hide();
        $('#URE_rd_nopermission').hide();
        $('#URE_lbl_nopermission').hide();
        $('#URE_lb_timing').hide();
        $('#URE_lb_timing').prop('selectedIndex',0);
        $('#URE_lb_ampm').hide();
        $('#URE_btn_submit').hide();
        $('#URE_tble_projectlistbx').hide();
    }
    // CHANGE EVENT FOR SESSION LISTBOX
    $('#URE_lb_ampm').change(function(){
        $('#URE_tble_reasonlbltxtarea,#URE_tble_enterthereport,#URE_tble_bandwidth,#URE_tble_frstsel_projectlistbx').html('');
        if($('#URE_lb_ampm').val()=='SELECT')
        {
            $('#URE_tble_reasonlbltxtarea').html('');
            $('#URE_tble_frstsel_projectlistbx').html('');
            $('#URE_tble_enterthereport').html('');
            $('#URE_tble_projectlistbx').hide();
            $('#URE_tble_bandwidth').html('');
            $('#URE_btn_submit').hide();
            $('#URE_lbl_errmsg').hide();
        }
        else if($('#URE_lb_ampm').val()=='FULLDAY')
        {
            $('#URE_tble_projectlistbx').hide();
            URE_tble_reason();
            $('#URE_rd_permission').attr('disabled','disabled');
            $('#URE_rd_nopermission').attr('disabled','disabled');
            $('#URE_btn_submit').show();
            $('#URE_lb_timing').hide();
            $('#URE_lbl_permission').hide();
            $('#URE_rd_permission').hide();
            $('#URE_rd_nopermission').hide();
            $('#URE_lbl_nopermission').hide();
            $('#URE_lbl_errmsg').hide();
        }
        else
        {
            $('#URE_tble_projectlistbx').show();
            URE_tble_reason();
            projectlist();
            URE_report();
            URE_tble_bandwidth();
            $('#URE_rd_permission').attr('checked',false);
            $('#URE_rd_nopermission').attr("checked",false);
            $('#URE_btn_submit').hide();
            $('#URE_rd_permission').removeAttr('disabled');
            $('#URE_rd_nopermission').removeAttr('disabled');
            $('#URE_lbl_permission').show();
            $('#URE_rd_permission').show();
            $('#URE_rd_nopermission').show();
            $('#URE_lbl_nopermission').show();
            $('#URE_lb_timing').hide();
            $('#URE_lbl_errmsg').hide();
        }
    });
    //CHANGE EVENT FOR BANDWIDTH TEXTBX
    $(document).on('change blur','#URE_tb_band',function(){
        var bandwidth=$('#URE_tb_band').val();
        if(bandwidth > 1000)
        {
            var msg=err_msg[9].toString().replace("[BW]",bandwidth);
            $('#URE_lbl_errmsg').text(msg).show();
        }
        else
        {
            $('#URE_lbl_errmsg').hide();
        }
    });
    // CHANGE EVENT FOR REPORT TEXTAREA
    $(document).on('change','#URE_ta_report',function(){
        $('#URE_btn_submit').show();
        $('#URE_btn_submit').attr('disabled','disabled');
        $('#URE_lbl_errmsg').hide();
    });
    //FUNCTION FOR PROJECT LIST
    function projectlist(){
        var project_list;
        for (var i=0;i<project_array.length;i++) {
            project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] + '</td></tr>';
        }
        $('#URE_tble_frstsel_projectlistbx').append(project_list).show();
    }
    // FUNCTION FOR REASON
    function URE_tble_reason(){
        $('<tr><td width="150"><label name="URE_lbl_reason" id="URE_lbl_reason" >REASON<em>*</em></label></td><td><textarea  name="URE_ta_reason" id="URE_ta_reason" ></textarea></td></tr>').appendTo($("#URE_tble_reasonlbltxtarea"));
    }
    // FUNCTION FOR MULTIPLE DAY REASON
    function URE_mulreason(){
        $('<tr><td width="150"><label name="URE_lbl_reason" id="URE_lbl_reason" >REASON<em>*</em></label></td><td><textarea  name="URE_ta_reason" id="URE_ta_reason" ></textarea></td></tr>').appendTo($("#URE_tble_reason"));
    }
    // FUNCTION FOR REPORT TEXTAREA
    function URE_report(){
        $('<tr><td width="150"><label name="URE_lbl_report" id="URE_lbl_report" >REPORT<em>*</em></label></td><td><textarea  name="URE_ta_report" id="URE_ta_report" ></textarea></td></tr>').appendTo($("#URE_tble_enterthereport"));
    }
    // FUCNTION FOR BANDWIDTH
    function URE_tble_bandwidth(){
        $('<tr><td width="150"><label name="URE_lbl_band" id="URE_lbl_band" >BANDWIDTH<em>*</em></label></td><td><input type="text" name="URE_tb_band" id="URE_tb_band" class="autosize amountonly" style="width:75px;"><td><label name="URE_lbl_band" id="URE_lbl_band">MB</label></td></td></tr></tr>').appendTo($("#URE_tble_bandwidth"));
        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
    }
    //FORM VALIDATION
    $(document).on('change blur','#URE_form_dailyuserentry',function(){
        if($("input[name=entry]:checked").val()=="SINGLE DAY ENTRY"){
            var URE_sessionlstbx= $("#URE_lb_ampm").val();
            var URE_tble_reasontxtarea =$("#URE_ta_reason").val();
            var URE_reportenter =$("#URE_ta_report").val();
            var URE_bndtxt = $("#URE_tb_band").val();
            var URE_projectselectlistbx = $("input[id=checkbox]").is(":checked");
            var URE_permissionlstbx = $("#URE_lb_timing").val();
            var URE_permission=$("input[name=permission]:checked").val()=="PERMISSION";
            var URE_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
            var URE_presenthalfdysvld=$("#URE_lb_attendance").val();
            if(((URE_presenthalfdysvld=='0') && (URE_sessionlstbx=='AM' || URE_sessionlstbx=="PM")) || ((URE_presenthalfdysvld=='OD') && (URE_sessionlstbx=='AM' || URE_sessionlstbx=="PM") ))
            {
                if(((URE_tble_reasontxtarea.trim()!="")&&(URE_reportenter!='')&&( URE_projectselectlistbx==true) && (URE_bndtxt!='') &&(URE_bndtxt<=1000) && ((URE_permission==true) || (URE_nopermission==true))))
                {
                    if(URE_permission==true)
                    {
                        if(URE_permissionlstbx!='SELECT')
                        {
                            $("#URE_btn_submit").removeAttr("disabled");
                        }
                        else
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else
                    {
                        $("#URE_btn_submit").removeAttr("disabled");
                    }
                }
                else
                {
                    $("#URE_btn_submit").attr("disabled", "disabled");
                }
            }
            else if((URE_presenthalfdysvld=='0' && URE_sessionlstbx=='FULLDAY') || (URE_presenthalfdysvld=='OD' && URE_sessionlstbx=='FULLDAY'))
            {
                if(URE_tble_reasontxtarea.trim()=="")
                {
                    $("#URE_btn_submit").attr("disabled", "disabled");
                }
                else
                {
                    $("#URE_btn_submit").removeAttr("disabled");
                }
            }
            else if(URE_presenthalfdysvld=='1')
            {
                if(((URE_reportenter.trim()!="")&&(URE_bndtxt!='') && (URE_bndtxt<=1000) &&( URE_projectselectlistbx==true) && ((URE_permission==true) || (URE_nopermission==true))))
                {
                    if(URE_permission==true)
                    {
                        if(URE_permissionlstbx!='SELECT')
                        {
                            $("#URE_btn_submit").removeAttr("disabled");
                        }
                        else
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else
                    {
                        $("#URE_btn_submit").removeAttr("disabled");
                    }
                }
                else
                {
                    $("#URE_btn_submit").attr("disabled", "disabled");
                }
            }
        }
        else if($("input[name=entry]:checked").val()=="MULTIPLE DAY ENTRY"){
            var URE_tble_reasontxtarea =$("#URE_ta_reason").val();
            var URE_presenthalfdysvld=$("#URE_lb_attdnce").val();
            if((URE_presenthalfdysvld=='0') || (URE_presenthalfdysvld=='OD'))
            {
                if(URE_tble_reasontxtarea.trim()=="")
                {
                    $("#URE_btn_save").attr("disabled", "disabled");
                }
                else
                {
                    $("#URE_btn_save").removeAttr("disabled");
                }
            }
        }
    });
    // CLICK EVENT FOR SAVE BUTTON
    $(document).on('click','#URE_btn_submit',function(){
        $(".preloader").show();
        var formElement = document.getElementById("URE_form_dailyuserentry");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:err_msg[0],confirmation:true}});
                    UARD_clear();
                    $('#URE_tb_date').val('');
                }
                else if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:err_msg[4],confirmation:true}});
                    UARD_clear();
                    $('#URE_tb_date').val('');
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:msg_alert,confirmation:true}});
                    UARD_clear();
                    $('#URE_tb_date').val('');
                }
            }
        }
        var option="SINGLE DAY ENTRY";
        xmlhttp.open("POST","DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
    // CLICK EVENT FOR SINGLE DAY RADIO BUTTON
    $('#URE_rd_sinentry').click(function(){
        $('#URE_tbl_singleday').show();
        $('#URE_tbl_multipleday').hide();
        $('#URE_lbl_reason').hide();
        $('#URE_ta_reason').hide();
        $('#URE_tbl_attendence').hide();
        $('#URE_btn_save').hide();
        $('#URE_tb_date').val('');
        $('#URE_lbl_attdnce').hide();
        $('#URE_lb_attdnce').hide();
        $('#URE_btn_save').hide();
        $('#URE_lbl_msg').hide();
    });
    //CLICK EVENT FOR MULTIPLE DAY RADIO BUTTON
    $('#URE_rd_mulentry').click(function(){
        $('#URE_tbl_singleday').hide();
        $('#URE_tbl_multipleday').show();
        $('#URE_lbl_errmsg').hide();
        $('#URE_ta_fromdate').val('');
        $('#URE_ta_todate').val('');
        UARD_clear();
    });
// DATEPICKER FUNCTION
    $('.dtpic').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    // CHANGE EVENT FOR FROM DATE
    $(document).on('change','#URE_ta_fromdate',function(){
        var URE_fromdate = $('#URE_ta_fromdate').datepicker('getDate');
        var date = new Date( Date.parse( URE_fromdate ));
        date.setDate( date.getDate()  );
        var URE_todate = date.toDateString();
        URE_todate = new Date( Date.parse( URE_todate ));
        $('#URE_ta_todate').datepicker("option","minDate",URE_todate);
        var max_date=new Date();
        var month=max_date.getMonth()+1;
        var year=max_date.getFullYear();
        var date=max_date.getDate();
        var max_date = new Date(year,month,date);
        $('#URE_ta_todate').datepicker("option","maxDate",max_date);
    });
    // CHANGE EVENT FOR MUTIPLE DAY ATTENDANCE
    $('#URE_lb_attdnce').change(function(){
        $('#URE_tble_reason').html('');
        if($('#URE_lb_attdnce').val()=='SELECT')
        {
            $('#URE_lbl_reason').hide();
            $('#URE_ta_reason').hide();
            $('#URE_btn_save').hide();
        }
        else if(($('#URE_lb_attdnce').val()=='0') || ($('#URE_lb_attdnce').val()=='OD'))
        {
            URE_mulreason()
            $('#URE_lbl_reason').show();
            $('#URE_ta_reason').show();
            $('#URE_btn_save').show();
        }
    });
    // FUNCTION FOR CLEAR
    function URE_mulclear()
    {
        $('#URE_lbl_reason').hide();
        $('#URE_tble_reason').html('');
        $('#URE_ta_reason').hide();
        $('#URE_btn_save').hide();
        $('#URE_tbl_attendence').hide();
        $('#URE_lbl_attdnce').hide();
        $('#URE_lb_attdnce').hide();
    }
    // CHANGE EVENT FOR MULTIPLE DAY SAVE BUTTON
    $('#URE_btn_save').click(function(){
        $('.preloader').show();
        var formElement = document.getElementById("URE_form_dailyuserentry");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var msg_alert=xmlhttp.responseText;
                $('.preloader').hide();
                if(msg_alert==1){
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:err_msg[0],confirmation:true,position:$('#URE_tbl_multipleday').position()}});
                    URE_mulclear()
                    $('#URE_lbl_sdte').show();
                    $('#URE_ta_fromdate').val('').show();
                    $('#URE_lbl_edte').show();
                    $('#URE_ta_todate').val('').show();
                }
                else if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:err_msg[4],confirmation:true}});
                    $('#URE_lbl_sdte').show();
                    $('#URE_ta_fromdate').val('').show();
                    $('#URE_lbl_edte').show();
                    $('#URE_ta_todate').val('').show();
                    URE_mulclear()
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER REPORT ENTRY",msgcontent:msg_alert,confirmation:true}});
                    $('#URE_lbl_sdte').show();
                    $('#URE_ta_fromdate').val('').show();
                    $('#URE_lbl_edte').show();
                    $('#URE_ta_todate').val('').show();
                    URE_mulclear()
                }
            }
        }
        var option="MULTIPLE DAY ENTRY";
        xmlhttp.open("POST","DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
// CHANGE FUNCTIO FOR TO DATE ALEREADY EXISTS
    $('.valid' ).change(function(){
        var fromdate=$('#URE_ta_fromdate').val();
        var todate=$('#URE_ta_todate').val();
        if(fromdate!='' && todate!='')
        {
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var date_array=JSON.parse(xmlhttp.responseText);
                    var error_date='';
                    $('.preloader').hide();
                    for(var i=0;i<date_array.length;i++){
                        if(i==0){
                            error_date=date_array[i]
                        }
                        else{
                            error_date+=','+date_array[i]
                        }
                    }
                    if(error_date=='')
                    {
                        $('#URE_tbl_attendence').show();
                        $('#URE_lbl_attdnce').show();
                        $('#URE_lb_attdnce').val('SELECT').show();
                        $('#URE_lbl_msg').text(msg).hide();
                    }
                    else
                    {
                        var msg=err_msg[3].toString().replace("[DATE]",error_date);
                        $('#URE_lbl_msg').text(msg).show();
//                    $('#URE_ta_fromdate').val('').show();
//                    $('#URE_ta_todate').val('').show();
                        $('#URE_tbl_attendence').hide();
                        $('#URE_lbl_attdnce').hide();
                        $('#URE_lb_attdnce').hide();
                    }
                }
            }
            var option="BETWEEN DATE";
            xmlhttp.open("GET","DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&fromdate="+fromdate+"&todate="+todate,true);
            xmlhttp.send();
        }
    });
});
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif" /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;""><h3><p>USER REPORT ENTRY</p></h3></div></div>
<form id="URE_form_dailyuserentry" name="URE_form_dailyuserentry" class ='content'>
    <table>
        <tr>
            <td><input type="radio" id="URE_rd_sinentry" name="entry" value="SINGLE DAY ENTRY"/>
                <label name="entry" id="URE_lbl_sinentry" >SINGLE DAY ENTRY</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="URE_rd_mulentry" name="entry" value="MULTIPLE DAY ENTRY"/>
                <label name="entry" id="URE_lbl_mulentry" >MULTIPLE DAY ENTRY</label></td>
        </tr>
    </table>
    <table id="URE_tbl_singleday" hidden>
        <tr>
            <td width="150"><label name="URE_lbl_dte" id="URE_lbl_dte" >DATE</label></td>
            <td><input type ="text" id="URE_tb_date" class='proj datemandtry formshown' name="URE_tb_date" style="width:75px;" /></td>
        </tr>
        <table id="URE_tble_attendence" hidden>
            <tr>
                <td width="150"><label name="URE_lbl_attendance" id="URE_lbl_attendance">ATTENDANCE</label></td>
                <td width="150">
                    <select id="URE_lb_attendance" name="URE_lb_attendance">
                        <option>SELECT</option>
                        <option value="1">PRESENT</option>
                        <option value="0">ABSENT</option>
                        <option value="OD">ONDUTY</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="radio" id="URE_rd_permission" name="permission" value="PERMISSION"/>
                    <label name="URE_permission" id="URE_lbl_permission">PERMISSION<em>*</em></label></td>
                <td>
                    <select name="URE_lb_timing" id="URE_lb_timing" hidden >
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="radio" id="URE_rd_nopermission" name="permission" value="NOPERMISSION"/>
                    <label name="URE_nopermission" id="URE_lbl_nopermission">NO PERMISSION<em>*</em></label></td>
            </tr>
            <tr>
                <td><label name="URE_lbl_session" id="URE_lbl_session" hidden >SESSION</label></td>
                <td><select name="URE_lb_ampm" id="URE_lb_ampm" >
                        <option>SELECT</option>
                        <option>FULLDAY</option>
                        <option>AM</option>
                        <option>PM</option>
                    </select></td>
            </tr>
        </table>
        <table id="URE_tble_reasonlbltxtarea"></table>
        <table id="URE_tble_projectlistbx" hidden>
            <tr><td width="150"><label name="URE_lbl_txtselectproj" id="URE_lbl_txtselectproj">PROJECT</label><em>*</em></td>
                <td> <table id="URE_tble_frstsel_projectlistbx" ></table></td>
            </tr>
        </table>
        <table id="URE_tble_enterthereport"></table>
        <table id="URE_tble_bandwidth"></table>
        <tr>
            <input type="button"  class="btn" name="URE_btn_submit" id="URE_btn_submit"  value="SAVE" disabled>
        </tr>
        <td><label id="URE_lbl_errmsg" name="URE_lbl_errmsg" class="errormsg"></label></td></tr>
    </table>
    <table id="URE_tbl_multipleday" hidden>
        <tr>
            <td width="150"><label name="URE_lbl_sdte" id="URE_lbl_dte" >FROM DATE</label></td>
            <td><input type ="text" id="URE_ta_fromdate" class='proj datemandtry formshown dtpic valid' name="URE_ta_fromdate" style="width:75px;" /></td>
        </tr>
        <tr>
            <td width="150"><label name="URE_lbl_edte" id="URE_lbl_dte" >TO DATE</label></td>
            <td><input type ="text" id="URE_ta_todate" class='proj datemandtry formshown dtpic valid' name="URE_ta_todate" style="width:75px;" /></td>
        </tr>
        <table id="URE_tbl_attendence" hidden>
            <tr>
                <td width="150"><label name="URE_lbl_attdnce" id="URE_lbl_attdnce">ATTENDANCE</label></td>
                <td width="150">
                    <select id="URE_lb_attdnce" name="URE_lb_attdnce">
                        <option>SELECT</option>
                        <option value="0">ABSENT</option>
                        <option value="OD">ONDUTY</option>
                    </select>
                </td>
        </table>
        <table id="URE_tble_reason"></table>
        <tr>
            <input type="button"  class="btn" name="URE_btn_save" id="URE_btn_save"  value="SAVE" disabled>
        </tr>
        <td><label id="URE_lbl_msg" name="URE_lbl_msg" class="errormsg"></label></td></tr>
    </table>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->