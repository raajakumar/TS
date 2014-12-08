<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************REVENUE*******************************************************//
//DONE BY:LALITHA
//VER 0.04-SD:13/11/2014 ED:13/11/2014,TRACKER NO:97,Tested Newly added outflg for project by emp,Updated showned err msg nd hide,Renamed column name of prjct by revenue
//VER 0.03-SD:29/10/2014 ED:31/10/2014,TRACKER NO:97,Fixed the column nd div width,Changed the data table header is meaningful nd put space also,Updated Sorting fr dte,Hide the section id nd old dt datas while listbx changing fn,Added Preloader in list bx fn ,Column values alignd in centre
//VER 0.02-SD:14/10/2014 ED:21/10/2014,TRACKER NO:97,Did others two parts of projects,Changed data tble for prjct rvn by actv nonactv emp option,Removed hard code of list bx option(tkn data nd id also frm db),Updated data tble,validation,Loaded all err msg frm db,hiding err msg,lbls nd others fields in unwanted places,Changed queries,update dte frmt,Update comments,Set min nd max dte
//DONE BY:SASIKALA
//VER 0.01-INITIAL VERSION, SD:08/10/2014 ED:15/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var err_msg_array=[];
//READY FUNCTION START
$(document).ready(function(){
    $('#REV_btn_prjctsrch').hide();
    $('#REV_btn_empsrch').hide();
    $('#REV_btn_search').hide();
    $('#REV_btn_searchdaterange').hide();
    var REV_active_emp=[];
    var  REV_nonactive_emp=[];
    var REV_project_name=[];
    var REV_project_listbx=[];
    $('.preloader').show();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var values_array=JSON.parse(xmlhttp.responseText);
            REV_project_listbx=values_array[0];
            REV_project_name=values_array[1];
            REV_active_emp=values_array[2];
            REV_nonactive_emp=values_array[3];
            err_msg_array=values_array[4];
            if(REV_project_listbx.length!=0){
                var project_list='<option>SELECT</option>';
                for (var i=0;i<REV_project_listbx.length;i++) {
                    project_list += '<option value="' + REV_project_listbx[i][1] + '">' + REV_project_listbx[i][0] + '</option>';
                }
                $('#REV_lb_project').html(project_list);
                $('#REV_lb_project').show();
                $('#REV_lbl_prjct').show();
            }
            else
            {
                $('#REV_nodata_rc').text(err_msg_array[0]).show();
            }
            var project_name='<option>SELECT</option>';
            for (var i=0;i<REV_project_name.length;i++) {
                project_name += '<option value="' + REV_project_name[i] + '">' + REV_project_name[i] + '</option>';
            }
            $('#REV_lb_projectname').html(project_name);
            $('#REV_lb_projectnamedaterange').html(project_name);
        }
    }
    var option="common";
    xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option);
    xmlhttp.send();
//FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
//DATE PICKER FUNCTION
    $('.REV_datepicker ').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });
    //END DATE PICKER FUNCTION
    //DATE PICKER FUNCTION
    $('.REV_datepickerrnge ').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });
    //END DATE PICKER FUNCTION
    //CHANGE FUNCTION FOR PERMANENT RADIO BTN
    $(document).on('change','.valid',function(){
        $('sectionrnge').html('');
        var REV_start_date= $('#REV_tb_strtdte').val()
        var REV_end_date=$('#REV_tb_enddte').val()
        $('#REV_tble_nonactive_bydaterange').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_nodata_startenddate').hide();
        $('#sectionrnge').hide();
        if((REV_start_date!='') && (REV_end_date!='' ))
        {
            $("#REV_btn_search").removeAttr("disabled");
        }
        else
        {
            $("#REV_btn_search").attr("disabled","disabled");
        }
    });
    //VALIDATION SEARCH BTN FOR PROJECT NAME BY DATE RANGE
    $(document).on('change','.validsrchbtn',function(){
        $('sectionprbydtrange').html('');
        var REV_start_date= $('#REV_tb_strtdtebyrange').val()
        var REV_end_date=$('#REV_tb_enddtebyrange').val();
        $('#REV_tble_projctrevenue_bydaterange').html('');
        $('#REV_lbl_totaldays').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_nodata_startenddate').hide();
        if((REV_start_date!='') && (REV_end_date!='' ))
        {
            $("#REV_btn_searchdaterange").removeAttr("disabled");
        }
        else
        {
            $("#REV_btn_searchdaterange").attr("disabled","disabled");
        }
    });
