<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************FORM_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE**************************************//
//DONE BY:safi
//ver 0.03 SD:06/011/2014 ED:07/11/2014,tracker no:74,updated autocomplte function,set date for datepicker,changed validation part
//DONE BY:SASIKALA
//VER 0.02 SD:14/10/2014 ED:16/10/2014,TRACKER NO:86,DESC:VALIDATION'S DONE
//VER 0.01-INITIAL VERSION, SD:20/09/2014 ED:13/10/2014,TRACKER NO:74 DONE BY:SHALINI
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
//CHECK PRELOADER STATUS N HIDE START
var SubPage=1;
//function CheckPageStatus(){
//    alert('page')
//    if(MenuPage!=1 && SubPage!=1)
//        $(".preloader").hide();
//}
// READY FUNCTION STARTS
$(document).ready(function(){
    showTable();
    get_Values();
    var  CACS_VIEW_customername;
    $('textarea').autogrow({onInitialize: true});
    $(".autosize").doValidation({rule:'general',prop:{autosize:true}});
//    $(".autosize").doValidation({rule:'alphanumeric',prop:{whitespace:true,autosize:true}});
    //DATE PICKER FUNCTION
    $('.PE_tb_sdatedatepicker').datepicker({
        dateFormat:"dd-mm-yy",
        maxDate: Date(),
        changeYear: true,
        changeMonth: true
    });
    //DATE PICKER FUNCTION
    $('.PE_tb_edatedatepicker').datepicker({
        dateFormat:"dd-mm-yy",
        maxDate: Date(),
        changeYear: true,
        changeMonth: true
    });



    //CHANGE EVENT FOR STARTDATE
    $(document).on('change','#PE_tb_sdate',function(){
        var PE_startdate = $('#PE_tb_sdate').datepicker('getDate');
        var date = new Date( Date.parse( PE_startdate ));
        date.setDate( date.getDate()  );
        var PE_enddate = date.toDateString();
        PE_enddate = new Date( Date.parse( PE_enddate ));
        $('#PE_tb_edate').datepicker("option","minDate",PE_enddate);
        var max_date=new Date(PE_startdate);
        var month=max_date.getMonth();
        var year=max_date.getFullYear()+2;
        var date=max_date.getDate();
        var max_date = new Date(year,month,date);
        $('#PE_tb_edate').datepicker("option","maxDate",max_date);
    });
    //AUTOCOMPLETE TEXT
    var error_message=[];
    var comp_start_date;
    function get_Values(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
//            //TO HIDE PRELOADER START
//            SubPage=0;
//            CheckPageStatus();
//              //TO HIDE PRELOADER END
                var values=JSON.parse(xmlhttp.responseText);
                var proj_auto=values[0];
                error_message=values[1];
                comp_start_date=values[2];
                CACS_VIEW_customername=proj_auto;
            }
        }
        var option='AUTO';
        xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?&option="+option,true);
        xmlhttp.send();
    }
    $(document).on("change blur",'#projectname',function(){
        var checkproject_name=$(this).val();
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var check_array=(xmlhttp.responseText);
                    if(check_array==1){
                        $("#PE_btn_update").attr("disabled", "disabled");
                    }
                    else{
                        $("#PE_btn_update").removeAttr("disabled");
                    }
                }
            }
            var option='CHECK';
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
            xmlhttp.send();
        }

    });


    $(document).on("change blur",'#PE_ta_prjdescrptn',function(){
        $('#PE_ta_prjdescrptn').val($('#PE_ta_prjdescrptn').val().toUpperCase())
        var trimfunc=($('#PE_ta_prjdescrptn').val()).trim()
        $('#PE_ta_prjdescrptn').val(trimfunc)


    });
    //CHANGE EVENT FOR PROJECT TEXT BOX
    $(document).on("change blur",'#PE_tb_prjectname', function (){
        $('#PE_ta_prjdescrptn').val("");
        $('#PE_tb_edate').val('');
        $('#PE_tb_sdate').val('');
        var PE_startdate=(comp_start_date).split('-');
        var day=PE_startdate[0];
        var month=PE_startdate[1];
        var year=PE_startdate[2];
        PE_startdate=new Date(year,month-1,day);
        $('#PE_tb_sdate').datepicker("option","minDate",PE_startdate);
        var max_date=new Date();
        var month=max_date.getMonth();
        var year=max_date.getFullYear()+2;
        var date=max_date.getDate();
        var max_date = new Date(year,month,date);
        $('#PE_tb_sdate').datepicker("option","maxDate",max_date);
//        $('#PE_tb_sdate').datepicker("option","minDate",null);
        var checkproject_name=$(this).val();
        $('#PE_tb_prjectname').val(checkproject_name.toUpperCase())
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var check_array=JSON.parse(xmlhttp.responseText);
                    var desc=check_array[1];
                    var min_enddate=check_array[2];
                    var count=0;
                    for(var i=0;i<CACS_VIEW_customername.length;i++){
                        if(CACS_VIEW_customername[i]==checkproject_name){
                            $('#PE_tb_sdate').datepicker("option","minDate",new Date(min_enddate));
                            var max_date=new Date();
                            var month=max_date.getMonth();
                            var year=max_date.getFullYear()+2;
                            var date=max_date.getDate();
                            var max_date = new Date(year,month,date);
                            $('#PE_tb_sdate').datepicker("option","maxDate",max_date);

                            $('#PE_ta_prjdescrptn').val(desc);
                            $('#PE_tb_status').val('REOPEN');
                            count=1;
                            break;
                            //reopen
                        }
                    }
                    if(count!=1){
                        if(check_array[0]==1){
                            $('#PE_lbl_erromsg').text(error_message[0]).show();
                            $('#PE_tb_status').val('');
                            $("#PE_btn_save").attr("disabled", "disabled");
//                        $("#PE_tb_status").val('REOPEN').show();//error messag
                        }
                        else
                        {
//                            $('#PE_tb_status').text('PROJECT NAME ALREADY EXISTS');
                            $('#PE_lbl_erromsg').hide();
                            $('#PE_tb_status').val('STARTED').show();
                            validation();

                        }
                    }
                }
            }
            var option='CHECK';
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
            xmlhttp.send();
        }
        else{
            $('#PE_lbl_erromsg').hide();
        }
    });
    //FUNCTION TO HIGHLIGHT SEARCH TEXT
    function CACS_VIEW_highlightSearchText() {
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
    var CACS_VIEW_customername=[];
    var CACS_VIEW_customerflag;
    $("#PE_tb_prjectname").keypress(function(){
        CACS_VIEW_customerflag=0;
        CACS_VIEW_highlightSearchText();
        $("#PE_tb_prjectname").autocomplete({
            source: CACS_VIEW_customername,
            select:CACS_VIEW_AutoCompleteSelectHandler
        });
    });
//FUNCTION TO GET SELECTED VALUE
    function CACS_VIEW_AutoCompleteSelectHandler(event, ui) {
        CACS_VIEW_customerflag=1;
        $('#CACS_VIEW_lbl_custautoerrmsg').hide();
    }
// CLICK EVENT FOR SAVE BUTTON
    $(document).on('click','#PE_btn_save',function(){
        $(".preloader").show();
        var formElement = document.getElementById("PE_form_projectentry");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[1],confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                    get_Values();
                }
                else if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[2],confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                    get_Values();
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:msg_alert,confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                    get_Values();
                }
            }
        }
        var option='SAVE';
        xmlhttp.open("POST","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
    function validation(){

        var projectname= $('#PE_tb_prjectname').val();
        var projectsdate= $("#PE_tb_sdate").val();
        var projectstatus=$("#PE_tb_status").val();
        var projectdes=$("#PE_ta_prjdescrptn").val().trim();
        var projectedate=$("#PE_tb_edate").val();
        if((projectname!="") &&(projectstatus!='')&& (projectsdate!="") && (projectdes !="")&&(projectedate!=""))
        {
            $("#PE_btn_save").removeAttr("disabled");
        }
        else
        {
            $("#PE_btn_save").attr("disabled", "disabled");
        }

    }

// SAVE BUTTON VALIDATION
    $(document).on('change blur','.valid',function(){
        validation();

    });



// CREATING UPDATE AND CANCEL BUTTON
    var data='';
    var action = '';
    var updatebutton = "<input type='button' id='PE_btn_update' class='ajaxupdate btn' disabled value='Update'>";
    var cancel = "<input type='button' class='ajaxcancel btn' value='Cancel'>";
    var pre_tds;
    var field_arr = new Array('text','text');
    var field_name = new Array('projectname','projectdes');
    // FUNCTION FOR DATETABLE
    function showTable(){
        $.ajax({
            url:"DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
            type:"POST",
            data:"option=showData",
            cache: false,
            success: function(response){
                var header='<table id="demoajax" border="1" cellspacing="0" class="srcresult" width="1200">'
                header+=response;
                $('section').html(header);
                $('#demoajax').DataTable({
                    dom: 'T<"clear">lfrtip',
                    tableTools: {"aButtons": [
                        "pdf"],
                        "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                    }
                });
                $('#tablecontainer').show();
            }
        });
    }
// CLICK EVENT FOR EDIT BUTTON
    $('section').on('click','.ajaxedit',function(){
        $('.ajaxedit').attr("disabled","disabled");
//                var edittrid = $(this).parent().parent().attr('id');

        var combineid = $(this).parent().parent().attr('id');
        var combineid_split=combineid.split('_');
        var edittrid=combineid_split[0];

//        alert(combineid);
//        var tds = $('#'+edittrid).children('td');
        var tds = $('#'+combineid).children('td');

//        alert(tds[0].html());
        var tdstr = '';
        var td = '';
        pre_tds = tds;
        tdstr += "<td><input type='text' id='projectname' name='projectname'  class='autosize enable'value='"+$(tds[0]).html()+"'></td>";
        tdstr += "<td><textarea id='projectdes' name='projectdes'  class='autosize enable' value='"+$(tds[1]).html()+"'></textarea></td>";
        if($(tds[2]).html()=='STARTED'||$(tds[2]).html()=='REOPEN'){
            tdstr+="<td><select id='status' name='status' class='enable'><option value="+$(tds[2]).html()+">"+$(tds[2]).html()+"</option><option value='CLOSED'>CLOSED</option></select></td>";
        }
        else if($(tds[2]).html()=='CLOSED'){
            tdstr+="<td><select id='status' name='status' class='enable'><option value="+$(tds[2]).html()+">"+$(tds[2]).html()+"</option><option value='STARTED'>STARTED</option></select></td>";
        }

        tdstr+="<td nowrap><input type='text' id='std' name='start_date' style='width:75px'; class='PE_tb_edatedatepicker  enable datemandtry ' value='"+$(tds[3]).html()+"'></td>";
        tdstr+="<td nowrap><input type='text' name='end_date' id='PE_tb_enddate' style='width:75px'; class='PE_tb_edatedatepicker enable datemandtry' value='"+$(tds[4]).html()+"' ></td>";
        tdstr+="<td>"+$(tds[5]).html()+"</td>";
        tdstr+="<td>"+$(tds[6]).html()+"</td>";
        tdstr+="<td>"+updatebutton +" " + cancel+"</td>";
//        $('#'+edittrid).html(tdstr);
        $('#'+combineid).html(tdstr);
        $('#projectdes').val($(tds[1]).html())

        $('.PE_tb_edatedatepicker').datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        var PE_startdate=($('#std').val()).split('-');
        var day=PE_startdate[0];
        var month=PE_startdate[1];
        var year=PE_startdate[2];
        PE_startdate=new Date(year,month-1,day);
        var date = new Date( Date.parse( PE_startdate ));
        date.setDate( date.getDate()  );
        var PE_enddate = date.toDateString();
        PE_enddate = new Date( Date.parse( PE_enddate ));
        $('#PE_tb_enddate').datepicker("option","minDate",PE_enddate);
        var max_date=new Date();
        var month=max_date.getMonth();
        var year=max_date.getFullYear()+2;
        var date=max_date.getDate();
        var max_date = new Date(year,month,date);
        $('#PE_tb_enddate').datepicker("option","maxDate",max_date);
        $(".autosize").doValidation({rule:'general',prop:{autosize:true}});
        var PE_sdate=(comp_start_date).split('-');
        var day=PE_sdate[0];
        var month=PE_sdate[1];
        var year=PE_sdate[2];
        PE_sdate=new Date(year,month-1,day);
        $('#std').datepicker("option","minDate",PE_sdate);
        var max_date=new Date();
        var month=max_date.getMonth();
        var year=max_date.getFullYear()+2;
        var date=max_date.getDate();
        var max_date = new Date(year,month,date);
        $('#std').datepicker("option","maxDate",max_date);
        $('#std').change(function(){
            var PE_startdate=($('#std').val()).split('-');
            var day=PE_startdate[0];
            var month=PE_startdate[1];
            var year=PE_startdate[2];
            PE_startdate=new Date(year,month-1,day);
            var date = new Date( Date.parse( PE_startdate ));
            date.setDate( date.getDate()  );
            var PE_enddate = date.toDateString();
            PE_enddate = new Date( Date.parse( PE_enddate ));
            $('#PE_tb_enddate').datepicker("option","minDate",PE_enddate);
            var max_date=new Date(PE_startdate);
            var month=max_date.getMonth();
            var year=max_date.getFullYear()+2;
            var date=max_date.getDate();
            var max_date = new Date(year,month,date);
            $('#PE_tb_enddate').datepicker("option","maxDate",max_date);
        });
    });



// UPDATE BUTTON VALIDATION
    $(document).on('change blur','.enable',function(){
        var projectname= $('#projectname').val();
        var projectsdate= $("#std").val();
        var projectstatus=$("#status").val();
        var projectdes=$("#projectdes").val().trim();
        var projectedate=$("#PE_tb_enddate").val();
        if((projectname!="") && (projectstatus!='') && (projectsdate!="") && (projectdes !="") && (projectedate!=""))
        {
            $("#PE_btn_update").removeAttr("disabled");
        }
        else
        {
            $("#PE_btn_update").attr("disabled", "disabled");
        }
    });
//CLICK EVENT FOR CANCEL BUTTON
    $(document).on("click",'.ajaxcancel', function (){
        $('.ajaxedit').removeAttr("disabled");
    });
//CLICK EVENT FOR UPDATE BUTTON
    $('section').on("click",'.ajaxedit', function (){
//        $('.ajaxedit').attr("disabled","disabled");
        var checkproject_name=$('#projectname').val();
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var check_array=(xmlhttp.responseText);
                    if(check_array==1){

                        $('#std').prop('disabled','disabled');
//                        $('#status').prop('disabled','disabled');

                    }
                    else
                    {
                        $('#std').removeAttr('disabled');
//                        $('#status').removeAttr('disabled');
                    }
                }
            }
            var option='RANDOM';
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
            xmlhttp.send();
        }
    });
