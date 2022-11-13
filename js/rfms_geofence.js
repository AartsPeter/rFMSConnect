function Manage_Geofences() {

    InitializeMAP();
    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    map.addControl(new L.Control.Draw({
        edit: { featureGroup: drawnItems, poly: { allowIntersection: false } },
        draw: { polygon: { allowIntersection: true }, circlemarker:false, marker:false }
    }));
    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        drawnItems.addLayer(layer);
        LayerControl.addOverlay(drawnItems,'New geofence');
    //    document.getElementById('nameGeofence').value= 'New geofence';
        ActiveLayer = drawnItems;
        ShowFenceButtons();
    });
    document.getElementById('delete').onclick = function(e) {
        drawnItems.clearLayers();
        HideFenceButtons();
        UpdateCounter('','loadprogress');
        document.getElementById('nameGeofence').name= '';
        document.getElementById('nameGeofence').value= '';
        ActiveLayer.clearLayers();
        LayerControl.removeLayer(ActiveLayer);
        map.flyto([46.00,8.65],16);
    }
    document.getElementById('export').onclick = function (e) {
        // Extract GeoJson from featureGroup
        var $data = drawnItems.toGeoJSON();
        if ($data.features.length != 0) {
            if (StoreGeojson($data)==true) {
                drawnItems.clearLayers();
                LoadGeofences();
            }
        }
    }
}
function onEachFeature(feature, layer) {
    drawnItems.addLayer(layer);
}
function ShowFenceButtons(){
    var element=document.getElementById("delete") ;
    element.classList.remove("hide");
    var element=document.getElementById("export") ;
    element.classList.remove("hide");
    var element=document.getElementById("nameGeofence") ;
    element.classList.remove("hide");
    var element=document.getElementById("info") ;
    element.classList.add("hide");
    var element=document.getElementById("GeofenceCat") ;
    element.classList.remove("hide");
}
function HideFenceButtons(){
    var element=document.getElementById("delete") ;
    element.classList.add("hide");
    var element=document.getElementById("export") ;
    element.classList.add("hide");
    var element=document.getElementById("nameGeofence") ;
    element.classList.add("hide");
    var element=document.getElementById("info") ;
    element.classList.remove("hide");
    var element=document.getElementById("GeofenceCat") ;
    element.classList.add("hide");
}
function HideActionWindow(){
    var element=document.getElementById("FencesWindow") ;
    element.classList.remove("col-lg-5");
    element.classList.add("col-lg-6");
    var element2=document.getElementById("MapWindow") ;
    element2.classList.remove("col-lg-4");
    element2.classList.add("col-lg-6");
    var element1=document.getElementById("ActionWindow") ;
    element1.classList.add("hide");
    map.invalidateSize();
}
function ShowActionWindow(){
    var element=document.getElementById("FencesWindow") ;
    element.classList.remove("col-lg-6");
    element.classList.add("col-lg-5");
    var element1=document.getElementById("MapWindow") ;
    element1.classList.remove("col-lg-6");
    element1.classList.add("col-lg-4");
    var element2=document.getElementById("ActionWindow") ;
    element2.classList.remove("hide");
    map.invalidateSize();
}

