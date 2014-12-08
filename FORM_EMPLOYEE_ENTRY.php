<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.02-SD:14/11/2014 ED:14/11/2014,TRACKER NO:74,Changed check bx name
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:93
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //GLOBAL DECLARATION
    var EMP_ENTRY_errorAarray=[];
    var EMP_ENTRY_loginid=[];
    var value_array=[];
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        $(".preloader").show();
        $('#EMP_ENTRY_lbl_validnumber').hide();
//CALLING INITIAL DATAS LOADED FUNCTION
        emp_listbx()
//FUNCTION FOR INITIAL LOADING DATAS LOGINID,ERR MSG
        function emp_listbx()
        {
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    value_array=JSON.parse(xmlhttp.responseText);
                    EMP_ENTRY_errorAarray=value_array[0];
                    EMP_ENTRY_loginid=value_array[1];
                    if(EMP_ENTRY_loginid!='')
                    {
                        var login_list='<option>SELECT</option>';
                        for (var i=0;i<EMP_ENTRY_loginid.length;i++) {
                            login_list += '<option value="' + EMP_ENTRY_loginid[i] + '">' + EMP_ENTRY_loginid[i] + '</option>';
                        }
                        $('#EMP_ENTRY_tb_loginid').html(login_list);
                        $('#EMP_ENTRY_lbl_loginid').show();
                        $('#EMP_ENTRY_tb_loginid').show();
                    }
                    else
                    {
                        $('#EMP_ENTRY_lbl_loginid').hide();
                        $('#EMP_ENTRY_lbl_nologinid').text(EMP_ENTRY_errorAarray[5]).show()
                        $('#EMP_ENTRY_tb_loginid').hide();
                    }
                    $(".title_alpha").prop("title",EMP_ENTRY_errorAarray[0]);
                    $(".title_nos").prop("title",EMP_ENTRY_errorAarray[1]);
                }
            }
            var option="INITIAL_DATAS";
            xmlhttp.open("GET","DB_EMPLOYEE_ENTRY.do?option="+option);
            xmlhttp.send();
        }
//DO VALIDATION START
        $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
        $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
        $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
        $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
//DO VALIDATION END
//SET DOB DATEPICKER
        var EMP_ENTRY_d = new Date();
        var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
        EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
        $('#EMP_ENTRY_tb_dob').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true,
                yearRange: '1920:' + EMP_ENTRY_year + '',
                defaultDate: EMP_ENTRY_d
            });
        var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
        $('#EMP_ENTRY_tb_dob').datepicker("option","maxDate",pass_changedmonth);
//END DATE PICKER FUNCTION
//CHANGE FUNCTION FOR LOGIN ID
        $(document).on('change','#EMP_ENTRY_tb_loginid',function(){
            $(".preloader").show();
            $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
            $('.sizefix').prop("size","20");
            $('#EMP_ENTRY_lbl_validnumber').hide();
            $('#EMP_ENTRY_lbl_validnumber1').hide();
            $('#EMP_ENTRY_tb_firstname').val('');
            $('#EMP_ENTRY_tb_lastname').val('');
            $('#EMP_ENTRY_tb_dob').val('');
            $('#EMP_ENTRY_tb_designation').val('');
            $('#EMP_ENTRY_tb_permobile').val('');
            $('#EMP_ENTRY_tb_kinname').val('');
            $('#EMP_ENTRY_tb_relationhd').val('');
            $('#EMP_ENTRY_tb_mobile').val('');
            $('#EMP_ENTRY_tb_laptopno').val('');
            $('#EMP_ENTRY_tb_chargerno').val('');
            $('input[name=EMP_ENTRY_chk_bag]').attr('checked',false);
            $('input[name=EMP_ENTRY_chk_mouse]').attr('checked',false);
            $('input[name=EMP_ENTRY_chk_dracess]').attr('checked',false);
            $('input[name=EMP_ENTRY_chk_idcrd]').attr('checked',false);
            $('input[name=EMP_ENTRY_chk_headset]').attr('checked',false);
            var EMP_ENTRY_login_id=$(this).val();
            if(EMP_ENTRY_login_id!="SELECT"){
                $(".preloader").hide();
                $("#EMP_ENTRY_table_employeetbl").show();
                $('#EMP_ENTRY_table_others').show();
                $('#EMP_ENTRY_btns').show();
            }
            else
            {
                $(".preloader").hide();
                $("#EMP_ENTRY_table_employeetbl").hide();
                $('#EMP_ENTRY_table_others').hide();
                $('#EMP_ENTRY_btns').hide();
            }
        });
