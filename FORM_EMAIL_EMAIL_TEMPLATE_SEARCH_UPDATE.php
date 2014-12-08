<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE SEARCH/UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.02-SD:14/11/2014 ED:14/11/2014,TRACKER NO:74,Fixed width
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:99
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var ET_SRC_UPD_DEL_result_array=[];
var ET_SRC_UPD_DEL_name;
var ET_SRC_UPD_DEL_sucsval=0,ET_SRC_UPD_DEL_emailsubject="",ET_SRC_UPD_DEL_emailbody="";
var ET_SRC_UPD_DEL_errorMsg_array=[];
var ET_SRC_UPD_DEL_emailtemplate_list=[];
//READY FUNCTION START
$(document).ready(function(){
    $(".preloader").show();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            ET_SRC_UPD_DEL_emailtemplate_list=value_array[0];
            ET_SRC_UPD_DEL_errorMsg_array=value_array[1];
            if(ET_SRC_UPD_DEL_emailtemplate_list.length!=0){
                var ET_SRC_UPD_DEL_emltemp_list='<option>SELECT</option>';
                for (var i=0;i<ET_SRC_UPD_DEL_emailtemplate_list.length;i++) {
                    ET_SRC_UPD_DEL_emltemp_list += '<option value="' + ET_SRC_UPD_DEL_emailtemplate_list[i][1] + '">' + ET_SRC_UPD_DEL_emailtemplate_list[i][0] + '</option>';
                }
                $('#ET_SRC_UPD_DEL_lb_scriptname').html(ET_SRC_UPD_DEL_emltemp_list);
                $('#ET_SRC_UPD_DEL_lbl_scriptname').show();
                $('#ET_SRC_UPD_DEL_lb_scriptname').show();
            }
            else
            {
                $('#ET_SRC_UPD_DEL_div_headernodata').text(ET_SRC_UPD_DEL_errorMsg_array[0]).show();
            }
        }
    }
    var option="INITIAL_DATAS";
    xmlhttp.open("GET","DB_EMAIL_EMAIL_TEMPLATE_SEARCH_UPDATE.do?option="+option);
    xmlhttp.send();
    //END FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
    //JQUERY LIB VALIDATION START
    $('.uppercase').doValidation({rule:'general',prop:{uppercase:true}});
    $('textarea').autogrow({onInitialize: true});
    //JQUERY LIB VALIDATION END
//KEY PRESS FUNCTION START
    var ET_SRC_UPD_DEL_max=1000;
    $('.maxlength').keypress(function(e)
    {
        if(e.which < 0x20)
        {
            return;
        }
        if(this.value.length==ET_SRC_UPD_DEL_max)
        {
            e.preventDefault();
        }
        else if(this.value.length > ET_SRC_UPD_DEL_max)
        {
            this.value=this.value.substring(0,ET_SRC_UPD_DEL_max);
        }
    });
