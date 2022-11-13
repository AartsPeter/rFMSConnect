function FilterDBVehicle(){
	ClearVehicleMessage();
	var Vehicle = document.getElementById("vehicles").value;
	var StartDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd H:MM");
	var EndDate =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd H:MM");
	var $VSInfo = $('#TripReport');var $TTInfo = $('#TripTable');$TTInfo.html('');
	SaveSelectedReportDate();
	rFMS_Markers.clearLayers();
	rFMS_Trigger.clearLayers();
	rFMS_Trip.clearLayers();
	if (Vehicle.length==""){
		$VSInfo.html('');$VSInfo.append("<div class='notice-warning' ><strong>No vehicle selected !</div>");return;
	}
	var SD=new Date(document.getElementById("SelectDate").value);
	var SDE=new Date(document.getElementById("SelectDateEnd").value);
	var $Difference_In_Days=DateDiff(SD,SDE);
	var $SumInfo = $('#TripSumReport');var $TimeLineInfo = $('#TripDetailsTimeline');$TimeLineInfo.html('');
	var $TFU=0;var $TD=0;$TCO2=0;var $TNT=0;$TFE=0;$counter=0;$Tcounter=0;$Str='';
	var markers=[];
	ShowPlaceholder('TripTable','table');
	ShowPlaceholder('TripSumReport','sumreport');
	$counter=0;
	$.ajax({
		url: window.location.origin+'/scripts/GetTripsDetails.php?id='+Vehicle+'&StartDate='+StartDate+'&EndDate='+EndDate,
		dataType:'JSON',
		type: "GET", 
		success:function(data){
			if (data.length>0){
				$VSInfo.html('');
				$.each(data, function(key, val){
					$TD+=parseFloat(val.Distance);
					$TFU+=parseFloat(val.FuelUsed);
					$TCO2+=parseFloat(val.CO2_emission);
					$Tcounter+=parseFloat(val.amountTrips);
					$counter++
				})
				$FuelUsage=100/($TD/$TFU);
				$TFE+=parseFloat($FuelUsage);
				$SumInfo.html('');
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="trips p-2">		<span><small>Trips</small>	</span></br><span class="info-box-number">'+formatNumber($counter)+'</span></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="distance p-2">	<span><small>Distance </small></span></br><span class="info-box-number">'+formatNumber($TD.toFixed(0))+'</b> <small>km</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="fuelused p-2">	<span><small>Fuel used </small></span></br><span class="info-box-number">'+formatNumber($TFU.toFixed(0))+'</b> <small>L</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="fuelusage p-2">	<span><small>Fuel Economy </small></span></br><span class="info-box-number">'+formatNumber($TFE.toFixed(2))+' </b> <small>L/100km</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="co2 p-2">		<span><small>CO2 </small></span></br><span class="info-box-number">'+formatNumber($TCO2.toFixed(0))+'</b> <small>kg</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="days p-2">		<span><small>Days </small></span></br><span class="info-box-number">'+$Difference_In_Days+'</b></div></div>';
				$SumInfo.append($Str);
				UpdateCounter('','TripTable');
				ShowTableVehicleTrips(data);
			}
			else {
				UpdateCounter('','TripTable');$SumInfo.html('');
				$VSInfo.html('');$VSInfo.append("<div class='notice-danger' ><strong>No trip data found !</div>");
				UpdateCounter("","DetailsTableHeaderPH");UpdateCounter("","TripsTableHeaderPH");
				UpdateCounter("","TripReport");UpdateCounter("","TripsTableHeader");UpdateCounter("","FleetSumReport");UpdateCounter("","FleetTripReport");UpdateCounter("","FleetFuelReport");
				Notiflix.Notify.warning('No trip information found for the selected period !');
			}
		}
	});	
}
function ClearVehicleMessage(){
	var $VSInfo = $('#TripReport');
	$VSInfo.html('');
	var $VSInfo = $('#TripSumReport');
	$VSInfo.html('');
	var $VSInfo = $('#VehicleInfoTrip');
	$VSInfo.html('');
}

function SelectCustomerVehicles(){
	$select = $('#vehicles');	
	$select.html('');var $text="";
	if (document.getElementById("groups").value=='*'){
		$select.append('<option value="" selected> <i>First select a group</i></option>');
		var element=document.getElementById("vehicles") ;
		element.classList.add("alert-danger");
	}
	else {
		var element=document.getElementById("vehicles") ;
		element.classList.remove("alert-danger");
		var element=document.getElementById("reportarea") ;
		element.classList.remove("hide");
		var $VSInfo = $('#TripReport');
		$VSInfo.html('');
		$.ajax({
			url: window.location.origin+'/scripts/GetCustomerVehicles.php',
			dataType:'JSON',
			success:function(data){
				var $selected='selected';
				var $class='';
				$.each(data, function(key, val){
					if (val.customerVehicleName==val.LicensePlate)	{ $text=val.customerVehicleName;} else { $text=val.customerVehicleName+' ('+ val.LicensePlate+')';}
					if (val.vehicleActive!="1")						{ $class="select_old";	}
					$select.append('<option value="' + val.id + '" class="'+$class+'" '+$selected+'>'+$text+'</option>');
					if ($selected=='selected') {$selected='';}
				})
				FilterDBVehicle();
			}
		});	
	}
}	

