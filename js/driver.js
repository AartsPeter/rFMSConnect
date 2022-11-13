
var Global_Check={};
function Convert2Address($array){
    $.ajax({
        url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json?prox='+$array[0]+','+$array[1]+'&mode=retrieveAddresses&gen=9&language=en&maxresults=1&app_id=Ch87ydlyXjsmnI5AcXho&app_code=z2HsXPK48vhNPwyfcFr5ew',
        type: 'GET',
        dataType: 'json',
        success: function (data) { return '<i class="fas fa-map-marked-alt fa-fw tooltipicon"></i><i class="fas fa-fw"></i> ' + data.Response.View[0].Result[0].Location.Address.Label; }
    });
}

function SelectVehicle(){
    var $HInfo = $('#selectedVehicle');$HInfo.html('');
    var $HFInfo = $('#selectedVehicleFooter');$HFInfo.html('');
    $HInfo.append('<table class="display table noWrap border-bottom" id="tablePDCSelectVehicle"></table>');
//    $HFInfo.append('<div class="row ">' +
//        '        <div class="col-12">' +
//        '            <button class="btn btn-primary " data-toggle="modal" data-target="#NewVehicleModal">ADD other vehicle </button>' +
//        '        </div>' +
//        '    </div>');
    var table=$('#tablePDCSelectVehicle').DataTable( {
        ajax: {	url: window.location.origin+'/scripts/GetLastVehicles.php',dataSrc: ''	},"bInfo" : false,
        autoWidth: false,destroy:true,dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": true,"lengthMenu": [[5, -1], [5, "All"]],
        ordering: false, footer:false,scrollCollapse: true,paging: true,
        drawCallback: function( settings ) { $("#tablePDCSelectVehicle thead").addClass('hide'); } ,
        buttons: {buttons: []},
        columns: [
            { data: "customerVehicleName",  "title":"",	"className":"dt-left"},
            { data: "brand",                "title":"",	"className":"dt-left "},
            { data: "model",                "title":"",	"className":"dt-left "},
            { data: "LicensePlate",		    "title":"",	"className":"dt-right d-none d-md-block"},
        ]
    } );
    $('#tablePDCSelectVehicle').off('click');
    $('#tablePDCSelectVehicle').on('click', 'tr', function () {
        var data = table.row( this ).data();
        $HInfo = $('#selectedVehicle');$HInfo.html(''); var $HFInfo = $('#selectedVehicleFooter');$HFInfo.html('');
        $HInfo.append('<button class="btn btn-primary col-12" onclick="SelectVehicle();"><div class="d-flex"><div class="p-0 "><B>'+data['customerVehicleName']+'</B></div><div class="ml-auto"><i class="fad fa-edit "></i></div></div></button>');
        Global_Check['vehicle']=data['vin']; Global_Check['vehicleName']=data['customerVehicleName'];
        Global_Check['vehicle_odometer']=data['odometer'];
    });
}
function SelectTrailer(){
    var $HInfo = $('#selectedTrailer');$HInfo.html('');
    var $HFInfo = $('#selectedTrailerFooter');$HFInfo.html('');
        $HInfo.append('<table class="display table noWrap border" id="tablePDCSelectTrailer"></table>');
        $HFInfo.append(' <div class="row">' +
        '        <div class="col-12">' +
        '            <button class="btn btn-primary " data-toggle="modal" data-target="#NewTrailerModal">ADD other trailer </button>' +
        '            <button class="btn btn-secondary ml-2" data-toggle="modal" onclick="SelectNoTrailer();" data-target="#">SKIP trailer </button>' +
        '        </div>' +
        '    </div>');
    var table=$('#tablePDCSelectTrailer').DataTable( {
        ajax: {	url: window.location.origin+'/scripts/GetLastTrailer.php',dataSrc: ''	},"bInfo" : false,
        autoWidth: false,destroy:true,dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": true,"lengthMenu": [[5, -1], [5, "All"]],
        ordering: false, footer:false,scrollCollapse: true,paging: true,
        drawCallback: function( settings ) { $("#tablePDCSelectTrailer thead").addClass('hide'); } ,
        buttons: {buttons: []},
        columns: [
            { data: "trailerName",  "title":"", "className":"dt-left"},
            { data: "LicensePlate",	"title":"",	"className":"dt-left"},
            { data: "lastTrip",		"title":"",	"className":"dt-left"},
        ]
    } );
    $('#tablePDCSelectTrailer').off('click');
    $('#tablePDCSelectTrailer').on('click', 'tr', function () {
        var data = table.row( this ).data();
        $HInfo.html('');
        $HInfo.append('<button class="btn btn-primary col-12" onclick="SelectTrailer();"><div class="d-flex"><div class="p-0 "><B>'+data['trailerName']+'</B></div><div class="ml-auto"><i class="fad fa-edit "></i></div></div></button>')
        Global_Check['trailer']=data['vin'];
        Global_Check['trailerName']=data['trailerName'];
    });
}
function SelectNoTrailer(Search){
    var $HInfo = $('#selectedTrailer');$HInfo.html(''); var $HFInfo = $('#selectedTrailerFooter');$HFInfo.html('');
    $HInfo.html('');$HInfo.append('<button class="btn btn-info col-12" onclick="SelectTrailer();"><div class="d-flex"><div class="p-0 "><B>No Trailer selected</B></div><div class="ml-auto"><i class="fad fa-edit "></i></div></div></button>')

}
function ReadDriverStatus($a,$b){
    ShowPlaceholder($a,'single');
    ShowPlaceholder($b,'single');

    $.ajax({
        url: window.location.origin+'/scripts/GetDriverDashboard.php',
        type:'GET',
        dataType:'json',
        success:function(data){
            if (data.length!=0){
                Global_Check['vehicle']=data['LastVehicle'];
                Global_Check['vehicleName']=data['LastVehicleName'];
                Global_Check['vehicle_odometer']=data['odometer'];
                Global_Check['trailer']=data['TrailerVin'];
                Global_Check['trailerName']=data['TrailerName'];
                var $VInfo = $('#'+$a);$VInfo.html('');
                var $TInfo = $('#'+$b);$TInfo.html('');
                if (data['LastVehicleName']==''){
                    SelectVehicle();
                }
                else {
                    $VInfo.append('<button class="btn btn-secondary col-12" onclick="SelectVehicle();"><div class="d-flex"><div class="p-0 "><B>' + data['LastVehicleName'] + '</B></div><div class=" ml-auto"><i class="fad fa-edit "></i></div></div></button>')
                }
                $TInfo.append('<button class="btn btn-secondary col-12" onclick="SelectTrailer();"><div class="d-flex"><div class="p-0 "><B>' + data['TrailerName'] + '</B></div><div class="ml-auto"><i class="fad fa-edit "></i></div></div></button>')

            }
        },
        error:function(data){
        }
    });
}