//EMPLOYEE SAVE BUTTON VALIDATION
        $(document).on('change','#EMP_ENTRY_form_employeename',function(){
            var EMP_ENTRY_Firstname= $("#EMP_ENTRY_tb_firstname").val();
            var EMP_ENTRY_Lastname =$("#EMP_ENTRY_tb_lastname").val();
            var EMP_ENTRY_tb_dob=$('#EMP_ENTRY_tb_dob').val();
            var EMP_ENTRY_empdesig =$("#EMP_ENTRY_tb_designation").val();
            var EMP_ENTRY_Mobileno = $("#EMP_ENTRY_tb_permobile").val();
            var EMP_ENTRY_kinname = $("#EMP_ENTRY_tb_kinname").val();
            var EMP_ENTRY_relationhd = $("#EMP_ENTRY_tb_relationhd").val();
            var EMP_ENTRY_mobile= $("#EMP_ENTRY_tb_mobile").val();
            if((EMP_ENTRY_Firstname!='') && (EMP_ENTRY_Lastname!='' ) && (EMP_ENTRY_tb_dob!='' ) && (EMP_ENTRY_empdesig!='' )&&( EMP_ENTRY_Mobileno!='' && (parseInt($('#EMP_ENTRY_tb_permobile').val())!=0)) && (EMP_ENTRY_kinname!='')&& (EMP_ENTRY_relationhd!='' )&& (EMP_ENTRY_Mobileno.length>=10)&&(EMP_ENTRY_mobile.length>=10 ))
            {
                $("#EMP_ENTRY_btn_save").removeAttr("disabled");
            }
            else
            {
                $("#EMP_ENTRY_btn_save").attr("disabled","disabled");
            }
        });
//BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
        $(document).on('blur','.valid',function(){
            var EMP_ENTRY_Mobileno=$(this).attr("id");
            var EMP_ENTRY_Mobilenoval=$(this).val();
            if(EMP_ENTRY_Mobilenoval.length==10)
            {
                if(EMP_ENTRY_Mobileno=='EMP_ENTRY_tb_permobile')
                    $('#EMP_ENTRY_lbl_validnumber').hide();
                else
                    $('#EMP_ENTRY_lbl_validnumber1').hide();
            }
            else
            {
                if(EMP_ENTRY_Mobileno=='EMP_ENTRY_tb_permobile')
                    $('#EMP_ENTRY_lbl_validnumber').text(EMP_ENTRY_errorAarray[3]).show();
                else
                    $('#EMP_ENTRY_lbl_validnumber1').text(EMP_ENTRY_errorAarray[3]).show();
            }
        });
