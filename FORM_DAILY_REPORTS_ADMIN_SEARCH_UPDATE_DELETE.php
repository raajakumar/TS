<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS ADMIN SEARCH UPDATE DELETE***********************************//
//DONE BY:LALITHA
//VER 0.06 SD:20/11/2014 ED:20/11/2014,TRACKER NO:74,DESC:Updated to showned point by point line for report nd reason,Showned permission in report fr all active employee flextble nd also Changed flex tble query
//VER 0.05 SD:14/11/2014 ED 14/11/2014,TRACKER NO:74,DESC:Fixed width
//VER 0.04 SD:06/11/2014 ED 06/11/2014,TRACKER NO:74,DESC:Impmlemented auto focus in radio nd search btn clicking,Fixed width fr all db column,Removed(report:)lbl,Replaced name login(loginid),Hide the err msg while changing dp
//DONE BY:SASIKALA
//VER 0.03 SD:17/10/2014 ED 18/10/2014,TRACKER NO:74,DESC:DID PERMISSION AS MANDATORY AND BUTTON VALIDATION
//VER 0.02 SD:08/10/2014 ED:08/10/2014,TRACKER NO:74,DESC:UPDATED MAIL SEND WHEN UPDATION OCCUR
//VER 0.01-INITIAL VERSION, SD:08/08/2014 ED:01/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
//READY FUNCTION START
$(document).ready(function(){
    $('#ASRC_UPD_DEL_tble_attendence').hide();
    $('#ASRC_UPD_DEL_tbl_entry').hide();
    $('#ASRC_UPD_DEL_btn_del').hide();
    $('#ASRC_UPD_DEL_btn_submit').hide();
    $('#ASRC_UPD_DEL_errmsg').hide();
    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
    $('#ASRC_UPD_DEL_odsrch_btn').hide();
    $('#updatepart').hide();
    var permission_array=[];
    var project_array=[];
    var allmindate;
    var allmaxdate;
    var err_msg=[];
    var active_emp=[];
    var nonactive_emp=[];
    var odmindate;
    var odmaxdate;
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            permission_array=value_array[0];
            project_array=value_array[1];
            allmindate=value_array[2];
            err_msg=value_array[3];
            if(allmindate=='01-01-1970')
            {
                $('#ASRC_UPD_DEL_form_adminsearchupdate').replaceWith('<p><label class="errormsg">'+err_msg[10]+'</label></p>');
            }
            else
            {
                active_emp=value_array[5];
                nonactive_emp=value_array[6];
                allmaxdate=value_array[7];
                odmindate=value_array[8];
                odmaxdate=value_array[9];
                $('#ASRC_UPD_DEL_tb_dte').datepicker("option","minDate",allmindate);
                $('#ASRC_UPD_DEL_tb_dte').datepicker("option","maxDate",allmaxdate);
                $('#ASRC_UPD_DEL_tb_sdte').datepicker("option","minDate",odmindate);
                $('#ASRC_UPD_DEL_tb_sdte').datepicker("option","maxDate",odmaxdate);
                $('#ASRC_UPD_DEL_tb_edte').datepicker("option","maxDate",odmaxdate);
                $('#ASRC_UPD_DEL_lbl_optn').show();
                $('#option').val('SELECT').show();
            }
        }
    }
    var option="admin_search_update";
    xmlhttp.open("GET","COMMON.do?option="+option);
    xmlhttp.send();
    $('textarea').autogrow({onInitialize: true});
    $('#ASRC_UPD_DEL_btn_search').hide();
    $('#ASRC_UPD_DEL_btn_srch').hide();
    $('#ASRC_UPD_DEL_btn_srchupd').hide();
    $('#ASRC_UPD_DEL_btn_allsearch').hide();
    $('#ASRC_UPD_DEL_btn_srch').hide()
    //DATE PICKER FUNCTION
    $('.ASRC_UPD_DEL_date').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //CHANGE EVENT FOR STARTDATE AND ENDDATE
    $(document).on('change','#ASRC_UPD_DEL_tb_strtdte,#ASRC_UPD_DEL_tb_enddte',function(){
        ASRC_UPD_DEL_clear()
        $('#ASRC_UPD_DEL_tbl_htmltable').html('');
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    $('#ASRC_UPD_DEL_tb_dte').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    // CHANGE EVENT FOR STARTDATE
    $(document).on('change','#ASRC_UPD_DEL_tb_strtdte',function(){
        var ASRC_UPD_DEL_startdate = $('#ASRC_UPD_DEL_tb_strtdte').datepicker('getDate');
        var date = new Date( Date.parse( ASRC_UPD_DEL_startdate ));
        date.setDate( date.getDate()  );
        var ASRC_UPD_DEL_todate = date.toDateString();
        ASRC_UPD_DEL_todate = new Date( Date.parse( ASRC_UPD_DEL_todate ));
        $('#ASRC_UPD_DEL_tb_enddte').datepicker("option","minDate",ASRC_UPD_DEL_todate);
    });
    //CLICK EVENT FOR ALL ACTIVE EMPLOYEE SEARCH BUTTTON
    $(document).on('click','#ASRC_UPD_DEL_btn_allsearch',function(){
        $(".preloader").show();
        $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
        $('section').html('')
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').show();
        var date=$('#ASRC_UPD_DEL_tb_dte').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array!=''){
                    var ASRC_UPD_DEL_tableheader='<table id="ASRC_UPD_DEL_tbl_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:1400px"><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:90px">LOGIN ID</th><th style="width:1100px">REPORT</th><th style="width:90px">USERSTAMP</th><th style="width:100px" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                    for(var j=0;j<allvalues_array.length;j++){
                        var report=allvalues_array[j].admreport;
                        var reason=allvalues_array[j].admreason;
                        var permission=allvalues_array[j].permission;
                        var userstamp=allvalues_array[j].admuserstamp;
                        var timestamp=allvalues_array[j].admtimestamp;
                        var login=allvalues_array[j].admlogin;
                        if(permission==null)
                        {
                            if(report==null)
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;"> REASON:'+reason+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else if(reason==null)
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;">'+report+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>REASON:'+reason+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                        }
                        else
                        {
                            if(report==null)
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;"> REASON:'+reason+'<br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else if(reason==null)
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                ASRC_UPD_DEL_tableheader+='<tr ><td>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>REASON:'+reason+' <br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                        }
                    }
                    ASRC_UPD_DEL_tableheader+='</tbody></table>';
                    $('section').html(ASRC_UPD_DEL_tableheader);
                    $('#ASRC_UPD_DEL_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ],
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg[8].toString().replace("[DATE]",date);
                    $('#ASRC_UPD_DEL_errmsg').text(sd).show();
                    $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                }
            }
        }
        $('#ASRC_UPD_DEL_div_tablecontainer').show();
        var choice='ALLDATE';
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?alldate="+date+"&option="+choice,true);
        xmlhttp.send();
        sorting()
    });
    //CHANGE EVENT FOR BETWEEN RANGE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_btwnrange',function(){
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_rd_actveemp').attr('checked',false);
        $('#ASRC_UPD_DEL_rd_nonactveemp').attr('checked',false);
        $('#ASRC_UPD_DEL_rd_actveemp').show();
        $('#ASRC_UPD_DEL_lbl_actveemp').show();
        $('#ASRC_UPD_DEL_rd_nonactveemp').show();
        $('#ASRC_UPD_DEL_lbl_nonactveemp').show();
        $('#ASRC_UPD_DEL_lbl_btwnranges').show();
        $('#ASRC_UPD_DEL_lbl_dte').hide();
        $('#ASRC_UPD_DEL_tb_dte').hide();
        $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_btn_allsearch').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').hide();
        $('#ASRC_UPD_DEL_ta_reason').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    $('.enable').change(function(){
        if($("#ASRC_UPD_DEL_tb_dte").val()=='')
        {
            $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
        }
        else
        {
            $("#ASRC_UPD_DEL_btn_allsearch").removeAttr("disabled");
            $("#ASRC_UPD_DEL_btn_allsearch").show();
        }
    });
    //CHANGE EVENT FOR DATE
    $('#ASRC_UPD_DEL_tb_dte').change(function(){
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').html('');
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
    });
    //CHANGE EVENT FOR NON ACTIVE  RADIO
    $(document).on('change','#ASRC_UPD_DEL_rd_nonactveemp',function(){
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_lbl_loginid').show();
        $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_btn_searchupd').hide();
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_lbl_strtdte').hide();
        $('#ASRC_UPD_DEL_tb_strtdte').hide();
        $('#ASRC_UPD_DEL_lbl_enddte').hide();
        $('#ASRC_UPD_DEL_tb_enddte').hide();
        $('#ASRC_UPD_DEL_btn_search').hide();
        $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
        $('#ASRC_UPD_DEL_errmsg').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    //CHANGE EVENT FOR ALL ACTIVE  RANGE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_allactveemp',function(){

        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_lbl_allactveemps').show();
        $('#ASRC_UPD_DEL_btn_searchupd').hide();
        $('#ASRC_UPD_DEL_btn_search').hide();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_tb_dte').show();
        $('#ASRC_UPD_DEL_lbl_dte').show();
        $('#ASRC_UPD_DEL_tb_dte').val('');
        $('#ASRC_UPD_DEL_btn_allsearch').show();
        $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
        $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
        $('#ASRC_UPD_DEL_lbl_actveemp').hide();
        $('#ASRC_UPD_DEL_rd_actveemp').hide();
        $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
        $('#ASRC_UPD_DEL_lbl_strtdte').hide();
        $('#ASRC_UPD_DEL_tb_strtdte').hide();
        $('#ASRC_UPD_DEL_lbl_enddte').hide();
        $('#ASRC_UPD_DEL_tb_enddte').hide();
        $('#ASRC_UPD_DEL_lbl_loginid').hide();
        $('#ASRC_UPD_DEL_lb_loginid').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        ASRC_UPD_DEL_clear();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    // CHANGE EVENT FOR LOGINID LISTBOX
    $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
        ASRC_UPD_DEL_clear();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    //CHANGE EVENT FOR BETWEN ACTIVE EMPLOYEE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_actveemp',function(){
        if($('#ASRC_UPD_DEL_rd_actveemp').attr('checked',true))
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<active_emp.length;i++) {
                active_employee += '<option value="' + active_emp[i] + '">' + active_emp[i] + '</option>';
            }
            $('#ASRC_UPD_DEL_lb_loginid').html(active_employee);
        }
        $('#ASRC_UPD_DEL_lbl_loginid').show();
        $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
        $('#ASRC_UPD_DEL_lbl_btwnranges').show();
        ASRC_UPD_DEL_clear()
        $('#ASRC_UPD_DEL_btn_submit').hide();
        $('#ASRC_UPD_DEL_lbl_strtdte').hide();
        $('#ASRC_UPD_DEL_tb_strtdte').hide();
        $('#ASRC_UPD_DEL_lbl_enddte').hide();
        $('#ASRC_UPD_DEL_tb_enddte').hide();
        $('#ASRC_UPD_DEL_btn_search').hide();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
    });
    //CHANGE EVENT FOR BETWEN NON ACTIVE EMPLOYEE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_nonactveemp',function(){
        if($('#ASRC_UPD_DEL_rd_nonactveemp').attr('checked',true))
        {
            var nonactive_employee='<option>SELECT</option>';
            for (var i=0;i<nonactive_emp.length;i++) {
                nonactive_employee += '<option value="' + nonactive_emp[i] + '">' + nonactive_emp[i] + '</option>';
            }
            $('#ASRC_UPD_DEL_lb_loginid').html(nonactive_employee);
        }
        $('#ASRC_UPD_DEL_lbl_loginid').show();
        $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
        ASRC_UPD_DEL_clear()
        $('#ASRC_UPD_DEL_lbl_btwnranges').show();
        $('#ASRC_UPD_DEL_lbl_strtdte').hide();
        $('#ASRC_UPD_DEL_tb_strtdte').hide();
        $('#ASRC_UPD_DEL_lbl_enddte').hide();
        $('#ASRC_UPD_DEL_tb_enddte').hide();
        $('#ASRC_UPD_DEL_btn_search').hide();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    //CHANGE EVENT FOR LOGIN ID LISTBX
    $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
        var ASRC_UPD_DEL_loginidlist =$("#ASRC_UPD_DEL_lb_loginid").val();
        if(ASRC_UPD_DEL_loginidlist=='SELECT')
        {
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lbl_strtdte').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').hide();
            $('#ASRC_UPD_DEL_lbl_enddte').hide();
            $('#ASRC_UPD_DEL_tb_enddte').hide();
            $('#ASRC_UPD_DEL_btn_search').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').val('').hide();
            $('#ASRC_UPD_DEL_tb_enddte').val('').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
            $('#ASRC_UPD_DEL_tble_attendence').hide();
            $('#ASRC_UPD_DEL_lbl_dte').hide();
            $('#ASRC_UPD_DEL_date').hide();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lbl_reason').hide();
            $('#ASRC_UPD_DEL_ta_reason').hide();
            $('#ASRC_UPD_DEL_lb_ampm').hide();
            $('#ASRC_UPD_DEL_lbl_report').hide();
            $('#ASRC_UPD_DEL_ta_report').hide();
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_chk_permission').hide();
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_band').hide();
            $('#ASRC_UPD_DEL_tb_band').hide();
            ASRC_UPD_DEL_clear()
        }
        else
        {
            var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var finaldate=JSON.parse(xmlhttp.responseText);
                    var min_date=finaldate[0];
                    var max_date=finaldate[1];
                    var rprt_min_date=finaldate[2];
                    $('#ASRC_UPD_DEL_tb_enddte').datepicker("option","maxDate",max_date);
                    $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","minDate",min_date);
                    $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","maxDate",max_date);
                    $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","minDate",rprt_min_date);
                    $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","maxDate",rprt_max_date);
                }
            }
            var choice="login_id"
            xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?login_id="+loginid+"&option="+choice,true);
            xmlhttp.send();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lbl_strtdte').show();
            $('#ASRC_UPD_DEL_tb_strtdte').show();
            $('#ASRC_UPD_DEL_lbl_enddte').show();
            $('#ASRC_UPD_DEL_tb_enddte').show();
            $('#ASRC_UPD_DEL_btn_search').show();
            $('#ASRC_UPD_DEL_tb_strtdte').val('').show();
            $('#ASRC_UPD_DEL_tb_enddte').val('').show();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
            $("#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_secndselectprojct,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_mrg_projectlistbx,#ASRC_UPD_DEL_aftern_projectlistbx,#ASRC_UPD_DEL_btn_allsearch").html('')
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lbl_reason').hide();
            $('#ASRC_UPD_DEL_ta_reason').hide();
            $("#ASRC_UPD_DEL_btn_submit,#ASRC_UPD_DEL_mrg_projectlistbx").html('');
            $('#ASRC_UPD_DEL_lb_ampm').hide();
            $('#ASRC_UPD_DEL_lbl_report').hide();
            $('#ASRC_UPD_DEL_ta_report').hide();
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_chk_permission').hide();
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_band').hide();
            $('#ASRC_UPD_DEL_tb_band').hide();
        }
    });
    // CHANGE EVENT FOR STARTDATE AND ENDDATE
    $(document).on('change','.valid',function(){
        if(($("#ASRC_UPD_DEL_tb_strtdte").val()=='')||($("#ASRC_UPD_DEL_tb_enddte").val()==''))
        {

            $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
        }
        else
        {
            $("#ASRC_UPD_DEL_btn_search").removeAttr("disabled");
        }
    });
    var values_array=[];
    $(document).on('click','#ASRC_UPD_DEL_btn_search',function(){
        $('section').html('')
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $(".preloader").show();
        flextablerange()
        $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
        $("#ASRC_UPD_DEL_btn_del").attr("disabled", "disabled");
    });
    //FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    // FUNCTION FOR DATATABLE
    function flextablerange(){
        var start_date=$('#ASRC_UPD_DEL_tb_strtdte').val();
        var end_date=$('#ASRC_UPD_DEL_tb_enddte').val();
        var activeloginid=$('#ASRC_UPD_DEL_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                values_array=JSON.parse(xmlhttp.responseText);
                if(values_array.length!=0){
                    var ASRC_UPD_DEL_table_header='<table id="ASRC_UPD_DEL_tbl_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:1400px"><thead  bgcolor="#6495ed" style="color:white"><tr><th  style="width:10px"></th><th style="width:70px"  class="uk-date-column" nowrap>DATE</th><th style="width:1100px">REPORT</th><th style="width:150px">USERSTAMP</th><th class="uk-timestp-column" style="width:100px">TIMESTAMP</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        var emp_date=values_array[j].date;
                        var emp_report=values_array[j].report;
                        var emp_reason=values_array[j].reason;
                        var timestamp=values_array[j].timestamp;
                        var userstamp=values_array[j].user_stamp;
                        var permission=values_array[j].permission;
                        var id=values_array[j].id;
                        var flag=values_array[j].flag;
                        if(permission==null)
                        {
                            if(emp_report==null)
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;"> REASON:'+emp_reason+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else if(emp_reason==null)
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>REASON:'+emp_reason+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                        }
                        else
                        {
                            if(emp_report==null)
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;"> REASON:'+emp_reason+'<br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else if(emp_reason==null)
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td>'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>REASON:'+emp_reason+' <br>PERMISSION:'+permission+' hrs</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                            }
                        }
                    }
                    ASRC_UPD_DEL_table_header+='</tbody></table>';
                    $('section').html(ASRC_UPD_DEL_table_header);
                    $('#ASRC_UPD_DEL_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ],
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg[6].toString().replace("[SDATE]",start_date);
                    var msg=sd.toString().replace("[EDATE]",end_date);
                    $('#ASRC_UPD_DEL_errmsg').text(msg).show();
                    $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                }
            }

        }
        $('#ASRC_UPD_DEL_div_tablecontainer').show();
        var choice='DATERANGE';
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?start_date="+start_date+"&end_date="+end_date+"&option="+choice+"&actionloginid="+activeloginid,true);
        xmlhttp.send();
        sorting()
    }
    //FUNCTION FOR SORTING
    function sorting(){
        jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        }
        jQuery.fn.dataTableExt.oSort['uk_timestp-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_timestp-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        };
    }
    //CHANGE EVENT FOR RADIO BUTTTON
    $(document).on('change','.ASRC_UPD_DEL_class_radio',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $("#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_secndselectprojct,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_mrg_projectlistbx,#ASRC_UPD_DEL_aftern_projectlistbx,#ASRC_UPD_DEL_btn_allsearch").html('')
        $('#ASRC_UPD_DEL_btn_srch').show()
        $('#ASRC_UPD_DEL_btn_del').show();
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $("#ASRC_UPD_DEL_btn_srch").removeAttr("disabled");
        $("#ASRC_UPD_DEL_btn_del").removeAttr("disabled");
        $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
        $('#ASRC_UPD_DEL_tble_attendence').hide();
        $('#ASRC_UPD_DEL_btn_submit').hide();
        $('#ASRC_UPD_DEL_lbl_txtselectproj').hide();
        $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
        $('#ASRC_UPD_DEL_ta_report').hide();
        $('#ASRC_UPD_DEL_lbl_report').hide();
        $('#ASRC_UPD_DEL_chk_permission').hide();
        $('#ASRC_UPD_DEL_lbl_permission').hide();
        $('#ASRC_UPD_DEL_lb_timing').hide();
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_lbl_reason').hide();
        $('#ASRC_UPD_DEL_ta_reason').hide();
        $('#ASRC_UPD_DEL_lb_ampm').hide();
        $('#ASRC_UPD_DEL_lbl_band').hide();
        $('#ASRC_UPD_DEL_tb_band').hide();
        $('#ASRC_UPD_DEL_lbl_report').hide();
        $('#ASRC_UPD_DEL_ta_report').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    // CLICK EVENT FOR DELETE BUTTON
    $(document).on('click','#ASRC_UPD_DEL_btn_del',function(){
        $(".preloader").show();
        var delid=$("input[name=ASRC_UPD_DEL_rd_flxtbl]:checked").val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var delete_msg=xmlhttp.responseText;
                if(delete_msg==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[2],confirmation:true}});
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
                else if(delete_msg==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[5],confirmation:true}});
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:delete_msg,confirmation:true}});
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
            }
        }
        var choice="DELETE";
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?del_id="+delid+"&option="+choice,true);
        xmlhttp.send();
    });
    // CLICK EVENT FOR SEARCH BUTTON
    var attendance;
    var date;
    var report;
    var userstamp;
    var timestamp;
    var reason;
    var permission;
    var pdid;
    var morningsession;
    var afternoonsession;
    var bandwidth;
    var projectid_array;
    var flag;
    $(document).on('click','#ASRC_UPD_DEL_btn_srch',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        var SRC_UPD_idradiovalue=$('input:radio[name=ASRC_UPD_DEL_rd_flxtbl]:checked').attr('id');
        $("#ASRC_UPD_DEL_btn_srch").attr("disabled", "disabled");
        $("#ASRC_UPD_DEL_btn_del").attr("disabled", "disabled");
        for(var j=0;j<values_array.length;j++){
            var id=values_array[j].id;
            if(id==SRC_UPD_idradiovalue)
            {
                date=  values_array[j].date;
                report=values_array[j].report1;
                userstamp=values_array[j].userstamp;
                timestamp=values_array[j].timestamp;
                reason=values_array[j].reason1;
                permission=values_array[j].permission;
                attendance=values_array[j].attendance;
                pdid=values_array[j].pdid;
                morningsession=values_array[j].morningsession;
                afternoonsession=values_array[j].afternoonsession;
                bandwidth=values_array[j].bandwidth;
                flag=values_array[j].flag;
                if(attendance=='1')
                {
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<permission_array.length;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                }
                else if((attendance=='0.5') || (attendance=='0.5OD'))
                {
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                }
                $('#ASRC_UPD_DEL_tble_attendence').show();
                form_show(attendance)
            }
        }
    });
    // FUNCTION FOR PROJECTID CHECKED
    function projecdid(){
        for(var i=0;i<project_array.length;i++){
            for(var j=0;j<projectid_array.length;j++){
                if(projectid_array[j]==project_array[i][1]){
                    $("#" + project_array[i][1]+'p').prop( "checked", true );
                }
            }
        }
    }
    // FUNCTION FOR FORM AFTER SEARCH BUTTON CLICK
    function form_show(attendance)
    {
        if(attendance=='1')
        {
            projectid_array=pdid.split(",");
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            $('#ASRC_UPD_DEL_lb_attendance').val('1');
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lb_ampm').hide();
            $('#ASRC_UPD_DEL_tble_projectlistbx').show();
            $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
            projectlist();
            projecdid();
            ASRC_UPD_DEL_report()
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled","disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled","disabled");
            $('#ASRC_UPD_DEL_ta_report').val(report);
            ASRC_UPD_DEL_tble_bandwidth()
            $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
            $('#ASRC_UPD_DEL_btn_submit').show();
        }
        if(attendance=='0')
        {
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            $('#ASRC_UPD_DEL_lb_attendance').val('0');
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_permission').attr("disabled","disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').attr("disabled","disabled");
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            $('#ASRC_UPD_DEL_lb_ampm').val("FULLDAY");
            ASRC_UPD_DEL_reason()
            $('#ASRC_UPD_DEL_ta_reason').val(reason);
            $('#ASRC_UPD_DEL_btn_submit').show();
            if(flag==null)
            {
                $('#ASRC_UPD_DEL_chk_flag').attr('checked',false);
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
            }
            else
            {
                $('#ASRC_UPD_DEL_chk_flag').attr('checked','checked');
                $("#ASRC_UPD_DEL_chk_flag").show();
                $("#ASRC_UPD_DEL_lbl_flag").show();
            }
        }
        if(attendance=='0.5')
        {
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            projectid_array=pdid.split(",");
            $('#ASRC_UPD_DEL_lb_attendance').val('0');
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ABSENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();


            }
            else if((morningsession=='ABSENT' && afternoonsession=='PRESENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            ASRC_UPD_DEL_reason()
            $('#ASRC_UPD_DEL_ta_reason').val(reason);
            ASRC_UPD_DEL_report()
            $('#ASRC_UPD_DEL_ta_report').val(report);
            ASRC_UPD_DEL_tble_bandwidth()
            $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
            $('#ASRC_UPD_DEL_btn_submit').show();

        }
        if(attendance=='OD')
        {
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            $('#ASRC_UPD_DEL_lb_attendance').val('OD');
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            $('#ASRC_UPD_DEL_lb_ampm').val("FULLDAY");
            $('#ASRC_UPD_DEL_rd_permission').attr("disabled","disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').attr("disabled","disabled");
            ASRC_UPD_DEL_reason()
            $('#ASRC_UPD_DEL_ta_reason').val(reason);
            $('#ASRC_UPD_DEL_btn_submit').show();
        }
        if(attendance=='0.5OD')
        {
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            projectid_array=pdid.split(",");
            $('#ASRC_UPD_DEL_lb_attendance').val('OD');
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ONDUTY'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            else if((morningsession=='ONDUTY' && afternoonsession=='PRESENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            ASRC_UPD_DEL_reason()
            $('#ASRC_UPD_DEL_ta_reason').val(reason);
            ASRC_UPD_DEL_report()
            $('#ASRC_UPD_DEL_ta_report').val(report);
            ASRC_UPD_DEL_tble_bandwidth()
            $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
            $('#ASRC_UPD_DEL_btn_submit').show();

        }
        if(permission!=null)
        {
            $('#ASRC_UPD_DEL_rd_permission').attr('checked','checked');
            $('#ASRC_UPD_DEL_lb_timing').show();
            $('#ASRC_UPD_DEL_lb_timing').val(permission);
        }
        else
        {
            $('#ASRC_UPD_DEL_rd_nopermission').attr('checked','checked');
        }

    }
    // CHANGE EVENT FOR ATTENDANCE
    $('#ASRC_UPD_DEL_lb_attendance').change(function(){
        if(attendance==$('#ASRC_UPD_DEL_lb_attendance').val())
        {
            $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
            $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
            $('#ASRC_UPD_DEL_tble_enterthereport').html('');
            $('#ASRC_UPD_DEL_tble_bandwidth').html('');
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
            form_show(attendance)
            $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
        }
        else{
            projectid_array='';
            $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
            $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
            $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
            if($('#ASRC_UPD_DEL_lb_attendance').val()=='1')
            {
                $('#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_ta_reason,#ASRC_UPD_DEL_tble_bandwidth').html('');
                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<permission_array.length;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lb_ampm').hide();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                projectlist();
                ASRC_UPD_DEL_report();
                ASRC_UPD_DEL_tble_bandwidth();
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                $('#ASRC_UPD_DEL_errmsg').hide();
            }
            else if($('#ASRC_UPD_DEL_lb_attendance').val()=='0')
            {
                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<4;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                $('#ASRC_UPD_DEL_lbl_session').show();
                $('#ASRC_UPD_DEL_lb_ampm').val('SELECT').show();
                $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                $('#ASRC_UPD_DEL_errmsg').hide();

            }
            else if($('#ASRC_UPD_DEL_lb_attendance').val()=='OD')
            {
                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<4;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                $('#ASRC_UPD_DEL_lbl_session').show();
                $('#ASRC_UPD_DEL_lb_ampm').val('SELECT').show();
                $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                $('#ASRC_UPD_DEL_errmsg').hide();

            }
        }
    });
    var maxdate=new Date();
    var month=maxdate.getMonth()+1;
    var year=maxdate.getFullYear();
    var date=maxdate.getDate();
    var rprt_max_date = new Date(year,month,date);
    $('#ASRC_UPD_DEL_ta_reportdate').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    // CHANGE EVENT FOR REPORTDATE ALREADY EXISTS
    $(document).on('change','#ASRC_UPD_DEL_ta_reportdate',function(){
        $('.preloader').show();
        var reportdate=$('#ASRC_UPD_DEL_ta_reportdate').val();
        var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var msgalert=xmlhttp.responseText;
                $('.preloader').hide();
                if(msgalert==1)
                {
                    var msg=err_msg[3].toString().replace("[DATE]",reportdate)
                    $('#ASRC_UPD_DEL_errmsg').text(msg).show();
                    $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                }
                else{

                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
            }

        }
        var choice="DATE"
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?reportdate="+reportdate+"&login_id="+loginid+"&option="+choice,true);
        xmlhttp.send();
    });
    //CLICK EVENT FOR PERMISSION RADIO BUTTON
    $(document).on('click','#ASRC_UPD_DEL_rd_permission',function()
    {
        if($('#ASRC_UPD_DEL_rd_permission').attr("checked","checked"))
        {
            $('#ASRC_UPD_DEL_lb_timing').val('SELECT').show();

        }
        else
        {
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
        }
    });
    //CLICK EVENT FOR NOPERMISSION RADIO BUTTON
    $(document).on('click','#ASRC_UPD_DEL_rd_nopermission',function()
    {
        $('#ASRC_UPD_DEL_lb_timing').hide();
        $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);

    });
    // FUNCTION FOR FORM CLEAR
    function ASRC_UPD_DEL_clear(){
        $('#ASRC_UPD_DEL_tble_attendence').hide();
        $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
        $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
        $('#ASRC_UPD_DEL_tble_enterthereport').html('');
        $('#ASRC_UPD_DEL_tble_bandwidth').html('');
        $('#ASRC_UPD_DEL_btn_allsearch').html('');
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_lbl_permission').hide();
        $('#ASRC_UPD_DEL_rd_permission').hide();
        $('#ASRC_UPD_DEL_lbl_nopermission').hide();
        $('#ASRC_UPD_DEL_rd_nopermission').hide();
        $('#ASRC_UPD_DEL_lb_timing').hide();
        $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
        $('#ASRC_UPD_DEL_lb_ampm').hide();
        $('#ASRC_UPD_DEL_btn_submit').hide();
        $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
        $('#ASRC_UPD_DEL_lbl_oddte').hide();
        $('#ASRC_UPD_DEL_tb_oddte').hide();
        $('#ASRC_UPD_DEL_lbl_des').hide();
        $('#ASRC_UPD_DEL_ta_des').hide();
        $('#ASRC_UPD_DEL_odsubmit').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    }
    // CHANGE EVENT FOR SESSION
    $('#ASRC_UPD_DEL_lb_ampm').change(function(){
        projectid_array='';
        $('#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
        if($('#ASRC_UPD_DEL_lb_ampm').val()=='SELECT')
        {
            $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
            $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
            $('#ASRC_UPD_DEL_tble_enterthereport').html('');
            $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
            $('#ASRC_UPD_DEL_tble_bandwidth').html('');
            $('#ASRC_UPD_DEL_btn_submit').hide();
            $('#ASRC_UPD_DEL_banerrmsg').hide();
        }
        else if($('#ASRC_UPD_DEL_lb_ampm').val()=='FULLDAY')
        {
            $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
            ASRC_UPD_DEL_reason();
            $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
            $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
            $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
            $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
            $('#ASRC_UPD_DEL_btn_submit').show();
//            $("#ASRC_UPD_DEL_chk_flag").show();
//            $("#ASRC_UPD_DEL_lbl_flag").show();
            $('#ASRC_UPD_DEL_banerrmsg').hide();
        }
        else
        {
            ASRC_UPD_DEL_reason();
            $('#ASRC_UPD_DEL_btn_submit').hide();
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
            $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<4;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
            $('#ASRC_UPD_DEL_tble_projectlistbx').show();
            $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
            projectlist();
            ASRC_UPD_DEL_report();
            ASRC_UPD_DEL_tble_bandwidth();
            $('#ASRC_UPD_DEL_banerrmsg').hide();
        }
    });
    // CHANGE EVENT FOR REPORT TEXTAREA
    $(document).on('change','#ASRC_UPD_DEL_ta_report',function(){

        $('#ASRC_UPD_DEL_btn_submit').show();
        $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    //CHANGE EVENT FOR BANDWIDTH TEXTBX
    $(document).on('change blur','#ASRC_UPD_DEL_tb_band',function(){
        var bandwidth=$('#ASRC_UPD_DEL_tb_band').val();
        if(bandwidth > 1000)
        {
            var msg=err_msg[9].toString().replace("[BW]",bandwidth);
            $('#ASRC_UPD_DEL_banerrmsg').text(msg).show();
        }
        else
        {
            $('#ASRC_UPD_DEL_banerrmsg').hide();
        }
    });
    // FUNCTION FOR PROJECT LIST
    function projectlist(){
        $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html("");
        var project_list;
        for (var i=0;i<project_array.length;i++) {
            project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] + '</td></tr>';
        }

        $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').append(project_list);
    }
    // FUNCTION FOR REASON
    function ASRC_UPD_DEL_reason(){
        $('<tr><td width="150"><label name="ASRC_UPD_DEL_lbl_reason" id="ASRC_UPD_DEL_lbl_reason">REASON<em>*</em></label></td><td><textarea  name="ASRC_UPD_DEL_ta_reason" id="ASRC_UPD_DEL_ta_reason" class="update_validate"></textarea></td></tr>').appendTo($("#ASRC_UPD_DEL_tble_reasonlbltxtarea"));
    }
    // FUNCTION FOR REPORT
    function ASRC_UPD_DEL_report(){
        $('<tr><td width="150"><label name="ASRC_UPD_DEL_lbl_report" id="ASRC_UPD_DEL_lbl_report" >ENTER THE REPORT<em>*</em></label></td><td><textarea  name="ASRC_UPD_DEL_ta_report" id="ASRC_UPD_DEL_ta_report" class="update_validate"></textarea></td></tr>').appendTo($("#ASRC_UPD_DEL_tble_enterthereport"));
    }
    // FUNCTIO FOR BANDWIDTH
    function ASRC_UPD_DEL_tble_bandwidth(){
        $('<tr><td width="150"><label name="ASRC_UPD_DEL_lbl_band" id="ASRC_UPD_DEL_lbl_band">BANDWIDTH<em>*</em></label></td><td><input type="text" name="ASRC_UPD_DEL_tb_band" id="ASRC_UPD_DEL_tb_band" class="autosize amountonly update_validate" style="width:75px;" ><td><label name="ASRC_UPD_DEL_lbl_band" id="ASRC_UPD_DEL_lbl_band">MB</label></td></td></tr></tr>').appendTo($("#ASRC_UPD_DEL_tble_bandwidth"));
        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
    }
    //FORM VALIDATION
    $(document).on('change blur','.update_validate',function(){
        var ASRC_UPD_DEL_sessionlstbx= $("#ASRC_UPD_DEL_lb_ampm").val();
        var ASRC_UPD_DEL_reasontxtarea =$("#ASRC_UPD_DEL_ta_reason").val();
        var ASRC_UPD_DEL_reportenter =$("#ASRC_UPD_DEL_ta_report").val();
        var ASRC_UPD_DEL_bndtxt = $("#ASRC_UPD_DEL_tb_band").val();
        var ASRC_UPD_DEL_projectselectlistbx=$('input[name="checkbox[]"]:checked').length;
        var ASRC_UPD_DEL_permissionlstbx = $("#ASRC_UPD_DEL_lb_timing").val();
        var ASRC_UPD_DEL_permission=$("input[name=permission]:checked").val()=="PERMISSION";
        var ASRC_UPD_DEL_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
        var ASRC_UPD_DEL_presenthalfdysvld=$("#ASRC_UPD_DEL_lb_attendance").val();
        if(((ASRC_UPD_DEL_presenthalfdysvld=='0') && (ASRC_UPD_DEL_sessionlstbx=='AM' || ASRC_UPD_DEL_sessionlstbx=="PM")) || ((ASRC_UPD_DEL_presenthalfdysvld=='OD') && (ASRC_UPD_DEL_sessionlstbx=='AM' || ASRC_UPD_DEL_sessionlstbx=="PM") ))
        {
            if(((ASRC_UPD_DEL_reasontxtarea.trim()!="")&&(ASRC_UPD_DEL_reportenter!='')&&( ASRC_UPD_DEL_projectselectlistbx>0) && (ASRC_UPD_DEL_bndtxt!='')&& (parseFloat(ASRC_UPD_DEL_bndtxt)!=0) && (ASRC_UPD_DEL_bndtxt<=1000) && ((ASRC_UPD_DEL_permission==true) || (ASRC_UPD_DEL_nopermission==true))))
            {
                if(ASRC_UPD_DEL_permission==true)
                {
                    if(ASRC_UPD_DEL_permissionlstbx!='SELECT')
                    {
                        $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                    }
                    else
                    {
                        $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                    }
                }
                else
                {
                    $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                }
            }
            else
            {
                $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
            }
        }
        else if((ASRC_UPD_DEL_presenthalfdysvld=='0' && ASRC_UPD_DEL_sessionlstbx=='FULLDAY') || (ASRC_UPD_DEL_presenthalfdysvld=='OD' && ASRC_UPD_DEL_sessionlstbx=='FULLDAY'))
        {
            if(ASRC_UPD_DEL_reasontxtarea.trim()=="")
            {
                $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
            }
            else
            {
                $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
            }
        }
        else if(ASRC_UPD_DEL_presenthalfdysvld=='1')
        {
            if(((ASRC_UPD_DEL_reportenter.trim()!="")&&(ASRC_UPD_DEL_bndtxt!='')&& (parseFloat(ASRC_UPD_DEL_bndtxt)!=0) && (ASRC_UPD_DEL_bndtxt<=1000) && (ASRC_UPD_DEL_bndtxt<=1000)&&( ASRC_UPD_DEL_projectselectlistbx>0) && ((ASRC_UPD_DEL_permission==true) || (ASRC_UPD_DEL_nopermission==true))))
            {
                if(ASRC_UPD_DEL_permission==true)
                {
                    if(ASRC_UPD_DEL_permissionlstbx!='SELECT')
                    {
                        $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                    }
                    else
                    {
                        $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                    }
                }
                else
                {
                    $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                }
            }
            else
            {
                $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
            }
        }
    });
    //FUNCTION FOR UPDATE BUTTON
    $(document).on('click','#ASRC_UPD_DEL_btn_submit',function(){
        $(".preloader").show();
        var formElement = document.getElementById("ASRC_UPD_DEL_form_adminsearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:err_msg[1],confirmation:true}});
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
                else if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:err_msg[7],confirmation:true}});
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:msg_alert,confirmation:true}});
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
            }

        }
        var option="UPDATE"
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
    // CHANGE EVENT FOR OPTION LIST BOX
    $(document).on('change','#option',function(){
        $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
        $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
        $('#ASRC_UPD_DEL_oderrmsg').hide();
        if($('#option').val()=='ADMIN REPORT SEARCH/UPDATE')
        {
            $('#ASRC_UPD_DEL_tbl_entry').show();
            $('#ASRC_UPD_DEL_tble_dailyuserentry').show();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
            $('#ASRC_UPD_DEL_btn_del').hide();
            $('#ASRC_UPD_DEL_rd_allactveemp').attr('checked',false);
            $('#ASRC_UPD_DEL_rd_btwnrange').attr('checked',false);
            $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
            $('#ASRC_UPD_DEL_tb_dte').hide();
            $('#ASRC_UPD_DEL_btn_submit').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $('#ASRC_UPD_DEL_lbl_dte').hide();
            $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
            $('#ASRC_UPD_DEL_rd_actveemp').hide();
            $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
            $('#ASRC_UPD_DEL_lbl_strtdte').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').hide();
            $('#ASRC_UPD_DEL_lbl_enddte').hide();
            $('#ASRC_UPD_DEL_tb_enddte').hide();
            $('#ASRC_UPD_DEL_lbl_loginid').hide();
            $('#ASRC_UPD_DEL_lb_loginid').hide();
            $('#ASRC_UPD_DEL_lbl_actveemp').hide();
            $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
            $('#ASRC_UPD_DEL_btn_search').hide();
            $('#ASRC_UPD_DEL_tble_attendence').hide();
            $('#ASRC_UPD_DEL_oderrmsg').hide();
            $('#ASRC_UPD_DEL_tble_odshow').hide();
            $('#ASRC_UPD_DEL_btn_allsearch').hide();
            $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide();
            $('#ASRC_UPD_DEL_odsrch_btn').hide();
            $('#ASRC_UPD_DEL_errmsg').text("").hide();
            ASRC_UPD_DEL_clear()
            $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
        }
        else if($('#option').val()=='ONDUTY REPORT SEARCH/UPDATE')
        {
            $('#ASRC_UPD_DEL_tble_odshow').show();
            $('#ASRC_UPD_DEL_errmsg').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $('#ASRC_UPD_DEL_btn_del').hide();
            $('#ASRC_UPD_DEL_tb_sdte').val('');
            $('#ASRC_UPD_DEL_tb_edte').val('');
            $('#ASRC_UPD_DEL_errmsg').hide();
            $("#ASRC_UPD_DEL_od_btn").attr("disabled", "disabled");
            $('#ASRC_UPD_DEL_tbl_entry').hide();
            ASRC_UPD_DEL_clear()
            $('#ASRC_UPD_DEL_div_tablecontainer').hide();
            $('#ASRC_UPD_DEL_lbl_reportdte').hide();
            $('#ASRC_UPD_DEL_ta_reportdate').hide();

        }
        else
        {
            $('#ASRC_UPD_DEL_tbl_entry').hide();
            $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
            $('#ASRC_UPD_DEL_btn_del').hide();
            $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
            $('#ASRC_UPD_DEL_tb_dte').hide();
            $('#ASRC_UPD_DEL_btn_submit').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $('#ASRC_UPD_DEL_lbl_dte').hide();
            $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
            $('#ASRC_UPD_DEL_rd_actveemp').hide();
            $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
            $('#ASRC_UPD_DEL_lbl_strtdte').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').hide();
            $('#ASRC_UPD_DEL_lbl_enddte').hide();
            $('#ASRC_UPD_DEL_tb_enddte').hide();
            $('#ASRC_UPD_DEL_lbl_loginid').hide();
            $('#ASRC_UPD_DEL_lb_loginid').hide();
            $('#ASRC_UPD_DEL_lbl_actveemp').hide();
            $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
            $('#ASRC_UPD_DEL_btn_search').hide();
            $('#ASRC_UPD_DEL_tble_attendence').hide();
            $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
            $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
            $('#ASRC_UPD_DEL_tble_odshow').hide();
            $('#ASRC_UPD_DEL_errmsg').hide();
            ASRC_UPD_DEL_clear()
            $('#ASRC_UPD_DEL_oderrmsg').hide();
            $('#ASRC_UPD_DEL_btn_allsearch').hide();
            $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $('#ASRC_UPD_DEL_btn_del').hide();
            $('#ASRC_UPD_DEL_tb_sdte').hide();
            $('#ASRC_UPD_DEL_tb_edte').hide();
            $('#ASRC_UPD_DEL_tbl_entry').hide();
            $('#ASRC_UPD_DEL_div_tablecontainer').hide();
            $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
            $('#ASRC_UPD_DEL_odsrch_btn').hide();
            $('#ASRC_UPD_DEL_lbl_reportdte').hide();
            $('#ASRC_UPD_DEL_ta_reportdate').hide();
        }
    });