// CLICK EVENT FOR UPDATE BUTTON
    $('section').on('click','.ajaxupdate',function(){


        var edittrid = $(this).parent().parent().attr('id');



        var combineid = $(this).parent().parent().attr('id');
        var combineid_split=combineid.split('_');
        var edittrid=combineid_split[0];
        var pdid=combineid_split[1];
        var projectname =  $("input[name='"+field_name[0]+"']");
        var projectdes = $("textarea[name='"+field_name[1]+"']");
        var prostatus =  $('#status').val();
        var projectsdate = $("input[name='start_date']");
        var projectedate =  $("input[name='end_date']");
        data = "&name="+projectname.val()+"&des="+projectdes.val()+"&sta="+prostatus+"&ssd="+projectsdate.val()+"&eed="+projectedate.val()+"&editid="+edittrid+"&pdid="+pdid+"&option=updateData";
        $.ajax({
            url:"DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
            type:"POST",
            data:data,
            cache: false,
            success: function(response){
                if(response==1){
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[3],confirmation:true}});
                    showTable()
                    get_Values();

//                    $('#demoajax').html(response);
                }
                else if(response==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[4],confirmation:true}});
                    showTable()
                    get_Values();

                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:response,confirmation:true}});
                    showTable()
                    get_Values();

                }
            }
        });
    });
