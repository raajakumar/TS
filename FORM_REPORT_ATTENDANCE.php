<!--//*******************************************FILE DESCRIPTION*********************************************//
//***********************************************ATTENDANCE**************************************************//
//DONE BY:LALITHA
//VER 0.03-SD:07/11/2014 EED:07/11/2014,TRACKER NO:97,Updated Month nd Yr dp fr all db
//VER 0.02-SD:30/10/2014 ED:31/10/2014,TRACKER NO:97,Updated date sorting fn,Changed Header name,Updated Preloader,Increased width of Date bx,Hide the err msgs in select option,Put Mandatry symbol fr dte nd login bx,Hide the Data tble Section id while loading others records,Fixed width fr all dt column,Aligned centre the vals in dt,Updated Comments in db,Removed empty lines
//DONE BY:SAFI
//VER 0.01-INITIAL VERSION, SD:26/09/2014 ED:1/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "HEADER.php";
include "COMMON.php";
?>
<!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!--SCRIPT TAG START-->
<script>
//DOCUMENT READY FUNCTION START
$(document).ready(function(){
    $(".preloader").show();
    $('#REP_btn_search').hide();
    var err_msg_array=[];
    var mindate;
    var maxdate;
    //INITIAL LOADING DATAS FR LISTBX VALUES
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var final_array=JSON.parse(xmlhttp.responseText);
            var loginid_array=final_array[0];
            err_msg_array=final_array[1];
            var report_array=final_array[2];
            mindate=final_array[3];
            maxdate=final_array[4];
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<loginid_array.length;i++) {
                active_employee += '<option value="' + loginid_array[i] + '">' + loginid_array[i] + '</option>';
            }
            $('#REP_lb_loginid').html(active_employee);
            var report_option='<option>SELECT</option>';
            for (var i=0;i<report_array.length;i++) {
                report_option += '<option value="' + report_array[i][1] + '">' + report_array[i][0] + '</option>';
            }
            $('#REP_lb_attendance').html(report_option);
        }
    }
    var option="search_option";
    xmlhttp.open("GET","DB_REPORT_ATTENDANCE.do?option="+option);
    xmlhttp.send();
    //CHANGE FUNCTION FOR LOGIN ID LIST BX
    $(document).on('change','#REP_lb_loginid',function(){

        $(".preloader").show();
        $("#REP_btn_search").attr("disabled","disabled");
        $('#REP_tble_absent_count').html('');
        $('#REP_tablecontainer').hide();
        $('#REP_lbl_error').hide();
        $('#no_of_working_days').hide();
        $('#no_of_days').hide();
        var loginid=$('#REP_lb_loginid').val();
        $('#REP_date').val("");
        if(loginid=="SELECT"){
            $(".preloader").hide();
            $('#REP_lbl_dte').hide();
            $('#REP_date').hide();
            $('#REP_tablecontainer').hide();
            $('#no_of_working_days').hide();
            $('#no_of_days').hide();
            $("#REP_btn_search").hide();;
        }
        else{
            var loginid=$('#REP_lb_loginid').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var finaldate=JSON.parse(xmlhttp.responseText);
                    $(".preloader").hide();
                    var min_date=finaldate[0];
                    var max_date=finaldate[1];
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
                        var option=$('#REP_lb_attendance').val();
                        if(option=='1'){
                            validationdp()
                        }
                        else{
                            validation_dps()
                        }
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
                $(".date-pickers").datepicker("option","minDate", new Date(min_date));
                $(".date-pickers").datepicker("option","maxDate", new Date(max_date));
                //VALIDATION FOR DATE BX
                function validationdp(){
                    $('#REP_tablecontainer').hide();
                    $('#no_of_working_days').hide();
                    $('#no_of_days').hide();
                    $('#REP_lbl_error').hide();
                    $("#REP_btn_search").attr("disabled","disabled");
                    if(($('#REP_date').val()!='undefined')&&($('#REP_date').val()!='')&&($('#REP_lb_loginid').val()!="SELECT")&&($('#REP_lb_attendance').val()!="SELECT"))
                    {
                        $("#REP_btn_search").removeAttr("disabled");
                    }
                    else
                    {
                        $("#REP_btn_search").attr("disabled","disabled");
                    }
                }
                $('#REP_lbl_dte').show();
                $('#REP_date').show();
                $('#REP_btn_search').show();
            }
            var choice="login_id"
            xmlhttp.open("GET","DB_REPORT_ATTENDANCE.do?login_id="+loginid+"&option="+choice,true);
            xmlhttp.send();
        }
    });
    //CHANGE FUNCTION FOR ATTENDANCE LISTBX
    $(document).on('change','#REP_lb_attendance',function(){
        $(".preloader").show();
        $('#REP_tablecontainer').hide();
        $('#REP_btn_search').hide();
        $('#REP_lbl_dte').show();

        $('#REP_date').val("");
        $('#REP_lb_loginid').hide();
        $('#REP_lb_loginid').prop('selectedIndex',0)
        $('#REP_lbl_loginid').hide();
        $('#no_of_working_days').hide();
        $('#no_of_days').hide();
        $('#REP_lbl_error').hide();
        $('#REP_tble_absent_count').html('');
        var option=$('#REP_lb_attendance').val();
        if(option=="1"){
            $(".preloader").hide();
            $('#REP_lb_loginid').show();
            $('#REP_lbl_loginid').show();
            $('#REP_lbl_dte').hide();
            $('#REP_date').hide();
            $('#REP_btn_search').hide();
        }
        if(option=="SELECT"){
            $(".preloader").hide();
            $('#REP_lbl_dte').hide();
            $('#REP_date').hide();
            $('#REP_tablecontainer').hide();
            $('#REP_btn_search').hide();
            $('#no_of_working_days').hide();
            $('#no_of_days').hide();
        }
        if(option=='6' || option=='2'){
            $(".preloader").hide();
            $('#REP_btn_search').attr("disabled","disabled").show();
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
                    validation_dps()
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
            $(".date-pickers").datepicker("option","minDate", new Date(mindate));
            $(".date-pickers").datepicker("option","maxDate", new Date(maxdate));
            $('#REP_date').show();
            //VALIDATION FOR DATE BX

        }
    });
    function validation_dps(){
        $('#REP_tablecontainer').hide();
        $('#no_of_working_days').hide();
        $('#no_of_days').hide();
        $('#REP_lbl_error').hide();
        $("#REP_btn_search").attr("disabled","disabled");
        if(($('#REP_date').val()!='undefined')&&($('#REP_date').val()!=''))
        {
            $("#REP_btn_search").removeAttr("disabled");
        }
        else
        {
            $("#REP_btn_search").attr("disabled","disabled");
        }
    }
    var allvalues_array;
    //CHANGE FUNCTION FOR DATE BX
    $(document).on('click','#REP_btn_search',function(){
        $(".preloader").show();
        $("#REP_btn_search").attr("disabled","disabled");
        $('#REP_tble_absent_count').html('');
        $('section').html('');
        $('#no_of_days').hide();
        $('#no_of_working_days').hide();
        $('#REP_lbl_error').hide();
        var option=$('#REP_lb_attendance').val();
        var date=$('#REP_date').val();
        var loginid=$('#REP_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array.length!=0){
                    if(option=='6'){
                        var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult" style="width:500px"  ><thead  bgcolor="#6495ed" style="color:white"><tr><th >NAME</th><th style="width:90px">ABSENT COUNT</th></tr></thead><tbody>'
                        for(var j=0;j<allvalues_array.length;j++){
                            var name=allvalues_array[j].name;
                            var absent_count=allvalues_array[j].absent_count;
                            ADM_tableheader+='<tr ><td>'+name+'</td><td align="center" style="width:90px">'+absent_count+'</td></tr>';
                        }
                    }
                    else if(option=='2'){
                        var working_days= allvalues_array[0].total_working_days;
                        var total_days= allvalues_array[0].total_days;
                        $('#no_of_working_days').text("TOTAL NO OF WORKING DAYS: "  +  working_days  +  " DAYS").show();
                        $('#no_of_days').text("TOTAL NO OF DAYS: "  +   total_days   +  " DAYS").show();
                        var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult" style="width:600px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>NAME</th><th>NO OF PRESENT</th><th>NO OF ABSENT</th><th>NO OF ONDUTY</th><th>TOTAL HOUR(S) OF PERMISSION</th></tr></thead><tbody>'
                        for(var j=0;j<allvalues_array.length;j++){
                            var name=allvalues_array[j].loginid;
                            var absent_count=allvalues_array[j].absent_count;
                            var present_count=allvalues_array[j].present_count;
                            var onduty_count=allvalues_array[j].onduty_count;
                            var permission_count=allvalues_array[j].permission_count;
                            ADM_tableheader+='<tr ><td>'+name+'</td><td align="center">'+present_count+'</td><td align="center">'+absent_count+'</td><td align="center">'+onduty_count+'</td><td align="center">'+permission_count+'</td></tr>';
                        }
                    }
                    else if(option=='1'){
                        var working_days= allvalues_array[0].working_day;
                        var total_days= allvalues_array[0].today_no_days;
                        $('#no_of_working_days').text("TOTAL NO OF WORKING DAYS: "  +  working_days  +  " DAYS").show();
                        $('#no_of_days').text("TOTAL NO OF DAYS: "  +   total_days   +  " DAYS").show();
                        var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult" style="width:500px"><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="min-width:80px;">DATE</th><th style="min-width:20px;">PRESENT</th><th>ABSENT</th><th>ONDUTY</th><th>PERMISSION HOUR(S)</th></tr></thead><tbody>'
                        for(var j=0;j<allvalues_array.length;j++){
                            var report_date=allvalues_array[j].reportdate;
                            var absent_count=allvalues_array[j].absents;
                            if(absent_count==null)
                                absent_count='';
                            var present_count=allvalues_array[j].presents;
                            if(present_count==null)
                                present_count='';
                            var onduty_count=allvalues_array[j].ondutys;
                            if(onduty_count==null)
                                onduty_count='';
                            var permission_count=allvalues_array[j].permission_counts;
                            if(permission_count==null)
                                permission_count='';
                            ADM_tableheader+='<tr ><td>'+report_date+'</td><td  style="width:20px;" align="center">'+present_count+'</td><td align="center">'+absent_count+'</td><td align="center">'+onduty_count+'</td><td align="center">'+permission_count+'</td></tr>';
                        }
                    }
                    ADM_tableheader+='</tbody></table>';
                    $('section').html(ADM_tableheader);
                    $('#REP_tble_absent_count').DataTable( {
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
                    $('#REP_lbl_error').hide();
                }
                else
                {
                    $('#REP_tble_absent_count').hide();
                    $('#REP_div_absent_count').hide();
                    var msg=err_msg_array[0].toString().replace("[DATE]",date);
                    $('#REP_lbl_error').text(msg).show();
                    $('#REP_tablecontainer').hide();
                    $('#no_of_working_days').hide();
                    $('#no_of_days').hide();
                }
            }
        }
        $('#REP_tablecontainer').show();
        xmlhttp.open("GET","DB_REPORT_ATTENDANCE.do?option="+option+"&date="+date+"&loginid="+loginid,true);
        xmlhttp.send();
    });
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
        };
    }
});
//DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>ATTENDANCE</h3><p></div></div>
    <form   id="REP_form_attendance" class="content" >
        <table>
            <tr>
                <td width="150"><label name="REP_lbl_optn" id="REP_lbl_optn" class="srctitle">SELECT A OPTION</label><em>*</em></td>
                <td width="150">
                    <select id="REP_lb_attendance" name="option">
                    </select>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <label name="REP_lbl_loginid" id="REP_lbl_loginid"  hidden>LOGIN ID<em>*</em></label></td>
                <br>
                <td>
                    <select name="REP_lb_loginid" id="REP_lb_loginid" hidden>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150"><label name="REP_lbl_dte" id="REP_lbl_dte" class="srctitle"  hidden>DATE<em>*</em></label></td>
                <td><input type ="text" id="REP_date" class='date-pickers datemandtry' hidden name="date" style="width:105px;" /></td>
            </tr>
            <tr>
                <td><input type="button" class="btn" name="REP_btn_search" id="REP_btn_search"  value="SEARCH" disabled></td>
            </tr>
        </table>
        <div>
            <label id="no_of_days" class="srctitle"></label><br>
            <label id="no_of_working_days" class="srctitle"></label>
        </div>
        <div class="container" id="REP_tablecontainer" style="width:500px;" hidden>
            <section style="width:500px;">
            </section>
        </div>
        <label id="REP_lbl_error" class="errormsg" hidden></label>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->