function ShowTripTable($DataArray) {
    var table=$('#TripTable').DataTable( {
		createdRow: function( row, data, dataIndex){
            if( data.tripdelayed ==  true){
                $(row).addClass('delayedrow');
            }
		},
		data: $DataArray,autoWidth: true,destroy:true,	order:[[1,'desc' ]],	dom: 'Bfltirp',	lengthMenu: [[10,75,200,-1],[10,75,200,"All"]],"bLengthChange": false,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
	    buttons: {buttons: [
			{ 	extend: 'excel',className: 'btn btn-primary btn-sm' ,filename: 'rFMSReader_Trip-Data_'+document.getElementById("vehicles").value},
			{	extend: 'pdf', 	className: 'btn btn-primary btn-sm'	,orientation: 'landscape', pageSize: 'A3',messageTop: 'Trip-data for selected vehicle '+document.getElementById("vehicles").value			},
			{	extend: 'print',className: 'btn btn-primary btn-sm' ,messageTop: 'Trip-data for selected vehicle '+document.getElementById("vehicles").value}]
		},
		columns: [
			{ "data": "VIN", "title":"vehicle","visible":false,"defaultContent": "-"},
//			{ "data": "customerVehicleName", "title":"name","visible":"false","defaultContent": "-"},
			{ "data": "starttrip", "title":"starttrip UTC","defaultContent": "-"},
            { "data": "endtrip","title":"Trip End UTC","defaultContent": "-"},
            { "data": "StartOdo","title":"Odo-meter Start","defaultContent": "-","width": "7%"},
            { "data": "EndOdo","title":"Odo-meter End","defaultContent": "-","width": "7%"},
            { "data": "driver1","title":"Driver","defaultContent": "-","width": "8%"},
            { "data": "duration","title":"Duration (hh:mm)","defaultContent": "-", "className":"dt-right","width": "5%" },
            { "data": "distance","title":"Distance (km)","defaultContent": "-", "className":"dt-right","width": "5%" },
            { "data": "averagespeed","title":"Avg. Speed (km/u)","defaultContent": "-" , "className":"dt-right","width": "5%"},
            { "data": "averageweight","title":"Avg. Weight (kg)","defaultContent": "-" , "className":"dt-right","width": "5%"},
            { "data": "fuelused","title":"Fuel Used (L)","defaultContent": "-" , "className":"dt-right","width": "5%"},
			{ "data": "fuelusage" ,"title":"Economy (l/100km)","defaultContent": "-", "className":"dt-right","width": "5%"},
			{ "data": "startdelay","title":"Delay at start (hh:mm)","defaultContent": "-" , "className":"dt-right","width": "5%"},
			{ "data": "enddelay","title":"Delay at end (hh:mm)","defaultContent": "-" ,"className":"dt-right","width": "5%"},
			{ "data": "tripdelayed","title":"delayed","defaultContent": "-" ,"className":"dt-center","width": "3%"}
//			{ "data": "timerrate","title":"timerrate","defaultContent": "-" ,"width": "5%"}
        ]
    } );
	$('#TripTable').off('click');
	$('#TripTable tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowVehicleTable($Vehicles){
	var table=$('#tableVehicles').DataTable( {
		data:$Vehicles,autoWidth: true,	destroy:true,	order:[[1,'asc' ]],	dom: 'Bfltirp',	lengthMenu: [[25,75,200,-1],[25,75,200,"All"]],"bLengthChange": false,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		buttons: {
			buttons: [
				{ extend: 'excel', className: 'btn btn-primary btn-sm' },
				{ extend: 'pdf', className: 'btn btn-primary btn-sm', orientation: 'landscape',  pageSize: 'A3', messageTop: 'Active Vehicles'},
				{ extend: 'print', className: 'btn btn-primary btn-sm' },
			]
		},
		columns: [
			{ "data": "customerVehicleName","title":"Vehicle Name","className":"dt-left"},
			{ "data": "Name","title":"Customer Name","defaultContent": "" , "className":"dt-left","width": "14%"},
			{ "data": "LicensePlate","title":"License Plate","defaultContent": "" ,"className":"dt-right","width": "3%"},
			{ "data": "model","title":"model","defaultContent": "" ,"className":"dt-right","width": "5%" },
			{ "data": "Year","title":"Production Year", "className":"dt-right","width": "54"},
			{ "data": "OdoMeter","title":"ODO Meter","defaultContent": "", "className":"dt-right","width": "5%" },
			{ "data": "serviceDistance","title":"Service Distance","defaultContent": "", "className":"dt-right","width": "4%"  },
			{ "data": "LastActivity","title":"Last Contact","defaultContent": "" },
			{ "data": "LastDriver","title":"Last Driver","defaultContent": "", "className":"dt-left","width": "10%" },
			{ "data": "FuelLevel","title":"Fuel in %","defaultContent": "" ,"className":"dt-right","width": "5%"},
			{ "data": "catalystFuelLevel","title":"Adblue level in %","defaultContent": "" ,"className":"dt-right","width": "5%" },
			{ "data": "TotalFuelUsed","title":"Total Fuel Used in ml","defaultContent": "", "className":"dt-right","width": "5%" },
			{ "data": "obu_sw_version","title":"OBU Version","defaultContent": "" },
			{ "data": "VIN", "title":"Vin" }
			]
	} );
	$('#tablevehicles').off('click');
	$('#tablevehicles tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}

function MissingDistance(SO,EO){
	if (SO<=0){return false}
	if (EO<=0){return false}
	var distance=SO-EO;
	if ( Math.abs(distance)>2000){return true}
	else {return false} 
	
}
function CreateMarkMissingDistance(SO,EO,SD){
	var $tripreport={};
	var $TripL=Math.abs((SO-EO))/1000;	    
	$tripreport.distance=$TripL.toFixed(3);	 
	$tripreport.VIN='Missing Distance';	    
	$tripreport.starttrip=new Date(SD).format("yyyy/mm/dd HH:MM:ss");
	return $tripreport;
}
function StartDBValidation(markers){
	var TripReport=[];
	var TripActive=true;
	var EndODO=0;
	var StartODO=0;
	var TripArray=[];
	for (var t=0; t < markers.length; ++t )
	{
		if (markers[t].triggerType == 'ENGINE_ON' && TripActive==false){
			TripActive=true;
			StartODO=markers[t].hrTotalVehicleDistance;
//			var TimeODO = markers[t].createdDateTime;
//			if (MissingDistance(StartODO,EndODO)==true){
//				ArrayRow=CreateMarkMissingDistance(StartODO,EndODO,TimeODO);
//				TripReport.push(ArrayRow);
//			}
		}	
		if (markers[t].triggerType == 'ENGINE_ON' && TripActive==true){
			var ArrayRow=[];
			EndODO=markers[t].hrTotalVehicleDistance;
			TripArray.push(markers[t]);
			ArrayRow=TripDBValidate(TripArray);
			if (ArrayRow.distance>0){
				TripReport.push(ArrayRow);
			}
			TripArray=[];
			TripActive=true;
			StartODO=markers[t].hrTotalVehicleDistance;
		}	
		if (markers[t].triggerType == 'ENGINE_OFF'){
			var ArrayRow=[];
			EndODO=markers[t].hrTotalVehicleDistance;
			TripArray.push(markers[t]);
			ArrayRow=TripDBValidate(TripArray);
			if (ArrayRow.distance>0){
				TripReport.push(ArrayRow);
			}
			TripArray=[];
			TripActive=false;
		}
		if (TripActive==true){
			TripArray.push(markers[t]);
		}
	}
	//$LInfo.html('');
	ShowTripTable(TripReport);
}
function DrawTripSpeed(){
	var dataPoints = [];
	dataPoints.push([ new Date(markers[0].createdDateTime), 0]);
	for ( var t=1; t < markers.length; ++t ){
		dataPoints.push([ new Date(markers[t].createdDateTime), parseFloat(markers[t].wheelBasedSpeed)]);
	}
	dataPoints.push([ new Date(markers[t-1].createdDateTime), 0]);

	Highcharts.chart('TripSpeed',{
		chart: { type: 'areaspline', zoomType: 'x', marginLeft: 120, marginRight: 40,styledMode: true },
		title: { text: 'Vehicle Speed',margin: 0},
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] }},
		yAxis: { title: { text:"Vehicle Speed (km/u)"  },tickInterval: 25, min: 0,max:110  },
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{point.color}">{point.x:%e %b %H:%M} <br><b>{point.y}</b> km/h </span>',
				 crosshairs: true,shared:true, useHTML:true},
		plotOptions: { spline: { marker: {enabled: true}}, series: {turboThreshold: 1,animation: false,fillColor: '#ddd'}},
		colors: ['#266cab'],
		credits:{enabled: false},
		legend: {enabled: false },
		series: [{ name: 'Speed', data: dataPoints, colorIndex:"4" }]
	});
}
function DrawTripDistance(){
	var dataPoints = [];
//	dataPoints.push([ new Date(markers[0].createdDateTime), 0]);
	for ( var t=1; t < markers.length; ++t ){
		var $distance=(markers[t].hrTotalVehicleDistance-markers[t-1].hrTotalVehicleDistance)/1000;
		dataPoints.push([ new Date(markers[t].createdDateTime), parseFloat($distance)]);
	}
	dataPoints.push([ new Date(markers[t-1].createdDateTime), 0]);

	Highcharts.chart('TripDistance',{
		chart: { type: 'column', zoomType: 'x', marginLeft: 120, marginRight: 40,styledMode: true },
		title: { text: 'Distance per Timer',margin: 0 },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] }},
		yAxis: { title: { text:"Distance <br>km"  },tickInterval: 5, min: 0,max:20  },
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{point.color}">{point.x:%e %b %H:%M} <br><b>{point.y}</b> km </span>',
				 crosshairs: true,shared:true},
		plotOptions: { column: {pointPadding: 0.2,  pointWidth: 5, borderWidth: 0},spline: { marker: {enabled: true}}, series: {turboThreshold: 1,animation: false}},
		credits:{enabled: false},legend: {enabled: false },
		series: [{ name: 'Distance', data: dataPoints, colorIndex:"4" }]
	});
}
function DrawTripFuel(){
	var dataPoints = [];var dataPoints1 = [];var dataPoints2 = [];
	dataPoints.push([ new Date(markers[0].createdDateTime), 0]);
	for ( var t=0; t < markers.length; ++t ){
		if (markers[t].fuelLevel1>0){	
			dataPoints.push([ new Date(markers[t].createdDateTime), parseFloat(markers[t].fuelLevel1)]);
		}
		if (markers[t].catalystFuelLevel>0){	
			dataPoints2.push([ new Date(markers[t].createdDateTime), parseFloat(markers[t].catalystFuelLevel)]);
		}
		if (markers[t].engineTotalFuelUsed>0){	
			dataPoints1.push([new Date(markers[t].createdDateTime),parseFloat(markers[t].engineTotalFuelUsed)]);
		}
	}

	Highcharts.chart('TripFuel',{
		chart: { type: 'line', zoomType: 'x',marginLeft: 120, marginRight: 40,styledMode: true },
		title: { text: 'Fuel level',margin: 0 },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] }},
		yAxis: [{ title: { text:"Fuellevel & Adblue in %"}, tickInterval: 25, min: 0, max:100}],
		tooltip:{headerFormat: '', pointFormat: '<span style="color:{point.color}"><b>{series.name}</b><br>{point.x:%e %b %H:%M} <br><b>{point.y}</b> %</span>', crosshairs: true,shared: false, useHTML: true},
		plotOptions: { spline: { marker: {enabled: false}}, series: {turboThreshold: 1,animation: false}},
		credits:{enabled: false},legend: {enabled: false },
		series: [{ name: 'Fuellevel', data: dataPoints,  color:'#8AC543',step: 'left'  },
				 { name: 'AdBlue',    data: dataPoints2, color:'#266CAB',step: 'left'  }
				]
	});
}
function DrawTripFuelUsage(){
	var dataPoints = [];
	var markerDate = [];
	dataPoints.push([ new Date(markers[0].createdDateTime), 0]);
	for ( var t=0; t < markers.length; ++t ){
		if (markers[t].engineTotalFuelUsed>0){	
			markerDate.push([markers[t].createdDateTime,markers[t].engineTotalFuelUsed,markers[t].hrTotalVehicleDistance]);
		}
	}
	for ( var y=1; y < markerDate.length; ++y ){
		var $fuelused=markerDate[y][1]-markerDate[y-1][1];
		var $distance=markerDate[y][2]-markerDate[y-1][2];
		var $fuelusage=100/($distance/$fuelused);
		dataPoints.push([ new Date(markerDate[y][0]), parseFloat($fuelusage)]);
	}
	dataPoints.push([ new Date(markers[t-1].createdDateTime), 0]);

	Highcharts.chart('TripFuelUsage',{
		chart: { type: 'spline', zoomType: 'x', marginLeft:120, marginRight: 40,styledMode: true },
		title: { text: 'Fuel Economy',margin: 0 },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] },  startOnTick: true,     endOnTick: true},
		yAxis: { title: { text:"FuelUsage L/100 km"  },tickInterval: 10, min: 0, max:100  },
		tooltip: {headerFormat: '',	pointFormat: '{point.x:%e %b %Y <br>%H:%M}: <b>{point.y:.2f}</b> L/100km',crosshairs: true,shared: true,},
		plotOptions: { column: {pointPadding: 0.2,  pointWidth: 5, borderWidth: 0},spline: { marker: {enabled: true}}, series: {turboThreshold: 1,animation: false}},
		credits:{enabled: false},
		legend: {enabled: false },
		series: [{ name: 'FuelUsage L/100 km', data: dataPoints }]
	});
}
function DrawTripWeight(){
	var dataPoints = [];
	dataPoints.push([ new Date(markers[0].createdDateTime), 0]);
	for ( var t=0; t < markers.length; ++t ){
		if (markers[t].grossCombinationVehicleWeight<65000 && markers[t].grossCombinationVehicleWeight> 0){
			dataPoints.push([ new Date(markers[t].createdDateTime), parseFloat(markers[t].grossCombinationVehicleWeight)]);			
		}
	}
	dataPoints.push([ new Date(markers[t-1].createdDateTime), 0]);

	Highcharts.chart('TripWeight',{
		chart: { type: 'line', zoomType: 'x',marginLeft: 120, marginRight: 40,styledMode: true },
		title: { text: 'Vehicle Weight',margin: 0 },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] }},
		yAxis: { title: { text:"Weight in KG"  },tickInterval: 10000, min: 8000, max:60000  },
		tooltip: {headerFormat: '',	
				  pointFormat: '<span style="color:{point.color}">{point.x:%e %b %H:%M} <br><b>{point.y}</b> kg</span>',
				  crosshairs: true,shared: true,},
		plotOptions: {spline: { marker: {enabled: true}}, series: {turboThreshold: 1,animation: false, fillColor: '#ddd'}},
		colors: ['#FC5F01'],
		credits:{enabled: false},
		legend: {enabled: false },
		series: [{ name: 'Vehicle weight in kg', data: dataPoints, step: 'left'  }]
	});
}
function setTTColor($s){
	$color="6";
	if ($s=='YELLOW'){$color="4";}
	if ($s=='INFO'){$color="0";}
	if ($s=='OFF'){$color="9";}
	if ($s=='RED'){$color="5";}
	return $color;
}

