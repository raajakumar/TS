<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************USER SEARCH DETAILS*************************************************//
//DONE BY:LALITHA
//VER 0.02 SD:31/10/2014 ED:31/10/2014,TRACKER NO:79,Updated date sorting,Changed resize the width,Changed header name,Changed resize the width
//VER 0.01-INITIAL VERSION,SD:11/10/2014 ED:11/10/2014,TRACKER NO:79
//*********************************************************************************************************//
<?PHP
include "HEADER.php";
?>
<!--HTML TAG START-->

<!--HEAD TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        $(".preloader").show();
        var values_arraystotal=[];
        var values_array=[];
        table();
        //FUNCTION FOR FORM TABLE DATE FORMAT
        function FormTableDateFormat(inputdate){
            var string = inputdate.split("-");
            return string[2]+'-'+ string[1]+'-'+string[0];
        }
        function table(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    values_arraystotal=JSON.parse(xmlhttp.responseText);
                    values_array=values_arraystotal[0];
                    var USD_SRC_errorAarray=values_arraystotal[1];
                    if(values_array.length!=0)
                    {
                        var USU_table_header='<table id="USD_SRC_SRC_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:900px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>LOGIN ID</th><th>ROLE</th><th>REC VER</th><th style="width:10px;"  class="uk-date-column">JOIN DATE</th><th style="width:50px;" class="uk-date-column">TERMINATION DATE</th><th style="min-width:750px;">REASON OF TERMINATION</th><th style="width:70px">EMP TYPE</th><th>USERSTAMP</th><th style="width:150px;" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var USD_SRC_loginid=values_array[j].loginid;
                            var USD_SRC_rcid=values_array[j].rcid;
                            var USD_SRC_recordver=values_array[j].recordver;
                            var USD_SRC_joindate=values_array[j].joindate;
                            var USD_SRC_terminationdate=values_array[j].terminationdate;
                            if((USD_SRC_terminationdate=='null')||(USD_SRC_terminationdate==undefined))
                            {
                                USD_SRC_terminationdate='';
                            }
                            var USD_SRC_reasonoftermination=values_array[j].reasonoftermination;
                            if((USD_SRC_reasonoftermination=='null')||(USD_SRC_reasonoftermination==undefined))
                            {
                                USD_SRC_reasonoftermination='';
                            }
                            var USD_SRC_emptype=values_array[j].emptypes;
                            if((USD_SRC_emptype=='null')||(USD_SRC_emptype==undefined))
                            {
                                USD_SRC_emptype='';
                            }
                            var USD_SRC_userstamp=values_array[j].userstamp;
                            var USD_SRC_timestamp=values_array[j].timestamp;
                            USU_table_header+='<tr><td>'+USD_SRC_loginid+'</td><td>'+USD_SRC_rcid+'</td><td>'+USD_SRC_recordver+'</td><td nowrap>'+USD_SRC_joindate+'</td><td style="width:10px;" align="center">'+USD_SRC_terminationdate+'</td><td style="width:850px;">'+USD_SRC_reasonoftermination+'</td><td style="width:70px;">'+USD_SRC_emptype+'</td><td>'+USD_SRC_userstamp+'</td><td  style="min-width:150px;">'+USD_SRC_timestamp+'</td></tr>';
                        }
                        USU_table_header+='</tbody></table>';
                        $('section').html(USU_table_header);
                        $('#USD_SRC_SRC_tble_htmltable').DataTable( {
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
                        $('#URSRC_lbl_norole_err').text(USD_SRC_errorAarray).show();
                    }
                }
            }
            $('#tablecontainer').show();
            xmlhttp.open("POST","DB_ACCESS_RIGHTS_USER_SEARCH_DETAIL.do",true);
            xmlhttp.send();
        }
        //FUNCTION FOR SORTING
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
    <div class="title" id="fhead"><div style="padding-left:500px; text-align:left;"><p><h3>USER SEARCH DETAILS </h3><p></div></div>
    <form class="content" name="USD_SRC_SRC_form_user" id="USD_SRC_SRC_form_user" autocomplete="off" >
        <div class="container">
            <div class="container" id="tablecontainer" style="width:900px;" hidden>
                <section style="width:900px;">
                </section>
            </div>
        </div>
        <div><label id="URSRC_lbl_norole_err" name="URSRC_lbl_norole_err" class="errormsg"></label></div>
    </form>
</div>

</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->