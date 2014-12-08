<!--//******************************************FILE DESCRIPTION*********************************************//
//**********************************************TICKLER HISTORY ***********************************************//
//DONE BY:SASIKALA
//VER 0.03:SD:15/11/2014 ED:15/11/2015:updated Sp calling SP
//VER 0.02 SD:13/10/2014 ED:13/10/2014;desc:updated datatable
//VER 0.01-INITIAL VERSION, SD:06/10/2014 ED:08/10/2014,TRACKER NO:96
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
    //READY FUNCTION START
    $(document).ready(function(){
        var TH_err_msg=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var arrayvalues=JSON.parse(xmlhttp.responseText);
                TH_employeeid=arrayvalues[0];
                TH_err_msg=arrayvalues[1];
            }
        }
        var option="active_emp";
        xmlhttp.open("GET","DB_REPORT_TICKLER_HISTORY.do?option="+option);
        xmlhttp.send();
//FUNCTION TO HIGHLIGHT SEARCH TEXT
        function TH_view_highlightSearchText() {
            $.ui.autocomplete.prototype._renderItem = function( ul, item) {
                var re = new RegExp(this.term, "i") ;
                var t = item.label.replace(re,"<span class=autotxt>" + this.term + "</span>");//higlight color,class shld be same as here
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + t + "</a>" )
                    .appendTo( ul );
            }
        };
//FUNCTION TO AUTOCOMPLETE SEARCH TEXT
        var TH_employeeid=[];
        var TH_employee_idflag;
        $("#TH_tb_empid").keypress(function(){
            TH_employee_idflag=0;
            $('#TH_lbl_heading').hide();
            $('#TH_tble_flextble').hide();
            $('#TH_div_flexdata_result').hide();
            $('#TH_lbl_nodata').hide();
            TH_view_highlightSearchText();
            $("#TH_tb_empid").autocomplete({
                source: TH_employeeid,
                select:TH_AutoCompleteSelectHandler
            });
        });
// FUNCTION FOR AUTOCOMPLETESELECTHANDLER
        function TH_AutoCompleteSelectHandler(event, ui) {
            TH_employee_idflag=1;
            $('#TH_lbl_notmatch').hide();
            $('#TH_btn_search').removeAttr("disabled");
        }
        // CHANGE EVENT FOR EMPLOYEEID TEXT BOX
        $(document).on('change','#TH_tb_empid',function(){
            if(TH_employee_idflag==1)
            {
                $('#TH_lbl_notmatch').hide();
                $('#TH_btn_search').removeAttr("disabled");
                $('#TH_tble_flextble').hide();
                $('#TH_div_flexdata_result').hide();
            }
            else
            {
                $('#TH_lbl_notmatch').text(TH_err_msg[0]).show();
                $('#TH_btn_search').attr("disabled","disabled");
                $('#TH_tble_flextble').hide();
            }
        });
// CLICK EVENT FOR SEARCH BUTTON
        $(document).on('click','#TH_btn_search',function(){
            $('.preloader').show();
            flextable()
            $('#TH_btn_search').attr("disabled","disabled");
        });
        //FUNCTION FOR FORMTABLEDATEFORMAT
        function FormTableDateFormat(inputdate){
            var string = inputdate.split("-");
            return string[2]+'-'+ string[1]+'-'+string[0];
        }
        function replaceSpclcharAngularBrack(str)
        {
            var finalstr = str.replace(/</g, "&lt;");
            finalstr = finalstr.replace(/>/g, "&gt;");
            return finalstr;
        }
