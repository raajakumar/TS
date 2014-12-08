<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY REPORT ENTRY******************************************//
//DONE BY:LALITHA
//VER 0.02,SD:14/11/2014 ED:14/11/2014,TRACKER NO:74,Fixed max date nd min dte
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:16/10/2014 ED:19/10/2014,TRACKER NO:86
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
    // READY FUNCTION STARTS
    $(document).ready(function(){
        //ERROR MESSAGE
        var js_errormsg_array=[];
        var js_min_date=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                js_errormsg_array=value_array[0];
                js_min_date=value_array[1];
            }
        }
        var choice='ADMIN WEEKLY REPORT ENTRY';
        xmlhttp.open("POST","COMMON.do?option="+choice,true);
        xmlhttp.send();
        $('#AWRE_SRC_btn_submit').hide();
        $('#AWRE_SRC_btn_reset').hide();
        $('textarea').autogrow({onInitialize: true});
        $('.AWRE_SRC_tb_datepicker').datepicker("option","minDate",(js_min_date));
        datesarray();
        //FUNCTION FOR FINDING DATE ALREADY ENTERED
        function W_GetWeekInmonth(date)
        {
            var date1 = new Date(date);
            WeekNumber = ['1', '2', '3', '4', '5'];
            var weekNum = 0 | date1.getDate() / 7;
            weekNum = ( date1.getDate() % 7 === 0 ) ? weekNum - 1 : weekNum;
            var month=new Array();
            month[0]="January";
            month[1]="February";
            month[2]="March";
            month[3]="April";
            month[4]="May";
            month[5]="June";
            month[6]="July";
            month[7]="August";
            month[8]="September";
            month[9]="October";
            month[10]="November";
            month[11]="December";
            var d1=month[date1.getMonth()];
            var a=(WeekNumber[weekNum] +d1+date1.getFullYear());
            return a;
        }
        function W_GetWeekInMonth(date)
        {
            var date1 = new Date(date);
            WeekNumber = ['1', '2', '3', '4', '5'];
            var weekNum = 0 | date1.getDate() / 7;
            weekNum = ( date1.getDate() % 7 === 0 ) ? weekNum - 1 : weekNum;
            return WeekNumber[weekNum];
        }

//CHANGE EVENT FOR SELECTDATE
        $(document).on("change",'#AWRE_SRC_tb_selectdate', function (){
            $('textarea').height(50).width(60);
            var checkreportdate=$(this).val();
            var y=0;
            var array1=[];
            for(var z=0;z<date_array.length;z++)
            {
                var d8=date_array[z];
                var d7=W_GetWeekInmonth(d8);
                var d9=W_GetWeekInmonth(checkreportdate);
                if((d7)==(d9))
                {
                    y++;
                }
            }
            for(var i=0;i<date_array.length;i++)
            {
                var d3=date_array[i];
                var d2=W_GetWeekInMonth(d3);
                array1.push(d2);
            }
            var p=0;
            for(var k=0;k<array1.length;k++)
            {
                var d4=array1[k];
                var d1=W_GetWeekInMonth(checkreportdate);
                if(d4==d1)
                {
                    p++;
                }
            }
            if(p==0)
            {
                $("#AWRE_SRC_lbl_enterreport").show();
                $("#AWRE_SRC_ta_enterreport").val('').show();
                $("#AWRE_SRC_btn_submit").show();
                $("#AWRE_SRC_btn_reset").show();
                $('#AWRE_errmsg').text(msg).hide();
            }
            else if(y==0)
            {
                $("#AWRE_SRC_lbl_enterreport").show();
                $("#AWRE_SRC_ta_enterreport").val('').show();
                $("#AWRE_SRC_btn_submit").show();
                $("#AWRE_SRC_btn_reset").show();
                $('#AWRE_errmsg').text(msg).hide();
            }
            else
            {
                var date=$("#AWRE_SRC_tb_selectdate").val();
                var msg=js_errormsg_array[1].toString().replace("[DATE]",date);
                $('#AWRE_errmsg').text(msg).show();
                $("#AWRE_SRC_lbl_enterreport").hide();
                $("#AWRE_SRC_ta_enterreport").val('').hide();
                $("#AWRE_SRC_btn_submit").hide();
                $("#AWRE_SRC_btn_reset").hide();
            }
        });
        //CHECKING REPORT ALREADY ENTERED IN THIS WEEK OR NOT
        var date_array=[];
        function datesarray(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    date_array=JSON.parse(xmlhttp.responseText);
                }
            }
            var option='CHECK';
            xmlhttp.open("GET","DB_WEEKLY_REPORT_ADMIN_WEEKLY_REPORT_ENTRY.do?&option="+option,true);
            xmlhttp.send();
        }
        //DATEPICKER FUNCTION
        $('.AWRE_SRC_tb_datepicker').datepicker({
            dateFormat:"dd MM yy",
            changeYear: true,
            changeMonth: true
        });
        var maxdate=new Date();
        var month=maxdate.getMonth()+1;
        var year=maxdate.getFullYear();
        var date=maxdate.getDate();
        var max_date = new Date(year,month,date);
        var datepicker_maxdate=new Date(Date.parse(max_date));

        var PE_startdate='23-05-2012'.split('-');
        var day=PE_startdate[0];
        var month=PE_startdate[1];
        var year=PE_startdate[2];
        PE_startdate=new Date(year,month-1,day);
        var date = new Date( Date.parse( PE_startdate ));
        date.setDate( date.getDate()  );
        var PE_enddate = date.toDateString();
        PE_enddate = new Date( Date.parse( PE_enddate ));
        $('.AWRE_SRC_tb_datepicker').datepicker("option","minDate",PE_enddate);
        $('.AWRE_SRC_tb_datepicker').datepicker("option","maxDate",datepicker_maxdate);
