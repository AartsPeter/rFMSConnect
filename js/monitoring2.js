//
// This library is part of the rFMSreader and is free of use
//
// Peter Aarts 2022
//
function LoadMonitorData($search){
	if ($search===undefined){$s='';} else {$s='?id='+$search;}
	$.ajax({
		type: "GET",dataType: "JSON",
		url: window.location.origin+'/scripts/GetAPIMonitorEndPoints.php'+$s,
		success: function(data) {
			$.each(data, function (key, val) {
				ReadPerformanceLog(val);
			});
		}
	});
}
function ReadPerformanceLog($data){
	var $startDT= document.getElementById("SelectDate");
	if (!!$startDT){ $startDT = new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd H:MM");;}
	var $hours	 = document.getElementById("SelectHours");
	if (!!$hours) {$hours=document.getElementById("SelectHours").value} else {$hours=$data.hours;}
	if ($startDT == null)
		{SDE=new Date();SD=new Date();}
	else
		{SDE=new Date($startDT);SD=new Date($startDT);}
	SD.setHours(SD.getHours() - $hours);
	SDE.setHours(SDE.getHours());
	var $endpoint=$data.name_EndPoint;
	ShowPlaceholder('Stat_'+$data.id,'stats');
	ShowPlaceholder('Perf_'+$data.id,'graph');
	ShowPlaceholder('Avai_'+$data.id,'graph');
	$.ajax({
		type: "GET", dataType: "JSON",
		url: window.location.origin + '/scripts/GetPerformanceLog.php?startDate=' + SD.format("yyyy/mm/dd H:MM") + '&endDate=' + SDE.format("yyyy/mm/dd H:MM") + '&Endpoint=' + $endpoint,
		success: function (data2) {
			UpdateCounter('', 'Stat_' + $data.id);
			UpdateCounter('', 'Perf_' + $data.id);
			UpdateCounter('', 'Avai_' + $data.id);
			CountStats(data2, 'Stat_' + $data.id, $endpoint, $hours);
			Endpoint_Monitor_Graph(data2, 'Perf_' + $data.id, $hours);
		},
		error: function () {
			UpdateCounter('loading failed', 'Stat_' + $data.id);
			UpdateCounter('loading failed', 'Perf_' + $data.id);
			UpdateCounter('loading failed', 'Avai_' + $data.id);
		}
	});
}

function ReadBackLog($hours,$field,$startDT){
	if ($startDT==null){
		var SDE=new Date();
		var SD=new Date();}
	else {
		var SDE=new Date($startDT);
		var SD=new Date($startDT);
	}
    SD.setHours(SD.getHours() - $hours);
	ShowPlaceholder('BackLogStats'+$field,'');
	ShowPlaceholder('BackLogGraph'+$field,'');
	SDE.setHours(SDE.getHours() + 2 );
	$.ajax({
		type: "GET",dataType: "JSON",
		url: window.location.origin+'/scripts/GetBacklog.php?startDate='+SD.format("yyyy-mm-dd H:MM")+'&endDate='+SDE.format("yyyy-mm-dd H:MM")+'&Endpoint='+$field,
		success: function(data) {
			BackLogData(data,$field,$hours);
			ShowBackLogStats(data,$field);
		},
		error:function(){
			UpdateCounter('','BackLogStats'+$field);UpdateCounter('','BackLogGraph'+$field);
		}		
	});
}	
function CountStats($Array,$field,$filter,$hours) {
	var $AmountRequest=0;
	var $OkRequest=0;
	var $NOkRequest=0;var $NOData=0;
	var Response=0;$AR_KPI=" gray";$A_KPI=" gray";
	var $AR=0;
	var $AV=0;
	for (var i = 0; i <= $Array.length-1; i++) {
		if ($Array[i].Endpoint==$filter){
			$AmountRequest++;		
			if ($Array[i].Status==200||$Array[i].Status==429){$OkRequest++;Response=Response +parseFloat($Array[i].rt);}
			else if ($Array[i].Status==888){$NOData++;Response=Response +parseFloat($Array[i].rt);}
			else {$NOkRequest++;}
			$AV=($OkRequest/$AmountRequest)*100;
			$AR=Response/$OkRequest;
		}
	}
	if ($AR>15){$AR_KPI="text-danger";}
	else if ($AR>5&&$AR<=15){$AR_KPI="text-warning";}
	else {$AR_KPI="text-success";}
	if ($AV<99){$A_KPI="text-danger";}
	else if ($AV>99&&$AV<=99.7){$A_KPI="text-warning";}
	else {$A_KPI="text-success";}
	var $SInfo = $('#'+$field);
	$SInfo.html('');
	$SInfo.append(
	'<div class="row"><div class="col-12 d-flex">'+
 	'<div class="col-6 text-left">'+
		'<div class="dark">'+
			'<label>amount requests</label>'+
			'<h4><b>'+$AmountRequest.toFixed(0)+'</b></h4>'+
		'</div>'+
	'</div>'+
	'<div class="col-6 text-right">'+
		'<div class="dark">'+
			'<label>errors / No Data</label>'+
			'<h4><b>'+$NOkRequest.toFixed(0)+' / '+$NOData.toFixed(0)+'</b></h4>'+
		'</div>'+
	'</div></div></div>'+
	'<div class="row"><div class="col-12 d-flex">'+
	'<div class="col-6 text-left">'+
		'<div class="'+$A_KPI+'">'+
			'<label>availability</label>'+
			'<h4><b>'+$AV.toFixed(2)+' % </b></H4>'+
		'</div>'+
	'</div>'+
	'<div class="col-6 text-right">'+
		'<div class="'+$AR_KPI+'">'+
			'<label>av. response</label>'+
			'<h4><b>'+$AR.toFixed(2)+' s </b></h4>'+
		'</div>'+
	'</div></div></div>');
}
function ShowBackLogStats($Array,$field) {
	var $SInfo = $('#BackLogStats'+$field);	
	$SInfo.html('');
	var $AmountRequest=0;
	var $AR_KPI='';	var $DR_KPI='';
	for (var i = 0; i <= $Array.length-1; i++) {
		$AmountRequest++;		
		$backlog=((new Date($Array[i].requestDateTime)-new Date($Array[i].receivedDateTime))/1000)/60;
		$databacklog=((new Date($Array[i].receivedDateTime)-new Date($Array[i].messageDateTime))/1000)/60;
		var $AR=($backlog / $Array.length) * $Array.length;
		var $DR=($databacklog / $Array.length) * $Array.length;
	}
//	var $AR=($Response/$AmountRequest);
	if ($AR>6){$AR_KPI="red-kpi";}	
	else if ($AR>5.5&&$AR<=6){$AR_KPI="orange-kpi";}
	else {$AR_KPI="green-kpi";}
	if ($DR>6){$DR_KPI="red-kpi";}	
	else if ($DR>5.5&&$DR<=6){$AR_KPI="orange-kpi";}
	else {$DR_KPI="green-kpi";}
	var RequestDate = new Date($Array[0].requestDateTime);
	var MessageDate = new Date($Array[0].messageDateTime);
	var ReceivedDate = new Date($Array[0].receivedDateTime);
	// API Backlog calculation 
	var $APIbacklog = ((RequestDate.getTime() - ReceivedDate.getTime()) / 1000) /3600;
	var $APIbacklog10 = ((new Date($Array[10].requestDateTime).getTime() - new Date($Array[10].receivedDateTime).getTime()) / 1000) /3600;
	if ($APIbacklog10<$APIbacklog){$APIProgress='<i class="fas fa-arrow-up text-danger"></i>'} else {$APIProgress='<i class="fas fa-arrow-down text-success"></i>'}
	if ($APIbacklog>0.10){$A_KPI="text-danger";}
	else if ($APIbacklog<=0.03&&$APIbacklog>0.08){$A_KPI="text-warning";}
	else {$A_KPI="text-success";}
	// M2M data Backlog calculation 
	var $Databacklog = ((ReceivedDate.getTime() - MessageDate.getTime()) / 1000) /3600;
	var $Databacklog10 = ((new Date($Array[10].receivedDateTime).getTime() - new Date($Array[10].messageDateTime).getTime()) / 1000) /3600;
	if ($Databacklog10<$Databacklog){$DataProgress='<i class="fas fa-arrow-up text-danger"></i>'} else {$DataProgress='<i class="fas fa-arrow-down text-success"></i>'}
	if ($Databacklog>0.10){$D_KPI="text-danger";}
	else if ($Databacklog<=0.03&&$Databacklog>0.08){$D_KPI="text-warning";}
	else {$D_KPI="text-success";}
	$SInfo.append(
	'<div class="row">'+
		'<div class="col-12 d-flex ">'+
			'<div class="col-6 text-left">'+
				'<div class="'+$A_KPI+'">'+
					'<label>API Average </label>'+
					'<h4><b>'+$Array[0].backlog+'  '+$APIProgress+'</b><br>'+
				'</div>'+
			'</div>'+
			'<div class="col-6 text-right">'+
				'<div class="'+$AR_KPI+'">'+
					'<label>API Backlog</label>'+
					'<h4><b>'+$AR.toFixed(2)+' s</b></h4>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="col-12 d-flex ">'+
			'<div class="col-6 text-left">'+
				'<div class="'+$D_KPI+'">'+
					'<label>M2M Average</label>'+
					'<h4><b>'+$Array[0].databacklog+'  '+$DataProgress+'</b></h4>'+
				'</div>'+
			'</div>'+
			'<div class="col-6 text-right">'+
				'<label>M2M Backlog </label>'+
				'<div class="'+$DR_KPI+'"><h4><b>'+$DR.toFixed(2)+' s</b></h4></div>'+
			'</div>'+
		'</div>'+
	'</div>');
	$str ='<div class="d-flex col-12 align-items-end text-left small ">';
	$str+='<div class=""><B>API </B>: difference in timestamp API-request and received datetime of leatest object</div>';
	$str+='<div class=""><b>M2M </b>: difference in timestamp created and received datetime of leatest object</div>';
	$str+='</div>';
	UpdateCounter($str,'BackLogExplain');
}

function Endpoint_Monitor_Graph(markers,$field) {
	var $dataPoints = [];var $dataPoints1 = [];
	$.each(markers, function(key, val){
		if (val.Status==200 || val.Status==429|| val.Status==888 ) { $dataPoints.push({ 'x':Date.parse(val.DateTime),y:parseInt(100),lineColor:3,colorIndex:'2'});	}
		else	{ $dataPoints.push({ x:Date.parse(val.DateTime),y:parseInt(.8),lineColor:"#F00",colorIndex:"#F00"} ) ;	}
		if (val.Status==200)				{ $dataPoints1.push({ x:Date.parse(val.DateTime) , y:parseFloat(val.rt)	} ); }
		if (val.Status==888)				{ $dataPoints1.push({ x:Date.parse(val.DateTime) , y:parseFloat(0.5) 	} ); }
		if (val.Status==0 ||val.Status==500){ $dataPoints1.push({ x:Date.parse(val.DateTime) , y:parseFloat(-0.1) 	} ); }
	});
	Highcharts.chart($field, {
		chart: 	{ zoomType: 'x', styledMode: true,marginTop:10, marginBottom:30 ,marginLeft: 10, marginRight: 10 },
		xAxis: 	{ type: 'datetime', visible: true  },
		yAxis: [{ title: '', visible: false, tickInterval: 1, min: 0, max:100, labels: { enabled: false}  },{title: '',tickInterval: 1, min: 0,  visible: false}],
		legend:	{ enabled: false},
		credits:{ enabled: false},
		title: 	{ text: '' },
		tooltip:{ crosshairs: true,useHTML:true,shared:true,
			headerFormat:'<span style="color:{series.colorIndex}"><small>{point.x:%Y-%m-%d %H:%M:%S}</small></span>',
			pointFormat: '<br><span style="color:var(--primary)">{series.name} : <b>{point.y}</b></span>',
		},
		plotOptions: {
			series:  { turboThreshold: 10000,animation: false,dataLabels:{enabled:false,rotation: 0,x:10,y:-20,allowOverlap: true}},
			line:	 { marker: {enabled: true, symbol: 'circle', radius: 2, states: {hover: {enabled: true} } } },
			area:    { fillOpacity: 0.5}
		},
		series:[
			{ data: $dataPoints 	,type:'area',name:'availability',yAxis:0 ,step:true,colorIndex:'2'},
			{ data: $dataPoints1 	,type:'line',name:'seconds',	 yAxis:1 ,colorIndex:'0'}
		]
	});
}
function BackLogData(markers,$field,$hours) {
	var dataPoints = [];var dataPoints1 = [];
	dataPoints.push([ new Date(markers[0].requestDateTime), 0]);
	for ( var t=0; t < markers.length; ++t ){
		dataPoints.push([ new Date(markers[t].requestDateTime), round(new Date('1970-01-01T' + markers[t].databacklog + 'Z').getTime()/1000/60,2)]);
		dataPoints1.push([ new Date(markers[t].requestDateTime), round(new Date('1970-01-01T' + markers[t].backlog + 'Z').getTime()/1000/60,2)]);
	}
	Highcharts.setOptions({time: {timezone: 'Europe/Amsterdam' }});
	Highcharts.chart('BackLogGraph'+$field,{
		chart: { type: 'line',zoomType: 'x',styledMode: true},
		title: { text: ''},
		xAxis: { type: 'datetime', dateTimeLabelFormats: {hour: '%H:00',minute:'%H:%M' },title: { text: ''},tickInterval: 3600,labels: {autoRotation: [-30, -40] }},
		yAxis: { labels: {enabled:true},title:'',tickInterval: 1, min: 0, max:40  },
		tooltip:{headerFormat: '<span style="color:{series.colorIndex}">{point.x:%Y-%m-%d %H:%M:%S}</span>',
				 pointFormat: '<span style="color:{series.colorIndex}">{series.name} </span> </br> <b> {point.y}</b><small> minutes</small>{series.label}',
				 crosshairs: true,useHTML:true,shared:true},
		plotOptions: { line: { marker: {enabled: false}}, series: {turboThreshold: 1,animation: true,}},
		credits:{enabled: false},legend: {enabled: false },
		series: [{name: 'API-BackLog ', data: dataPoints1,colorIndex:"0" },
				 {name: 'M2M-BackLog ', data: dataPoints, colorIndex:"1" }]
	});		
}

function ShowMonitor($search){
	if ($search===undefined){$s='';} else {$s='?id='+$search;}
	$.ajax({
		type: "GET",dataType: "JSON",
		url: window.location.origin+'/scripts/GetAPICollectors.php'+$s,
		success: function(data) {
			BuildMonitor(data,"MonitorBuilder");
			LoadMonitorData($search);
		},
		error:function(){}
	});
//	ReadBackLog($h,'DAF-rFMS1',$D);
}

function BuildMonitor($data,$id) {
	var $MInfo = $('#' + $id);
	$MInfo.html('');
	var $a = "active";
	if ($data.length>1) {
		var $tabdata = '<div class="tab-content card border p-3">';
		var $tabstr = '<ul class = "nav nav-tabs pl-3">';
		$.each($data, function (key, val) {
			if (val.active == "1") {
				$tabstr += '<li class="nav-item"> <a class="nav-link ' + $a + '" href="#TAB' + val.id + '" data-toggle="tab">' + val.name + '</a></li>';
				$tabdata += '	<div class="tab-pane show ' + $a + '" id="TAB' + val.id + '" role="tabpanel">';
				$tabdata += '		<div class="row " id="_tabdata' + val.id + '">';
				$tabdata += '		</div>';
				$tabdata += '	</div>';
				$a = '';
			}
		});
		$tabdata += '</div>';
		$tabstr += '</ul>';
		$MInfo.append($tabstr);
		$MInfo.append($tabdata);
	}
	else {
		var $tabdata = '<div class="p-2 ">';
		$tabdata += '		<div class="row " id="_tabdata' + $data[0].id + '">';
		$tabdata += '		</div>';
		$MInfo.append($tabdata);
	}
	$.each($data, function (key, val) {
		if (val.active == "1") {
			$.ajax({
				type: "GET", dataType: "JSON",
				url: window.location.origin + '/scripts/GetAPIScheduler.php?id=' + val.id,
				success: function (data) {
					var $TInfo = $('#_tabdata' + val.id);
					$TInfo.html('');
					var $tabdata = '';
					$.each(data, function (key, $val) {
						if ($val.active == "1") {
							$tabdata += '			<div class="col col-xl my-3 apigraphdiv ">';
							$tabdata += '				<div class="card apigraph">';
							$tabdata += '					<div class="card-header">' + $val.name_EndPoint + '</div>';
							$tabdata += '					<div class="card-body p-0 px-1">';
							$tabdata += '						<div id="Stat_' + $val.id + '" class="rFMSStats col-12 ">' + $val.id + '</div>';
							$tabdata += '						<div id="Perf_' + $val.id + '" class="rFMSGraph col-12 ">' + $val.id + '</div>';
							$tabdata += '					</div>';
							$tabdata += '				</div>';
							$tabdata += '			</div>';
						}
					});
					$TInfo.append($tabdata);
				},
				error: function () {}
			});
		}
	});
}