function DrawTellTaleGraph(markers){
	var TTData = [];
	var PB={};var FL={};var ABL={};var EMI={};var EO={};var ECT={};var EEF={};$counter=0;
		PB.x= Date.parse(markers[0].createdDateTime+' UTC');PB.x2 ='';PB.y= 0;PB.status= 'off';PB.colorIndex= '9';
		FL.x= Date.parse(markers[0].createdDateTime+' UTC');FL.x2 ='';FL.y= 1;FL.status= 'off';FL.colorIndex= '9';
		ABL.x=Date.parse(markers[0].createdDateTime+' UTC');ABL.x2='';ABL.y=2;ABL.status='off';ABL.colorIndex='9';
		EMI.x=Date.parse(markers[0].createdDateTime+' UTC');EMI.x2='';EMI.y=3;EMI.status='off';EMI.colorIndex='9';
		EO.x= Date.parse(markers[0].createdDateTime+' UTC');EO.x2 ='';EO.y= 4;EO.status= 'off';EO.colorIndex= '9';
		ECT.x=Date.parse(markers[0].createdDateTime+' UTC');ECT.x2='';ECT.y=5;ECT.status='off';ECT.colorIndex='9';
		EEF.x=Date.parse(markers[0].createdDateTime+' UTC');EEF.x2='';EEF.y=6;EEF.status='off';EEF.colorIndex='9';
	let $ED=Date.parse(markers[markers.length-1].createdDateTime+' UTC');
	for ( var t=0; t < markers.length; ++t ){
		$STR=markers[t].triggerType;
		if ($STR=='TELL_TALE'){
			$counter++;
			var $str=markers[t].triggerInfo;
			var $n = $str.indexOf("->");
			var $tale = $str.substring(0, $n);
			var $state =$str.substring($n+2,$str.length);
			var $D=Date.parse(markers[t].createdDateTime+' UTC');
			if ($tale=="PARKING_BRAKE" ){
				if (PB.status!=$state){
					PB.x2=$D;TTData.push(PB);
					if (t<markers.length){PB={};PB.x=$D;PB.x2='';PB.y=0;PB.status=$state;PB.colorIndex=setTTColor($state);}
				}
			}
			if ($tale=="FUEL_LEVEL" ){
				if (FL.status!=$state){
					FL.x2=$D;TTData.push(FL);
					if (t<markers.length){	FL={};FL.x=$D;FL.x2='';FL.y=1;FL.status=$state;FL.colorIndex=setTTColor($state);	}
				}
			}
			if ($tale=="ENGINE_MIL_INDICATOR" ){
				if (EMI.status!=$state){
					EMI.x2=$D;TTData.push(EMI);
					if (t<markers.length){EMI={};EMI.x=$D;EMI.x2='';EMI.y=3;EMI.status=$state;EMI.colorIndex=setTTColor($state);	}
				}
			}
			if ($tale=="ENGINE_EMISSION_FAILURE" ){
				if (EEF.status!=$state){
					EEF.x2=$D;TTData.push(EEF);
					if (t<markers.length){
						EEF={};EEF.x=$D;EEF.x2='';EEF.y=6;EEF.status=$state;EEF.colorIndex=setTTColor($state);
					}			
				}
			}
		}
	}
	if (EEF.x2 == '') { EEF.x2 = $ED;TTData.push(EEF);}
	if (EMI.x2 == '') { EMI.x2 = $ED;TTData.push(EMI);}
	if (FL.x2 == '')  { FL.x2 = $ED;TTData.push(FL);}
	if (PB.x2 == '')  { PB.x2 = $ED;TTData.push(PB);}
	if (ABL.x2 == '') { ABL.x2 = $ED;TTData.push(ABL);}
	if (EO.x2 == '') { EO.x2 = $ED;TTData.push(EO);}
	if (ECT.x2 == '') { ECT.x2 = $ED;TTData.push(ECT);}
	Highcharts.chart('TripTT', {
		chart: 	{zoomType: 'x', marginTop: 0,marginLeft:40, marginRight: 10,styledMode: true, type:'xrange' },
		title: 	{text: '',margin: 0 },
		xAxis: 	{type: 'datetime',dateTimeLabelFormats: {hour: '%H:00',minute: '%H:%M' },title: { text: ''},tickInterval: 1800000,labels: {autoRotation: [-30, -40] }},
		yAxis: 	{title: {text: ''},categories: ['PB','FL','ABL','EMI','EO','ECT','EEF' ],reversed: true },
		tooltip:{xDateFormat: '%H:%M:%S',headerFormat: '{point.x} - {point.x2}',pointFormat: '<br/>Status : <b>{point.status}</b>',crosshairs: false,shared: false},
		credits:{enabled: false},legend:{enabled: false},
		series: [{borderRadius:5,borderWidth:0,pointWidth: 10,data: TTData}]
	});
}