//KEY PRESS FUNCTION END
    var ET_SRC_UPD_DEL_emailsubject;
    var ET_SRC_UPD_DEL_emailbody;
    var ET_SRC_UPD_DEL_userstamp;
    var ET_SRC_UPD_DEL_timestmp;
    var id;
    var ET_SRC_UPD_DEL_table_value='';
    var values_array=[];
    //CHANGE FUNCTION FOR SCRIPTNAME
    $('#ET_SRC_UPD_DEL_lb_scriptname').change(function()
    {
        $(".preloader").show();
        $('#ET_SRC_UPD_DEL_div_headernodata').hide();
        ET_SRC_UPD_DEL_name=$('#ET_SRC_UPD_DEL_lb_scriptname').find('option:selected').text();
        $('#ET_SRC_UPD_DEL_div_header').hide();
        var ET_SRC_UPD_DEL_scriptname = $("#ET_SRC_UPD_DEL_lb_scriptname").val();
        if(ET_SRC_UPD_DEL_scriptname=='SELECT')
        {
            $(".preloader").hide();
            $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
            $('#ET_SRC_UPD_DEL_div_header').hide();
            $('#ET_SRC_UPD_DEL_div_headernodata').hide();
            $('#ET_SRC_UPD_DEL_div_update').hide();
            $('#ET_SRC_UPD_DEL_tble_srchupd').hide();
        }
        else
        {
            $('#ET_SRC_UPD_DEL_div_table').show();
            $('#ET_SRC_UPD_DEL_div_update').hide();
            $('#ET_SRC_UPD_DEL_tble_srchupd').hide();
            $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
            ET_SRC_UPD_DEL_srch_result();
        }
    });
    //RESPONSE FUNCTION FOR FLEXTABLE SHOWING
    function ET_SRC_UPD_DEL_srch_result(){
        $(".preloader").hide();
        var formElement = document.getElementById("ET_SRC_UPD_DEL_form_emailtemplate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var ET_SRC_UPD_DEL_table_header='<tr><th></th><th style="width:1000px">EMAIL SUBJECT</th><th style="width:1000px">EMAIL BODY</th><th style="width:90px">USERSTAMP</th><th style="width:150px" nowrap>TIMESTAMP</th></tr>'
                $('#ET_SRC_UPD_DEL_tble_htmltable').html(ET_SRC_UPD_DEL_table_header);
                values_array=JSON.parse(xmlhttp.responseText);
                if(values_array.length!=0)
                {
                    var ET_SRC_UPD_DEL_errmsg =ET_SRC_UPD_DEL_errorMsg_array[3].replace('[SCRIPT]',ET_SRC_UPD_DEL_name);
                    $('#ET_SRC_UPD_DEL_div_header').text(ET_SRC_UPD_DEL_errmsg).show();
                    $('#ET_SRC_UPD_DEL_div_flexdata_result').show();
                    for(var j=0;j<values_array.length;j++){
                        ET_SRC_UPD_DEL_emailsubject=values_array[j].ET_SRC_UPD_DEL_subject;
                        ET_SRC_UPD_DEL_emailsubject=unescapeHTML(ET_SRC_UPD_DEL_emailsubject);
                        ET_SRC_UPD_DEL_emailbody=values_array[j].ET_SRC_UPD_DEL_body;
                        ET_SRC_UPD_DEL_emailbody=unescapeHTML(ET_SRC_UPD_DEL_emailbody);
                        ET_SRC_UPD_DEL_userstamp=values_array[j].ET_SRC_UPD_DEL_userstamp;
                        ET_SRC_UPD_DEL_timestmp=values_array[j].ET_SRC_UPD_DEL_timestamp;
                        id=values_array[j].id;
                        ET_SRC_UPD_DEL_table_value='<tbody><tr><td><input type="radio" name="ET_SRC_UPD_DEL_rd_flxtbl" class="ET_SRC_UPD_DEL_radio" id='+id+'  value='+id+' ></td><td style="width:1000px">'+ET_SRC_UPD_DEL_emailsubject+'</td><td style="width:1000px">'+ET_SRC_UPD_DEL_emailbody+'</td><td>'+ET_SRC_UPD_DEL_userstamp+'</td><td>'+ET_SRC_UPD_DEL_timestmp+'</td></tr>';
                        $('#ET_SRC_UPD_DEL_tble_htmltable').append(ET_SRC_UPD_DEL_table_value).show();
                    }
                }
                else
                {
                    $('#ET_SRC_UPD_DEL_div_header').hide();
                    $('#ET_SRC_UPD_DEL_div_table').hide();
                    $('#ET_SRC_UPD_DEL_div_headernodata').text(ET_SRC_UPD_DEL_errorMsg_array[1]).show();
                    $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
                    $(".preloader").hide();
                }
            }
        }
        var choice="EMAIL_TEMPLATE_DETAILS"
        xmlhttp.open("POST","DB_EMAIL_EMAIL_TEMPLATE_SEARCH_UPDATE.do?&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    }
    //RADIO CLICK FUNCTION
    $(document).on('click','.ET_SRC_UPD_DEL_radio',function(){
        $('#ET_SRC_UPD_DEL_div_update').hide();
        $('#ET_SRC_UPD_DEL_tble_srchupd').show();
        $("#ET_SRC_UPD_DEL_btn_search").removeAttr("disabled","disabled").show();
    });
    //CLICK EVENT FUCNTION FOR BUTTON SEARCH
    $(document).on('click','#ET_SRC_UPD_DEL_btn_search',function(){
        $('#ET_SRC_UPD_DEL_div_update').show();
        $("#ET_SRC_UPD_DEL_btn_search").attr("disabled","disabled");
        $("#ET_SRC_UPD_DEL_btn_update").attr("disabled","disabled");
        var SRC_UPD_idradiovalue=$('input:radio[name=ET_SRC_UPD_DEL_rd_flxtbl]:checked').attr('id');
        for(var j=0;j<values_array.length;j++){
            if(id==SRC_UPD_idradiovalue)
            {
                ET_SRC_UPD_DEL_name=$('#ET_SRC_UPD_DEL_lb_scriptname').find('option:selected').text();
                var ET_SRC_UPD_DEL_names=(ET_SRC_UPD_DEL_name).length+2;
                $('#ET_SRC_UPD_DEL_tb_script').attr("size",ET_SRC_UPD_DEL_names);
                $('#ET_SRC_UPD_DEL_tb_script').val(ET_SRC_UPD_DEL_name).show();
                $('#ET_SRC_UPD_DEL_tb_script').val(ET_SRC_UPD_DEL_name).show();
                $('#ET_SRC_UPD_DEL_ta_updsubject').val(ET_SRC_UPD_DEL_emailsubject).show();
                $('#ET_SRC_UPD_DEL_ta_updbody').val(ET_SRC_UPD_DEL_emailbody);
                $("#ET_SRC_UPD_DEL_btn_update").attr("disabled","disabled");
            }
        }
        $("textarea").height(30);
    });
    //EMAIL SCRIPT VALIDATION
    $(document).on('change blur','.validation',function()
    {
        if(($("#ET_SRC_UPD_DEL_ta_updsubject").val()=='')||($("#ET_SRC_UPD_DEL_ta_updbody").val()=='')||($("#ET_SRC_UPD_DEL_ta_updsubject").val().trim()==ET_SRC_UPD_DEL_emailsubject)&&($("#ET_SRC_UPD_DEL_ta_updbody").val().trim()==ET_SRC_UPD_DEL_emailbody))
        {
            $("#ET_SRC_UPD_DEL_btn_update").attr("disabled", "disabled");
        }
        else
        {
            $("#ET_SRC_UPD_DEL_btn_update").removeAttr("disabled");
        }
    });
    //FUNCTION FOR DECODE THE SPECIAL CHARCTERS
    function unescapeHTML(p_string)
    {
        if ((typeof p_string === "string") && (new RegExp(/&amp;|&lt;|&gt;|&quot;|&#39;/).test(p_string)))
        {
            return p_string.replace(/&amp;/g, "&").replace(/&lt/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, "\"").replace(/&#39;/g, "'");
        }
        return p_string;
    }
    //CLICK EVENT FUCNTION FOR UPDATE
    $('#ET_SRC_UPD_DEL_btn_update').click(function()
    {
        $(".preloader").show();
        var ET_SRC_UPD_DEL_scriptname=$('#ET_SRC_UPD_DEL_lb_scriptname').val();
        var ET_SRC_UPD_DEL_datasubject=$('#ET_SRC_UPD_DEL_ta_updsubject').val();
        var ET_SRC_UPD_DEL_databody=$('#ET_SRC_UPD_DEL_ta_updbody').val();
        var formElement = document.getElementById("ET_SRC_UPD_DEL_form_emailtemplate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var ET_SRC_UPD_DEL_update_result=xmlhttp.responseText;
                if(ET_SRC_UPD_DEL_update_result==1){
                    var ET_SRC_UPD_DEL_scriptname=$('#ET_SRC_UPD_DEL_lb_scriptname').val();
                    $("#ET_SRC_UPD_DEL_btn_search").hide();
                    $('#ET_SRC_UPD_DEL_div_update').hide();
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE SEARCH/UPDATE",msgcontent:ET_SRC_UPD_DEL_errorMsg_array[2]}});
                    ET_SRC_UPD_DEL_srch_result()
                }
                else
                {
                    //MESSAGE BOX FOR NOT UPDATED
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE SEARCH/UPDATE",msgcontent:ET_SRC_UPD_DEL_errorMsg_array[0]}});
                }
                $(".preloader").hide();
            }
        }
        var choice="EMAIL_TEMPLATE_UPDATE"
        xmlhttp.open("POST","DB_EMAIL_EMAIL_TEMPLATE_SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    //CLICK EVENT FUCNTION FOR RESET
    $('#ET_SRC_UPD_DEL_btn_reset').click(function()
    {
        $('#ET_SRC_UPD_DEL_ta_updsubject').val('');
        $('#ET_SRC_UPD_DEL_ta_updbody').val('');
        $("#ET_SRC_UPD_DEL_btn_update").attr("disabled", "disabled");
        $("textarea").height(30);
    });
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>EMAIL TEMPLATE SEARCH/UPDATE</h3><p></div></div>
    <form   id="ET_SRC_UPD_DEL_form_emailtemplate" class="content" >
        <table>
        </table>
        <tr>
            <td><label name="ET_SRC_UPD_DEL_lbl_scriptname" id="ET_SRC_UPD_DEL_lbl_scriptname">SCRIPT NAME<em>*</em></label></td>
            <td><select name="ET_SRC_UPD_DEL_lb_scriptname" id="ET_SRC_UPD_DEL_lb_scriptname">
                    <option>SELECT</option>
                </select></td>
        </tr>
        </table>
        <div class="srctitle" name="ET_SRC_UPD_DEL_div_header" id="ET_SRC_UPD_DEL_div_header"></div>
        <div class="errormsg" name="ET_SRC_UPD_DEL_div_headernodata" id="ET_SRC_UPD_DEL_div_headernodata"></div>
        <table id="ET_SRC_UPD_DEL_tble_htmltable" class="srcresult" style="width:1300px">
        </table>
        <table id="ET_SRC_UPD_DEL_tble_srchupd" name="ET_SRC_UPD_DEL_tble_srchupd" hidden><tr>
                <td><input type="button" class="btn" name="ET_SRC_UPD_DEL_btn_search" id="ET_SRC_UPD_DEL_btn_search" disabled="" value="SEARCH" style="width:100;height:30"></td>
            </tr>
        </table>
        <div id="ET_SRC_UPD_DEL_div_update" name="ET_SRC_UPD_DEL_div_update" hidden>
            <table>
                <tr>
                    <td><label name="ET_SRC_UPD_DEL_lbl_scriptupd" id="ET_SRC_UPD_DEL_lbl_scriptupd">SCRIPT NAME</label></td>
                    <td><input type="text" name="ET_SRC_UPD_DEL_tb_script" id="ET_SRC_UPD_DEL_tb_script" class="rdonly" readonly></td>
                </tr>
                <td><input type="text" name="ET_SRC_UPD_DEL_tb_scriptnamehidden" id="ET_SRC_UPD_DEL_tb_scriptnamehidden" hidden></td>
                <tr>
                    <td><label name="ET_SRC_UPD_DEL_lbl_subjectupd" id="ET_SRC_UPD_DEL_lbl_subjectupd">SUBJECT<em>*</em></label></td>
                    <td><textarea rows="5" cols="100" name="ET_SRC_UPD_DEL_ta_updsubject" id="ET_SRC_UPD_DEL_ta_updsubject" class="validation uppercase maxlength"></textarea></td>
                </tr>
                <tr>
                    <td><label name="ET_SRC_UPD_DEL_lbl_bodyupd" id="ET_SRC_UPD_DEL_lbl_bodyupd">BODY<em>*</em></label></td>
                    <td><textarea rows="5" cols="100" name="ET_SRC_UPD_DEL_ta_updbody" id="ET_SRC_UPD_DEL_ta_updbody" class="validation uppercase maxlength"></textarea></td>
                </tr>
                <tr>
                    <td align="right"><input type="button" class="btn" name="ET_SRC_UPD_DEL_btn_update" id="ET_SRC_UPD_DEL_btn_update" value="UPDATE"></td>
                    <td align="left"><input type="button" class="btn" name="ET_SRC_UPD_DEL_btn_reset" id="ET_SRC_UPD_DEL_btn_reset" value="RESET"></td>
                </tr>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->