// CHANGE EVENT FOR PROJECT LISTBOX
    $('#REV_lb_project').change(function(){
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_btn_searchdaterange').hide();
        $('#REV_tble_searchbtn').html('');
        $('#REV_nodata_pd').hide();
        $('#REV_tble_nonactive_bydaterange').hide();
        $('#REV_nodata_pdflextble').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_btn_search').hide();
        var option=$("#REV_lb_project").val();
        if(option=="SELECT")
        {
            $('#REV_btn_searchdaterange').hide();
            $('#REV_tb_strtdtebyrange').hide();
            $('#REV_tb_enddtebyrange').hide();
            $('#REV_lbl_strtdtebyrange').hide();
            $('#REV_lbl_enddtebyrange').hide();
            $('#REV_tble_prjctrevactnonact').hide();
            $('#REV_tble_prjctrevenue').hide();
            $('#REV_lbl_prjctnme').hide();
            $('#REV_lb_projectname').hide();
            $('#REV_btn_prjctsrch').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_lb_projectnamedaterange').hide();
            $('#REV_lbl_prjctnmedaterange').hide();
            $('#REV_tble_prjctrevenuedaterange').hide();
            $('#REV_nodata_pd').hide();
            $('#REV_div_projecttotal').hide();
            $('#nonactiveempdatatble').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_div_projecttotal_dtebyrange').hide();

        }
        else if(option=='7')
        {
            if(REV_project_name.length!=0)
            {
                $('#REV_tble_prjctrevenue').show();
                $('#REV_lbl_prjctnme').show();
                $('#REV_lb_projectname').val("SELECT").show();
            }
            else
            {
                $('#REV_nodata_pd').text(err_msg_array[0]).show();
            }
            $('#REV_lbl_totaldays').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_rd_actveemp').hide();
            $('#REV_rd_nonactveemp').hide();
            $('#REV_lbl_actveemp').hide();
            $('#REV_lbl_nonactveemp').hide();
            $('#REV_lbl_actveemps').hide();
            $('#REV_lbl_nonactveemps').hide();
            $('#REV_div_loginid').hide();
            $('#REV_lb_projectnamedaterange').hide();
            $('#REV_lbl_prjctnmedaterange').hide();
            $('#REV_tble_prjctrevenuedaterange').hide();
            $('#REV_tble_startdate').hide();
            $('#REV_tble_searchbtn').hide();
            $('#REV_tb_strtdtedaterange').val('').hide();
            $('#REV_lbl_enddtedaterange').hide();
            $('#REV_tb_enddtedaterange').val('').hide();
            $('#REV_btn_searchdaterange').hide();

        }
        else if(option=='8')
        {
            $('#REV_tble_prjctrevactnonact').show();
            $('#REV_rd_actveemp').show();
            $('#REV_rd_actveemp').attr("checked",false);
            $('#REV_rd_nonactveemp').show();
            $('#REV_rd_nonactveemp').attr("checked",false);
            $('#REV_lbl_actveemp').show();
            $('#REV_lbl_nonactveemp').show();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_prjctnme').hide();
            $('#REV_lb_projectname').hide();
            $('#REV_btn_prjctsrch').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_actveemps').hide();
            $('#REV_div_projecttotal').hide();
            $('#REV_lbl_nonactveemps').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_lbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_lb_projectnamedaterange').hide();
            $('#REV_lbl_prjctnmedaterange').hide();
            $('#REV_tble_prjctrevenuedaterange').hide();
            $('#REV_tble_startdate').hide();
            $('#REV_tble_searchbtn').hide();
            $('#REV_tb_strtdtedaterange').val('').hide();
            $('#REV_lbl_enddtedaterange').hide();
            $('#REV_tb_enddtedaterange').val('').hide();
            $('#REV_btn_searchdaterange').hide();
        }
        else if(option=='9')
        {
            $('#REV_tble_prjctrevactnonact').show();
            $('#REV_rd_actveemp').show();
            $('#REV_rd_actveemp').attr("checked",false);
            $('#REV_rd_nonactveemp').show();
            $('#REV_rd_nonactveemp').attr("checked",false);
            $('#REV_lbl_actveemp').show();
            $('#REV_lbl_nonactveemp').show();
            $('#REV_lbl_prjctnme').hide();
            $('#REV_lb_projectname').hide();
            $('#REV_btn_prjctsrch').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_div_projecttotal').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_lbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_actveemps').hide();
            $('#REV_lbl_nonactveemps').hide();
            $('#REV_lb_projectnamedaterange').hide();
            $('#REV_lbl_prjctnmedaterange').hide();
            $('#REV_tble_prjctrevenuedaterange').hide();
            $('#REV_tble_startdate').hide();
            $('#REV_tble_searchbtn').hide();
            $('#REV_tb_strtdtedaterange').val('').hide();
            $('#REV_lbl_enddtedaterange').hide();
            $('#REV_tb_enddtedaterange').val('').hide();
            $('#REV_btn_searchdaterange').hide();

        }
        else if(option=='10')
        {

            if(REV_project_name.length!=0)
            {
                $('#REV_tble_prjctrevenue').show();
                $('#REV_lbl_prjctnme').show();
                $('#REV_lb_projectname').val("SELECT").show();
            }
            else
            {
                $('#REV_nodata_pd').text(err_msg_array[0]).show();
            }
            $('#REV_lbl_totaldays').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_rd_actveemp').hide();
            $('#REV_rd_nonactveemp').hide();
            $('#REV_lbl_actveemp').hide();
            $('#REV_lbl_nonactveemp').hide();
            $('#REV_lbl_actveemps').hide();
            $('#REV_lbl_nonactveemps').hide();
            $('#REV_div_loginid').hide();
            $('#REV_btn_prjctsrch').hide();
            $('#REV_div_projecttotal').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_tb_strtdtedaterange').val('').hide();
            $('#REV_lbl_enddtedaterange').hide();
            $('#REV_tb_enddtedaterange').val('').hide();
            $('#REV_btn_searchdaterange').hide();
        }
    });