function ReadDriverDamageStatus(s,$lastVehicle){
    var $LInfo = $('#'+s);
    var $str='';
    ShowSpinnerSmall(s);
    var localDate = new Date();
    $.ajax({
        url: window.location.origin+'/scripts/GetDriverDashboardDamages.php',
        type:'GET',
        dataType:'json',
        success:function(data){
            $LInfo.html('');
            $.each(data, function(key, val){
                if (val.Damages==null){val.Damages=0;}
                if (val.trailer==null){val.trailer=0;}
                if (val.customerVehicleName==null){val.customerVehicleName='</b><span class="grey">Please select vehicle</span>';}
                else {
                    $str += '<div class="col-12 d-flex text-grey">';
                    $str +='<div class="mr-auto"><i class="fas fa-check rest"></i>  <b>' + val.Checks + '</b></div>';

                    if (val.trailer != 0) {
                        $str += '<div class="m-auto "><i class="fad fa-truck fa-flip-horizontal"></i> <b>' + val.customerVehicleName + '</b> ';
                        $str += '<i class="fas fa-trailer fa-flip-horizontal"></i>  <b>' + val.trailerName + '</b></div>';
                    } else {
                        $str += '<div class="m-auto"><i class="fas fa-truck "></i>  <b>' + val.customerVehicleName + '</b></div>';
                    }
                    $str += '	<div class="ml-auto text-right"><i class="fad fa-tools text-danger"></i> <b>' + val.Damages + '</b></div>';
                    $str += '</div><div class="dropdown-divider"></div>';
                }
            })
            $str+='	<div class="row px-3 py-2 ">';
            $str+='		<a href="pages/pdc_new.php" id="StartCheck" class="btn btn-lg btn-primary mb-3 col-7 mr-1 "><i class="fad fa-list fa-fw"></i><b> Start Check</b></a>';
//            $str+='		<button class="btn btn-secondary btn-lg col-5 gray" "><b>Damage </b><i class="fad fa-wrench "></i></button>';
            $str+='		<a href="pages/pdc_new.php?id=99" class="btn btn-danger btn-lg ml-auto mb-3 col-4 ml-1" "><b>Accident </b><i class="fad fa-car-crash"></i></a>';
            $str+='	</div>';
            $LInfo.append($str);
        },
        error:function(){$LInfo.html('');$LInfo.append('No Driver information available');}
    });
}