// ONDUTY SEARCH AND UPDATE PART
    $('.date').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });

// CHANGE EVENT FOR ONDUTY START DATE
    $(document).on('change','#ASRC_UPD_DEL_tb_sdte',function(){
        var ASRC_UPD_DEL_sdate = $('#ASRC_UPD_DEL_tb_sdte').datepicker('getDate');
        var date = new Date( Date.parse( ASRC_UPD_DEL_sdate ));
        date.setDate( date.getDate()  );
        var ASRC_UPD_DEL_edate = date.toDateString();
        ASRC_UPD_DEL_edate = new Date( Date.parse( ASRC_UPD_DEL_edate ));
        $('#ASRC_UPD_DEL_tb_edte').datepicker("option","minDate",ASRC_UPD_DEL_edate);

    });
    // CHANGE EVENT FOR  STARTDATE AND ENDDATE
    $('.date').change(function(){
        if(($("#ASRC_UPD_DEL_tb_sdte").val()=='')||($("#ASRC_UPD_DEL_tb_edte").val()==''))
        {
            $("#ASRC_UPD_DEL_od_btn").attr("disabled", "disabled");
        }
        else
        {
            $("#ASRC_UPD_DEL_od_btn").removeAttr("disabled");
        }

    });
    $(document).on('change','#ASRC_UPD_DEL_tb_sdte,#ASRC_UPD_DEL_tb_edte',function(){
        $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').html('');
        $('#ASRC_UPD_DEL_oderrmsg').hide();
        $('#ASRC_UPD_DEL_odsrch_btn').hide();
        $('#ASRC_UPD_DEL_lbl_oddte').hide();
        $('#ASRC_UPD_DEL_tb_oddte').hide();
        $('#ASRC_UPD_DEL_lbl_des').hide();
        $('#ASRC_UPD_DEL_ta_des').hide();
        $('#ASRC_UPD_DEL_odsubmit').hide();
        $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
    });
    //CLICK FUNCTIO FOR SEARCH BUTTON IN ONDUTY
    $(document).on('click','#ASRC_UPD_DEL_od_btn',function(){
        $('#ASRC_UPD_section_od').html('')
        $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
        $(".preloader").show();
        $('#ASRC_UPD_DEL_od_btn').attr("disabled","disabled");
        $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').show();
        ondutyflextable()
    });
    // ONDUTY DATA TABLE
    function ondutyflextable(){
        var sdate=$('#ASRC_UPD_DEL_tb_sdte').val();
        var edate=$('#ASRC_UPD_DEL_tb_edte').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array.length!=0){
                    var ASRC_UPD_DEL_tbleheader='<table id="ASRC_UPD_DEL_tbl_ondutyhtmltable" border="1"  cellspacing="0" class="srcresult" style="width:1100px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:10px;"></th><th style="width:20px;" class="uk-date-column">DATE</th><th style="width:500px">DESCRIPTION</th><th style="width:90px">USERSTAMP</th><th style="width:100px" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>';
                    for(var j=0;j<allvalues_array.length;j++){
                        var id=allvalues_array[j].id;
                        var description=allvalues_array[j].description;
                        var userstamp=allvalues_array[j].userstamp;
                        var timestamp=allvalues_array[j].timestamp;
                        var date=allvalues_array[j].date;
                        ASRC_UPD_DEL_tbleheader+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_tbl" class="ASRC_UPD_DEL_class_radio odclass" id='+id+'  value='+id+' ></td><td>'+date+'</td><td style="max-width:600px">'+description+'</td><td style="max-width:155px">'+userstamp+'</td><td style="max-width:100px" nowrap>'+timestamp+'</td></tr>';
                    }
                    ASRC_UPD_DEL_tbleheader+='</tbody></table>';
                    $('#ASRC_UPD_section_od').html(ASRC_UPD_DEL_tbleheader);
                    $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ],
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg[6].toString().replace("[SDATE]",sdate);
                    var msg=sd.toString().replace("[EDATE]",edate);
                    $('#ASRC_UPD_DEL_oderrmsg').text(msg).show();
                    $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                }
            }
        }
        $('#ASRC_UPD_DEL_div_ondutytablecontainer').show();
        var choice='ONDUTY';
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?sdate="+sdate+"&edate="+edate+"&option="+choice,true);
        xmlhttp.send();
        sorting()
    }