// CHANGE EVENT FOR PROJECT NAME
    var daterange_val=[];
    $('#REV_lb_projectname').change(function(){
        $('.preloader').show();
        $('#REV_btn_prjctsrch').show();
        $('#REV_tble_searchbtn').html('');
        $('#REV_nodata_loginid').hide();
        $('#REV_div_projecttotal').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_nodata_pdflextble').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_nodata_staenddate').hide();
        if($('#REV_lb_projectname').val()=="SELECT")
        {
            $('.preloader').hide();
            $('#REV_btn_prjctsrch').attr("disabled","disabled");
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_btn_searchdaterange').hide();
            $('#REV_tble_searchbtn').html('');
        }
        else
        {
            $('.preloader').hide();
            $('#REV_tble_searchbtn').show();
            $('#REV_tble_startdate').show();
            $('#REV_btn_prjctsrch').removeAttr("disabled");
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_totaldays').hide();
        }
    });
// CLICK EVENT FOR ACTIVE RADIO BUTTON
    $('#REV_rd_actveemp').click(function(){
        $('#REV_div_loginid').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        if(REV_active_emp.length!=0)
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<REV_active_emp.length;i++) {
                active_employee += '<option value="' + REV_active_emp[i] + '">' + REV_active_emp[i] + '</option>';
            }
            $('#REV_lb_loginid').html(active_employee);
            $('#REV_lbl_actveemps').show();
            $('#REV_lbl_loginid').show();
            $('#REV_lb_loginid').show();
        }
        else
        {
            $('#REV_nodata_uld').text(err_msg_array[0]).show();
        }
        $('#REV_btn_empsrch').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tble_empday').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
    });
