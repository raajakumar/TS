<!--//*******************************************FILE DESCRIPTION*********************************************//
//*************************************BANDWIDTH****************************************************************//
//DONE BY:LALITHA
//VER 0.02 SD:31/10/2014 ED:31/10/2014,TRACKER NO:97,Updated dt section id while changed other records loaded,Alingned centre the vals
//VER 0.01-INITIAL VERSION, SD:23/10/2014 ED:25/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "HEADER.php";
?>
<!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var REP_BND_errorAarray=[];
//READY FUNCTION START
$(document).ready(function(){
    $('#REP_BND_nodata_rc').hide();
    $(".ui-datepicker-calendar").hide();
    var REP_BND_reportconfig_listbx=[];
    var REP_BND_active_emp=[];
    var  REP_BND_nonactive_emp=[];
    $('.preloader').show();
    $('#REP_BND_btn_search').hide();
    $('#REP_BND_btn_mysearch').hide();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var values_array=JSON.parse(xmlhttp.responseText);
            REP_BND_reportconfig_listbx=values_array[0];
            REP_BND_active_emp=values_array[1];
            REP_BND_nonactive_emp=values_array[2];
            REP_BND_errorAarray=values_array[3];
            if(REP_BND_reportconfig_listbx.length!=0){
                var REP_BND_config_list='<option>SELECT</option>';
                for (var i=0;i<REP_BND_reportconfig_listbx.length;i++) {
                    REP_BND_config_list += '<option value="' + REP_BND_reportconfig_listbx[i][1] + '">' + REP_BND_reportconfig_listbx[i][0] + '</option>';
                }
                $('#REP_BND_lb_reportconfig').html(REP_BND_config_list);
                $('#REP_BND_lbl_reportconfig').show();
                $('#REP_BND_lb_reportconfig').show();
            }
            else
            {
                $('#REP_BND_nodata_rc').text(REP_BND_errorAarray[2]).show();
            }
        }
    }
    var option="common";
    xmlhttp.open("GET","DB_REPORT_BANDWIDTH.do?option="+option);
    xmlhttp.send();
    //CHANGE FUNCTION FOR BANDWIDTH LISTBX
    $(document).on('change','#REP_BND_lb_reportconfig',function(){
        var formElement = document.getElementById("REP_BND_form_bandwidth");
        var date_val=[];
        $('#REP_BND_db_selectmnth').val('');
        $('#REP_BND_lbl_loginid').hide();
        $('#REP_BND_lb_loginid').hide();
        $('#REP_BND_btn_search').hide();
        $('#REP_BND_lbl_selectmnths').hide();
        $('#REP_BND_db_selectmnths').hide();
        $('#REP_BND_div_monthyr').hide();
        $('#REP_BND_div_actvenon_dterange').hide();
        $('#REP_BND_div_monthyr').hide();
        $('#REP_BND_lbl_actveemps').hide();
        $('#REP_BND_lbl_nonactveemps').hide();
        $('#REP_BND_nodata_pdflextble').hide();
        $('#REV_nodata_pdflextbles').hide();
        $('#REP_BND_nodata_lgnid').hide();
        $("#REP_BND_btn_mysearch").attr("disabled","disabled");
        $('input:radio[name=REP_BND_rd_actveemp]').attr('checked',false);
        var option=$("#REP_BND_lb_reportconfig").val();
        if(option=="SELECT")
        {
            $('#REP_BND_lbl_selectmnth').hide();
            $('#REP_BND_db_selectmnth').hide();
            $('#REP_BND_btn_mysearch').hide();
            $('#REP_BND_tble_prjctrevactnonact').hide();
        }
        //BANDWIDTH BY MONTH
        else if(option=='11')
        {
            //FUNCTION FOR SETTINF MIN ND MAX DATE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    date_val=JSON.parse(xmlhttp.responseText);
                    var REP_BND_start_dates=date_val[0];
                    var REP_BND_end_dates=date_val[1];
                }
                //DATE PICKER FUNCTION START
                $('#REP_BND_db_selectmnth').datepicker( {
                    changeMonth: true,      //provide option to select Month
                    changeYear: true,       //provide option to select year
                    showButtonPanel: true,   // button panel having today and done button
                    dateFormat: 'MM-yy',    //set date format
                    //ONCLOSE FUNCTION
                    onClose: function(dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                        $(this).blur();//remove focus input box
                        $("#REP_BND_btn_mysearch").attr("disabled");
                        dpvalidation()
                    }
                });
                //FOCUS FUNCTION
                $("#REP_BND_db_selectmnth").focus(function () {
                    $(".ui-datepicker-calendar").hide();
                    $("#ui-datepicker-div").position({
                        my: "center top",
                        at: "center bottom",
                        of: $(this)
                    });
                });
                $("#REP_BND_db_selectmnth").datepicker("option","minDate", new Date(REP_BND_start_dates));
                $("#REP_BND_db_selectmnth").datepicker("option","maxDate", new Date(REP_BND_end_dates));
                //VALIDATION FNCTION FOR DATE BX OF BW BY MONTH
                function dpvalidation(){
                    $('section').html('');
                    $('sections').html('');
                    $('#REP_BND_div_monthyr').hide();
                    $('#REP_BND_nodata_pdflextble').hide();
                    $("#REP_BND_btn_mysearch").attr("disabled","disabled");
                    if(($('#REP_BND_db_selectmnth').val()!='undefined')&&($('#REP_BND_db_selectmnth').val()!=''))
                    {
                        $("#REP_BND_btn_mysearch").removeAttr("disabled");
                    }
                    else
                    {
                        $("#REP_BND_btn_mysearch").attr("disabled");
                    }
                }
                $('#REP_BND_lbl_selectmnth').show();
                $('#REP_BND_btn_mysearch').show();
                $('#REP_BND_db_selectmnth').show();
                $('#REP_BND_tble_prjctrevactnonact').hide();
            }
            var choice="minmax_dtewth_monthyr";
            xmlhttp.open("GET","DB_REPORT_BANDWIDTH.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        }
        //BANDWIDTH BY EMPLOYEE
        else if(option=='12')
        {
            $('#REP_BND_tble_prjctrevactnonact').show();
            $('#REP_BND_rd_actveemp').show();
            $('#REP_BND_lbl_actveemp').show();
            $('#REP_BND_rd_nonemp').show();
            $('#REP_BND_lbl_nonactveemp').show();
            $('#REP_BND_lbl_selectmnth').hide();
            $('#REP_BND_btn_mysearch').hide();
            $('#REP_BND_db_selectmnth').hide();
        }
    });
    // CLICK EVENT FOR ACTIVE RADIO BUTTON
    $(document).on('click','#REP_BND_rd_actveemp',function(){
        $('#REP_BND_btn_search').hide();
        $('#REP_BND_lbl_selectmnths').hide();
        $('#REP_BND_db_selectmnths').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REP_BND_div_actvenon_dterange').hide();
        $('#REP_BND_lbl_nonactveemps').hide();
        $('#REV_nodata_pdflextbles').hide();
        $('#REP_BND_nodata_lgnid').hide();
        if(REP_BND_active_emp.length!=0)
        {
            var REP_BND_active_employee='<option>SELECT</option>';
            for (var i=0;i<REP_BND_active_emp.length;i++) {
                REP_BND_active_employee += '<option value="' + REP_BND_active_emp[i] + '">' + REP_BND_active_emp[i] + '</option>';
            }
            $('#REP_BND_lb_loginid').html(REP_BND_active_employee);
            $('#REP_BND_lbl_actveemps').show();
            $('#REP_BND_lbl_loginid').show();
            $('#REP_BND_lb_loginid').show();
        }
        else
        {
            $('#REP_BND_nodata_lgnid').text(err_msg_array[0]).show();
        }
    });
    // CLICK EVENT FOR NON ACTIVE RADIO BUTTON
    $(document).on('click','#REP_BND_rd_nonemp',function(){
        $('#REP_BND_db_selectmnths').val('');
        $('#REP_BND_btn_search').hide();
        $('#REP_BND_lbl_selectmnths').hide();
        $('#REP_BND_db_selectmnths').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REP_BND_div_actvenon_dterange').hide();
        $('#REP_BND_lbl_actveemps').hide();
        $('#REV_nodata_pdflextbles').hide();
        $('#REP_BND_nodata_lgnid').hide();
        if(REP_BND_nonactive_emp.length!=0)
        {
            var REP_BND_nonactive='<option>SELECT</option>';
            for (var i=0;i<REP_BND_nonactive_emp.length;i++) {
                REP_BND_nonactive += '<option value="' + REP_BND_nonactive_emp[i] + '">' + REP_BND_nonactive_emp[i] + '</option>';
            }
            $('#REP_BND_lb_loginid').html(REP_BND_nonactive);
            $('#REP_BND_lbl_nonactveemps').show();
            $('#REP_BND_lbl_loginid').show();
            $('#REP_BND_lb_loginid').show();
        }
        else
        {
            $('#REP_BND_nodata_lgnid').text(REP_BND_errorAarray[0]).show();
        }
    });
    // CHANGE EVENT FOR LOGIN ID LIST BX
    $(document).on('change','#REP_BND_lb_loginid',function(){
        var formElement = document.getElementById("REP_BND_form_bandwidth");
        var date_val=[];
        $('#REP_BND_db_selectmnths').val('');
        $('#REP_BND_btn_search').attr("disabled","disabled");
        $('#REP_BND_lbl_selectmnths').hide();
        $('#REP_BND_db_selectmnths').hide();
        $('#REP_BND_div_actvenon_dterange').hide();
        $('#REV_nodata_pdflextbles').hide();
        var REP_BND_loginid=$('#REP_BND_lb_loginid').val();
        if($('#REP_BND_lb_loginid').val()=="SELECT")
        {
            $('#REP_BND_btn_search').hide();
            $('#REP_BND_btn_search').attr("disabled","disabled");
            $('#REP_BND_lbl_selectmnths').hide();
            $('#REP_BND_db_selectmnths').hide();
        }
        else
        {
            //FUNCTION FOR SETTINF MIN ND MAX DATE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    date_val=JSON.parse(xmlhttp.responseText);
                    var REP_BND_start_dates=date_val[0];
                    var REP_BND_end_dates=date_val[1];
                }
                //DATE PICKER FUNCTION
                $('.date-pickers').datepicker( {
                    changeMonth: true,      //provide option to select Month
                    changeYear: true,       //provide option to select year
                    showButtonPanel: true,   // button panel having today and done button
                    dateFormat: 'MM-yy',    //set date format
                    //ONCLOSE FUNCTION
                    onClose: function(dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                        $(this).blur();//remove focus input box
                        $("#REP_BND_btn_search").attr("disabled");
                        validationdp()
                    }
                });
                //FOCUS FUNCTION
                $(".date-pickers").focus(function () {
                    $(".ui-datepicker-calendar").hide();
                    $("#ui-datepicker-div").position({
                        my: "center top",
                        at: "center bottom",
                        of: $(this)
                    });
                });
                $(".date-pickers").datepicker("option","minDate", new Date(REP_BND_start_dates));
                $(".date-pickers").datepicker("option","maxDate", new Date(REP_BND_end_dates));
                //VALIDATION FOR DATE BX
                function validationdp(){
                    $('section').html('');
                    $('sections').html('');
                    $('#REP_BND_div_actvenon_dterange').hide();
                    $('#REV_nodata_pdflextbles').hide();
                    $("#REP_BND_btn_search").attr("disabled","disabled");
                    if(($('#REP_BND_db_selectmnths').val()!='undefined')&&($('#REP_BND_db_selectmnths').val()!='')&&($('#REP_BND_lb_loginid').val()!="SELECT"))
                    {
                        $("#REP_BND_btn_search").removeAttr("disabled");
                    }
                    else
                    {
                        $("#REP_BND_btn_search").attr("disabled","disabled");
                    }
                }
                $('#REP_BND_btn_search').show();
                $('#REP_BND_lbl_selectmnths').show();
                $('#REP_BND_db_selectmnths').show();
            }
            var choice="minmax_dtewth_loginid";
            xmlhttp.open("GET","DB_REPORT_BANDWIDTH.do?REP_BND_loginid="+REP_BND_loginid+"&option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        }
    });
    // CLICK EVENT FOR LOGIN ID SEARCH BTN
    var REP_BND_actnon_values=[];
    $(document).on('click','#REP_BND_btn_search',function(){
        $('#REV_nodata_pdflextbles').hide();
        $('#REP_BND_div_actvenon_dterange').hide();
        $('#REP_BND_tble_lgn').html('');
        $('#REP_BND_btn_search').attr("disabled","disabled");
        var REP_BND_monthyear=$('#REP_BND_db_selectmnths').val();
        var REP_BND_loginid=$('#REP_BND_lb_loginid').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var REP_BND_actnon_values=JSON.parse(xmlhttp.responseText);
                if(REP_BND_actnon_values)
                {
                    var REP_BND_reportdate= REP_BND_actnon_values[0];
                    var total= REP_BND_actnon_values[1];
                    if(REP_BND_reportdate.length!=1)
                    {
                        var REP_BND_table_header='<table id="REP_BND_tble_lgn" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>REPORT DATE</th><th>BANDWIDTH</th></tr></thead><tfoot><tr> <th colspan="1" style="text-align:right">TOTAL:</th><th></th></tr></tfoot><tbody>'
                    }
                    else
                    {
                        var REP_BND_table_header='<table id="REP_BND_tble_lgn" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>REPORT DATE</th><th>BANDWIDTH</th></tr></thead><tbody>'
                    }
                    for(var i=0;i<REP_BND_reportdate.length;i++){
                        var REP_BND_reportdte=REP_BND_reportdate[i].REP_BND_rptdte;
                        var REP_BND_bandwidthmb=REP_BND_reportdate[i].REP_BND_bndwdth;
                        REP_BND_table_header+='<tr><td align="center">'+REP_BND_reportdte+'</td><td align="center">'+REP_BND_bandwidthmb+'</td></tr>';
                    }
                    REP_BND_table_header+='</tbody></table>';
                    $('section').html(REP_BND_table_header);
                    $('#REP_BND_tble_lgn').DataTable({
                        //SET PDF ONLY
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        },
                        //FOOTER FUNCTION
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over all pages
                            data = api.column( 1 ).data();
                            total = data.length ?
                                data.reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                } ) :
                                0;
                            // Total over this page
                            data = api.column( 1, { page: 'current'} ).data();
                            pageTotal = data.length ?
                                data.reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                } ) :
                                0;
                            // Update footer
                            var pt=pageTotal.toFixed(2)
                            var n=total.toFixed(2)
                            $( api.column( 1 ).footer() ).html(
                                +pt +' ('+ n +' total)'
                            );
                        }
                    });
                }
                else
                {
                    var sd=REP_BND_errorAarray[1].toString().replace("[DATE]",REP_BND_monthyear);
                    $('#REV_nodata_pdflextbles').text(sd).show();
                    $('#REP_BND_div_actvenon_dterange').hide();
                }
            }
        }
        $('#REP_BND_div_actvenon_dterange').show();
        var option="REP_BND_loginid_searchoption";
        xmlhttp.open("GET","DB_REPORT_BANDWIDTH.do?option="+option+"&REP_BND_loginid="+REP_BND_loginid+"&REP_BND_monthyear="+REP_BND_monthyear);
        xmlhttp.send();
    });
    // CLICK EVENT FOR MONTH ND YEAR SEARCH BTN
    var REP_BND_monthyr_values=[];
    $(document).on('click','#REP_BND_btn_mysearch',function(){
        $('#REP_BND_nodata_pdflextble').hide();
        $('#REP_BND_div_monthyr').hide();
        $('#REP_BND_tble_bw').html('');
        $('#REP_BND_btn_mysearch').attr("disabled","disabled");
        var REP_BND_monthyear=$('#REP_BND_db_selectmnth').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var REP_BND_monthyr_values=JSON.parse(xmlhttp.responseText);
                if(REP_BND_monthyr_values)
                {
                    var REP_BND_userbndwdth= REP_BND_monthyr_values[0];
                    var total= REP_BND_monthyr_values[1];
                    if(REP_BND_userbndwdth.length!=1)
                    {
                        var REP_BND_table_header='<table id="REP_BND_tble_bw" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>LOGIN ID</th><th>BANDWIDTH</th></tr></thead><tfoot><tr> <th colspan="1" style="text-align:right">TOTAL:</th><th></th></tr></tfoot><tbody>'
                    }
                    else
                    {
                        var REP_BND_table_header='<table id="REP_BND_tble_bw" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>LOGIN ID</th><th>BANDWIDTH</th></tr></thead><tbody>'
                        $('#REP_BND_div_monthyr').show();
                    }
                    for(var i=0;i<REP_BND_userbndwdth.length;i++){
                        var REP_BND_loginid=REP_BND_userbndwdth[i].REP_BND_lgnid;
                        var REP_BND_bandwidthmb=REP_BND_userbndwdth[i].REP_BND_bndwdth;
                        REP_BND_table_header+='<tr><td>'+REP_BND_loginid+'</td><td align="center">'+REP_BND_bandwidthmb+'</td></tr>';
                    }
                    REP_BND_table_header+='</tbody></table>';
                    $('sections').html(REP_BND_table_header);
                    $('#REP_BND_tble_bw').DataTable({
                        //SET PDF ONLY
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        },
                        //FOOTER FUNCTION
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over all pages
                            data = api.column( 1 ).data();
                            total = data.length ?
                                data.reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                } ) :
                                0;
                            // Total over this page
                            data = api.column( 1, { page: 'current'} ).data();
                            pageTotal = data.length ?
                                data.reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                } ) :
                                0;
                            // Update footer
                            var pt=pageTotal.toFixed(2)
                            var n=total.toFixed(2)
                            $( api.column( 1 ).footer() ).html(
                                +pt +' ('+ n +' total)'
                            );
                            // Update footer
                            var pt=pageTotal.toFixed(2)
                            var n=total.toFixed(2)
                            $( api.column( 1 ).footer() ).html(
                                +n +' ('+ n +' total)'
                            );
                        }
                    });
                }
                else
                {
                    var sd=REP_BND_errorAarray[1].toString().replace("[DATE]",REP_BND_monthyear);
                    $('#REP_BND_nodata_pdflextble').text(sd).show();
                    $('#REP_BND_div_monthyr').hide();
                }
            }
        }
        $('#REP_BND_div_monthyr').show();
        var option="REP_BND_monthyear_searchoption";
        xmlhttp.open("GET","DB_REPORT_BANDWIDTH.do?option="+option+"&REP_BND_db_selectmnth="+REP_BND_monthyear);
        xmlhttp.send();
    });
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>BANDWIDTH</h3><p></div></div>
    <form   id="REP_BND_form_bandwidth" class="content" >
        <table>
            <table>
                <tr>
                    <td width="150"><label name="REP_BND_lbl_reportconfig" id="REP_BND_lbl_reportconfig" hidden>BANDWIDTH<em>*</em></label></td>
                    <td width="150">
                        <select id="REP_BND_lb_reportconfig" name="REP_BND_lb_reportconfig" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REP_BND_nodata_rc" name="REP_BND_nodata_rc" class="errormsg"></label></td></tr>
            <table>
                <tr>
                    <td width="150"><label name="REP_BND_lbl_selectmnth" id="REP_BND_lbl_selectmnth" hidden>SELECT MONTH<em>*</em></label></td>
                    <td><input type="text" name="REP_BND_db_selectmnth" id="REP_BND_db_selectmnth" class="date-picker datemandtry validation" style="width:110px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="REP_BND_btn_mysearch" id="REP_BND_btn_mysearch"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <tr><td><label id="REP_BND_nodata_pdflextble" name="REP_BND_nodata_pdflextble" class="errormsg"></label></td></tr>
            <div id ="REP_BND_div_monthyr" class="container" style="width:500px" hidden>
                <sections>
                </sections>
            </div>
            <table id="REP_BND_tble_prjctrevactnonact" hidden>
                <tr>
                    <td><input type="radio" name="REP_BND_rd_actveemp" id="REP_BND_rd_actveemp" value="EMPLOYEE" hidden >
                        <label name="REP_BND_lbl_actveemp" id="REP_BND_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="REP_BND_rd_actveemp" id="REP_BND_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>
                        <label name="REP_BND_lbl_nonactveemp" id="REP_BND_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE </label></td>
                </tr>
                <tr>
                    <td><label name="REP_BND_lbl_actveemps" id="REP_BND_lbl_actveemps" class="srctitle" hidden>ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><label name="REP_BND_lbl_nonactveemps" id="REP_BND_lbl_nonactveemps" class="srctitle" hidden>NON ACTIVE EMPLOYEE </label></td>
                </tr>
            </table>
            <tr><td><label id="REP_BND_nodata_lgnid" name="REP_BND_nodata_lgnid" class="errormsg"></label></td></tr>
            <table>
                <tr><td width="150">
                        <label name="REP_BND_lbl_loginid" id="REP_BND_lbl_loginid"  hidden>LOGIN ID<em>*</em></label></td>
                    <td>
                        <select name="REP_BND_lb_loginid" id="REP_BND_lb_loginid" hidden>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150"><label name="REP_BND_lbl_selectmnths" id="REP_BND_lbl_selectmnths" hidden>SELECT MONTH<em>*</em></label></td>
                    <td><input type="text" name="REP_BND_db_selectmnths" id="REP_BND_db_selectmnths" class="date-pickers datemandtry valid" style="width:110px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="REP_BND_btn_search" id="REP_BND_btn_search"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <tr><td><label id="REP_BND_nodatas_pdflextble" name="REP_BND_nodatas_pdflextble" class="errormsg"></label></td></tr>
            <div id ="REP_BND_div_actvenon_dterange" class="container" style="width:500px" hidden>
                <section>
                </section>
            </div>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->