function ReadVehicleAlerts(SV,SD,SDE){
	if (SV==''){
		var SelectedVIN = document.getElementById("vehicles").value;}
	else {var SelectedVIN = SV}
	if (SD==''){var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");} else {var SelectedDate=SD}
	if (SDE==''){var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");} else {var SelectedDateEnd=SDE}
	$TellTales=[];
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicleAlerts.php?id='+SelectedVIN+'&StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',
		type: "GET", 
		success:function(data){
			$.each(data, function(key, val){		
				$TellTales.push(val);
			})
			if ($TellTales.length>0){
				ShowTellTaleTable($TellTales);
			}
		}
	});	
}


function ReadGroupTrips(){
	UpdateCounter("","DetailsTableHeaderPH");UpdateCounter("","TripsTableHeaderPH");
	UpdateCounter("","TripReport");UpdateCounter("","TripsTableHeader");
	ShowPlaceholder('FleetTripReport','graph');
	ShowPlaceholder('FleetFuelReport','graph');
	ShowPlaceholder('FleetSumReport','sumreport');
	ShowPlaceholder('TripsTableHeaderPH','table');

	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd H:MM");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd 23:59");
	SaveSelectedReportDate();
	var SD=new Date(document.getElementById("SelectDate").value);
	var SDE=new Date(document.getElementById("SelectDateEnd").value);