// CLICK EVENT FOR ONDUTY RADIO BUTTON
    $(document).on('click','.odclass',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $('#ASRC_UPD_DEL_odsrch_btn').show();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_odsrch_btn').removeAttr("disabled","disabled");
        $('#ASRC_UPD_DEL_lbl_oddte').hide();
        $('#ASRC_UPD_DEL_tb_oddte').hide();
        $('#ASRC_UPD_DEL_lbl_des').hide();
        $('#ASRC_UPD_DEL_ta_des').hide();
        $('#ASRC_UPD_DEL_odsubmit').hide();

    });
    // CLICK EVENT FOR ONDUTY SEARCH BUTTON
    $(document).on('click','#ASRC_UPD_DEL_odsrch_btn',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        var ASRC_UPD_DEL_radio=$('input:radio[name=ASRC_UPD_DEL_rd_tbl]:checked').attr('id');
        $("#ASRC_UPD_DEL_odsrch_btn").attr("disabled", "disabled");
        $("#updatepart").show();
        for(var j=0;j<allvalues_array.length;j++){
            var id=allvalues_array[j].id;
            if(id==ASRC_UPD_DEL_radio)
            {
                var date=  allvalues_array[j].date;
                var description=allvalues_array[j].description;
                $('#ASRC_UPD_DEL_lbl_oddte').show();
                $('#ASRC_UPD_DEL_tb_oddte').val(date).show();
                $('#ASRC_UPD_DEL_lbl_des').show();
                $('#ASRC_UPD_DEL_ta_des').val(description).show();
                $('#ASRC_UPD_DEL_odsubmit').show();
                $('#ASRC_UPD_DEL_odsubmit').attr("disabled","disabled");
                $('#ASRC_UPD_DEL_oderrmsg').hide();
            }
        }
    });
    $('#ASRC_UPD_DEL_ta_des').change(function(){
        if($("#ASRC_UPD_DEL_ta_des").val()=='')
        {
            $("#ASRC_UPD_DEL_odsubmit").attr("disabled", "disabled");
        }
        else
        {
            $("#ASRC_UPD_DEL_odsubmit").removeAttr("disabled");
            $("#ASRC_UPD_DEL_odsubmit").show();
        }
    });
    // CLICK FUNCTIO ONDUTY UPDATE BUTTON
    $('#ASRC_UPD_DEL_odsubmit').click(function(){
        $(".preloader").show();
        var formElement = document.getElementById("ASRC_UPD_DEL_form_adminsearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ONDUTY SEARCH/UPDATE",msgcontent:msg_alert,confirmation:true}});
                ondutyflextable()
                $('#ASRC_UPD_DEL_tb_oddte').hide();
                $('#ASRC_UPD_DEL_lbl_oddte').hide();
                $('#ASRC_UPD_DEL_ta_des').hide();
                $('#ASRC_UPD_DEL_lbl_des').hide();
                $("#ASRC_UPD_DEL_odsubmit").hide();
                $('#ASRC_UPD_DEL_ta_des').css("height", "50px");
            }
        }
        var option="ONDUTYUPDATE";
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
});
//END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;"><p><h3>ADMIN REPORT SEARCH/UPDATE/DELETE</h3><p></div></div>
    <form   id="ASRC_UPD_DEL_form_adminsearchupdate" class="content" >
        <table>
            <tr>
                <td width="150"><label name="ASRC_UPD_DEL_lbl_optn" id="ASRC_UPD_DEL_lbl_optn">SELECT A OPTION</label><em>*</em></td>
                <td width="150">
                    <select id="option" name="option">
                        <option>SELECT</option>
                        <option>ADMIN REPORT SEARCH/UPDATE</option>
                        <option>ONDUTY REPORT SEARCH/UPDATE</option>
                    </select>
                </td>
            </tr>
        </table>
        <table id="ASRC_UPD_DEL_tble_dailyuserentry" hidden>
            <table id="ASRC_UPD_DEL_tbl_entry">
                <tr>
                    <td><input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_btwnrange" value="RANGES" class='attnd'>
                        <label name="ASRC_UPD_DEL_lbl_btwnrange" id="ASRC_UPD_DEL_lbl_btwnrange">BETWEEN RANGE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_allactveemp"   value="RANGES" class='attnd'>
                        <label name="ASRC_UPD_DEL_lbl_allactveemp" id="ASRC_UPD_DEL_lbl_allactveemp">ALL ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_allactveemps" id="ASRC_UPD_DEL_lbl_allactveemps" class="srctitle" hidden>ALL ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_dte" id="ASRC_UPD_DEL_lbl_dte" hidden>DATE</label></td>
                    <td> <input type="text" name="ASRC_UPD_DEL_tb_dte" id="ASRC_UPD_DEL_tb_dte" class="ASRC_UPD_DEL_date valid enable"   style="width:75px;"  hidden ></td><br>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_btwnranges" id="ASRC_UPD_DEL_lbl_btwnranges" class="srctitle" hidden>BETWEEN RANGE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_actveemp" value="EMPLOYEE" hidden >
                        <label name="ASRC_UPD_DEL_lbl_actveemp" id="ASRC_UPD_DEL_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_nonactveemp"   value="EMPLOYEE" class='attnd' hidden>
                        <label name="ASRC_UPD_DEL_lbl_nonactveemp" id="ASRC_UPD_DEL_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><input type="button" class="btn" id="ASRC_UPD_DEL_btn_allsearch" onclick="buttonchange()"  value="SEARCH" hidden disabled></td>
                </tr>
                <tr>
                    <td>
                        <label name="ASRC_UPD_DELlbl_loginid" id="ASRC_UPD_DEL_lbl_loginid"  hidden>LOGIN ID</label></td>
                    <br>
                    <td>
                        <select name="ASRC_UPD_DEL_lb_loginid" id="ASRC_UPD_DEL_lb_loginid" hidden>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_strtdte" id="ASRC_UPD_DEL_lbl_strtdte"  hidden>START DATE<em>*</em></label></td>
                    <td><input type="text" name="ASRC_UPD_DEL_tb_strtdte" id="ASRC_UPD_DEL_tb_strtdte" hidden class="ASRC_UPD_DEL_date valid clear" style="width:75px;"></td><br>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_enddte" id="ASRC_UPD_DEL_lbl_enddte" hidden>END DATE<em>*</em></label></td>
                    <td><input type="text" name="ASRC_UPD_DEL_tb_enddte" id="ASRC_UPD_DEL_tb_enddte" hidden class="ASRC_UPD_DEL_date valid clear" style="width:75px;"></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="ASRC_UPD_DEL_btn_search" id="ASRC_UPD_DEL_btn_search"  value="SEARCH" disabled hidden></td>
                </tr>
            </table>
            <div class="container" id="ASRC_UPD_DEL_div_tablecontainer" hidden>
                <section>
                </section>
            </div>
            <tr><td><input type="button" id="ASRC_UPD_DEL_btn_srch" class="btn" name="ASRC_UPD_DEL_btn_srch" value="SEARCH" hidden/></td>
                <td><input type="button" id="ASRC_UPD_DEL_btn_del" class="btn" name="ASRC_UPD_DEL_btn_del" value="DELETE" hidden disabled/></td>
            </tr>
            <table>
                <tr>
                    <td width="150"><label name="ASRC_UPD_DEL_lbl_reportdte" id="ASRC_UPD_DEL_lbl_reportdte" hidden>DATE</label></td>
                    <td><input type ="text" id="ASRC_UPD_DEL_ta_reportdate" class='proj datemandtry update_validate ' hidden name="ASRC_UPD_DEL_ta_reportdate" style="width:75px;" /></td><td><label id="ASRC_UPD_DEL_errmsg" name="ASRC_UPD_DEL_errmsg" class="errormsg"></label></td>
                </tr>
            </table>
            <table id="ASRC_UPD_DEL_tble_attendence">
                <tr>
                    <td width="150"><label name="ASRC_UPD_DEL_lbl_attendance" id="ASRC_UPD_DEL_lbl_attendance" >ATTENDANCE</label><em>*</em></td>
                    <td width="150">
                        <select id="ASRC_UPD_DEL_lb_attendance" name="ASRC_UPD_DEL_lb_attendance" class="update_validate">
                            <option value="1">PRESENT</option>
                            <option value="0">ABSENT</option>
                            <option value="OD">ONDUTY</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" name="permission" id="ASRC_UPD_DEL_rd_permission" value="PERMISSION" class='permissn update_validate'  hidden >
                        <label name="ASRC_UPD_DEL_permission" id="ASRC_UPD_DEL_lbl_permission" hiddeen>PERMISSION<em>*</em></label></td>
                    <td>
                        <select name="ASRC_UPD_DEL_lb_timing" id="ASRC_UPD_DEL_lb_timing" class="update_validate" hidden >
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" name="permission" id="ASRC_UPD_DEL_rd_nopermission" value="NOPERMISSION" class='permissn update_validate'  hidden >
                        <label name="ASRC_UPD_DEL_nopermission" id="ASRC_UPD_DEL_lbl_nopermission" hiddeen>NO PERMISSION<em>*</em></label></td>
                </tr>
                <tr>
                    <td><label name="ASRC_UPD_DEL_lbl_session" id="ASRC_UPD_DEL_lbl_session" hidden >SESSION</label></td>
                    <td><select name="ASRC_UPD_DEL_lb_ampm" id="ASRC_UPD_DEL_lb_ampm" class="update_validate">
                            <option>SELECT</option>
                            <option>FULLDAY</option>
                            <option>AM</option>
                            <option>PM</option>
                        </select></td>
                </tr>
            </table>
            <table id="ASRC_UPD_DEL_tble_reasonlbltxtarea">
            </table>
            <table>
                <tr>
                    <td width="150"></td><td><input type="checkbox" name="flag" id="ASRC_UPD_DEL_chk_flag" class='update_validate'  hidden >
                        <label name="ASRC_UPD_DEL_lbl_flag" id="ASRC_UPD_DEL_lbl_flag" hidden>FLAG</label></td>
                </tr>
            </table>
            <table id="ASRC_UPD_DEL_tble_projectlistbx" hidden>
                <tr><td width="150"><label name="ASRC_UPD_DEL_lbl_txtselectproj" id="ASRC_UPD_DEL_lbl_txtselectproj" >PROJECT</label><em>*</em></td>
                    <td> <table id="ASRC_UPD_DEL_tble_frstsel_projectlistbx" ></table></td>
                </tr>
            </table>
            <table id="ASRC_UPD_DEL_tble_enterthereport"></table>
            <table id="ASRC_UPD_DEL_tble_bandwidth"></table>
            <table>
                <tr><label id="ASRC_UPD_DEL_banerrmsg" name="ASRC_UPD_DEL_banerrmsg" class="errormsg"></label></tr>
            </table>
            <tr>
                <input type="button"  class="btn" name="ASRC_UPD_DEL_btn_submit" id="ASRC_UPD_DEL_btn_submit"  value="UPDATE" disabled>
            </tr>
        </table>
        <table id="ASRC_UPD_DEL_tble_ondutyentry" hidden>
            <table id="ASRC_UPD_DEL_tble_odshow" hidden>
                <tr>
                    <td width="150"> <label name="ASRC_UPD_DEL_lbl_sdte" id="ASRC_UPD_DEL_lbl_sdte">START DATE</label></td>
                    <td><input type="text" id="ASRC_UPD_DEL_tb_sdte" name="ASRC_UPD_DEL_tb_sdte" class='date datemandtry' style="width:75px;"/></td>
                </tr>
                <tr>
                    <td width="150"> <label name="ASRC_UPD_DEL_lbl_edte" id="ASRC_UPD_DEL_lbl_edte">END DATE</label></td>
                    <td><input type="text" id="ASRC_UPD_DEL_tb_edte" name="ASRC_UPD_DEL_tb_edte" class='date datemandtry' style="width:75px;"/></td>
                </tr>
                <tr>
                    <td><input type="button" id="ASRC_UPD_DEL_od_btn" name="ASRC_UPD_DEL_od_btn" value="SEARCH" class="btn"  disabled /></td>
                </tr>
            </table>
            <div class="container" id="ASRC_UPD_DEL_div_ondutytablecontainer" hidden>
                <section id="ASRC_UPD_section_od">
                </section>
            </div>
            <tr>
                <td><input type="button" id="ASRC_UPD_DEL_odsrch_btn" name="ASRC_UPD_DEL_odsrch_btn" value="SEARCH" class="btn"  disabled  /></td>
            </tr>
            <tr>
                <td><label id="ASRC_UPD_DEL_oderrmsg" name="ASRC_UPD_DEL_oderrmsg" class="errormsg" hidden></label></td></tr>
            <table id="updatepart">
                <tr>
                    <td width="150"> <label name="ASRC_UPD_DEL_lbl_oddte" id="ASRC_UPD_DEL_lbl_oddte">DATE</label></td>
                    <td><input type="text" id="ASRC_UPD_DEL_tb_oddte" name="ASRC_UPD_DEL_tb_oddte" class='odenable datemandtry' style="width:75px;" readonly/></td>
                </tr>
                <tr>
                    <td width="150"> <label name="ASRC_UPD_DEL_lbl_des" id="ASRC_UPD_DEL_lbl_des">DESCRIPTION</label></td>
                    <td><textarea id="ASRC_UPD_DEL_ta_des" name="ASRC_UPD_DEL_ta_des" class='odenable'></textarea></td>
                </tr>
                <tr>
                    <td><input type="button" id="ASRC_UPD_DEL_odsubmit" name="ASRC_UPD_DEL_odsubmit" value="UPDATE" class="btn" disabled  /></td>
                </tr>
            </table>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