function StartPDC($item){
    var $HInfo = $('#pdc_details');$HInfo.html('');var $str='';
    openpdcDet();
    $str+='<div class="card border shadow h-100">';
    $str+=' <div class="card-header ">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a  onclick="closepdcDet();"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto">Perform Checks - <span id="pdc_cc"></span></div>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="card-body pdc-pagina">';
    $str+='     <div class="row" >';
    var $count=0;var $ccount=0;
    $.each(Global_Check['categories'], function (key, val) {
        $str+='         <div class="col-12" id="pdccatcard_' + $count + '">';
        if (val.checked) {
            $str+=MarkCatChecked($count,0);
            $ccount++;
        } else{
            if (val.damages==0){  $str+=MarkCatNotChecked($count,0);}
            else {
                $str+=MarkCatDamage($count,0);
                $ccount++;
            }
        }
        $str+='         </div>';
        $count++;
    });
    $str+=' </div>';
    $str+=' </div>';
    $str+=' <div class="card-footer" id="PDCFinaliseButton"></div>';
    $str+='</div>';
    $HInfo.append($str);
    CheckComplete();
}
function CheckComplete(){
    var $count=0;var $ccount=0;
    $.each(Global_Check['categories'], function (key, val) {
        if (val.checked) { $ccount++;}
        else{ if (val.damages != 0){ $ccount++; } }
        $count++;
    });
    var $HI = '  <span class="badge badge-success">'+$ccount+' </span> | <span class="badge badge-secondary"></b>'+$count+'</span>  ';
    $('#pdc_cc').html($HI);
    if ($ccount==$count) {
        var $str = '<button class="btn btn-lg btn-primary col-12 px-0" onclick="FinalisePDC();"> Finalise Check  </button>';
    } else {
        var $str = '<button class="btn btn-lg btn-light col-12" disabled"> to finalise, perform all checks  </button>';
    }
    $('#PDCFinaliseButton').html($str);
}
function showCheckItems($search){
    var $HInfo = $('#pdc_details2');$HInfo.html('');var $str='';
    $str+='<div class="card border border h-100 pdc-pagina">';
    $str+=' <div class="card-header">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a  onclick="closepdcDet2();"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto">'+Global_Check['categories'][$search]['cat_name']+'</div><span class="small" id="pdc_cc"><span>' ;
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="card-body ">';
    $str+=' <div class="card shadow">';
    $str+='     <div class="col-12" >';
    $str+='         <div class="page-title" >What to check?</div><ul class="px-3">';
    $.each(Global_Check['categories'][$search]['checkitems'], function (key, val) {
        $str+='	    	   <a href="#good"><li>';
        $str+='                 <div class="py-1">'+val.check+'</i></div>';
        $str+='             </li></a>';
    });
    $str+='        </ul></div>';
    if (Global_Check['categories'][$search]['damages']==0) {
        $str += ' <div class="d-flex py-4 " id="good">';
        $str += '     <div class="col-12 m-auto"><button class="col-12 p-2 info-box-number btn btn-success"  onclick="MarkCatChecked(`' + $search + '`,`1`);closepdcDet2();"> All Good  <i class="fas fa-check "></i> </button> </div>';
        $str += ' </div>';
    }
    $str+='     </div>';
    $str+='     </div>';
    $str+=' </div>';
    $str += ' </div>';
    $HInfo.append($str);
    openpdcDet2();
}
function showSubCategories($search){
    var $HInfo = $('#pdc_details2');$HInfo.html('');
	var $str = '';var $SCC = 0;
	$str+='<div class="card border shadow h-100">';
    $str+=' <div class="card-header">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a  onclick="closepdcDet2();"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto">'+Global_Check['categories'][$search]['cat_name']+'</div><span class="small" id="pdc_cc"><span>';
    $str+='     </div>';
    $str+='</div>';
    $str+='     <div class="card-body pdc-pagina ">';
	$str+='         <div class="col-12 p-0">';
    $.each(Global_Check['categories'][$search]['subcategories'], function (key, val) {
        $str+='             <a onclick="ShowSubCatDamages(`'+$search+'`,`'+$SCC+'`);">';
        $str+='	    	    <div class="card  pdc-card mb-3">';
        $str+='	    	        <div class="bold d-flex p-2 px-3 mb-0">';
        $str+='	     	            <div class=" "><i class="fad fa-fw  fa-exclamation-triangle text-danger"></i></div>';
        $str+='                     <div class="text-grey px-2">'+val.subcat_name+'</div>';
        $str+='                     <div class="ml-auto px-2"><i class="fas fa-chevron-right"></i></div>';
        $str+='                 </div>';
        $str+='             </div></a>';
        $SCC++;
    });
    $str+='         </div>';
    $str+='     </div>';
    $str+=' </div>';
    $HInfo.append($str);
    openpdcDet2();
}
function MarkCatChecked($search,$build){
    var $str='';
	closepdcDet2();
    $str+=' 	<div class="card  shadow-sm mb-3" >';
    $str+='         <a class="text-grey" onclick="MarkCatNotChecked(`'+$search+'`,`1`);">';
    $str+='	    	<div class="card-title border-0 mb-2 d-flex">';
    $str+='            <div class="subtitle">'+ Global_Check['categories'][$search].cat_name + '</div>';
    $str+='            <div class="subtitle ml-auto"><i class="fad fa-edit"></i></div>';
    $str+='         </div></a>';
    $str+='     </div>';
    Global_Check['categories'][$search]['checked']=true;
    if ($build!=0){
        CheckComplete();
        $('#pdccatcard_'+parseInt($search)).html($str);
        var $anchor = "pdccatcard_" + (parseInt($search) + 1);
        scrollSmoothToBottom($anchor);
    }
    else {  return $str;}
}
function MarkCatNotChecked($search,$build){
    var $str='';$val=Global_Check['categories'][$search];
    $str+=' 	<div class="card shadow-sm mb-3">';
    $str+='          <a class="textgrey" onClick="openpdcDet2();showCheckItems(`'+$search+'`);">';
    $str+='	    	<div class="card-title d-flex">';
    $str+='            <div class="subtitle">'+ Global_Check['categories'][$search].cat_name + '</div>';
    $str+='            <div class="ml-auto"><i class="fas fa-chevron-right"></i></div>';
    $str+='         </div></a>';
    $str+='	    	<div class="card-body p-2">' ;
    $str+='             <div class="d-flex">';
    $str+='                 <div class="pl-2 pt-1"><i class="'+Global_Check['categories'][$search]['cat_icon']+' fa-fw  fa-2x text-grey"></i></div>';
    $str+='                 <div class="ml-auto">   <button class="btn-round btn-outline-danger " onclick="openpdcDet2();ShowCatDamage(`'+$search+'`);"><i class="fas fa-tools fa-fw"></i>   </button> </div>';
    $str+='                 <div class="mx-2">      <button class="btn-round btn-outline-success MarkCatChecked" onclick="MarkCatChecked(`'+$search+'`,`1`);">  <i class="fas fa-check fa-fw"></i> </button> </div>';
    $str+='             </div>';
    $str+='         </div>';
    $str+='     </div>';
    Global_Check['categories'][$search]['checked']=false;
    if ($build!=0){
        CheckComplete();
        var $HInfo = $('#pdccatcard_'+parseInt($search));$HInfo.html($str);
        var $anchor = "pdccatcard_" + (parseInt($search) - 1);
        scrollSmoothToBottom($anchor);}
    else {  return $str;}
}
function MarkCatDamage($search){
    var $str='';
    $str+=' 	<div class="card shadow-sm mb-3" >';
    $str+='	    	<div class="card-title d-flex">';
    $str+='            <div class="subtitle">'+ Global_Check['categories'][$search].cat_name + '</div>';
    $str+='            <div class="ml-auto"><a class="textgrey" onclick="openpdcDet2();showCheckItems(`'+$search+'`);;"><i class="fas fa-chevron-right"></i></a></div>';
    $str+='         </div>';
    $str+='	    	<div class="card-body p-2">' ;
    if (Global_Check['categories'][$search]['subcategories']==undefined) {
        $dc=0;
        $.each(Global_Check['categories'][$search]['damageitems'], function (key, val) {
            if (val.registered) {
                $str += '             <div class="d-flex px-2 ">';
                $str += '                 <a class="text-danger" onclick="ShowCatDamage(`' + $search + '`);"><i class="fas fa-fw fa-exclamation-triangle danger"></i> ' + val.title + '</a> ';
                $str += '                 <div class="ml-auto"> <a class="text-danger" onclick="UnMarkDamage(`'+$search+'`,``,`'+$dc+'`);StartPDC();"><i class="fad fa-fw fa-trash-alt text-danger"></i></a> </div>';
                $str += '             </div>';
            }
            $dc++;
        })
    }
    else {
        $sc=0;
        $.each(Global_Check['categories'][$search]['subcategories'], function (key, sval) {
            $dc=0;
            $.each(sval.damageitems, function (key, dval) {
                if (dval.registered) {
                    $str += '             <div class="d-flex px-2">';
                    $str += '                 <a class="text-danger" onclick="ShowCatDamage(`' + $search + '`);"><i class="fas fa-fw fa-exclamation-triangle danger"></i> '+dval.title+'</a> ';
                    $str += '                 <div class="ml-auto"> <a class="text-danger" onclick="UnMarkDamage(`'+$search+'`,`'+$sc+'`,`'+$dc+'`);StartPDC();"><i class="fad fa-fw fa-trash-alt text-danger"></i></a> </div>';
                    $str += '             </div>';
                }
                $dc++;
            })
            $sc++;
        })
    }
    $str+='         </div>';
    $str+='	    	<div class="p-2">' ;
    $str+='             <div class="d-flex">';
    $str+='                 <div class="ml-auto">';
    $str+='                      <button class="btn text-danger" onclick="openpdcDet2();ShowCatDamage(`'+$search+'`);"><b>Add Damages </b></button>';
    $str+='                 </div>';
    $str+='             </div>';
    $str+='         </div>';
    $str+='     </div>';
    return $str;
}