//CLICK EVENT FOR SAVE BUTTON
        $(document).on('click','#EMP_ENTRY_btn_save',function(){
            $(".preloader").show();
            var formElement = document.getElementById("EMP_ENTRY_form_employeename");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1)
                    {
                        $(".preloader").hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE DETAIL ENTRY",msgcontent:EMP_ENTRY_errorAarray[2]}});
                        EMP_ENTRY_employeedetailrset()
                        emp_listbx()
                    }
                    else
                    {
                        $(".preloader").hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE DETAIL ENTRY",msgcontent:EMP_ENTRY_errorAarray[4]}});

                    }
                }
            }
            var choice="EMPLOYDETAILS_SAVE"
            xmlhttp.open("POST","DB_EMPLOYEE_ENTRY.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
//CLICK EVENT FUCNTION FOR RESET
        $('#EMP_ENTRY_btn_reset').click(function()
        {
            EMP_ENTRY_employeedetailrset()
        });
//CLEAR ALL FIELDS
        function EMP_ENTRY_employeedetailrset()
        {
            $("#EMP_ENTRY_form_employeename")[0].reset();
            $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
            $('.sizefix').prop("size","20");
            $("#EMP_ENTRY_table_employeetbl").hide();
            $("#EMP_ENTRY_table_others").hide();
            $("#EMP_ENTRY_btns").hide();
            $('#EMP_ENTRY_lbl_validnumber').hide();
            $('#EMP_ENTRY_lbl_validnumber1').hide();
        }
    });
    //END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>EMPLOYEE DETAIL ENTRY</h3><p></div></div>
    <form  name="EMP_ENTRY_form_employeename" id="EMP_ENTRY_form_employeename" class="content" >
        <tr>
            <td><label name="EMP_ENTRY_lbl_loginid" id="EMP_ENTRY_lbl_loginid" hidden>LOGIN ID<em>*</em></label></td>
            <td><select name="EMP_ENTRY_tb_loginid" id="EMP_ENTRY_tb_loginid" hidden>
                </select></td>
            <td><div><label id="EMP_ENTRY_lbl_nologinid" name="EMP_ENTRY_lbl_nologinid" class="errormsg"></label></div></td>
        </tr>
        <table id="EMP_ENTRY_table_employeetbl" hidden>
            <tr>
                <td><label class="srctitle"  name="EMP_ENTRY_lbl_personnaldtls" id="EMP_ENTRY_lbl_personnaldtls">PERSONNAL DETAILS</label></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_firstname" id="EMP_ENTRY_lbl_firstname">FIRST NAME <em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_firstname" id="EMP_ENTRY_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha" ></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_lastname" id="EMP_ENTRY_lbl_lastname">LAST NAME <em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_lastname" id="EMP_ENTRY_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_dob" id="EMP_ENTRY_lbl_dob">DATE OF BIRTH<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_dob" id="EMP_ENTRY_tb_dob" class="datepickerdob datemandtry" style="width:75px;"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_designation" id="EMP_ENTRY_lbl_designation">DESIGNATION<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_designation" id="EMP_ENTRY_tb_designation" maxlength='50' class="alphanumericuppercse sizefix"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_permobile" id="EMP_ENTRY_lbl_permobile">PERSONAL MOBILE<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_permobile" id="EMP_ENTRY_tb_permobile"  maxlength='10' class="mobileno title_nos valid" style="width:75px" ></td>
                <td><div><label id="EMP_ENTRY_lbl_validnumber" name="EMP_ENTRY_lbl_validnumber" class="errormsg"></label></div></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_kinname" id="EMP_ENTRY_lbl_kinname">NEXT KIN NAME<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_kinname" id="EMP_ENTRY_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_relationhd" id="EMP_ENTRY_lbl_relationhd">RELATION HOOD<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_relationhd" id="EMP_ENTRY_tb_relationhd" maxlength='30' class="autosizealph sizefix title_alpha" ></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_mobile" id="EMP_ENTRY_lbl_mobile">MOBILE NO<em>*</em></label></td>
                <td><input type="text" name="EMP_ENTRY_tb_mobile" id="EMP_ENTRY_tb_mobile" class="mobileno title_nos valid" maxlength='10' style="width:75px"></td>
                <td><div><label id="EMP_ENTRY_lbl_validnumber1" name="EMP_ENTRY_lbl_validnumber1" class="errormsg"></label></div></td>
            </tr>
            <tr>
                <td><label class="srctitle"  name="EMP_ENTRY_lbl_others" id="EMP_ENTRY_lbl_others">OTHERS</label></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_laptopno" id="EMP_ENTRY_lbl_laptopno">LAPTOP NUMBER</label></td>
                <td><input type="text" name="EMP_ENTRY_tb_laptopno" id="EMP_ENTRY_tb_laptopno" maxlength='25' class="alphanumeric sizefix"></td>
            </tr>
            <tr>
                <td>
                    <label name="EMP_ENTRY_lbl_laptopno" id="EMP_ENTRY_lbl_laptopno">CHARGER NO</label></td>
                <td><input type="text" name="EMP_ENTRY_tb_chargerno" id="EMP_ENTRY_tb_chargerno" maxlength='70' class="alphanumeric sizefix"></td>
            </tr><tr><td></td><td>
                    <table id="EMP_ENTRY_table_others" style="width:200px" hidden>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMP_ENTRY_chk_bag" id="EMP_ENTRY_chk_bag" class="">
                                <label name="EMP_ENTRY_lbl_laptopbag" id="EMP_ENTRY_lbl_laptopbag">LAPTOP BAG</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMP_ENTRY_chk_mouse" id="EMP_ENTRY_chk_mouse" class="">
                                <label name="EMP_ENTRY_lbl_laptopno" id="EMP_ENTRY_lbl_laptopno">MOUSE</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMP_ENTRY_chk_dracess" id="EMP_ENTRY_chk_dracess"  class="">
                                <label name="EMP_ENTRY_lbl_dracess" id="EMP_ENTRY_lbl_dracess">DOOR ACCESS</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMP_ENTRY_chk_idcrd" id="EMP_ENTRY_chk_idcrd" class="">
                                <label name="EMP_ENTRY_lbl_idcrd" id="EMP_ENTRY_lbl_idcrd">ID CARD</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="EMP_ENTRY_chk_headset" id="EMP_ENTRY_chk_headset" class="">
                                <label name="EMP_ENTRY_lbl_headset" id="EMP_ENTRY_lbl_headset">HEAD SET</label></td>
                        </tr>
                    </table></td></tr>
            <table id="EMP_ENTRY_btns" style="width:400px"  hidden>
                <tr>
                    <td  align="right"><input class="btn" type="button"  id="EMP_ENTRY_btn_save" name="SAVE" value="SAVE" disabled hidden /></td>
                    <td align="left"><input type="button" class="btn" name="EMP_ENTRY_btn_reset" id="EMP_ENTRY_btn_reset" value="RESET"></td>
                </tr>
            </table>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