// CLICK EVENT FOR CANCEL BUTTON
    $('section').on('click','.ajaxcancel',function(){
        var edittrid = $(this).parent().parent().attr('id');
        $('#'+edittrid).html(pre_tds);
    });
});
// READY FUNCTION ENDS
</script>
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>PROJECT ENTRY/SEARCH/UPDATE</h3><p></div></div>
    <div class="container">
        <form  name="PE_form_projectentry" id="PE_form_projectentry" method="post" class="content">
            <table id="PE_tble_projectentry">
                <tr>
                    <td><label name="PE_lbl_prjectname" id="PE_lbl_prjectname">PROJECT NAME<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_prjectname" id="PE_tb_prjectname" class="valid autosize" maxlength='50'></td><td><label id="PE_lbl_erromsg" class="errormsg"></label></label></td>
                    <!--                <td><label id="PE_errmsg" name="PE_errmsg" class="errormsg" hidden></label></td>-->
                </tr>
                <tr>
                    <td><label name="PE_lbl_prjdescrptn" id="PE_lbl_prjdescrptn">PROJECT DESCRIPTION<em>*</em></label></td>
                    <td><textarea  name="PE_ta_prjdescrptn" id="PE_ta_prjdescrptn" class="maxlength autosize valid" maxlength='100' ></textarea></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_status" id="PE_lbl_status" >STATUS<em>*</em></label></td>
                    <td><input type="text" id="PE_tb_status" name="PE_tb_status" style="width:100px;" class="valid" readonly></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_sdate" id="PE_lbl_sdate" >START DATE<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_sdate" id="PE_tb_sdate" style="width:75px;" class="PE_tb_sdatedatepicker valid datemandtry "></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_edate" id="PE_lbl_edate" >END DATE<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_edate" id="PE_tb_edate" style="width:75px;" class="PE_tb_edatedatepicker valid datemandtry"></td>
                </tr>
                <tr>
                    <td align="left"><input type="button" class="btn" name="PE_btn_save" id="PE_btn_save"  value="SAVE" disabled></td>
                </tr>
            </table>
            <div class="container" id="tablecontainer" hidden>
                <section>

                </section>
            </div>
    </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->