// FUNCTION FOR FLEXTABLE
        function flextable(){
            var emp_id=$('#TH_tb_empid').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    if(values_array.length!=0)
                    {
                        var msg=TH_err_msg[1].toString().replace("[LOGINID]",emp_id);
                        $('#TH_lbl_heading').text(msg).show();
                        var TH_table_header='<table id="TH_tble_flextble" border="1"  cellspacing="0" class="srcresult" style="width:1200px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th width="100">HISTORY</th><th width="80">USERSTAMP</th><th width="100" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                        for(var i=0;i<values_array.length;i++){
                            var TH_tptype=values_array[i].tptype;
                            var TH_ttipdata=values_array[i].ttipdata;
                            var TH_oldvalue=values_array[i].oldvalue;
                            var TH_newvalue=values_array[i].newvalue;
                            var TH_userstamp=values_array[i].userstamp;
                            var TH_timestamp=values_array[i].timestamp;
                            var TH_arrayold=[];
                            var TH_arraynew=[];
                            TH_arrayold=(TH_oldvalue).split(',');


                            var TH_arroldvalue='';
                            var TH_arrnewvalue='';
                            for(var j=0;j<TH_arrayold.length;j++)
                            {
                                if(j==0)
                                {
                                    TH_arroldvalue=TH_arrayold[j];
                                }
                                else
                                {
                                    TH_arroldvalue +=' , '+TH_arrayold[j];
                                }
                            }
                            if(TH_newvalue!=null){
                                TH_arraynew=(TH_newvalue).split(',');

                            for(var k=0;k<TH_arraynew.length;k++)
                            {
                                if(k==0)
                                {
                                    TH_arrnewvalue=TH_arraynew[k];
                                }
                                else
                                {
                                    TH_arrnewvalue +=' , '+TH_arraynew[k];
                                }
                            }
                            }
                            else{
                                TH_arraynew= "NULL";
                            }
//                            TH_arraynew= replaceSpclcharAngularBrack(TH_newvalue)
                            TH_table_header+='<tr><td>'+'UPDATION/DELETION :'+''+TH_tptype+'<br><br>'+'TABLE NAME:'+''+TH_ttipdata+'<br><br>'+'OLD VALUE:'+TH_arroldvalue+'<br><br><br>'+'NEW VALUE:'+TH_arrnewvalue+'</td><td>'+TH_userstamp+'</td><td nowrap>'+TH_timestamp+'</td></tr>';
                        }
                        TH_table_header+='</tbody></table>';
                        $('section').html(TH_table_header);
                        $('#TH_tble_flextble').DataTable({
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
                        $('#TH_lbl_nodata').hide();
                    }
                    else
                    {
                        $('#TH_tble_flextble').hide();
                        $('#TH_lbl_heading').hide();
                        $('#TH_div_flexdata_result').hide();
                        var msg=TH_err_msg[2].toString().replace("[LOGINID]",emp_id)
                        $('#TH_lbl_nodata').text(msg).show();
                    }
                }
            }
            $('#TH_div_flexdata_result').show();
            var option='search';
            xmlhttp.open("GET","DB_REPORT_TICKLER_HISTORY.do?option="+option+"&empid="+emp_id,true);
            xmlhttp.send();
            sorting();
        }
        function sorting(){
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
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div class="preloader MaskPanel"><div class="preloader statusarea"><div style="padding-top:90px; text-align:center">
                <img src="image/Loading.gif"/></div></div></div>
    <div class="title" id="fhead"><div style="padding-left:500px; text-align:left;"><p><h3>TICKLER HISTORY</h3><p></div></div>
    <form class="content" name="TH_form_employeeid" id="TH_form_employeeid">
        <table>
            <table border=0>
                <tr>
                    <td><label name="TH_lbl_employeeid" id="TH_lbl_employeeid">LOGIN ID</label></td>
                    <td><input type="text" class="autosize" name="TH_tb_empid" id="TH_tb_empid" style="width:300px"/></td><td><label id="TH_lbl_notmatch" name="TH_lbl_notmatch" class="errormsg" hidden></label></td>
                <tr><td></td><td><input type="button" name="TH_btn_search" class='btn' value="SEARCH" id="TH_btn_search" disabled/></td><tr>
            </table>
            <tr>
                <td><label id="TH_lbl_heading" name="TH_lbl_heading"  class="srctitle" hidden></label></td>
                <td><label id="TH_lbl_nodata" name="TH_lbl_nodata"  class="errormsg" hidden></label></td>
            </tr>
            <div id ="TH_div_flexdata_result" class="container" hidden>
                <section>

                </section>
            </div>
            </tr>

        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->