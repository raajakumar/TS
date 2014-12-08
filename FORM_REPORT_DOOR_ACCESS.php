<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************DOOR ACCESS DETAILS*************************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION,SD:04/11/2014 ED:04/11/2014,TRACKER NO:97
//*********************************************************************************************************//
<?PHP
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        $(".preloader").show();
        var values_arraystotal=[];
        var values_array=[];
        table();
        //FUNCTION FOR SHOWING DATA TABLE OF DOOR ACCESS RECORDS
        function table(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    values_arraystotal=JSON.parse(xmlhttp.responseText);
                    values_array=values_arraystotal[0];
                    var DR_ACC_errorAarray=values_arraystotal[1];
                    if(values_array.length!=0)
                    {
                        var DR_ACC_table_header='<table id="DR_ACC_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:500px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>LOGIN ID</th><th style="width:30px">DOOR ACCESS</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var loginid=values_array[j].loginid;
                            var DR_ACC_draccess=values_array[j].DR_ACC_draccess;
                            if((DR_ACC_draccess=='null')||(DR_ACC_draccess==undefined))
                            {
                                DR_ACC_draccess='';
                            }
                            DR_ACC_table_header+='<tr><td>'+loginid+'</td><td  style="width:30px" align="center">'+DR_ACC_draccess+'</td></tr>';
                        }
                        DR_ACC_table_header+='</tbody></table>';
                        $('section').html(DR_ACC_table_header);
                        $('#DR_ACC_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType":"full_numbers",
                            tableTools: {"aButtons": [
                                "pdf"],
                                "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                            }
                        });
                    }
                    else
                    {
                        $('#DR_ACC_lbl_norole_err').text(DR_ACC_errorAarray).show();
                    }
                }
            }
            $('#tablecontainer').show();
            xmlhttp.open("POST","DB_REPORT_DOOR_ACCESS.do",true);
            xmlhttp.send();
        }
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body class="dt-example">
<div class="wrapper">
    <div class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"/></div></div></div>
    <div class="title" id="fhead"><div style="padding-left:500px; text-align:left;"><p><h3>DOOR ACCESS DETAILS</h3><p></div></div>
    <form class="content" name="DR_ACC_form_user" id="DR_ACC_form_user" autocomplete="off" >
        <div class="container">
            <div class="container" id="tablecontainer" style="width:500px;" hidden>
                <section style="width:500px;">
                </section>
            </div>
        </div>
        <div><label id="DR_ACC_lbl_norole_err" name="DR_ACC_lbl_norole_err" class="errormsg"></label></div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->