function imageIsLoaded(e) {
    $('img').attr('src', e.target.result);
    $('img').fadeIn();
}

function RegisterFormDamages($search,$ssearch,$dsearch){
    var $HInfo = $('#pdc_details2');$HInfo.html('');var $str='';
    $str+='<div class="card border shadow h-100">';
    $str+=' <div class="card-header">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a onclick="closepdcDet2();"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto">'+Global_Check['categories'][$search]['cat_name']+'</div>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="card-body pdc-pagina">';
    if ($ssearch!='') {
        var val=Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch];
    } else {
        var val=Global_Check['categories'][$search]['damageitems'][$dsearch];
    }
    $str+='     <div class="d-flex">';
    $str+='         <div class="col-1"><i class="fad fa-fw fa-exclamation-triangle text-danger"></i></div>';
    $str+='         <div class="col-10">'+val.title+'</div>';
    $str+='         <div class="col-1"> <a  href="" class="text-danger" onclick="ShowCatDamage(`'+$search+'`);"><i class="fad fa-check-square fa-fw text-danger"></i></a> </div>';
    $str+='     </div>';
    $str+='     <div class="d-flex pt-3">';
    $str+='                 <div class="col-12"><b>'+val.event_description+'</b></div>';
    $str+='     </div>';
    $str+='     <div class="row pt-3">';
    $str+='                 <form class="col-12 form-signup" action="" method="POST" id="add_damage" enctype="multipart/form-data"> ';
    $str+='                     <div class="col-12">';
    $str+='                         <label>Damage comments/description</label>';
    $str+='                         <input class="form-control" type="textarea" id="damage_comments" value="'+val.comments+'" name="damage_description">';
    $str+='                     </div>';
    $str+='                     <div class="col-12 pt-3">';
    $str+='                         <label>Select photos evidence of damage max(3)</label>';
    if (val.evidence_picture==''){
        $str+='                         <input class="col-12 p-0" type="file" id="damage_evidence" multiple="multiple" name="damage_evidence" accept="image/jpeg, image/png, image/jpg" value="'+val.evidence_picture+'">';
        $str+='                         <div class="row col-12" id="display_image"></div>';
    } else {
        $str+='                          <div class="form-control-ro"><img class="border shadow-sm" src="'+val.evidence_picture+'" height="60px" alt="'+val.evidence_picture+'"></div>';
    }
    $str+='                     </div>';
    $str+='                     <div class="col-12 pt-5 m-auto">';
    $str+='                         <button class="col-12 text-white btn btn-lg btn-danger m-auto" onclick="RegisterDamage(`'+$search+'`,`'+$ssearch+'`,`'+$dsearch+'`);closepdcDet2();">Save Damage/Failure</button>';
    $str+='                     </div>';
    $str+='                 </form>';
    $str+='    </div>';
    $str+=' </div>';
    $str+='</div>';
    $HInfo.append($str);
    const image_input = document.querySelector("#damage_evidence").addEventListener("change", (e) => {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            const files = e.target.files;
            const output = document.querySelector("#display_image");
            output.innerHTML = "";
            for (let i = 0; i < files.length; i++) {
                if (!files[i].type.match("image")) continue;
                const picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    const picFile = event.target;
                    const div = document.createElement("div");
                    div.innerHTML = `<img class="thumbnail m-2 shadow" src="${picFile.result}" height ="60px" title="${picFile.name}"/>`;
                    output.appendChild(div);
                });
                picReader.readAsDataURL(files[i]);
                val.evidence_picture=picReader;
            }
        } else {
            alert("Your browser does not support File API");
        }
    });
}