function ShowGeoFenceOnMap($name,$id){
    UpdateCounter('<span class="sloader"></span>','loadprogress');
    if (typeof ActiveLayer !== 'undefined') {   // allowing only 1 geofence layer to be active, remove if exists
        ActiveLayer.clearLayers();
        LayerControl.removeLayer(ActiveLayer)
    }
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetGeoJson?id='+$id,
        success: function(data) {
            L.geoJson(data, {  onEachFeature: onEachFeature });
//            drawnItems.geometryToLayer(datalayer);
            map.fitBounds(drawnItems.getBounds(),{padding: [40, 40]});
//            LayerControl.addOverlay(datalayer,data.name);
            LayerControl.addOverlay(drawnItems,data.name);
            ActiveLayer = drawnItems;
            UpdateCounter('[ '+$name+' ]','loadprogress');
            document.getElementById('nameGeofence').name= $id;
            document.getElementById('nameGeofence').value= $name;
            selectElement('GeofenceCat',data.cat)
            ShowFenceButtons();
        },
        error:function() {}
    });
}
function StoreGeojson($content) {
    if ($content.features.length!=0) {
        $('#nameGeofence').removeClass('border border-danger');
        var sel = document.getElementById("groups");
        $content.client= sel.options[sel.selectedIndex].value;
        var $update = document.getElementById('nameGeofence').name;
        $content.name=document.getElementById('nameGeofence').value;
        $content.cat=document.getElementById('GeofenceCat').value;
        if ($content.name!='') {
            if ($update.length > 0) {
                $update = '?id=' + $update;
            }
            $.ajax({
                url: window.location.origin + '/scripts/POST_Geofence' + $update,
                type: "POST",
                data: JSON.stringify($content),
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                success: function (data) {
                    if (data.code == "200") {
                        $('#SaveCheck').html('<i class="fas fa-check-double rest"></i> ...Saved').attr('enabled', true);
                        document.getElementById('nameGeofence').value = '';
                        Notiflix.Notify.success(data.msg);
                        LoadGeofences();
                    }
                    HideFenceButtons();
                    UpdateCounter('','loadprogress');
                    document.getElementById('nameGeofence').name= '';
                    document.getElementById('nameGeofence').value= '';
                    ActiveLayer.clearLayers();
                    LayerControl.removeLayer(ActiveLayer);
                    return true;
                },
                error : function (data) { $('#nameGeofence').addClass('border border-danger');}
            });
        } else {
            $('#nameGeofence').addClass('border border-danger');
            return false;
        }
    }

}
function DeleteGeofenceConfirm($name,$id) {
    Notiflix.Confirm.show('Delete geofence','Do wish to delete geofence <br><b>`'+$name+'`</b> ?','Yes','No',
        () => {
            $.ajax({
                url: window.location.origin + '/scripts/Post_DeleteGeofence?id='+$id,
                type: "GET",
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    if(data == "yes") { Notiflix.Notify.success('Geofence <b>'+$name+'</b> deleted....');LoadGeofences();}
                    else {  Notiflix.Notify.warning('geofence deletion failed !');}
                },
                error : function(e){ Notiflix.Notify.failure('geofence deletion failed !');}
            });

        } );
}
function RemoveVehiclesGeofenceConfirm($name,$id) {
    Notiflix.Confirm.show('Delete geofence','Do wish to remove all related vehicles from geofence <br><b>`'+$name+'`</b> ?','Yes','No',
        () => {
            $.ajax({
                url: window.location.origin + '/scripts/Post_RemoveVehiclesGeofence?id='+$id,
                type: "GET",
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    if(data == 'success') { Notiflix.Notify.success('Geofence <b>'+$name+'</b> deleted....');LoadGeofences();HideActionWindow();}
                    else {  Notiflix.Notify.warning('geofence deletion failed !');}

                },
                error : function(e){ Notiflix.Notify.failure('geofence deletion failed !');}
            });

        } );
}

function LoadGeofences(){
    ShowPlaceholder('geofencesTablePersonal','table');
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetGeofences',
        success: function(data) {
            UpdateCounter('','geofencesTablePersonal');
            ShowSystemGeofencesTable(data.system);
            ShowPersonalGeofencesTable(data.personal);
            ShowPublicGeofencesTable(data.public);
        },
        error:function() {}
    });
}
function LoadGeofencesType(){
    $s = document.getElementById('GeofenceCat');
    $.ajax({
        dataType: "json",
        url:  window.location.origin+'/scripts/GetGeofencesCat',
        success: function(data) {
            $.each(data, function(key, val){
                var opt = document.createElement('option');
                opt.value = val.id;
                opt.innerHTML = val.name;
                $s.appendChild(opt);
            });
            $GF= new SlimSelect({ select: '#GeofenceCat' });
        },
        error:function() {}
    });
}