// CLICK EVENT FOR NONACTIVE RADIO BUTTON
    $('#REV_rd_nonactveemp').click(function(){
        $('#REV_div_loginid').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        if(REV_nonactive_emp.length!=0)
        {
            var nonactive_employee='<option>SELECT</option>';
            for (var i=0;i<REV_nonactive_emp.length;i++) {
                nonactive_employee += '<option value="' + REV_nonactive_emp[i] + '">' + REV_nonactive_emp[i] + '</option>';
            }
            $('#REV_lb_loginid').html(nonactive_employee);
            $('#REV_lbl_nonactveemps').show();
            $('#REV_lbl_loginid').show();
            $('#REV_lb_loginid').show();
        }
        else
        {
            $('#REV_nodata_uld').text(err_msg_array[0]).show();
        }
        $('#REV_btn_empsrch').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tble_empday').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
    });
// CHANGE EVENT FOR LOGINID
    $('#REV_lb_loginid').change(function(){
        $('.preloader').show();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tb_strtdte').val('');
        $('#REV_tb_enddte').val('');
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_loginid').hide();

        $('sectionprbydtrange').html('');
        $('sections').html('');
        $('sectionrnge').html('');
        $('#REV_btn_empsrch').hide();
        var formElement = document.getElementById("REV_form_revenue");
        var date_val=[];
        var REV_loginids=$("#REV_lb_loginid").val();
        var option=$("#REV_lb_project").val();
        $('#REV_div_loginid').hide();
        if($('#REV_lb_loginid').val()=="SELECT")
        {
//            $('.preloader').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_nodata_startenddate').hide();
        }
        else
        {
            if(option=='8')
            {
//                $('.preloader').hide();
                $('#REV_btn_empsrch').show();
                $('#REV_btn_empsrch').removeAttr("disabled");
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
            }
            else if(option=='9')
            {
//                $('.preloader').hide();
                $('#REV_btn_empsrch').hide();
                $('#REV_tb_strtdte').show();
                $('#REV_lbl_strtdte').show();
                $('#REV_lbl_enddte').show();
                $('#REV_tb_enddte').val('').show();
                $('#REV_btn_search').show();
                $("#REV_btn_search").attr("disabled","disabled");
                $('#REV_div_loginid').hide();
                //DATE PICKER FUNCTION
                $('.REV_datepickerrnge ').datepicker(
                    {
                        dateFormat: 'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                //END DATE PICKER FUNCTION
            }
        }
        //FUNCTION FOR SETTINF MIN ND MAX DATE
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                date_val=JSON.parse(xmlhttp.responseText);
                var REV_start_dates=date_val[0];
                var REV_end_dates=date_val[1];
                $('#REV_tb_strtdte').datepicker("option","minDate",new Date(REV_start_dates));
                $('#REV_tb_strtdte').datepicker("option","maxDate",new Date(REV_end_dates));
                $('#REV_tb_enddte').datepicker("option","maxDate",new Date(REV_end_dates));
            }
        }
        var choice="login_id";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?REV_loginids="+REV_loginids+"&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
        //SET END DATE
        $(document).on('change','#REV_tb_strtdte',function(){
            var USRC_UPD_startdate = $('#REV_tb_strtdte').datepicker('getDate');
            var date = new Date( Date.parse( USRC_UPD_startdate ));
            date.setDate( date.getDate()  );
            var USRC_UPD_todate = date.toDateString();
            USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
            $('#REV_tb_enddte').datepicker("option","minDate",USRC_UPD_todate);
        });
    });
    // CHANGE EVENT FOR PROJECT NAME
    $('#REV_lb_projectname').change(function(){
        $('.preloader').show();
        $('section').html('');
        $('sectionprbydtrange').html('');
        $('#REV_btn_prjctsrch').hide();
        $('#REV_tble_startdate').html('');
        $('#REV_btn_searchdaterange').hide();
        var REV_loginids=$("#REV_lb_projectname").val();
        var option=$("#REV_lb_project").val();
        $('#REV_div_loginid').hide();
        if($('#REV_lb_projectname').val()=="SELECT")
        {
            $('.preloader').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_btn_searchdaterange').hide();
            $('#REV_nodata_pdflextble').hide();
            $('#REV_div_projecttotal_dtebyrange').hide();
        }
        else
        {
            if(option=='7')
            {
                $('#REV_btn_prjctsrch').show();
                $('#REV_btn_prjctsrch').removeAttr("disabled");
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('.preloader').hide();
            }
            else if((option=='10'))
            {
                $('<tr><td width="150"><label name="REV_lbl_strtdte" id="REV_lbl_strtdtebyrange" >START DATE<em>*</em></label></td><td><input type="text" name="REV_tb_strtdtebyrange" id="REV_tb_strtdtebyrange" class=" validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></td></tr>').appendTo('#REV_tble_startdate')
                $('<tr><td width="150"><label name="REV_lbl_enddte" id="REV_lbl_enddtebyrange" >END DATE<em>*</em></label></td><td><input type="text" name="REV_tb_enddtebyrange" id="REV_tb_enddtebyrange" class=" validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></td></tr>').appendTo('#REV_tble_startdate')
                $('<tr><td><input type="button" class="btn" name="REV_btn_searchdaterange" id="REV_btn_searchdaterange"  value="SEARCH" disabled></td></tr>').appendTo('#REV_tble_searchbtn')
                //DATE PICKER FUNCTION
                $('.REV_datepicker').datepicker(
                    {
                        dateFormat: 'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                //END DATE PICKER FUNCTION
                var daterange_val=[];
                var formElement = document.getElementById("REV_form_revenue");
                var REV_project_name=$("#REV_lb_projectname").val();
                //FUNCTION FOR SETTINF MIN ND MAX DATE
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        daterange_val=JSON.parse(xmlhttp.responseText);
                        var REV_start_dates=daterange_val[0];
                        var REV_end_dates=daterange_val[1];
                        $('#REV_tb_strtdtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REV_tb_strtdtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                        $('#REV_tb_enddtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                    }
                }
                var choice="set_datemin_max";
                xmlhttp.open("GET","DB_REPORT_REVENUE.do?REV_project_name="+REV_project_name+"&option="+choice,true);
                xmlhttp.send(new FormData(formElement));
                //SET END DATE
                $(document).on('change','#REV_tb_strtdtebyrange',function(){
                    var USRC_UPD_startdate = $('#REV_tb_strtdtebyrange').datepicker('getDate');
                    var date = new Date( Date.parse( USRC_UPD_startdate ));
                    date.setDate( date.getDate()  );
                    var USRC_UPD_todate = date.toDateString();
                    USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                    $('#REV_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
                });
                $('#REV_lbl_selectdterange').show();
                $('#REV_btn_prjctsrch').hide();
                $('#REV_lbl_strtdtedaterange').show();
                $('#REV_tb_strtdtedaterange').val('').show();
                $('#REV_lbl_enddtedaterange').show();
                $('#REV_tb_enddtedaterange').val('').show();
                $("#REV_btn_search").attr("disabled","disabled");
                $('#REV_div_loginid').hide();
            }
        }
    });
// CLICK EVENT FOR PROJECT SEARCH BUTTON
    var projectvalues=[];
    $(document).on('click','#REV_btn_prjctsrch',function(){
        $('#REV_nodata_pdflextble').hide();
        $('#REV_div_projecttotal').hide();
        $('#REV_lbl_totaldays').hide();
        $('#REV_tble_totaldays').html('');
        $('#REV_btn_prjctsrch').attr("disabled","disabled");
        var REV_projectname=$('#REV_lb_projectname').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var projectvalues=JSON.parse(xmlhttp.responseText);
                if(projectvalues)
                {
                    var totalnoof_days= projectvalues[0].totalnoofdays;
                    $('#REV_lbl_totaldays').text("TOTAL NO OF  DAYS: "  +   totalnoof_days   +  " DAYS").show();
                    var REV_table_header='<table id="REV_tble_totaldays" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>USERNAME</th><th>NUMBER OF DAYS</th></tr></thead><tbody>'
                    for(var i=0;i<projectvalues.length;i++){
                        var username=projectvalues[i].username;
                        var noofdays=projectvalues[i].noofdays;
                        REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td></tr>';
                    }
                    REV_table_header+='</tbody></table>';
                    $('section').html(REV_table_header);
                    $('#REV_tble_totaldays').DataTable({
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[3].toString().replace("[NAME]",REV_projectname);
                    $('#REV_nodata_pdflextble').text(sd).show();
                    $('#REV_div_projecttotal').hide();
                }
                $(".preloader").hide();
            }
        }
        $('#REV_div_projecttotal').show();
        var option="projectname";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname);
        xmlhttp.send();
    });
// CLICK EVENT FOR LOGINID SEARCH BUTTON
    var loginidvalues=[];
    $(document).on('click','#REV_btn_empsrch',function(){
        $('#REV_nodata_loginid').hide();
        $('#REV_btn_empsrch').attr("disabled","disabled");
        $('#REV_div_loginid').hide();
//        $('sections').hide();
        $('#REV_tble_empday_nonactveemp1').html('');
        var REV_loginid=$('#REV_lb_loginid').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                loginidvalues=JSON.parse(xmlhttp.responseText);
                if(loginidvalues)
                {
                    var working_days= loginidvalues[0].noof_prjct_day;
                    var total_days= loginidvalues[0].working_day;
                    var eachprojt_working_days= loginidvalues[0].eachprjt_working_day;
                    $('#REV_lbl_ttlprjct').text("TOTAL PROJECTS WORKED OUT: "  +  working_days  +  " PROJECTS").show();
                    $('#REV_lbl_empday').text("TOTAL NO OF  DAYS: "  +   total_days   +  " DAYS").show();
                    $('#REV_lbl_eachproject_empday').text("EACH PROJECT WORKED DAYS: "  +   eachprojt_working_days).show();
                    var REV_table_header1='<table id="REV_tble_empday_nonactveemp1" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:10px" >PROJECT DATE</th><th>PROJECT NAME</th></tr></thead><tbody>'
                    for(var i=0;i<loginidvalues.length;i++){
                        var projectdate=loginidvalues[i].projectdate;
                        var projectname=loginidvalues[i].projectname;
                        REV_table_header1+='<tr><td style="width:10px">'+projectdate+'</td><td>'+projectname+'</td></tr>';
                    }
                    REV_table_header1+='</tbody></table>';
                    $('sections').html(REV_table_header1);
                    $('#REV_tble_empday_nonactveemp1').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ],
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[2].toString().replace("[LOGINID]",REV_loginid);
                    $('#REV_nodata_loginid').text(sd).show();
                    $('#REV_div_loginid').hide();
                }
            }
        }
        $('#REV_div_loginid').show();
        var option="nonactiveempdatatble";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid);
        xmlhttp.send();
        sorting();
    });