function RegisterDamage($search,$ssearch,$dsearch){
    if ($ssearch!='') {
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['registered']=true;
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['comments']=document.getElementById("damage_comments").value;
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['evidence_picture']=document.getElementById("damage_evidence").value;
        Global_Check['damages']++;
        Global_Check['categories'][$search]['damages']++;
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damages']++;
    } else {
        Global_Check['categories'][$search]['damageitems'][$dsearch]['registered']=true;
        Global_Check['categories'][$search]['damageitems'][$dsearch]['comments']=document.getElementById("damage_comments").value;
        Global_Check['categories'][$search]['damageitems'][$dsearch]['evidence_picture']=document.getElementById("damage_evidence").value;
        Global_Check['damages']++;
        Global_Check['categories'][$search]['damages']++;
    }
    closepdcDet2();
    StartPDC();
}

function UnMarkDamage($search,$ssearch,$dsearch){
    if ($ssearch!='') {
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['registered']=false;
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['comments']='';
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'][$dsearch]['evidence_picture']='';
        Global_Check['damages']--;
        Global_Check['categories'][$search]['damages']--;
        Global_Check['categories'][$search]['subcategories'][$ssearch]['damages']--;
    } else {
        Global_Check['categories'][$search]['damageitems'][$dsearch]['registered']=false;
        Global_Check['categories'][$search]['damageitems'][$dsearch]['comments']="";
        Global_Check['categories'][$search]['damageitems'][$dsearch]['evidence_picture']="";
        Global_Check['damages']--;
        Global_Check['categories'][$search]['damages']--;
    }
    closepdcDet2();
    StartPDC();
}

