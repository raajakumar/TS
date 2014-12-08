<?php
include "COMMON.php";
include "HEADER.php";
?>
<script>
$(document).ready(function(){
    $(".preloader").show();
    $('textarea').autogrow({onInitialize: true});
    var URT_SRC_terminate_array=[];
    var js_errormsg_array=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            URT_SRC_terminate_array=value_array[1];
            js_errormsg_array=value_array[0];
            var URSRC_emptype_array=value_array[2];
            var URT_SRC_radio_role='<tr><td><label class="srctitle" >SELECT ROLE ACCESS<em>*</em></label></td></tr>'
            for (var i=0;i<URT_SRC_terminate_array.length;i++) {
                var id="URT_SRC_tble_table"+i;
                var id1="URT_SRC_terminate_array"+i;
                var value=URT_SRC_terminate_array[i][1].replace(" ","_")
                URT_SRC_radio_role+='<tr ><td><input type="radio" name="URT_SRC_radio_nrole" id='+id1+' value='+value+' class="URT_SRC_radio_clsrole"  />' + URT_SRC_terminate_array[i][1] + '</td></tr>';
            }
            $('#URT_SRC_tble_roles').html(URT_SRC_radio_role);
            var emp_type='<option value="SELECT">SELECT</option>';

            for(var k=0;k<URSRC_emptype_array.length;k++){
                emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
            }
            $('#URSRC_lb_selectemptype').html(emp_type);
        }
    }
    var choice='USER_RIGHTS_TERMINATE';
    xmlhttp.open("POST","COMMON.do?option="+choice,true);
    xmlhttp.send();
    $(document).on('click','#URT_SRC_btn_termination',function(){
        $(".preloader").show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                var loggin=$("#URT_SRC_lb_loginterminate").val();
                var msg=js_errormsg_array[1].toString().replace("[LOGIN ID]",loggin);
                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,confirmation:true}});
                $("#URT_SRC_lbl_datepickertermination").hide();
                $("#URT_SRC_lb_loginterminate").hide();
                $("#URT_SRC_lbl_loginterminate").hide();
                $("#URT_SRC_tb_datepickertermination").hide();
                $("#URT_SRC_lbl_reasontermination").hide();
                $("#URT_SRC_ta_reasontermination").hide();
                $("#URT_SRC_btn_termination").hide();
                $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
            }
        }
        var choice='TERMINATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    $(document).on('click','#URT_SRC_btn_rejoin',function(){
        $(".preloader").show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                var loggin=$("#URT_SRC_lb_loginrejoin").val();
                var msg=js_errormsg_array[2].toString().replace("[LOGIN ID]",loggin);
                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,confirmation:true}});
                $("#URT_SRC_lbl_datepickerrejoin").hide();
                $("#URT_SRC_lbl_loginrejoin").show();
                $("#URT_SRC_tble_roles").hide();
                $("#URT_SRC_lb_loginupdate").hide();
                $("#URT_SRC_tb_datepickerrejoin").hide();
                $("#URT_SRC_btn_rejoin").hide();
                $('#URT_SRC_lbl_loginupdate').hide();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_radio_selectrejoin').hide();
                $('#URT_SRC_radio_selectsearchupdate').hide()
                $('#URT_SRC_lb_loginrejoin').hide();
                $('#URT_SRC_lbl_loginrejoin').hide();
                $('#URT_SRC_lbl_selectsearchupdate').hide();
                $('#URT_SRC_lbl_selectrejoin').hide();
                $('#URT_SRC_lbl_selectoption').hide();
                $("#URSRC_lbl_emptype").hide();
                $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);;
                $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
            }
        }
        var option='REJOIN';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });


    $(document).on('click','#URT_SRC_btn_update',function(){
        $(".preloader").show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1){
                    var loggin=$("#URT_SRC_lb_loginupdate").val();
                    var msg=js_errormsg_array[0].toString().replace("[LOGIN ID]",loggin);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,confirmation:true}});
                }
                $('#URT_SRC_lbl_loginrejoin').hide();
                $('#URT_SRC_lb_loginrejoin').hide();
                $('#URT_SRC_lbl_loginterminate').hide();
                $('#URT_SRC_lb_loginterminate').hide();
                $("#URT_SRC_tble_roles").hide();
                $("#URT_SRC_tb_datepickerrejoin").hide();
                $("#URT_SRC_lbl_datepickerrejoin").hide();
                $("#URT_SRC_btn_rejoin").hide();
                $("#URT_SRC_lbl_loginupdate").show();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_lbl_loginupdate').hide();
                $('#URT_SRC_lb_loginupdate').hide();
                $('#URT_SRC_lbl_selectsearchupdate').hide();
                $('#URT_SRC_lbl_selectrejoin').hide();
                $('#URT_SRC_radio_selectsearchupdate').hide();
                $('#URT_SRC_radio_selectrejoin').hide();
                $('#URT_SRC_lbl_selectoption').hide();
                $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
            }
        }
        var choice='UPDATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    $('#URT_SRC_lb_loginupdate').change(function(){
        $(".preloader").show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var mindate=values_array.enddate;
                    $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                    $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                    $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",mindate);
                }
            }
        }
        var option='FETCH';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_lb_loginterminate').change(function(){
        $(".preloader").show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var min_date=(xmlhttp.responseText);
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0])+1;
                    var minimumdate = new Date(year,month,date);

                    $('#URT_SRC_tb_datepickertermination').datepicker("option","minDate",minimumdate);
                }
            }
        }
        var option='GETDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_lb_loginrejoin').change(function(){
        $(".preloader").show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var min_date=(xmlhttp.responseText);
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0])+1;
                    var minimumdate = new Date(year,month,date);

                    $('#URT_SRC_tb_datepickerrejoin').datepicker("option","minDate",minimumdate);
                }
            }
        }
        var option='GETENDDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_radio_logintermination').click(function(){
        $(".preloader").show();
        var radio_value_loginidsearch=$(this).val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginterminate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginterminate').show().prop('selectedIndex',0);

                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[3],confirmation:true}});
                }

            }
        }
        var option='TERMINATIONLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();

    });

    $('#URT_SRC_radio_selectrejoin').click(function(){
        $(".preloader").show();
        var radio_value_loginidsearch=$(this).val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginrejoin').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginrejoin').show().prop('selectedIndex',0);

                }
                else
                {
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[4],confirmation:true}});
                }
            }
        }
        var option='REJOINLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_radio_selectsearchupdate').click(function(){
        $(".preloader").show();
        var radio_value_loginidsearch=$(this).val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginupdate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginupdate').show().prop('selectedIndex',0);

                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[5],confirmation:true}});

                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lb_loginupdate').hide();
                }
            }
        }
        var option='SEARCHLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();

    });
    $("#URT_SRC_btn_rejoin").hide();
    $("#URT_SRC_btn_termination").hide();
    $("#URT_SRC_btn_update").hide();
    $("#URT_SRC_tble_roles").hide();
    $("#URT_SRC_lbl_logintermination").show();

    $("#URT_SRC_lbl_loginsearchupdate").show();

    $('#URT_SRC_radio_logintermination').change(function(){
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_lbl_loginterminate").show();
        $("#URT_SRC_tble_roles").hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginterminate").val("SELECT");
        $("#URT_SRC_lb_loginterminate").show();
        $("#URT_SRC_lbl_selectoption").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $("#URT_SRC_radio_selectrejoin").hide();
        $("#URT_SRC_lbl_selectrejoin").hide();
        $("#URT_SRC_radio_selectsearchupdate").hide();
        $("#URT_SRC_lbl_selectsearchupdate").hide();
        $("#URT_SRC_lbl_loginrejoin").hide();
        $("#URT_SRC_lb_loginrejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
    });

    $('#URT_SRC_lb_loginterminate').change(function(){
        var loginid=$('#URT_SRC_lb_loginterminate').val();
        if(loginid=='SELECT')
        {
            $("#URT_SRC_lbl_datepickertermination").hide();
            $("#URT_SRC_tb_datepickertermination").hide();
            $("#URT_SRC_lbl_reasontermination").hide();
            $("#URT_SRC_ta_reasontermination").hide();
            $("#URT_SRC_btn_termination").hide();
        }
        else
        {
            $("#URT_SRC_lbl_datepickertermination").show();
            $("#URT_SRC_tb_datepickertermination").val('').show();
            $("#URT_SRC_lbl_reasontermination").show();
            $("#URT_SRC_ta_reasontermination").val('').show();
            $("#URT_SRC_btn_termination").show();
        }

    });

    $('#URT_SRC_lb_loginupdate').change(function(){

        var loginvalue=$('#URT_SRC_lb_loginupdate').val();
        if(loginvalue=='SELECT')
        {
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();

        }
        else
        {
            $('#URT_SRC_lbl_datepickerupdate').show();
            $('#URT_SRC_tb_datepickerupdate').val('').show();
            $('#URT_SRC_lbl_reasonupdate').show();
            $('#URT_SRC_ta_reasonupdate').val('').show();
            $('#URT_SRC_btn_update').show();
        }
    });
    $('#URT_SRC_lb_loginrejoin').change(function(){

        var loginidvalue=$('#URT_SRC_lb_loginrejoin').val();
        if(loginidvalue=='SELECT')
        {
            $("#URT_SRC_tble_roles").hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
        }
        else
        {
            $("#URT_SRC_tble_roles").show();
            $("#URT_SRC_tb_datepickerrejoin").val('').show();
            $("#URT_SRC_lbl_datepickerrejoin").show();
            $("#URT_SRC_btn_rejoin").show();
       $("#URSRC_lbl_emptype").show();
        $('#URSRC_lb_selectemptype').show();


        }
    });


    $('#URT_SRC_radio_loginsearchupdate').change(function(){
        $("#URT_SRC_lbl_selectoption").show();
        $("#URT_SRC_radio_selectrejoin").show();
        $("#URT_SRC_lbl_selectrejoin").show();
        $("#URT_SRC_lbl_selectsearchupdate").show();
        $("#URT_SRC_radio_selectsearchupdate").show();
        $("#URT_SRC_lbl_loginterminate").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $("#URT_SRC_lb_loginterminate").hide();
        $("#URT_SRC_lbl_datepickertermination").hide();
        $("#URT_SRC_tb_datepickertermination").hide();
        $("#URT_SRC_lbl_reasontermination").hide();
        $("#URT_SRC_ta_reasontermination").hide();
        $("#URT_SRC_btn_termination").hide();
        $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
    });

    $('#URT_SRC_radio_selectsearchupdate').change(function(){
        $('#URT_SRC_lbl_loginrejoin').hide();
        $('#URT_SRC_lb_loginrejoin').hide();
        $('#URT_SRC_lbl_loginterminate').hide();
        $('#URT_SRC_lb_loginterminate').hide();
        $("#URT_SRC_tble_roles").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginupdate").show();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $('#URT_SRC_lb_loginupdate').show();
    });

    $('#URT_SRC_radio_selectrejoin').change(function(){
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_lbl_loginrejoin").show();
        $("#URT_SRC_tble_roles").hide();
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);

    });