// CLICK EVENT FOR LOGINID SEARCH BUTTON
    var loginidvalues=[];
    $(document).on('click','#REV_btn_search',function(){
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_tble_nonactive_bydaterange').html('');
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        var REV_start_datevalue=$('#REV_tb_strtdte').val()
        var REV_end_datevalue=$('#REV_tb_enddte').val()
        $('#REV_btn_search').attr("disabled","disabled");
        var REV_loginid=$('#REV_lb_loginid').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                loginidvalues=JSON.parse(xmlhttp.responseText);
                if(loginidvalues)
                {
                    var total_no_projects= loginidvalues[0].total_no_project;
                    var eachproject_working_day= loginidvalues[0].eachprjt_working_day;
                    $('#REV_lbl_ttlprjct').text("TOTAL PROJECTS WORKED OUT: "  +  total_no_projects  +  " PROJECTS").show();
                    var REV_tble_header='<table id="REV_tble_nonactive_bydaterange" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:10px">PROJECT DATE</th><th >PROJECT NAME</th></tr></thead><tbody>'
                    for(var i=0;i<loginidvalues.length;i++){
                        var projectdate=loginidvalues[i].projectdate;
                        var projectname=loginidvalues[i].projectname;
                        REV_tble_header+='<tr><td style="width:10px">'+projectdate+'</td><td>'+projectname+'</td></tr>';
                    }
                    REV_tble_header+='</tbody></table>';
                    $('sectionrnge').html(REV_tble_header);
                    $('#REV_tble_nonactive_bydaterange').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ],

                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                    var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                    $('#REV_nodata_startenddate').text(msg).show();
                    $('#REV_div_nonactve_dterange').hide();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_eachproject_empday').hide();
                    $('#REV_lbl_empday').hide();
                }
                $(".preloader").hide();
            }
        }
        $('#REV_div_nonactve_dterange').show();
        var option="non_activeemp_dterange";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue);
        xmlhttp.send();
        sorting();
    });