function ShowCatDamage($search){
    if (Global_Check['categories'][$search]['subcategories']==undefined){
        var $HInfo = $('#pdc_details2');$HInfo.html('');
		var $str=''; var $dc=0;
        $str+='<div class="card border shadow h-100">';
        $str+=' <div class="card-header">';
        $str+='     <div class="d-flex">';
        $str+='         <div><a onclick="closepdcDet2();"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
        $str+='         <div class="m-auto">'+Global_Check['categories'][$search]['cat_name']+'</div><span class="small" id="pdc_cc"><span>' ;
        $str+='     </div>';
        $str+=' </div>';
        $str+=' <div class="card-body pdc-pagina">';
        $str+='	    	    <div class="card-body p-2 mb-3">'+Global_Check['categories'][$search]['cat_description']+'</div>';
        $.each(Global_Check['categories'][$search]['damageitems'], function (key, val) {
            if (val.registered==false){
                $str+='             <a class="card pdc-card  mb-3" onclick="RegisterFormDamages(`'+$search+'`,``,`'+$dc+'`)">';}
            else {
                $str+='             <a class="card pdc-card  alert-danger mb-3" onclick="RegisterFormDamages(`'+$search+'`,``,`'+$dc+'`)">';}
            $str+='	    	    <div class="bold text-primary d-flex p-2 px-3 mb-0">';
            $str+='	     	        <div class="danger"><i class="fas fa-exclamation-triangle text-danger"></i></div>';
            $str+='                 <div class="text-grey px-2">'+val.title+'</div>';
            if (val.registered==false){
                $str+='                 <div class="ml-auto px-2"><i class="fal fa-square text-grey"></i></div>'; }
            else {
                $str+='                 <div class="ml-auto px-2"><i class="fas fa-check-square text-danger"></i></div>'; }
            $str+='             </div>';
            $str+='             </a>';
            $dc++;
        });
        $str+='             </div>';
        $str+='         </div>';
        $str+='     </div>';
        $str+=' </div>';
        $HInfo.append($str);
        openpdcDet2();}
    else {
        showSubCategories($search);
    }
}
function ShowSubCatDamages($search,$ssearch){
    var $HInfo = $('#pdc_details2');$HInfo.html('');var $str='';var $dc=0;
    $str+='<div class="card h-100 border shadow">';
    $str+=' <div class="card-header ">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a  onclick="showSubCategories('+$search+');"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto">'+Global_Check['categories'][$search]['cat_name']+' / '+Global_Check['categories'][$search]['subcategories'][$ssearch]['subcat_name']+'</div><span class="small" id="pdc_cc"><span>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="card-body pdc-pagina">';
    $.each(Global_Check['categories'][$search]['subcategories'][$ssearch]['damageitems'], function (key, val) {
        if (val.registered==false){
            $str+='             <a class="card pdc-card mb-3" onclick="openpdcDet2();RegisterFormDamages(`'+$search+'`,`'+$ssearch+'`,`'+$dc+'`);">';}
        else {
            $str+='             <a class="card pdc-card mb-3" onclick="openpdcDet2();RegisterFormDamages(`'+$search+'`,`'+$ssearch+'`,`'+$dc+'`);">';}
        $str+='	    	    <div class="bold d-flex p-2 px-3 mb-0" >';
        $str+='	     	        <div class="text-danger"><i class="fad fa-exclamation-triangle text-danger"></i></div>';
        $str+='                 <div class="text-grey px-2 ">'+val.title+'</div>';
        if (val.registered==false){
            $str+='                 <div class="ml-auto px-2"><i class="fal fa-square fa-fw text-danger"></i></div>'; }
        else {
            $str+='                 <div class="ml-auto px-2"><i class="fad fa-check fa-fw text-danger"></i></div>'; }
        $str+='             </div>';
        $str+='             </a>';
        $dc++;
    });
    $str+='        </div>';
    $str+='     </div>';
    $str+=' </div>';
    $HInfo.append($str);
    openpdcDet2();
}
function loadPDCtemplate($template){
    $('#StartCheck').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> loading').attr('enabled', true);
    $.ajax({
        url: window.location.origin + '/scripts/GetPDCTemplate.php?id='+$template,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            Global_Check=data;
            ReadDriverStatus("selectedVehicle","selectedTrailer");
            $('#titleTemplate').html(Global_Check['templateName']).attr('enabled', true);
            $('#StartCheck').html('<i class="fad fa-list fa-fw"></i><i class="fas fa-fw"></i> Start Registration').attr('enabled', true);
            $('#TempDescription').html( '<div class="card-body px-4 alert-success">'+Global_Check['templateDescription']+'</div>');

        }
    });
}
function openpdcDet() {
    const smallDevice = window.matchMedia('(max-width: 768px)')
    smallDevice.addListener(handleDeviceChange);

    function handleDeviceChange(e) {
        if (e.matches) {
            var element=document.getElementById("pdc_details");
            element.classList.remove("hide");
            document.getElementById("pdc_details2").className += " hide";
            document.getElementById("pdc_main").className += " hide";
        }
        else {
            var element=document.getElementById("pdc_main");
            element.classList.remove("hide");
            var element1=document.getElementById("pdc_details");
            element1.classList.remove("hide");
            var element2=document.getElementById("pdc_details2");
            element2.classList.remove("hide");
        }
    }
    handleDeviceChange(smallDevice);
}
function closepdcDet() {
    var $LInfo = $('#pdc_details');$LInfo.html('');
    const smallDevice = window.matchMedia('(max-width: 768px)')
    smallDevice.addListener(handleDeviceChange);

    function handleDeviceChange(e) {
        if (e.matches) {
            var element=document.getElementById("pdc_main");
            element.classList.remove("hide");
            document.getElementById("pdc_details2").className += " hide";
            document.getElementById("pdc_details").className += " hide";
        }
        else {
            var element=document.getElementById("pdc_main");
            element.classList.remove("hide");
            var element1=document.getElementById("pdc_details");
            element1.classList.remove("hide");
            var element2=document.getElementById("pdc_details2");
            element2.classList.remove("hide");
        }
    }
    handleDeviceChange(smallDevice);
}
function openpdcDet2() {
    const smallDevice = window.matchMedia('(max-width: 768px)')
    smallDevice.addListener(handleDeviceChange);

    function handleDeviceChange(e) {
        if (e.matches) {
            var element=document.getElementById("pdc_details2");
            element.classList.remove("hide");
            document.getElementById("pdc_details").className += " hide";
            document.getElementById("pdc_main").className += " hide";
        }
        else {
            var element=document.getElementById("pdc_main");
            element.classList.remove("hide");
            var element1=document.getElementById("pdc_details");
            element1.classList.remove("hide");
            var element2=document.getElementById("pdc_details2");
            element2.classList.remove("hide");
        }
    }
    handleDeviceChange(smallDevice);
}
function closepdcDet2() {
	var $LInfo = $('#pdc_details2');$LInfo.html('');
    const smallDevice = window.matchMedia('(max-width: 768px)')
    smallDevice.addListener(handleDeviceChange);

    function handleDeviceChange(e) {
        if (e.matches) {
            var element=document.getElementById("pdc_details");
            element.classList.remove("hide");
            document.getElementById("pdc_details2").className += " hide";
            document.getElementById("pdc_main").className += " hide";
        }
        else {
            var element=document.getElementById("pdc_main");
            element.classList.remove("hide");
            var element1=document.getElementById("pdc_details");
            element1.classList.remove("hide");
            var element2=document.getElementById("pdc_details2");
            element2.classList.remove("hide");
        }
    }
    handleDeviceChange(smallDevice);
}
function openpdcDet3() {
//    document.getElementById("pdc_details3").style.width = "100%";
}
function closepdcDet3() {
//    document.getElementById("pdc_details3").style.width = "0%";
}