//CLICK EVENT FOR SUBMIT BUTTON
        $(document).on('click','#AWRE_SRC_btn_submit',function(){
            var formElement = document.getElementById("AWRE_SRC_form_reportentry");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    var msg=js_errormsg_array[0];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,confirmation:true}});
                    $("#AWRE_SRC_lbl_enterreport").hide();
                    $("#AWRE_SRC_ta_enterreport").val('').hide();
                    $("#AWRE_SRC_btn_submit").hide();
                    $("#AWRE_SRC_btn_reset").hide();
                    $("#AWRE_SRC_tb_selectdate").val('').show();
                    datesarray();
                }
            }
            var option='SUBMIT';
            xmlhttp.open("POST","DB_WEEKLY_REPORT_ADMIN_WEEKLY_REPORT_ENTRY.do?option="+option,true);
            xmlhttp.send(new FormData(formElement));
        });
        //CLICK EVENT FOR RESET BUTTON
        $('#AWRE_SRC_btn_reset').click(function(){
            $("#AWRE_SRC_tb_selectdate").val('').show();
            $("#AWRE_SRC_lbl_enterreport").hide();
            $("#AWRE_SRC_ta_enterreport").hide();
            $("#AWRE_SRC_btn_submit").hide();
            $("#AWRE_SRC_btn_reset").hide();
        });
        //VALIDATION FOR SUBMIT BUTTON
        $(document).on('change blur','.valid',function(){
            var date= $('#AWRE_SRC_tb_selectdate').val();
            var report=$("#AWRE_SRC_ta_enterreport").val().trim();
            if((date!="") &&(report !=""))
            {
                $("#AWRE_SRC_btn_submit").removeAttr("disabled");
            }
            else
            {
                $("#AWRE_SRC_btn_submit").attr("disabled", "disabled");
            }
        });
    });
    // READY FUNCTION ENDS
</script>
<body>
<!--BODY TAG START-->
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="https://googledrive.com/host/0Bzvv-O9jT9r_QW8yYzc1N1JVM2c/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>ADMIN WEEKLY REPORT ENTRY</h3><p></div></div>
    <form id="AWRE_SRC_form_reportentry" class="content">
        <table>
            <tr>
                <td width="200"><label name="AWRE_SRC_lbl_selectdate" id="AWRE_SRC_lbl_selectdate" >SELECT A DATE<em>*</em></label></td>
                <td><input type="text" name="AWRE_SRC_tb_selectdate" id="AWRE_SRC_tb_selectdate" style="width:120px;" class="AWRE_SRC_tb_datepicker valid datemandtry"></td>
            </tr>
            <tr>
                <td width="200"><label name="AWRE_SRC_lbl_enterreport" id="AWRE_SRC_lbl_enterreport" hidden>ENTER THE REPORT<em>*</em></label></td>
                <td><textarea name="AWRE_SRC_ta_enterreport" id="AWRE_SRC_ta_enterreport" class="valid" hidden></textarea></td>
            </tr>
            <tr>
                <td><input type="button" value="SUBMIT" id="AWRE_SRC_btn_submit" class="btn" disabled><input type="button" value="RESET" id="AWRE_SRC_btn_reset" class="btn"></td>
            </tr>
        </table>
        <tr><td><label id="AWRE_errmsg" name="AWRE_errmsg" class="errormsg" hidden></label></td></tr>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->