//DATE PICKER FUNCTION
    $('.URT_SRC_tb_termindatepickerclass').datepicker({
        dateFormat:"dd-mm-yy",
        maxDate:new Date(),
        changeYear: true,
        changeMonth: true

    });
    $('.URT_SRC_tb_rejoinndsearchdatepicker').datepicker({
        dateFormat:"dd-mm-yy",
        maxDate:new Date(),
        changeYear: true,
        changeMonth: true

    });
    $(document).on('change','#URT_SRC_form_terminatesearchupdate',function(){
        var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();

        if(Selectedradiooption=='URT_SRC_radio_valuelogintermination')
        {
            if(($('#URT_SRC_lb_loginterminate').val()!='SELECT') && ($("#URT_SRC_tb_datepickertermination").val()!="") && (($("#URT_SRC_ta_reasontermination").val()).trim()!=""))
            {
                $("#URT_SRC_btn_termination").removeAttr("disabled");
            }
            else
            {

                $("#URT_SRC_btn_termination").attr("disabled", "disabled");
            }
        }
        if(Selectedradiooption=='URT_SRC_radio_valueloginsearchupdate')
        {
            var Selectedsearchradiooption = $("input[name='URT_SRC_radio_nselectoption']:checked").val();
            if(Selectedsearchradiooption=='URT_SRC_radio_valuerejoin')
            {

                if(($("#URT_SRC_lbl_loginupdate").val()!='SELECT') &&($('#URSRC_lb_selectemptype').val()!='SELECT') && ($("#URT_SRC_tb_datepickerrejoin").val()!="")&& ($("input[name=URT_SRC_radio_nrole]").is(":checked")==true) )
                {

                    $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                }
            }
            else
            {
                if(($('#URT_SRC_lb_loginrejoin').val()) && ($("#URT_SRC_tb_datepickerupdate").val()) && (($("#URT_SRC_ta_reasonupdate").val()).trim()))
                {
                    $("#URT_SRC_btn_update").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_update").attr("disabled", "disabled");
                }

            }
        }
    });
});
</script>
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>ACCESS RIGHTS:TERMINATE SEARCH/UPDATE</h3><p></div></div>
    <form id="URT_SRC_form_terminatesearchupdate"  class ='content'>
        <table>

            <tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_logintermination"  value="URT_SRC_radio_valuelogintermination" ><label name="URT_SRC_lbl_nlogintermination" id="URT_SRC_lbl_logintermination" hidden>LOGIN TERMINATION</label></td> </tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_loginsearchupdate" value="URT_SRC_radio_valueloginsearchupdate" ><label name="URT_SRC_lbl_nloginsearchupdate" id="URT_SRC_lbl_loginsearchupdate"  hidden>SEARCH/UPDATE</label></td></tr>
            <!--URT_SRC_radio_nterminndupdatesearch termination-->
            <tr> <td> <label name="URT_SRC_lbl_nloginterminate" id="URT_SRC_lbl_loginterminate" class="srctitle" hidden>LOGIN ID<em>*</em> </label></td></tr>
            <tr> <td> <select name="URT_SRC_lb_nloginterminate" id="URT_SRC_lb_loginterminate" hidden> <option>SELECT</option></select></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class="srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickertermination" id="URT_SRC_tb_datepickertermination" class="URT_SRC_tb_termindatepickerclass datemandtry" style="width:75px;" hidden></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_nreasontermination" id="URT_SRC_lbl_reasontermination" class="srctitle" hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
            <tr> <td> <textarea name="URT_SRC_ta_nreasontermination" id="URT_SRC_ta_reasontermination" hidden> </textarea> </td></td> </tr>
            <tr> <td> <input type="button"  value="TERMINATE" id="URT_SRC_btn_termination" class="maxbtn" hidden> </td></tr>
            <!--select an option-->
            <tr> <td> <label name="URT_SRC_lbl_nselectoption" id="URT_SRC_lbl_selectoption" class="srctitle" hidden> SELECT A OPTION </label></td></tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectrejoin"    value="URT_SRC_radio_valuerejoin" hidden> <label name="URT_SRC_lbl_nselectrejoin" id="URT_SRC_lbl_selectrejoin"  hidden> REJOIN </label></td></tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectsearchupdate" hidden><label name="URT_SRC_lbl_nselectsearchupdate" id="URT_SRC_lbl_selectsearchupdate"  hidden> SEARCH/UPDATE </label></td></tr>

            <!--terminate rejoin-->
            <tr> <td> <label name="URT_SRC_lbl_nloginrejoin" id="URT_SRC_lbl_loginrejoin" class="srctitle" hidden> LOGIN ID <em>*</em> </label> </td> </tr>
            <tr> <td> <select name="URT_SRC_lb_nloginrejoin" id="URT_SRC_lb_loginrejoin"  hidden > <option>SELECT</option> </select></td></tr>
            <tr>
                <td ><label id="URSRC_lbl_emptype" hidden>SELECT TYPE OF EMPLOYEE<em>*</em></label></td>
            </tr>
            <tr>
                <td><select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" hidden  >
                        <option value='SELECT' selected="selected"> SELECT</option>
                    </select></td></tr>

            <tr> <td> <table id="URT_SRC_tble_roles"> </table></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_ndatepickerrejoin" id="URT_SRC_lbl_datepickerrejoin" class=" srctitle" hidden> SELECT A JOIN DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickerrejoin" id="URT_SRC_tb_datepickerrejoin" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></tr>
            <tr> <td> <input type="button" value="REJOIN" id="URT_SRC_btn_rejoin" class="btn"  hidden> </td></tr>

            <!--terminate updation-->
            <tr> <td> <label name="URT_SRC_lbl_nloginupdate" id="URT_SRC_lbl_loginupdate" class="srctitle " hidden>LOGIN ID<em>*</em> </label></td></tr>
            <tr> <td> <select name="URT_SRC_lb_nloginupdate" id="URT_SRC_lb_loginupdate" hidden> <option>SELECT</option></select></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_ndatepickerupdate" id="URT_SRC_lbl_datepickerupdate" class=" srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickerupdate" id="URT_SRC_tb_datepickerupdate" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_nreasonupdate" id="URT_SRC_lbl_reasonupdate" class=" srctitle"hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
            <tr> <td> <textarea name="URT_SRC_ta_nreasonupdate" id="URT_SRC_ta_reasonupdate" hidden> </textarea> </td> </tr>
            <tr> <td> <input type="button" value="UPDATE" id="URT_SRC_btn_update" class="btn" hidden> </td></tr>

        </table>
    </form>
</body>
</html>