function FinalisePDC(){
    var $HInfo = $('#pdc_details');$HInfo.html('');
    var $str='';
    $str+='<div class="card border shadow h-100">';
    $str+=' <div class="card-header">';
    $str+='     <div class="d-flex">';
    $str+='         <div><a  onclick="StartPDC()"><i class="fas fa-arrow-left larger fa-fw"></i></a></div>';
    $str+='         <div class="m-auto"> Save & Register</div>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="pdc-pagina ">';
    $str+='     <div class="card-body p-0" >';
    $str+='         <div class="card-title">Details</div>'
    $str+='         <div class="card-body p-0 pb-3">';
    $str+='             <div class="col-12">';
    $str+='                 <div class="row" >';
    $str+='                     <div class="col">Template name</div>  <div class="col text-right"><b>'+Global_Check['templateName']+'</b></div>';
    $str+='                 </div>';
    $str+='                 <div class="row" >';
   // $str+='                 <div class="col-6">Vehicle </div>      <div class="col-6"><b>'+Global_Check['vehicle']+'</b></div>';
   // $str+='                 <div class="col-6">Odometer </div>     <div class="col-6"><b>'+(Global_Check['vehicle_odometer'])/1000+'</b></div>';
   // $str+='                 <div class="col-6">Trailer </div>      <div class="col-6"><b>'+Global_Check['trailer']+'</b></div>';
   // $str+='                 <div class="col-6">Driver  </div>      <div class="col-6"><b>'+Global_Check['driver']+'</b></div>';
    $str+='                     <div class="col-6">Report Date </div>  <div class="col text-right"><b>'+new Date().format("yyyy/mm/dd HH:MM:ss")+'</b></div>';
    $str+='                 </div>';
    $str+='                 <div class="row" >';
    $str+='                     <div class="col-6">Damages </div>      <div class="col text-right"><b>'+Global_Check['damages']+'</b></div>';
    $str+='                 </div>';
    $str+='             </div>';
    $str+='             <div class=" col-12" >';
    $str+='                 <div class="mb-3 ">';
    $str+='                     <label>Additional notes</label>';
    $str+=' 			        <textarea role="textbox" rows="4" class="form-control text-left shadow-sm"  contenteditable  name="Add_Notes" id="add_notes" ></textarea>';
    $str+='                 </div>';
    $str+='                 <div class="mb-1">';
    $str+='                     <label>Signature</label>';
    $str+=' 			        <input type="text" class="form-control text-left shadow-sm" value="'+Global_Check['name_pdc_executing']+'" name="Add_Notes" id="signature" >';
    $str+='                 </div>';
    $str+='             </div>';
    $str+='             <div class="col-12" >';
    $str+='                 <div class="small px-1 "><i class="fad fa-check-square text-primary fa-fw"></i> I hereby confirm that I have carried out the pre-departure check in accordance with the regulations.</div>';
    $str+='             </div>';
    $str+='         </div>';
    $str+='     </div>';
    $str+=' </div>';
    $str+=' <div class="card-footer">';
    $str+='     <button class="col-12 btn btn-lg btn-primary" id="SaveCheck" onclick="RegisterCheckToSystem()" >Save & Upload <i class="fad fa-clipboard-check"></i></button>';
    $str+=' </div>';
    $HInfo.append($str);
}