//	var Difference_In_Days = SDE.diff(SD, 'days') 
	var $VSInfo = $('#TripReport');
	$VSInfo.html("<div class='loader' id='tableloader'></div>");
	var $TFU=0;var $TD=0;$TCO2=0;var $TNT=0;$TFE=0;$counter=0;$Tcounter=0;$Str='';
	markers=[];
	$counter=0;
	$.ajax({
		url: window.location.origin+'/scripts/GetTripSummary.php?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',
		type: "GET", 
		success:function(markers){
			if (markers.length>0){
				$VSInfo.html('');
				ShowTripSumTable(markers);
				FleetTripData();
			}
			else {
				$VSInfo.html('');
				$VSInfo.append("<div class='notice-danger' ><strong>No trip data found !</div>");
				UpdateCounter("","DetailsTableHeaderPH");UpdateCounter("","TripsTableHeaderPH");
				UpdateCounter("","TripReport");UpdateCounter("","TripsTableHeader");UpdateCounter("","FleetSumReport");UpdateCounter("","FleetTripReport");UpdateCounter("","FleetFuelReport");
				Notiflix.Notify.warning('No trip information found for the selected period !');
			}
		}
	});	
}
function ShowTripsTable($vehicleID,$name) {
	var $TInfo = $('#TripsDetailsTable');$TInfo.html('');
	var $SumInfo = $('#DetailsTableHeaderPH');
	var $Str='<div class="card card-body border-0 pt-0 mt-2">';
	$Str+='		<div class="page-title" >';
	$Str+='			<div class="mr-auto">TripDetails for selected vehicle <span class="text-primary"><b><u>'+$name+'</u></b></span></div>';
	$Str+='		</div>';
	$Str+='		<table id = "TripsDetailsTable" class = "display table noWrap pageResize " width = "100%"></table>';
	$Str+='	</div>';
	$SumInfo.html($Str);
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd H:MM");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd H:MM");	
	var table =$('#TripsDetailsTable').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetTripsDetails.php?id='+$vehicleID+'&StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		oLanguage: {sProcessing: ""}, processing: true,paging: true,pageResize: true,"bLengthChange": false,lengthMenu: [[5,-1],[5,"All"]],
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true, 
		buttons: {
			buttons: [
				{ extend: 'excel', 	className: 'btn btn-sm',title: 'rFMS_reader Groups_Overview' },
				{ extend: 'pdf', 	className: 'btn btn-sm', orientation: 'landscape', pageSize: 'A4', messageTop: 'Groups Overview',title: 'rFMS_reader Groups_Overview'},
				{ extend: 'csv', text: 'CSV', className: 'btn btn-sm', extension: '.csv', fieldSeparator: ';', },
				{ extend: 'print', 	className: 'btn btn-sm' },
			]
		},		
		columns: [
			{ "data": "customerVehicleName","title":"vehicle",				"visible":false,"defaultContent": "-"},
			{ "data": "StartDate", 			"title":"Start Time UTC",						"defaultContent": "-"},
            { "data": "EndDate",			"title":"End Trip UTC",							"defaultContent": "-"},
			{ "data": "start_odometer",		"title":"Start Odo-meter",		"visible":false,"defaultContent": "-"},
			{ "data": "end_odometer",		"title":"End Odo-meter",		"visible":false,"defaultContent": "-"},
            { "data": "Driver",				"title":"Driver",								"defaultContent": "-"},
            { "data": "Driver2ID",			"title":"Driver-2",				"visible":false,"defaultContent": "-"},
            { "data": "Duration",			"title":"Duration (hh:mm)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "DriveTime",			"title":"DriveTime",			"visible":true, "defaultContent": "-", "className":"dt-center"},
            { "data": "IdleTime",			"title":"IdleTime",				"visible":true,"defaultContent": "-", "className":"dt-center"},
            { "data": "PreDepIdle",			"title":"Idle before trip",						"defaultContent": "-", "className":"dt-center"},
            { "data": "Distance",			"title":"Distance (km)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsed",			"title":"Fuel Used (L)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "CO2_emission",		"title":"CO2 (kg/l)",							"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsage",			"title":"Fuel Usage (L/100km)",					"defaultContent": "-", "className":"dt-center"},
			{ "data": "AverageSpeed",		"title":"Average Speed",		"visible":false, "defaultContent": "-", "className":"dt-center"},
            { "data": "CounterT_E",			"title":"Calc Timer Count",		"visible":false,"defaultContent": "-", "className":"dt-right"},
            { "data": "CounterT",			"title":"Actual Timer Count",	"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "VIN", 				"title":"",						"visible":false, "defaultContent": "-"},
            { "data": "TripReliability",	"title":"Reliability in %",		"visible":false,	"defaultContent": "-", "className":"dt-right"},
			{ "data": "CruiseControlTime" ,	"title":"CC-Time",				"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "CruiseControleDistance","title":"CC-Distance",		"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "RetarderTime",		"title":"Retarder Time",		"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "RetarderDistance",	"title":"Retarder Distance",	"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "AverageDrivingSpeed","title":"Average Driving Speed","visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "HighestDrivingSpeed","title":"Highest Driving Speed","visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "start_latitude",		"title":"Start Lat",			"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "start_longitude",	"title":"Start Lng",			"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "end_latitude",		"title":"End Lat",				"visible":false,"defaultContent": "-", "className":"dt-right"},
			{ "data": "end_longitude",		"title":"End lng",				"visible":false,"defaultContent": "-", "className":"dt-right"},

        ]
    } );
	$('#TripsDetailsTable').off('click');  
	$('#TripsDetailsTable').on('click', 'tr', function () {
        var data = table.row( this ).data();
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		$('#EditTripModal').modal('show');
		ShowTripMap(data.Trip_NO);
    });
};
function ShowTableVehicleTrips($data) {
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd H:MM");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd H:MM");
	var table =$('#TripTable').DataTable( {data: $data,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		oLanguage: {sProcessing: "<div id='tableloader'></div>"}, processing: true,paging: true,pageResize: true,searching:false,lengthMenu: [[5,-1],[5,"All"]],
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,"bLengthChange": false,
		buttons: {buttons: [	]},
		columns: [
			{ "data": "StartDate", 			"title":"Start Time UTC",						"defaultContent": "-","width":"15%"},
			{ "data": "Driver",				"title":"Driver",								"defaultContent": "-","width":"15%"},
            { "data": "Duration",			"title":"Duration (hh:mm)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "DriveTime",			"title":"DriveTime",			"visible":true, "defaultContent": "-", "className":"dt-center"},
            { "data": "IdleTime",			"title":"IdleTime",				"visible":true,"defaultContent": "-", "className":"dt-center"},
            { "data": "PreDepIdle",			"title":"Idle before trip",		"visible":false,"defaultContent": "-", "className":"dt-center"},
            { "data": "Distance",			"title":"Distance (km)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsed",			"title":"Fuel Used (L)",						"defaultContent": "-", "className":"dt-center"},
            { "data": "CO2_emission",		"title":"CO2 (kg/l)",			"visible":false,"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsage",			"title":"Fuel Usage (L/100km)",					"defaultContent": "-", "className":"dt-center"},
            { "data": "TripReliability",	"title":"Q",									"defaultContent": "-", "className":"dt-center"},
			{ "data": "EndDate",			"title":"End Trip UTC",							"defaultContent": "-","width":"15%","className":"dt-right"},
		]
    } );
	$('#TripTable').off('click');
	$('#TripTable').on('click', 'tr', function () {
        var data = table.row( this ).data();
		//jQuery.noConflict();
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		ShowTripMap(data['Trip_NO']);

    });
};

