<!--//*******************************************FILE DESCRIPTION*********************************************//
//***********************************************COMPANY PROPERTY VERFICATION*******************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:03/11/2014 ED:04/11/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //GLOBAL DECLARATION
    var CPVD_login_id=[];
    var loginid=[];
    var err_msg_array=[];
    var active_loginid=[];
    //READY FUNCTION START
    $(document).ready(function(){
        //JQUERY LIB VALIDATION START
        $('textarea').autogrow({onInitialize: true});
        //JQUERY LIB VALIDATION END
        //GETTING ERR MSGS
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var values_array=JSON.parse(xmlhttp.responseText);
                err_msg_array=values_array[0];
                active_loginid=values_array[1];
                var CPVD_act_loginid_list='<option>SELECT</option>';
                for (var i=0;i<active_loginid.length;i++) {
                    CPVD_act_loginid_list += '<option value="' + active_loginid[i] + '">' + active_loginid[i] + '</option>';
                }
                $('#CPVD_lb_chckdby').html(CPVD_act_loginid_list);

            }
        }
        var option="INITIAL_DATAS";
        xmlhttp.open("GET","DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+option);
        xmlhttp.send();
        var data='';
        var action = '';
        showTable()
        var flag=0;
        //FUNCTION FOR LOADING ACTIVE LOGIN ID
        function showTable(){
            $.ajax({
                url:"DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do",
                type:"POST",
                data:"option=showData",
                cache: false,
                success: function(response){
                    loginid=response;
                    if(loginid!=0)
                    {
                        $(".preloader").hide();
                        $('#CPVD_lbl_loginid').show();
                        $('#CPVD_lb_loginid').html(loginid).show();
                    }
                    else
                    {
                        $('#CPVD_lbl_nologinid').text(err_msg_array[2]).show();
                    }
                }
            });
        }
        var CPVD_laptop_no=[];
        //CHANGE FUNCTION FOR LOGIN ID
        $(document).on('change','#CPVD_lb_loginid',function(){
            $(".preloader").show();
            $('#CPVD_ta_reason').val('');
            $("#CPVD_btn_send").attr("disabled", "disabled");
            var CPVD_lb_loginid=$('#CPVD_lb_loginid').val();
            if(CPVD_lb_loginid!='SELECT')
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var value_array=JSON.parse(xmlhttp.responseText);
                        for(var i=0;i<value_array.length;i++){
                            var CPVD_laptop_no=value_array[i].CPVD_lap_no;
                            var CPVD_charger_no=value_array[i].CPVD_charger_no;
                        }
                        $('#CPVD_lbl_laptopno').show();
                        $('#CPVD_tb_laptopno').val(CPVD_laptop_no).show();
                        $('#CPVD_lbl_chargerno').show();
                        $('#CPVD_tb_chargerno').val(CPVD_charger_no).show();
                        $('#CPVD_lbl_chckdby').show();
                        $('#CPVD_lb_chckdby').show();
                        $('#CPVD_lbl_chckdby').show();
                        $('#CPVD_lbl_reason').show();
                        $('#CPVD_ta_reason').show();
                        $('#CPVD_btns_sendreset').show();
                    }
                }
                var option="COMPANY_PROPERTY";
                xmlhttp.open("GET","DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+option+"&CPVD_lb_loginid="+CPVD_lb_loginid);
                xmlhttp.send();
            }
            else
            {
                $(".preloader").hide();
                $('#CPVD_lbl_reason').hide();
                $('#CPVD_ta_reason').hide();
                $('#CPVD_lbl_laptopno').hide();
                $('#CPVD_tb_laptopno').hide();
                $('#CPVD_lbl_chargerno').hide();
                $('#CPVD_tb_chargerno').hide();
                $('#CPVD_lbl_chckdby').hide();
                $('#CPVD_lb_chckdby').hide();
                $('#CPVD_btns_sendreset').hide();
            }
        });
        //BLUR FUNCTION FOR TRIM REASON
        $("#CPVD_ta_reason").blur(function(){
            $(".preloader").hide();
            $('#CPVD_ta_reason').val($('#CPVD_ta_reason').val().toUpperCase())
            var trimfunc=($('#CPVD_ta_reason').val()).trim()
            $('#CPVD_ta_reason').val(trimfunc)
        });
        //CHANGE FUNCTION FOR VALIDATION
        $(document).on('change','#CPVD_form_cmpnypropverfictn',function(){
            $("#CPVD_btn_send").attr("disabled", "disabled");
            var CPVD_tb_laptopno=$('#CPVD_tb_laptopno').val();
            var CPVD_tb_chargerno=$('#CPVD_tb_chargerno').val();
            var CPVD_lb_loginid=$('#CPVD_lb_loginid').val();
            var CPVD_lb_chckdby=$('#CPVD_lb_chckdby').val();
            var CPVD_ta_reason=$('#CPVD_ta_reason').val();
            if((CPVD_lb_loginid=="SELECT") ||(CPVD_tb_laptopno=="") || (CPVD_tb_chargerno=="")|| (CPVD_ta_reason=="")|| (CPVD_lb_chckdby=="SELECT"))
            {
                $("#CPVD_btn_send").attr("disabled", "disabled");
            }
            else
            {
                $("#CPVD_btn_send").removeAttr("disabled");
            }
        });
        //CLICK EVENT FOR SAVE BUTTON
        $(document).on('click','#CPVD_btn_send',function(){
            $(".preloader").show();
            var formElement = document.getElementById("CPVD_form_cmpnypropverfictn");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1)
                    {
                        $(".preloader").hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"COMPANY PROPERTY VERIFICATION",msgcontent:err_msg_array[0]}});
                        CPVD_rset()
                        showTable()
                    }
                    else
                    {
                        $(".preloader").hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"COMPANY PROPERTY VERIFICATION",msgcontent:err_msg_array[1]}});
                    }
                }
            }
            var choice="CMPNY_PROPETIES_SAVE"
            xmlhttp.open("POST","DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
        //CLICK EVENT FUCNTION FOR RESET
        $('#CPVD_btn_reset').click(function()
        {
            CPVD_rset()
        });
        //CLEAR ALL FIELDS
        function CPVD_rset()
        {
            $('#CPVD_lb_loginid').val('SELECT');
            $('#CPVD_lbl_reason').hide();
            $('#CPVD_ta_reason').hide();
            $('#CPVD_lbl_laptopno').hide();
            $('#CPVD_tb_laptopno').hide();
            $('#CPVD_lbl_chargerno').hide();
            $('#CPVD_tb_chargerno').hide();
            $('#CPVD_lbl_chckdby').hide();
            $('#CPVD_lb_chckdby').hide();
            $('#CPVD_lb_chckdby').val('SELECT');
            $('#CPVD_btns_sendreset').hide();
            $('#CPVD_ta_reason').prop("size","20");
            $('textarea').height(50).width(60);
        }
//READY FUNCTION END
    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>COMPANY PROPERTY VERIFICATION</h3><p></div></div>
    <form   id="CPVD_form_cmpnypropverfictn" class="content" >
        <table>
            <tr>
                <td><label name="CPVD_lbl_loginid" id="CPVD_lbl_loginid" hidden>LOGIN ID<em>*</em></label></td>
                <td><select name="CPVD_lb_loginid" id="CPVD_lb_loginid" hidden>
                        <option>SELECT</option>
                    </select></td>
            </tr>
            <td><div><label id="CPVD_lbl_nologinid" name="CPVD_lbl_nologinid" class="errormsg"></label></div></td>
            <tr>
                <td>
                    <label name="CPVD_lbl_laptopno" id="CPVD_lbl_laptopno" hidden>LAPTOP NUMBER</label></td>
                <td><input type="text" name="CPVD_tb_laptopno" id="CPVD_tb_laptopno" hidden maxlength='25' class="alphanumeric sizefix" disabled></td>
            </tr>
            <tr>
                <td>
                    <label name="CPVD_lbl_chargerno" id="CPVD_lbl_chargerno" hidden>CHARGER NUMBER</label></td>
                <td><input type="text" name="CPVD_tb_chargerno" id="CPVD_tb_chargerno" maxlength='25' class="alphanumeric sizefix" hidden disabled></td>
            </tr>
            <tr>
                <td><label name="CPVD_lbl_chckdby" id="CPVD_lbl_chckdby" hidden>CHECKED BY<em>*</em></label></td>
                <td><select name="CPVD_lb_chckdby" id="CPVD_lb_chckdby" hidden>
                        <option>SELECT</option>
                    </select></td>
            </tr>
            <tr>
                <td><label name="CPVD_lbl_reason" id="CPVD_lbl_reason" hidden>COMMENTS<em>*</em></label></td>
                <td><textarea rows="4" cols="50" name="CPVD_ta_reason" id="CPVD_ta_reason" hidden>
                    </textarea></td>
            </tr>
            <table id="CPVD_btns_sendreset"   hidden>
                <tr>
                    <td  align="right"><input class="btn" type="button"  id="CPVD_btn_send" name="SAVE" value="SEND" disabled hidden /></td>
                    <td align="left"><input type="button" class="btn" name="CPVD_btn_reset" id="CPVD_btn_reset" value="RESET"></td>
                </tr>
            </table>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->