function RegisterCheckToSystem(){
    Global_Check['add_notes']=document.getElementById("add_notes").value;
    Global_Check['signature']=Global_Check['driver']
    $('#SaveCheck').html('<span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span> saving...').attr('enabled', true);
    $.ajax({
        url: window.location.origin+'/scripts/POST_PDC_Check.php',
        type:"POST",
        data:JSON.stringify(Global_Check),
        contentType: "application/json;",
        dataType:'json',
        success:function(data){
            if (data.code == "200"){
                $('#SaveCheck').html('<i class="fas fa-check-double rest"></i> ...Saved').attr('enabled', true);
                setTimeout(() => { console.log("Waiting to show succes");
                    Global_Check={};
                    var $HInfo = $('#pdc_details');$HInfo.html('');
                    window.location.replace("../index.php");
                    }, 100);
            } else {
                $(".display-error").html("<ul>"+data.msg+"</ul>");
                $(".display-error").css("display","block");
            }

        }
    });
}

function jump_to_anchor(h){
//    var top = document.getElementById(h).offsetTop; //Getting Y of target element
//    window.scrollBy(0, 131);
//    $(h)[0].scrollIntoView();
//    window.scrollTo(0, top);                        //Go there directly or some transition

    var e = document.getElementById(h);
    e.classList.remove("col-12");
    e.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
}
