function InitializeDemoMAP(){
	osmUrl='https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png';
	osmAttrib='';
	osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
	HEREGrey = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/reduced.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: '&copy;<a href="http://developer.here.com">HERE</a>',subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',base: 'traffic',maxZoom: 20,type: 'traffictile',language: 'eng',format: 'png8',	size: '256'
	});
		HERE = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/normal.traffic.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: '&copy;<a href="http://developer.here.com">HERE</a>',subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',base: 'traffic',maxZoom: 20,type: 'traffictile',language: 'eng',format: 'png8',	size: '256'
	});
	// Define Layers for Overlays
	rFMS_Demo = 	new L.FeatureGroup();
	map = L.map('map', {center: [46.00,8.65],zoomSnap:0.25,	minZoom: 2,maxZoom: 18,zoom: 5,fadeAnimation: false});
//	map.zoomControl.remove();
	map.addLayer(HEREGrey);
	var baseLayers = {
		"HERE Greyscale": HEREGrey,
		"HERE Standard": HERE,
		"OpenStreetMap": osm,
	};
	var overlays = {};
	L.control.layers(baseLayers, overlays)
	.addTo(map);

}
function UpdateCounter (Counter,Reference){
	var $Info = $('#'+Reference);
	$Info.html('');
	$Info.append(Counter);
}
function ShowSpinnerBig(Reference){
	var $Info = $('#'+Reference);
	$Info.html('');
	$Info.append("<div class='bloader'></div>");
}
function ForceFullScreen(){
	top.resizeTo(window.screen.availWidth, window.screen.availHeight);
	top.moveTo(0,0);
	setTimeout("forceFullScreen()",250);
}
function ShowLatestDemo(){
	var LIcon = L.icon({ iconUrl: '../images/CircleDefault.png', iconSize: [8, 8], iconAnchor: [4, 4], tooltipAnchor: [0,-26]});
	var $counter=0
	var timeleft = 60;
	var downloadTimer = setInterval(function(){
		document.getElementById("progressBar").value = 60 - timeleft;
		timeleft -= 1;
		if(timeleft <= 0)
			clearInterval(downloadTimer);
		}, 1000);
	ShowSpinnerBig("DemoInfo");
	$.ajax({
		url: window.location.origin+'/scripts/GetVehiclesDEMO.php',
		dataType:'JSON',
		success:function(data){ 
			rFMS_Demo.clearLayers();
			$.each(data, function(key, val){
				if (val.La!=0){
					L.marker([val.La, val.Lo],  {
						icon: LIcon,
						draggable: false
					})
					.addTo(rFMS_Demo);
					$counter++;
				}
			});	
			map.addLayer(rFMS_Demo);
			map.fitBounds(rFMS_Demo.getBounds(),{padding: [40,40]});
//			UpdateCounter('<P class="col-12 primary"><center><i class="fas fa-truck primary"></i> - <b>'+$counter+'</b> </p>','DemoInfo');
			UpdateCounter('','DemoInfo');		
		},
	});
	UpdateCounter('<progress value="0" max="58" id="progressBar"></progress>','DemoProgress');
}