function ShowSystemGeofencesTable($data){
    var table =$('#geofencesTableSystem').DataTable( {
        data: $data,
        language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
        oLanguage: {sProcessing: "<div class='loader' id='tableloader'></div>"},processing: true,	autoWidth: false,destroy:true,searching: true,"lengthMenu": [[10, -1], [10, "All"]],
        dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[2,'asc' ]],
        buttons: {buttons: []},
        columns: [
            { "data": "active",
                "render": function(data, type, row) { if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
                "title":" ","visible":true,"width": "2%","class":"dt-right",responsivePriority: 1,},
            { "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { $str='';
                    if (row.geofence_type=="polygon"){ $str='<i class="fad fa-draw-polygon fa-fw" title="polygon geofence"></i>'}
                    if (row.geofence_type=="square") { $str='<i class="fad fa-draw-square fa-fw" title="square geofence"></i>'}
                    if (row.geofence_type=="circle") { $str='<i class="fad fa-draw-circle fa-fw" title="circle geofence"></i>'}
                    if (row.geofence_type=="marker") { $str='<i class="fad fa-map-marker-alt fa-fw" title="marker geofence"></i>'}
                    return $str;}},
            { "data": "name",			"title":"Name",			"class":"dt-left"},
            { "data": "description",	"title":"Description",	"class":"dt-left",visible:false},
            { "data": "categoryName",	"title":"Category",	"class":"dt-left",visible:true,width:'2%'},
            { "data": "counted",	    "title":"vehicles",	    "class":"dt-center",width:'2%'},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="ConnectGeofence(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="connect vehicle to geofence"><i class="fad fa-link "></i></span>'}},
            { "width": "2%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="ShowGeoFenceOnMap(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="show geofence on map"><i class="fad fa-fw fa-map-marked-alt"><i></span>'}},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) {
                    if (row.counted > 0 ) { return '<span onclick="RemoveVehiclesGeofenceConfirm(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="remove all related vehicles from geofence"><i class="fad fa-unlink"></i></span>';}
                    else { return '<span><i class = "fa-fw "></i></span>';}}, "width": "5%" ,"bSortable" : false},
        ]
    } );
    $('#geofencesTableSystem').off('click');
    $('#geofencesTableSystem tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
        else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
    } );
}
function ShowPersonalGeofencesTable($data){
    var table =$('#geofencesTablePersonal').DataTable( {
        data: $data,
        language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
        oLanguage: {sProcessing: "<div class='loader' id='tableloader'></div>"},processing: true,	autoWidth: false,destroy:true,searching: true,"lengthMenu": [[10, -1], [10, "All"]],
        dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[2,'asc' ]],
        buttons: {buttons: []},
        columns: [
            { "data": "active",
                "render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
                "title":" ","visible":true,"width": "1%","class":"dt-right",responsivePriority: 1,},
            { "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) {
                    if (row.geofence_type=="polygon"){ $str='<i class="fad fa-draw-polygon fa-fw" title="polygon geofence"></i>'}
                    if (row.geofence_type=="square") { $str='<i class="fad fa-draw-square fa-fw" title="square geofence"></i>'}
                    if (row.geofence_type=="circle") { $str='<i class="fad fa-draw-circle fa-fw" title="circle geofence"></i>'}
                    if (row.geofence_type=="point") { $str='<i class="fad fa-map-marker-alt fa-fw" title="marker POI"></i>'}
                    return $str;}},
            { "data": "name",			"title":"name",			"class":"dt-left"},
            { "data": "description",	"title":"description",	"class":"dt-left",visible:false},
            { "data": "categoryName",   "title":"category",	    "class":"dt-left",visible:true,width:'2%'},
            { "data": "counted",	    "title":"vehicles",	    "class":"dt-center",width:'2%'},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="ConnectGeofence(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="connect vehicle to geofence"><i class="fad fa-link "></i></span>'}},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="ShowGeoFenceOnMap(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="show geofence on map"><i class="fad fa-fw fa-map-marked-alt"><i></span>'}},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) {
                    if (row.counted > 0 ) { return '<span onclick="RemoveVehiclesGeofenceConfirm(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="remove all related vehicles from geofence"><i class="fad fa-unlink"></i></span>';}
                    else { return '<span><i class = "fa-fw "></i></span>';}}, "width": "5%" ,"bSortable" : false},
            { "render": function (data, type, row) {
                    if (row.editable == 'true') { return '<span onclick="DeleteGeofenceConfirm(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="delete geofence"><i class="fad fa-trash-alt fa-fw "><i></span>';}
                else { return '<span><i class = "fa-fw "></i></span>';}}, "width": "5%" ,"bSortable" : false}
        ]
    } );
    $('#geofencesTablePersonal').off('click');
    $('#geofencesTablePersonal tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
        else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
    } );
}
function ShowPublicGeofencesTable($data){
    var table =$('#geofencesTablePublic').DataTable( {
        data: $data,
        language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
        oLanguage: {sProcessing: "<div class='loader' id='tableloader'></div>"},processing: true,	autoWidth: false,destroy:true,searching: true,"lengthMenu": [[10, -1], [10, "All"]],
        dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[2,'asc' ]],
        buttons: {buttons: []},
        columns: [
            { "data": "active",
                "render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
                "title":" ","visible":true,"width": "2%","class":"dt-right",responsivePriority: 1,},
            { "width": "3%" ,"bSortable" : false,mRender: function (data, type, row) {
                    if (row.geofence_type=="polygon"){ $str='<i class="fad fa-draw-polygon fa-fw" title="polygon geofence"></i>'}
                    if (row.geofence_type=="square") { $str='<i class="fad fa-draw-square fa-fw" title="square geofence"></i>'}
                    if (row.geofence_type=="circle") { $str='<i class="fad fa-draw-circle fa-fw" title="circle geofence"></i>'}
                    if (row.geofence_type=="point") { $str='<i class="fad fa-map-marker-alt fa-fw" title="marker POI"></i>'}
                    return $str;}},            { "data": "name",			"title":"name",			"class":"dt-left"},
            { "data": "description",	"title":"description",	"class":"dt-left",visible:false},
            { "data": "categoryName",	    "title":"category",	"class":"dt-left",visible:true,width:'2%'},
            { "data": "counted",	    "title":"vehicles",	"class":"dt-center",width:'2%'},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) { return '<div onclick="ConnectGeofence(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="connect vehicle to geofence"><i class="fad fa-link "></i></div>'}},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="ShowGeoFenceOnMap(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="show geofence on map"><i class="fad fa-fw fa-map-marked-alt"><i></span>'}},
            { "width": "5%" ,"bSortable" : false,mRender: function (data, type, row) {
                    if (row.counted > 0 ) { return '<span onclick="RemoveVehiclesGeofenceConfirm(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="remove all related vehicles from geofence"><i class="fad fa-unlink"></i></span>';}
                    else { return '<span><i class = "fa-fw "></i></span>';}}, "width": "5%" ,"bSortable" : false},
            { "render": function (data, type, row) {
                    if (row.editable == 'true') { return '<span onclick="DeleteGeofenceConfirm(`'+row.name+'`,`'+row.id+'`);" class="pointer" title="delete geofence"><i class="fad fa-trash-alt fa-fw "><i></span>';}
                    else { return '<span><i class = "fa-fw "></i></span>';}}, "width": "5%" ,"bSortable" : false}
        ]
    } );
    $('#geofencesTablePublic').off('click');
    $('#geofencesTablePublic tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
        else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
    } );
}
function ConnectGeofence($name,$id){
    ShowGeoFenceOnMap($name,$id);
    ShowActionWindow();
    ShowPlaceholder('SetActionGeofences','form');
    $LInfo=$('#SetActionGeofences');$oList='';
    var prioOpt = Array('Alert','Register','Report');
    var regOpt  = Array('Out','In','Both');
    var alertOpt= Array('Message','Mail','Dashboard','SMS');
    $str='';
    $.ajax({
        url: window.location.origin+'/scripts/GetVehiclesGeofence?id='+$id,
        dataType:'JSON',
        success: function (data) {
            $oList=data;
            if (data.geofence[0].prio)          { var $prio = data.geofence[0].prio;}           else { var $prio = ".";}
            if (data.geofence[0].registration)  { var $reg  = data.geofence[0].registration;}   else { var $reg = ".";}
            if (data.geofence[0].alert )        { var $alert= data.geofence[0].alert; }         else { var $alert = ".";}
            $str+='<div class="row " id="contactform">';
//            $str+='     <div class="card-title p-3" >'+$name+'</div>';
            $str+='     <div class = "form-group col-12 ">';
            $str+='         <label for="vehicles">vehicles  (<span class="small" id="countedvehicles"> '+data.vehicles.length+'</span> )</label> ';
            $str+='         <select class = "w-100" name = "vehicles" value="select vehicle" id = "vehicles" multiple>';
            $.each(data.vehicles, function(key, val){
                if (val.Selected==true){
                    $str += '             <option class="text-primary rest" value="' + val.vin + '" selected> ' + val.customerVehicleName + '</option>';
                } else {
                    $str += '             <option class="text-primary" value="' + val.vin + '" > ' + val.customerVehicleName + '</option>';
                }
            });
            $str+='         </select>';
            $str+='     </div>';
            $str+='     <div class = "form-group col-12">';
            $str+='         <label for="registration">registration</label>';
            $str+='         <select class="w-100 " id="registration" name="registration" multiple>';
            $.each(regOpt, function(key, val){
                if ($reg.includes(val) == true){ $str+='<option value="'+val+'" selected>'+val+'</option>';}     else {$str+='<option value="'+val+'" >'+val+'</option>';}
            });
            $str+='         </select>';
            $str+='     </div>';
            $str+='     <div class = "form-group col-12">';
            $str+='         <label for="prio">Prio</label>';
            $str+='         <select class="w-100 " id="prio" name="prio" multiple>';
            $.each(prioOpt, function(key, val){
                if ($prio.includes(val) == true){ $str+='<option value="'+val+'" selected>'+val+'</option>';}     else {$str+='<option value="'+val+'" >'+val+'</option>';}
            });
            $str+='         </select>';
            $str+='     </div>';
            $str+='     <div class = "form-group col-12">';
            $str+='         <label for="alert">Alert</label>';
            $str+='         <select class="w-100 " id="geoalert" name="alert" multiple>';
            $.each(alertOpt, function(key, val){
                if ($alert.includes(val) == true){ $str+='<option value="'+val+'" selected>'+val+'</option>';}     else {$str+='<option value="'+val+'" >'+val+'</option>';}
            });
            $str+='         </select>';
            $str+='     </div>';
            $str+='     <div class = "form-group col-12" id="result"></div>';
            $str+='     <div class = "form-group col-12 ml-auto">';
            $str+='         <input name="submit" id="submit" tabIndex="5" value="Save" type = "submit" class="col-6 btn btn-primary">';
            $str+='     </div>';
            $str+='</div>';
            $LInfo.html('');
            $LInfo.html($str);
            $SV= new SlimSelect({ select: '#vehicles' });
            new SlimSelect({ select: '#registration' });
            new SlimSelect({ select: '#prio' });
            new SlimSelect({ select: '#geoalert' });
            $("#submit").click(function() {
                //reset previously set border colors and hide all message on .keyup()

                $('#vehicles').removeClass('border border-danger');
                $('#registration').removeClass('border border-danger');
                $('#prio').removeClass('border border-danger');
                $('#geoalert').removeClass('border border-danger');
                $('#result').html('');
                //get input field values

                var vehicles     = $('#vehicles').val();
                var reg          = String($('#registration').val());
                var prio         = String($('#prio').val());
                var alert        = String($('#geoalert').val());
                /********validate all our form fields***********/
                var flag = true;
                if(vehicles.length==0)  {
                    $('.ss-main .ss-multi-selected').addClass('alert-danger'); flag = false; }
                if(reg.length==0)       {
                    $('.ss-main .ss-multi-selected').addClass('alert-danger');   flag = false; }
                if(prio.length==0)      {
                    $('.ss-main .ss-multi-selected').addClass('alert-danger');    flag = false; }
                if(alert.length==0)     {
                    $('.ss-main .ss-multi-selected').addClass('alert-danger');   flag = false; }
                var $response={}
                $response.id=$id;
                $response.vehicles=vehicles;
                $response.reg=reg;
                $response.prio=prio;
                $response.alert=alert;
                /********Validation end here ****/
                /* If all are ok then we send ajax request to email_send.php *******/
                if(flag)
                {
                    $.ajax({
                        type: 'post',
                        url: window.location.origin+'/scripts/Post_GeofenceAction',
                        dataType: 'json',
                        data: JSON.stringify($response),
                        beforeSend: function() {
                            $('#submit').attr('disabled', true);
                            $('#submit').removeClass('btn-primary');
                            $('#submit').addClass('btn-secondary');

                            $('#submit').attr('value','wait..');
                        },
                        complete: function() {
                            $('#submit').attr('disabled', false);
                            $('#submit').removeClass('btn-secondary');
                        },
                        success: function(data) {
                            if(data.type == 'error')
                            {
                                $('#result').html('<div class="error">'+data.text+'</div>');
                            }else{
                                $('#result').html('<div class="success">'+data.text+'</div>');
                                $('input[type=select]').val('');
                                $('#submit').addClass('btn-success');
                                $('#submit').attr('value','Saved');
                                Notiflix.Notify.success('Geofence <b>'+$name+'</b> has '+data.text);
                                HideActionWindow();
                                LoadGeofences();
                                document.getElementById('nameGeofence').name= '';
                                document.getElementById('nameGeofence').value= '';
                                ActiveLayer.clearLayers();
                                LayerControl.removeLayer(ActiveLayer);
                            }
                            $("#result").hide();
                        },
                        error: function(e) {
                            $('#submit').addClass('btn-primary');
                            $('#submit').attr('value','Save');
                            { $('#result').html('<div class="p-3 alert alert-danger">failed writing</div>'); }
                        }

                    });
                }
                else
                { $('#result').html('<div class="p-1 px-3 alert alert-danger"><B>all fields are mandatory</b></div>'); }
            });


        },
        error:function(jqXHR, textStatus, errorThrown) {
            SessionAlert('Your application-session has expired... <br><br>returning to login-page!');
        }
    });

}