// CLICK EVENT FOR PROJECT SEARCH BUTTON
    var projectvalues=[];
    $(document).on('click','#REV_btn_searchdaterange',function(){
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_tble_projctrevenue_bydaterange').html('');
        $('#REV_btn_searchdaterange').attr("disabled","disabled");
        var REV_start_datevalue=$('#REV_tb_strtdtebyrange').val()
        var REV_end_datevalue=$('#REV_tb_enddtebyrange').val()
        var REV_projectname=$('#REV_lb_projectname').val();
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var projectvalues=JSON.parse(xmlhttp.responseText);
                if(projectvalues)
                {
                    var working_day= projectvalues[0].working_day;
                    var REV_table_header='<table id="REV_tble_projctrevenue_bydaterange" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>USERNAME</th><th>NUMBER OF DAYS</th></tr></thead><tbody>'
                    for(var i=0;i<projectvalues.length;i++){
                        var username=projectvalues[i].username;
                        var noofdays=projectvalues[i].noofdays;
                        REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td></tr>';
                    }
                    REV_table_header+='</tbody></table>';
                    $('sectionprbydtrange').html(REV_table_header);
                    $('#REV_tble_projctrevenue_bydaterange').DataTable({
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            "pdf"],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                    var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                    $('#REV_nodata_staenddate').text(msg).show();
                    $('#REV_div_projecttotal_dtebyrange').hide();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_empday').hide();
                    $('#REV_lbl_eachproject_empday').hide();
                }
                $(".preloader").hide();
            }
        }
        $('#REV_div_projecttotal_dtebyrange').show();
        var option="projectname_dtebyrange";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue);
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
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>REVENUE</h3><p></div></div>
    <form   id="REV_form_revenue" class="content" >
        <table>
            <table>
                <tr>
                    <td width="150"><label name="REV_lbl_prjct" id="REV_lbl_prjct" hidden>PROJECT<em>*</em></label></td>
                    <td width="150">
                        <select id="REV_lb_project" name="REV_lb_project" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_rc" name="REV_nodata_rc" class="errormsg"></label></td></tr>
            <table  id="REV_tble_prjctrevenue"  hidden>
                <tr>
                    <td width="150"><label name="REV_lbl_prjctnme" id="REV_lbl_prjctnme" hidden >PROJECT NAME<em>*</em></label></td>
                    <td>
                        <select id="REV_lb_projectname" name="REV_lb_projectname" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_pd" name="REV_nodata_pd" class="errormsg"></label></td></tr>
            <table id="REV_tble_startdate"></table>
            <table id="REV_tble_searchbtn"></table>
            <table>
                <tr>
                    <td><input type="button" id="REV_btn_prjctsrch" name="REV_btn_prjctsrch" value="SEARCH" class="btn" disabled/></td>
                </tr>
                <tr><td><label id="REV_nodata_pdflextble" name="REV_nodata_pdflextble" class="errormsg"></label></td></tr>
                <tr>
                    <td><label id="REV_lbl_totaldays" name="REV_lbl_totaldays"  class="srctitle" hidden></label></td>
                </tr>
            </table>
            <tr>
                <div id ="REV_div_projecttotal" class="container" style="width:500px" hidden>
                    <section>
                    </section>
                </div>
            </tr>
            <tr><td><label id="REV_nodata_staenddate" name="REV_nodata_staenddate" class="errormsg"></label></td></tr>
            <tr>
                <div id ="REV_div_projecttotal_dtebyrange" class="container" style="width:500px" hidden>
                    <sectionprbydtrange>
                    </sectionprbydtrange>
                </div>
            </tr>
        </table>
        <table id="REV_tble_prjctrevactnonact" hidden>
            <tr>
                <td><input type="radio" name="REV_rd_veemp" id="REV_rd_actveemp" value="EMPLOYEE" hidden >
                    <label name="REV_lbl_actveemp" id="REV_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
            </tr>
            <tr>
                <td><input type="radio" name="REV_rd_veemp" id="REV_rd_nonactveemp"   value="EMPLOYEE" class='attnd' hidden>
                    <label name="REV_lbl_nonactveemp" id="REV_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE </label></td>
            </tr>
            <tr>
                <td><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="srctitle" hidden>ACTIVE EMPLOYEE</label></td>
            </tr>
            <tr>
                <td><label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle" hidden>NON ACTIVE EMPLOYEE </label></td>
            </tr>
            <tr><td><label id="REV_nodata_uld" name="REV_nodata_uld" class="errormsg"></label></td></tr>
            <tr>
                <td><table>
                        <tr><td width="150">
                                <label name="REV_lbl_loginid" id="REV_lbl_loginid"  hidden>LOGIN ID<em>*</em></label></td>
                            <td>
                                <select name="REV_lb_loginid" id="REV_lb_loginid" hidden>
                                </select>
                            </td>
                        </tr></table></td></tr>
            <tr>
                <td><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn" disabled  /></td>
                <td>
            </tr>
            <tr><td><label id="REV_nodata_loginid" name="REV_nodata_loginid" class="errormsg"></label></td></tr>
            <table>
                <tr>
                    <td width="150"><label name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label></td>
                    <td><input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></td><br>
                </tr>
                <tr>
                    <td width="150"><label name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label></td>
                    <td><input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="REV_btn_search" id="REV_btn_search"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_startenddate" name="REV_nodata_startenddate" class="errormsg"></label></td></tr>
        </table>
        <tr>
            <td><label id="REV_lbl_ttlprjct" name="REV_lbl_ttlprjct"  class="srctitle" hidden></label></td><br>
        </tr>
        <tr>
            <td><label id="REV_lbl_empday" name="REV_lbl_empday"  class="srctitle" hidden></label></td>
        </tr><BR>
        <tr>
            <td><label id="REV_lbl_eachproject_empday" name="REV_lbl_eachproject_empday"  class="srctitle" hidden></label></td>
        </tr>
        <div  id ="REV_div_loginid" class="container" style="width:500px" hidden>
            <sections style="width:500px">
            </sections>
        </div>
        <div id ="REV_div_nonactve_dterange" class="container" style="width:500px" hidden>
            <sectionrnge style="width:500px">
            </sectionrnge>
        </div>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