function ShowTripSumTable($DataArray) {
	var $HInfo = $('#TripsTableHeaderPH');$HInfo.html('');
	var $Str='<div class="card card-body pt-0 border-0" >';
	$Str+='		<div class="page-title">TripSummary for all vehicles</div>'
	$Str+='		<div class="card border-0 card-body p-0" >';
	$Str+='		<table id="TripsTable" class="display table noWrap pageResize" width="100%"></table></div></div>';
	$HInfo.append($Str);
    var table=$('#TripsTable').DataTable( {
		data: $DataArray,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false, destroy: true, fixedColumns: true, scrollX: true, scrollCollapse: true, order:[[0,'asc' ]], dom: 'Bfltirp',
		lengthMenu: [[5,25,100,-1],[5,25,100,"All"]],"bLengthChange": false,
	    buttons: {buttons: [
			{ 	extend: 'excel',className: 'btn btn-sm',filename: 'rFMSReader_Trip-Data'},
			{	extend: 'pdf', 	className: 'btn btn-sm',orientation: 'landscape',pageSize: 'A3',messageTop: 'Trip-data '},
			{ extend: 'csv', text: 'CSV', className: 'btn btn-sm', extension: '.csv', fieldSeparator: ';', },
			{	extend: 'print',className: 'btn btn-sm',orientation: 'landscape',pageSize: 'A3',messageTop: 'Trip-data '}]
		},
		columns: [
			{ "data": "customerVehicleName","title":"Vehicle",				"defaultContent": "-"},
            { "data": "Distance",			"title":"Distance (km)",		"defaultContent": "-", "className":"dt-center"},
            { "data": "Duration",			"title":"Duration (hh:mm)",		"defaultContent": "-", "className":"dt-center"},
            { "data": "DriveTime",			"title":"DrivingTime",			"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsed",			"title":"Fuel Used (L)",		"defaultContent": "-", "className":"dt-center"},
            { "data": "CO2_emission",		"title":"CO2 (kg/l)",			"defaultContent": "-", "className":"dt-center"},
            { "data": "FuelUsage",			"title":"Fuel Usage (L/100km)",	"defaultContent": "-", "className":"dt-center"},
            { "data": "amountTrips",		"title":"Trips #",				"defaultContent": "-", "className":"dt-center"},
        ]
    } );
	$('#TripsTable').off('click');  
	$('#TripsTable').on('click', 'tr', function (e) {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		e.preventDefault();
		var data = table.row( this ).data();
		ShowTripsTable(data['id'],data['customerVehicleName']);

   } );		
};
