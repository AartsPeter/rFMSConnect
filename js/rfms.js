// definition of Global variables
var GM=[]; 			//Global markers
var $SelectedGroup='';

const deviceType = () => {
	const ua = navigator.userAgent;
	if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
		return "tablet";
	}
	else if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
		return "mobile";
	}
	return "desktop";
}
function typeWriter($anchor,$text) {
	var i = 0;
	if ($text ==null){ $text = 'get informed, be in control';}
	var speed = 50;
	if (i < $text.length) {
		$location=$anchor;
		document.getElementById($location).innerHTML += $text.charAt(i);
		i++;
		setTimeout(typeWriter, speed);
	}
}
function convertUTCDateToLocalDate(date) {
	const d = new Date();
	let diff = d.getTimezoneOffset();		
	date.setTime(date.getTime() - diff * 60 * 1000);
	return date;
}
function c_ToLocalDate($date) {
	const d = new Date();
	let diff = d.getTimezoneOffset();
	$date=new Date($date);
	$date.setTime($date.getTime() - diff * 60 * 1000);
	return Date.parse($date);
}
function isEven(value) {
    return !(value % 2)
}
function selectElement(id, valueToSelect) {
	let element = document.getElementById(id);
	element.value = valueToSelect;
}
function wait(ms){
	var start = new Date().getTime();
	var end = start;
	while(end < start + ms) {
		end = new Date().getTime();
	}
}
var DateSelectOptions={
	format: 'yyyy/mm/dd',
	todayBtn: true,
	clearBtn:true,
	orientation: "bottom auto",
	calendarWeeks: true,
	autoclose: true,
	todayHighlight: true,
	toggleActive: true
};
$(function () { $('.input-group.date').datepicker(DateSelectOptions);});

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
function ToggleCSSFooter($status){
	let root = getComputedStyle(document.body);
	var $ufh= root.getPropertyValue('--usedfooterheight');
	var $efh= root.getPropertyValue('--emptyfooterheight');
	if ($status=='1') {document.documentElement.style.setProperty ('--footerspace', $ufh);}
	else {document.documentElement.style.setProperty ('--footerspace',$efh);}	
}

function download(content, fileName, contentType) {
    var a = document.createElement("a");
    var file = new Blob([content], {type: contentType});
    a.href = URL.createObjectURL(file);
    a.download = fileName;
    a.click();
}
function distance(lat1, lon1, lat2, lon2) {
  var p = 0.017453292519943295;    // Math.PI / 180
  var c = Math.cos;
  var a = 0.5 - c((lat2 - lat1) * p)/2 +
          c(lat1 * p) * c(lat2 * p) *
          (1 - c((lon2 - lon1) * p))/2;
  return 12742 * Math.asin(Math.sqrt(a)); // 2 * R; R = 6371 km
}

function round(value, decimals) {
  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function TimeToSeconds(str) {
    var p = str.split(':'),
        s = 0, m = 1;

    while (p.length > 0) {
        s += m * parseInt(p.pop(), 10);
        m *= 60;
    }
    return s;
}
function SecondsToTime(seconds) {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  //const s = Math.round(seconds % 60);
  return [
    h > 9 ? h :  '0'+h,
    m > 9 ? m : (h ? '0' + m : m || '0'),
//    s > 9 ? s : '0' + s
  ].filter(Boolean).join(':');
}

function DateDiff(var1,var2){
	return parseInt((new Date(var2) -new Date(var1)) / (1000 * 60 * 60 * 24), 10);
}

var dateFormat = function () {
        var    token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            timezoneClip = /[^-+\dA-Z]/g,
            pad = function (val, len) {
                val = String(val);
                len = len || 2;
                while (val.length < len) val = "0" + val;
                return val;
            };

        // Regexes and supporting functions are cached through closure
        return function (date, mask, utc) {
            var dF = dateFormat;

            // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
            if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
                mask = date;
                date = undefined;
            }

            // Passing date through Date applies Date.parse, if necessary
            date = date ? new Date(date) : new Date;
            if (isNaN(date)) throw SyntaxError("invalid date");

            mask = String(dF.masks[mask] || mask || dF.masks["default"]);

            // Allow setting the utc argument via the mask
            if (mask.slice(0, 4) == "UTC:") {
                mask = mask.slice(4);
                utc = true;
            }

            var    _ = utc ? "getUTC" : "get",
                d = date[_ + "Date"](),
                D = date[_ + "Day"](),
                m = date[_ + "Month"](),
                y = date[_ + "FullYear"](),
                H = date[_ + "Hours"](),
                M = date[_ + "Minutes"](),
                s = date[_ + "Seconds"](),
                L = date[_ + "Milliseconds"](),
                o = utc ? 0 : date.getTimezoneOffset(),
                flags = {
                    d:    d,
                    dd:   pad(d),
                    ddd:  dF.i18n.dayNames[D],
                    dddd: dF.i18n.dayNames[D + 7],
                    m:    m + 1,
                    mm:   pad(m + 1),
                    mmm:  dF.i18n.monthNames[m],
                    mmmm: dF.i18n.monthNames[m + 12],
                    yy:   String(y).slice(2),
                    yyyy: y,
                    h:    H % 12 || 12,
                    hh:   pad(H % 12 || 12),
                    H:    H,
                    HH:   pad(H),
                    M:    M,
                    MM:   pad(M),
                    s:    s,
                    ss:   pad(s),
                    l:    pad(L, 3),
                    L:    pad(L > 99 ? Math.round(L / 10) : L),
                    t:    H < 12 ? "a"  : "p",
                    tt:   H < 12 ? "am" : "pm",
                    T:    H < 12 ? "A"  : "P",
                    TT:   H < 12 ? "AM" : "PM",
                    Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                    o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                    S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
                };

            return mask.replace(token, function ($0) {
                return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
            });
        };
    }();
    // Some common format strings
    dateFormat.masks = {
        "default":      "ddd mmm dd yyyy HH:MM:ss",
        shortDate:      "m/d/yy",
        mediumDate:     "mmm d, yyyy",
        longDate:       "mmmm d, yyyy",
        fullDate:       "dddd, mmmm d, yyyy",
        shortTime:      "h:MM TT",
        mediumTime:     "h:MM:ss TT",
        longTime:       "h:MM:ss TT Z",
        isoDate:        "yyyy-mm-dd",
        isoTime:        "HH:MM:ss",
        isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
        isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
    };

    // Internationalization strings
    dateFormat.i18n = {
        dayNames: [
            "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
            "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
        ],
        monthNames: [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ]
    };
    // For convenience...
    Date.prototype.format = function (mask, utc) {
        return dateFormat(this, mask, utc);
    };

function ChatDate(val,list){
	let $date = new Date(val);
	return moment($date).calendar(null,{
		lastDay : '[Yesterday]',
		sameDay : 'HH:mm',
		nextDay : '[Tomorrow]',
		lastWeek : '[last] dddd',
		nextWeek : 'dddd',
		sameElse : 'DD MMM YYYY'	}
	)
}

function ShowVehicleInfo(Search,MapType){
	var $LInfo = $('#VehicleDetails');
	var $str='';var $LP = '';
	$LInfo.html('');
	openVehDet();
	rFMS_Trip.clearLayers();
	rFMS_Trigger.clearLayers();
	rFMS_Markers.clearLayers();
	ShowLatestLayer();
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicleDetails?id='+Search,
		dataType:'JSON',
		success: function (data) {
			if (MapType!= 'a') {$str+='<div class="vehicleDetailsCard h-100">';	}
			$str+='	<div class="card-header sticky border-bottom">';
			if (!!data[0].LicensePlate) {
				if (data[0].LicensePlate != data[0].customerVehicleName) {
					$LP = '<BR><div class="VehicleListInfo ml-auto "></b> <small>' + data[0].LicensePlate + '</small></div>';
				}
			}
			$str+='<div class="vehicleCard "><div class="larger"><strong>'+ data[0].customerVehicleName +'</strong></div> '+$LP+'</div>';
			if (data[0].TrailerName!=undefined){
				$str+='<div class="vehicleCard "><div class=""><strong>'+data[0].TrailerName+'</strong></div></div> ';
			}
			$str+='		<button class=" closebtn close" onclick="closeVehDet();"><span class="" aria-hidden = "true"><i class="far fa-times "></i></span></button>';
			$str+='	</div>';
			$str+='<div class = "nav-tabs-custom " id = "PDCTabs">';
			$str+='	<ul class = "nav nav-tabs border-bottom">';
			$str+='		<li class = "nav-item"><a class = "nav-link active" href = "#PDCTAB1" id="" data-toggle="tab" title="vehicle data"><i class="fad fa-truck"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB2" data-toggle = "tab" title="driving time" ><i class="fad fa-user-clock"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB3" data-toggle = "tab" title="trips"><i class="fad fa-route"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB4" data-toggle = "tab" title="damages"><i class="fad fa-tools"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB5" data-toggle = "tab" title="trailer"><i class="fad fa-trailer"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB6" data-toggle = "tab" title="Tire Pressure Monitoring" ><i class="fad fa-tire-pressure-warning"></i></a></li>';
			$str+='		<li class = "nav-item"><a class = "nav-link" href = "#PDCTAB7" data-toggle = "tab" title="vehicle data" ><i class="fad fa-draw-polygon fa-fw " ></i></a></li>';
			$str+='	</ul>';
			$str+='</div>';
			$str+='<div class="tab-content">';
			$str+='	<div class="tab-pane active" id="PDCTAB1" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB2" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB3" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB4" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB5" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB6" role="tabpanel"></div>';
			$str+='	<div class="tab-pane " id="PDCTAB7" role="tabpanel"></div>';
			$str+='</div>';
			$LInfo.append($str);

			if (MapType!= 'a') {
				ShowMapVehicleData(data,'PDCTAB1');
				ReadMapVehicleTrips(Search, 'PDCTAB3');
				LatestVehicleDriver(data[0],'PDCTAB2');
				ShowMapVehicleDamages(Search,'PDCTAB4');
				map.setView([data[0].last_Latitude, data[0].last_Longitude], 15);
			}
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your application-session has expired... <br><br>returning to login-page!');
		}
	});

}
function ShowVehicleListMap(){
	var info = $('#VehicleList');
	var txt='                            <div class="VehicleFilter dropdown p-2">\n' +
		'                                    <div class=" col-12 px-1 text-left collapsed" href="#" id="VehicleFilter" role="button" data-toggle="collapse" data-target="#collapseMapFilter" aria-expanded="false" aria-controls="collapseMapFilter">\n' +
		'                                        <div class="d-flex">\n' +
		'                                            <form class="d-none d-sm-inline-block  mr-auto  mw-100 p-0">\n' +
		'                                                <div class="input-group">\n' +
		'                                                    <input type="text" class="form-control small" placeholder="Search for..." aria-label="Search"  id=\'filtername\' onfocus="this.value=\'\'" placeholder="search..." onClick="document.getElementById(\'filtername\').value = \'\'" onKeyUp="ShowLatest();">\n' +
		'                                                    <div class="input-group-append">\n' +
		'                                                        <button class="btn btn-primary" type="button">\n' +
		'                                                            <i class="fas fa-search fa-sm"></i>\n' +
		'                                                        </button>\n' +
		'                                                    </div>\n' +
		'                                                </div>\n' +
		'                                            </form>\n' +
		'                                            <div class="btn btn-primary ml-1 text-right"><i class="fas fa-filter"></i></div>\n' +
		'                                        </div>\n' +
		'                                    </div>\n' +
		'                                    <div class="collapse " id="collapseMapFilter">\n' +
		'                                        <div class="card border shadow m-1 p-0 pt-2">\n' +
		'                                            <div class="col-12 "><b>Total Vehicles</b> [ <b><span id="CTTO"></span></b> ]</div>\n' +
		'                                            <div class="col-12 pl-0 ">\n' +
		'                                                <div class="col-12">\n' +
		'                                                    <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                        <input type=\'checkbox\' class="custom-control-input" id=\'driving\' name=\'filter\' onclick=\'ShowLatest();\'  value=\'Driving\' checked="checked">\n' +
		'                                                        <label class="custom-control-label secondary" for="driving"><i class="fas fa-play fa-fw "></i> Driving [ <b><span  id="CTDR"></span></b> ]</label>\n' +
		'                                                    </div>\n' +
		'                                                </div>\n' +
		'                                                <div class="col-12">\n' +
		'                                                    <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                        <input type=\'checkbox\' class="custom-control-input" id=\'idle\' name=\'filter\' onclick=\'ShowLatest();\'  value=\'Pause\' checked="checked">\n' +
		'                                                        <label class="custom-control-label secondary" for="idle"><i class="fas fa-pause fa-fw "></i> Idle [ <b><span  id="CTID"></span></b> ]</label>\n' +
		'                                                    </div>\n' +
		'                                                </div>\n' +
		'                                                <div class="col-12">\n' +
		'                                                    <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                        <input type=\'checkbox\' class="custom-control-input" id=\'stopped\' name=\'filter\' onclick=\'ShowLatest();\'  value=\'Stoped\' checked="checked">\n' +
		'                                                        <label class="custom-control-label secondary" for="stopped"><i class="fas fa-stop fa-fw "></i> Stopped [ <b><span  id="CTST"></span></b> ]</label>\n' +
		'                                                    </div>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="dropdown-divider"></div>\n' +
		'                                            <div class="col-12">\n' +
		'                                                <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                    <input type=\'checkbox\' class="custom-control-input" id=\'alert\' name=\'filter\' onclick=\'ShowLatest();\' value=\'Alert\'   checked>\n' +
		'                                                    <label class="custom-control-label" for="alert"><i class="fas fa-exclamation fa-fw danger"></i> Alert ( <b><span id="CTAL"></span></b> )</label>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="col-12">\n' +
		'                                                <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                    <input type=\'checkbox\' class="custom-control-input" id=\'NoGPS\' name=\'filter\' onclick=\'ShowLatest();\' value=\'NoGPS\' >\n' +
		'                                                    <label class="custom-control-label" for="NoGPS"><i class="far fa-question-circle fa-fw warning"></i> No location ( <b><span id="CTNL"></span></b> )</label>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="col-12">\n' +
		'                                                <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                    <input type=\'checkbox\' class="custom-control-input" id=\'Delayed\' name=\'filter\' onclick=\'ShowLatest();\' value=\'Delayed\'   checked>\n' +
		'                                                    <label class="custom-control-label" for="Delayed"><i class="fas fa-history fa-fw warning"></i> Delayed ( <b><span id="CTDE"></span></b> )</label>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="col-12">\n' +
		'                                                <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                    <input type=\'checkbox\' class="custom-control-input" id=\'Geofence\' name=\'filter\' onclick=\'ShowLatest();\' value=\'Geofence\'   >\n' +
		'                                                    <label class="custom-control-label" for="Geofence"><i class="fas fa-fw fa-draw-polygon warning"></i> Geofence ( <b><span id="CTGE"></span></b> )</label>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="dropdown-divider"></div>\n' +
		'                                            <div class="col-12">\n' +
		'                                                <div class="custom-control custom-switch custom-switch-sm">\n' +
		'                                                    <input type=\'checkbox\' class="custom-control-input" id=\'Today\' name=\'filter\' onclick=\'ShowLatest();\' value=\'Today\' >\n' +
		'                                                    <label class="custom-control-label" for="Today"><i class="fa fa-fw"></i> Driving Today ( <b><span id="CTDT"></span></b> )</label>\n' +
		'                                                </div>\n' +
		'                                            </div>\n' +
		'                                            <div class="ml-auto">\n' +
		'                                                <a class="btn text-primary" data-toggle="collapse" data-target="#collapseMapFilter">close <i class="fas fa-chevron-up"></i></a>\n' +
		'                                            </div>\n' +
		'                                        </div>\n' +
		'                                    </div>\n' +
		'                                </div>\n' +
		'                                <div class="VehicleList " id="VehicleInfoD">';
}

function ShowVehicleCard(val,Reference){
	var $LInfo = $('#'+Reference);
	var $str='';var $status='';var $extra='';
	if (val.status.alert==true)		{}
	if (val.status.driving==true)	{ $status = '<i class="fas fa-play fa-fw " ></i>';$title = 'Driving';}
	if (val.status.paused==true)	{ $status = '<i class="fas fa-pause gray fa-fw" ></i>';$extra = 'alert- ';$title = 'Work / Paused';}
	if (val.status.stopped==true)	{ $status = '<i class="fas fa-stop gray fa-fw " ></i>';$title = 'Rest / Stopped';}
	if (val.DamageCount>0)			{ $extra  = 'alert-danger ';}
	if (val.TrailerName==undefined)	{ $trailer='<i class="fad fa-truck fa-fw fa-flip-horizontal" ></i><i class="fad fa-fw"></i>'; } else { $trailer = '<i class="fad fa-truck fa-fw fa-flip-horizontal" ></i><i class="fad fa-trailer fa-fw fa-flip-horizontal" ></i><i class="fad fa-fw"></i>'}
	ShowLatestLayer();
	$str+='<div class="col-12 col-md-6 col-lg-4 mb-3">';
	$str+='	<a onclick="map.flyTo(['+val.last_Latitude+','+val.last_Longitude+'],13);" class="DashTile pointer" title="click to show on map">';
	$str+='	<div class="card p-0 shadow-sm">';
	$str+='		<div class="card-header  d-flex text-primary '+$extra+'"><div>'+$trailer+''+val.customerVehicleName +'</div><div class="ml-auto" title ="'+$title+'"> '+$status+'</div></div>';
	$str+='		<div class="card-body p-1 ">';
	$str+='			<div class="col-12">';
	if (!!val.Driver)
		{$str+='			<div class="StatusLine text-primary"><i class="fad fa-user fa-fw " ></i><i class="fad fa-fw"></i><b>'+val.Driver+'</b> [ '+val.RemainingDriveToday+' ] </div>';}
	$str+='			</div>';
	$str+='			<div class="col-12 d-flex StatusLine">';
	if (!!val.LicensePlate)
		{$str+='			<div class="StatusLine mr-3"><span class="VehicleLicensePlate">'+val.LicensePlate+'</span></div>';}
	$str +='<div class="ml-auto">';
	if (Count_warning(val)>0){
		$str+='				<div class="mr-1 "><i class="fad fa-exclamation-triangle fa-fw text-danger" title=" Display Warnings "></i></i> <b>'+Count_warning(val)+'</b></div>';}
	if (val.DamageCount>0)
		{$str+='				<div class="mr-1 "><i class="fad fa-tools text-danger fa-fw" title=" Severe damage(s) "></i><b>'+val.DamageCount+'</b></div>';}
	if (val.Maintenance!='hide')
		{$str += '				<div class="mr-1"><i class="fad fa-wrench text-' + val.Maintenance + '" title=" Due for maintenace "></i></div>';}
	$str+='				</div>';
	$str+='			</div>';
	$str+='			<div class="col-12 gray small pt-2" id="VLAddress'+val.id+'"></div>';
	$str+='			<div class="col-12 gray small" id="VLAddress'+val.id+'"><i class="fad fa-clock fa-fw "></i><i class="fa fa-fw "></i>'+ dateFormat(val.LastActivity, "yyyy-mm-dd HH:MM ")+'</div>';
	$str+='		</div>' +
		  '	</a>' +
		  '</div>';
	$LInfo.append($str);
//	Convert2Address([val.last_Latitude, val.last_Longitude,'VLAddress'+val.id]);
}

function ShowMapVehicleData(data,Reference){
	var $LInfo = $('#'+Reference);
	var $str='';
	$str+='	<div class="card-body p-2">';
	$str+='		<div class="card shadow-sm mb-2 p-0">';
	if ($SelectedGroup=='*'){ $str+='			<div class="col-12 StatusLine "><i class="fad fa-fw fa-building fa-fw "></i><i class="fas fa-fw"></i> <span> '+data[0].name +'</b></span></div>';}
	$str+='		<div class="card-title">vehicle data</div>';
	$str+='			<div class="d-flex StatusLine col-12 ">';
	$str+='				<div class="col-12 p-0 " xmlns="http://www.w3.org/1999/html"><i class="fad fa-truck fa-fw"></i><i class="fas fa-fw"></i> '+data[0].brand+' - '+data[0].model+' <small>('+data[0].emissionLevel+')</small></div>';
	$str+='			</div>';
	$str+='			<div class="d-flex StatusLine col-12 ">';
	$str+='				<div class="col-6 p-0" title="odo-meter"><i class="fad fa-tachometer-alt fa-fw"></i><i class="fas fa-fw"></i> '+(data[0].OdoMeter/1000).toFixed(0)+'</b> km</div>';
	$str+='				<div class="ml-auto p-0" title="vehicle weight">'+ data[0].grossCombinationVehicleWeight +' kg <i class="fas fa-fw"></i><i class="fad fa-weight-hanging fa-fw"></i></div>';
	$str+='			</div>';
	$str+='			<div class="d-flex StatusLine col-12">';
	$str+='				<div class="col-6 p-0 " title="fuel level"><i class="fad fa-gas-pump fa-fw  "></i><i class="fas fa-fw"></i> '+data[0].FuelLevel+'</b> %</div>';
	$str+='				<div class="ml-auto p-0" title="adblue level">'+ data[0].CatalystFuelLevel +' % <i class="fas fa-fw"></i><i class="fad fa-gas-pump fa-fw text-primary"></i></div>';
	$str+='			</div>';
	$str+='			<div class="d-flex StatusLine col-12">';
	$str+='				<div class="col-6 p-0" title="license plate"><i class="fad fa-fw  "></i><i class="fas fa-fw"></i> '+data[0].LicensePlate+'</b></div>';
	$str+='				<div class="ml-auto p-0" title="current speed"> '+ data[0].currentSpeed +' km/u <i class="fas fa-fw"></i><i class="fad fa-tachometer-fast fa-fw"></i></div>';
	$str+='			</div>';
	$str+='			<div class="d-flex StatusLine col-12">';
	if (Count_warning(data[0])>0){
		$str+='			<div class="col-6 p-0"><i class="fad fa-exclamation-triangle fa-fw text-danger"></i><i class="fas fa-fw"></i> <b>'+Count_warning(data[0])+' warning lamps</b></div>';}
	else {
		$str+='			<div class="col-6 p-0 "><i class="fad fa-exclamation-triangle fa-fw  "></i><i class="fas fa-fw"></i> 0 warning lamps</b></div>';}
	$str+='				<div class="ml-auto p-0 "> '+dateFormat(data[0].LastActivity+' UTC', "yyyy-mm-dd HH:MM ")+' <i class="fas fa-fw"></i><i class="fad fa-history fa-fw"></i></div>';
	$str+='			</div>';
	$str+='		</div>';
	$str+='	<div class="card shadow-sm mb-2 p-0">';
	$str+='		<div class="card-title">last position</div>';
	$str+='		<div class="col-12 pb-3" id="VLAddress"></div>';
	$str+='	</div>';
	$LInfo.append($str);
	Convert2Address([data[0].last_Latitude, data[0].last_Longitude,'VLAddress']);
}
function ShowMapVehicleDamages(Search,Reference){
	var $LInfo = $('#'+Reference);
	$LInfo.append('	<div class="card-body p-2"><div class="card shadow-sm mb-3 pb-3 p-0" ><div class="card-title">PDC Damages </div><div class="p-3 small"><table class="display table" id="VehicleDamage"></table></div></div>');
	var table=$('#VehicleDamage').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetVehicleDamages?id='+Search,dataSrc: ''	},
		createdRow: function( row, data, dataIndex){if( data.repairStatus == 0){$(row).addClass('alert-danger');}	},
		lengthMenu: [[10, -1], [10, "All"]],autoWidth: false,destroy:true,order:[[6,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: true, footer:false,scrollCollapse: true,paging: true,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "createdDateTime",	"title":"date",			"class":"dt-left", width: "15%",visible:false},
			{ data: "severity",			"title":"",				"class":"dt-left",
				"render": function(data, type, row) {
					if(data == '0') {return '<i class="fas fa-exclamation-triangle fa-fw text-danger"></i>';}
					else {return '<i class="fas fa-clipboard-list fa-fw text-warning"></i>';}}
			},
			{ data: "cat_name",			"title":"category",		"class":"dt-left"},
			{ data: "subcat_name",		"title":"subcategory",	"class":"dt-left"},
			{ data: "description",		"title":"description",	"class":"dt-left",visible:false},
			{ data: "driver",			"title":"driver",		"class":"dt-left",visible:false},
			{data: "repairStatus", 	"title": "", 	"class": "dt-right", visible: true,
				"render": function (data, type, row) {if (data == '1') {return '<i class="fas fa-check text-success"></i>';} else {return '<i class="fas fa-tools text-danger fa-fw"></i>';}	}
			}
		],
	} );
	$('#VehicleDamage tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}

function ShowVehicleRD(Search,$a){
	var table=$('#VehicleDamage').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetVehicleDamages?id='+Search,dataSrc: ''	},
		createdRow: function( row, data, dataIndex){if( data.repairStatus == 0){$(row).addClass('alert-danger');}	},
		lengthMenu: [[10, -1], [10, "All"]],autoWidth: false,destroy:true,order:[[6,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: true, footer:false,scrollCollapse: true,paging: true,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "createdDateTime",	"title":"date",			"class":"dt-left", width: "15%"},
			{ data: "severity",			"title":"",				"class":"dt-left", width : "2%",
				"render": function(data, type, row) {
					if(data == '0') {return '<i class="fas fa-exclamation-triangle fa-fw text-danger"></i>';}
					else {return '<i class="fas fa-clipboard-list fa-fw text-warning"></i>';}}
			},
			{ data: "cat_name",			"title":"category",		"class":"dt-left", width: "15%"},
			{ data: "subcat_name",		"title":"subcategory",	"class":"dt-left", width: "15%"},
			{ data: "description",		"title":"description",	"class":"dt-left"				},
			{ data: "driver",			"title":"driver",		"class":"dt-left"},
			{data: "repairStatus", 	"title": "repaired", 	"class": "dt-left", width: "5%", visible: true,
				"render": function (data, type, row) {if (data == '1') {return '<i class="fas fa-check text-success"></i>';} else {return '<i class="fas fa-tools text-danger fa-fw"></i>';}	}
			}
		],
	} );
	$('#VehicleDamage tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowDriverRD(Search){
	var table=$('#tableDriverDamages').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetDriverDamages?id='+Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},"lengthMenu": [[10, -1], [10, "All"]],
		autoWidth: false,destroy:true,order:[[6,'desc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: false,bInfo:false,
		buttons: {buttons: []},
		createdRow: function( row, data, dataIndex){if( data.repairStatus == 0){$(row).addClass('alert-danger');}	},
		columns: [
			{ data: "createdDateTime",	"title":"date",			"class":"dt-left", width: "15%"},
			{ data: "severity",			"title":"",				"class":"dt-left", width : "2%",
				"render": function(data, type, row) {
					if(data == '0') {return '<i class="fas fa-exclamation-triangle fa-fw text-danger"></i>';}
					else {return '<i class="fas fa-clipboard-list fa-fw text-warning"></i>';}}
			},
			{ data: "cat_name",			"title":"category",		"class":"dt-left", width: "15%"},
			{ data: "subcat_name",		"title":"subcategory",	"class":"dt-left", width: "15%"},
			{ data: "description",		"title":"description",	"class":"dt-left"				},
			{ data: "vin",				"title":"vehicle",		"class":"dt-left"},
			{data: "repairStatus", 		"title":"repaired", 	"class": "dt-left", width: "5%", visible: true,
				"render": function (data, type, row) {if (data == '1') {return '<i class="fas fa-check text-success"></i>';} else {return '<i class="fas fa-tools text-danger fa-fw"></i>';}	}
			}
		],
	} );
}
function ShowTrailerRD(Search,$a){
	var table=$('#tableTrailerDamages').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetTrailerDamages?id='+Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},"lengthMenu": [[10, -1], [10, "All"]],
		autoWidth: false,destroy:true,order:[[6,'desc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: false,bInfo:false,
		buttons: {buttons: []},
		createdRow: function( row, data, dataIndex){if( data.repairStatus == 0){$(row).addClass('alert-danger');}	},
		columns: [
			{ data: "createdDateTime",	"title":"date",			"class":"dt-left", width: "15%"},
			{ data: "severity",			"title":"",				"class":"dt-left", width : "2%",
				"render": function(data, type, row) {
					if(data == '0') {return '<i class="fas fa-exclamation-triangle fa-fw text-danger"></i>';}
					else {return '<i class="fas fa-clipboard-list fa-fw text-warning"></i>';}}
			},
			{ data: "cat_name",			"title":"category",		"class":"dt-left", width: "15%"},
			{ data: "subcat_name",		"title":"subcategory",	"class":"dt-left", width: "15%"},
			{ data: "description",		"title":"description",	"class":"dt-left"				},
			{ data: "vin",				"title":"vehicle",		"class":"dt-left"},
			{data: "repairStatus", 		"title":"repaired", 	"class": "dt-left", width: "5%", visible: true,
				"render": function (data, type, row) {if (data == '1') {return '<i class="fas fa-check text-success"></i>';} else {return '<i class="fas fa-tools text-danger fa-fw"></i>';}	}
			}
		],
	} );
}
function ShowTrailerActivity($Search){
	var table=$('#tableDriverActivity').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetTrailerActivity?id='+$Search,dataSrc: ''	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		ordering: false, footer:false,scrollCollapse: true,paging: false,
		buttons: {buttons: []},
		columns: [
			{ data: "date",					"title":"date",		"class":"dt-left"},
			{ data: "customerVehicleName",	"title":"vehicle",	"class":"dt-center"},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="vehicle_edit?id='+row.VehicleId+'" class="btn "><i class="fad fa-truck"><i></a>'}},
			{ data: "driver",				"title":"driver",	"class":"dt-center"},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="driver_edit?id='+row.DriverId+'" class="btn "><i class="fad fa-user"><i></a>'}},
			{ data: "amountTrips",			"title":"#trips",	"class":"dt-center"},
			{ data: "Distance",				"title":"distance",	"class":"dt-center"},
			{ data: "Duration",				"title":"duration",	"class":"dt-center"},
			{ data: "DriveTime",			"title":"driving",	"class":"dt-center"},
			{ data: "FuelUsage",			"title":"fuel usage","class":"dt-center"},
			{ data: "FuelUsed",				"title":"fuel used","class":"dt-center"},
			]
	} );
}
function ShowDriverActivity($Search){
	if ($Search==null){$Search='';} else {$Search='?id='+$Search;}
	var table=$('#tableDriverActivity').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetDriverActivity'+$Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: false,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "date",					"title":"date",		"class":"dt-left"},
			{ "title":"vehicle",	"bSortable" : false,mRender: function (data, type, row) { if(!row.customerVehicleName) {return ''} else {return '<a href="vehicle_edit?id='+row.VehicleId+'" class="underline "> '+row.customerVehicleName+' </a>'}}},
			{ data: "has_PDC",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-check rest" title="PreDeparture vehicle Check executed"></i>';} else {return ' - ';}},
				"title":"PDC","visible":true,"width": "2%","class":"dt-center" },
			{ data: "amountTrips",			"title":"trips",	"class":"dt-center"},
			{ data: "Distance",				"title":"distance",	"class":"dt-center"},
			{ data: "Duration",				"title":"duration",	"class":"dt-center"},
			{ data: "DriveTime",			"title":"driving",	"class":"dt-center"},
			{ data: "FuelUsage",			"title":"fuel usage","class":"dt-center"},
			{ data: "FuelUsed",				"title":"fuel used","class":"dt-center"},

			]
	} );
}
function ShowVehicleActivity($Search){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table=$('#tableVehicleActivity').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetVehicleActivity?id='+$Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: true,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "date",			"title":"date",		"class":"dt-left"},
			{ data: "driver", "title":"Driver","bSortable" : false,mRender: function (data, type, row) {  if(!row.driver) {return ''} else { return '<a href="driver_edit?id='+row.DriverId+'" class="underline">'+row.driver+' </a>'}}},
			{ data: "has_PDC",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-check rest" title="PreDeparture vehicleCheck executed"></i>';} else {return ' - ';}},
				"title":"PDC","visible":true,"width": "2%","class":"dt-center" },
			{ data: "amountTrips",	"title":"#trips",	"class":"dt-center"},
			{ data: "Distance",		"title":"distance",	"class":"dt-center"},
			{ data: "Duration",		"title":"duration",	"class":"dt-center"},
			{ data: "DriveTime",	"title":"driving",	"class":"dt-center"},
			{ data: "FuelUsage",	"title":"fuel usage","class":"dt-center"},
			{ data: "FuelUsed",		"title":"fuel used","class":"dt-center"},

			]
	} );
}
function ShowVehicleGeofences($Search){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table=$('#tableVehicleGeofence').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetVehicleGeofence?id='+$Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: true,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "name",			"title":"name",		"class":"dt-left"},
			{ data: "status",		"title":"status",	"class":"dt-center"},
			{ data: "prio",			"title":"prio",		"class":"dt-center"},
			{ data: "alert",		"title":"alert",	"class":"dt-center"},
			{ data: "registration",	"title":"trigger",	"class":"dt-center"},

			]
	} );
}
function ShowVehicleMaintenanceHistory($Search){
	table=$('#tableVehicleMaintenance').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/getVehicleMaintenanceReports?id='+$Search,dataSrc: ''	},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		ordering: false, footer:false,scrollCollapse: true,paging: false,bInfo:false,
		buttons: {buttons: []},
		columns: [
			{ data: "createdDate",			"title":"date",		"class":"dt-left"},
			{ data: "actual_vehicle_milage","title":"odo-meter","class":"dt-left"},
			{ data: "dealer",				"title":"dealer",	"class":"dt-left"},
			{ data: "description",			"title":"info",		"class":"dt-left"},
			{ data: "damages",				"title":"repairs",	"class":"dt-center"},
			{ data: "checked",
                    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-check rest"></i>';} else {return ' - ';}},
					"title":"Checked","visible":true,"width": "2%","class":"dt-center" },
			]
	} );
}
function ShowVehicleMOTStatus($Search){	
	var l=new Date();
	$.ajax({
		url: window.location.origin+'/scripts/getVehicleMOTStatus?id='+$Search,
		dataType:'JSON',
		success: function (data) {
			$.each(data, function(key, val){
				var nsd=new Date(val.next_service_date);var trd=new Date(val.tachograph_revoke_date);
				var nsd_d=(l.getTime() - nsd.getTime())/ (1000 * 3600 * 24);
				var trd_d=(l.getTime() - trd.getTime())/ (1000 * 3600 * 24);
				if (nsd_d > 30){ var $s1 = '<span class="text-danger">'+val.next_service_date+"</span>";	} else { var $s1 = val.next_service_date;}
				if (trd_d > 30){ var $s2 = '<span class="text-danger">'+val.tachograph_revoke_date+"</span>";	} else { var $s2 = val.tachograph_revoke_date;}
				UpdateCounter($s1,'next_service_date');
				UpdateCounter($s2,'tachograph_revoke_date');				
			})
		}
	});

}
function LatestVehicleDriver(val,Reference){
	var $LInfo = $('#'+Reference);
	var $str='';
	$str+='	<div class="card-body p-2"><div class="card shadow-sm  p-0" ><div class="card-title">driver 1</div><div id="'+Reference+'_content"></div></div>';
	$LInfo.append($str);
	var $LInfo = $('#'+Reference+'_content');
	if (!!val.Driver1_ID) {
		var $str='';
		$str+='		<div class="panel-body">';
		$str+='			<div class="d-flex StatusLine col-12 ">';
		$str+='				<div class="col-12 p-0" id="Driver1" ></div>';
		$str+='			</div>';
		$str+='			<div class="px-3"><div id="DTM1"></div><div id="TripDT1" class="_rFMSDriverGraph w-100 pb-2"></div></div>';
		$str+='		</div>';
		$LInfo.html($str);
//		ShowPlaceholder ('Driver1','single');
		ReadDriverDetails(val.DriverId, 'Driver1', 'DTM1', 'TripDT1');
//		ShowDriveTime(val.DriverId, 'TripDT1', 'DTM1');
		ShowDriverTachoStatus(val.Driver1_ID,'TripDT1');
	}
	if (!!val.Driver2_ID) {
		var $LInfo = $('#'+Reference);
		var $str='';
		$str+='	<div class="card-body p-2"><div class="card shadow-sm  p-0" ><div class="card-title">driver 2</div><div id="'+Reference+'_content2"></div></div>';
		$LInfo.append($str);
		var $LInfo = $('#'+Reference+'_content2');
		$str='		<div class="panel-body">';
		$str+='			<div class="d-flex StatusLine col-12 ">';
		$str+='				<div class="col-12 p-0" id="Driver2" ></div>';
		$str+='			</div>';
		$str+='			<div class="px-3"><div id="DTM2"></div><div id="TripDT2" class="_rFMSDriverGraph w-100 pb-2"></div></div>';
		$str+='		</div>';
		$LInfo.html($str);
//		ShowPlaceholder ('Driver2','single');
		ReadDriverDetails(val.DriverId2,'Driver2','DTM2','TripDT2');
//		ShowDriveTime(val.DriverId2,'TripDT2','DTM2');
		ShowDriverTachoStatus(val.Driver2_ID,'TripDT2');
	}
}

function seconds2time (seconds) {
	seconds = seconds/1000;
    var hours   = Math.floor(seconds / 3600);
	var days	= Math.floor(hours / 24);
    var minutes = Math.floor((seconds - (hours * 3600)) / 60);
    var seconds = round((seconds/1000),2) - (hours * 3600) - (minutes * 60);
    var time = "";

	if (hours != 0) {
      time += hours+":";
    }
	else{
      time += "0:";
    }
    if (minutes != 0 || time !== "") {
      minutes = (minutes < 10 && time !== "") ? "0"+minutes : String(minutes);
      time += minutes;
    }
//    if (time === "") {
 //     time = seconds+"s";
 //   }
 //   else {
  //    time += (seconds < 10) ? "0"+seconds : String(seconds);
   // }
    return time;
}

function InitializeMAP(){
	var x = window.location.href;
	var filename = x.split('/').pop();
	filename=filename.toLowerCase();
	// fix for gaps between tiles
	if (!L.TileLayer.NoGap) {
		L.TileLayer.NoGap = L.TileLayer;
	}
	//end

	osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	osmAttrib=' <a href="http://openstreetmap.org">OpenStreetMap</a>';
	osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
	HERETruck = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/normal.traffic.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: '<a href="http://developer.here.com">&copy; HERE</a>',subdomains: '1234',mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',	base: 'base',maxZoom: 20,type: 'trucktile',	language: 'dut',format: 'png',size: '256'
	});
	HERESatelite = L.tileLayer('https://{s}.{base}.maps.api.here.com/maptile/2.1/{type}/{mapID}/hybrid.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: '<a href="http://developer.here.com">&copy; HERE</a>',	subdomains: '1234',mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',	app_code:'z2HsXPK48vhNPwyfcFr5ew',	base: 'aerial',	maxZoom: 20,type: 'trucktile',language: 'eng',	format: 'png',size: '256'
	});
	HERE = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/normal.traffic.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg=eng', {
		attribution: '<a href="http://developer.here.com">&copy; HERE</a>',subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',base: 'traffic',maxZoom: 20,type: 'traffictile',language: 'eng',format: 'png8',	size: '256'
	});
	HEREGrey = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/reduced.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: '<a href="http://developer.here.com">&copy; HERE</a>',subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',base: 'base',maxZoom: 20,type: 'maptile',language: 'eng',format: 'png8',	size: '256'
	});

	TomTom = L.tileLayer('https://{s}.api.tomtom.com/map/1/tile/basic/{style}/{z}/{x}/{y}.{ext}?key={apikey}&language={language}', {
		attribution: '<a href="https://tomtom.com" target="_blank">&copy; TomTom</a>',subdomains: 'abcd',apikey: 'qlmH59sLZa3TDpqBwQRxFh4wRNz0zpuw',style: 'main',maxZoom: 22,language: 'en-GB',ext: 'png',	size: '256'
	});
	TomTomTraffic = L.tileLayer('https://{s}.api.tomtom.com/traffic/map/4/tile/flow/{style}/{z}/{x}/{y}.{ext}?key={apikey}', {
		attribution: '<a href="https://tomtom.com" target="_blank">&copy; TomTom</a>',subdomains: 'abcd',apikey: 'qlmH59sLZa3TDpqBwQRxFh4wRNz0zpuw',style: 'absolute',maxZoom: 22,language: 'eng',ext: 'png',	size: '256'
	});
	TomTomIncidents = L.tileLayer('https://{s}.api.tomtom.com/traffic/map/4/tile/incidents/{style}/{z}/{x}/{y}.{ext}?key={apikey}', {
		attribution: '<a href="https://tomtom.com" target="_blank">&copy; TomTom</a>',subdomains: 'abcd',apikey: 'qlmH59sLZa3TDpqBwQRxFh4wRNz0zpuw',style: 's3',maxZoom: 22,language: 'eng',ext: 'png',	size: '256'
	});

	// Define Layers for Overlays
	var overlays={};
	rFMS_Markers = 	new L.FeatureGroup();
	rFMS_Demo = 	new L.FeatureGroup();
	rFMS_Trip = 	new L.FeatureGroup();
	rFMS_Trigger = 	new L.FeatureGroup();
	rFMS_Latest = 	new L.MarkerClusterGroup({spiderfyOnMaxZoom: true,showCoverageOnHover: false,zoomToBoundsOnClick: true});
	rFMS_Dealers = 	new L.MarkerClusterGroup({spiderfyOnMaxZoom: true,showCoverageOnHover: true,zoomToBoundsOnClick: true});
	rFMS_Trip_Delay = new L.FeatureGroup();
	map = L.map('map', {center: [46.00,8.65],zoomSnap:0.25,	minZoom: 2,maxZoom: 18,zoom: 5,fadeAnimation: false,zoomAnimation: false});
	map.addLayer(TomTom);
	var baseLayers = {
		"HERE Traffic": HERE,
		"HERE Truck": HERETruck,
		"HERE Greyscale": HEREGrey,
		"HERE Satellite" :HERESatelite,
		"TomTom" :TomTom,
		"OpenStreetMap": osm,
	};
	if (filename != 'manage_geofences'){
		overlays = {
			"TomTom Traffic": TomTomTraffic ,
			"TomTom Incidents": TomTomIncidents ,
			"Vehicles"	: rFMS_Latest,
			"Trip"	 	: rFMS_Trip,
			"Dealers"	: rFMS_Dealers
		};
	} else {
		overlays = {};

	}
	LayerControl = L.control.layers(baseLayers, overlays,{sortLayers:true,position:'topleft'}).addTo(map);
}

function ShowDBMarkers(markers){
	var TimerIcon = 	L.icon({ iconUrl: '../images/timer.png', 		iconSize: [14, 14], iconAnchor: [7, 7], 	tooltipAnchor: [0,-10]})
	var TimerDIcon= 	L.icon({ iconUrl: '../images/timerdelayed.png',	iconSize: [16, 16], iconAnchor: [8, 8], 	tooltipAnchor: [0,-26]})
	var OtherIcon = 	L.icon({ iconUrl: '../images/circlegreen.png', 	iconSize: [16, 16], iconAnchor: [8, 8],  	tooltipAnchor: [0,-26]})
	var StartIcon = 	L.icon({ iconUrl: '../images/StartMarker.png', 	iconSize: [, 36 ], 	iconAnchor: [18, 36],  	tooltipAnchor: [0,-26]})
	var EndIcon = 		L.icon({ iconUrl: '../images/EndMarker.png',	iconSize: [, 36 ], 	iconAnchor: [18, 36],  	tooltipAnchor: [0,-26]})
	var TripTimerIcon =	L.icon({ iconUrl: '../images/triparrow.png', 	iconSize: [16, 16], iconAnchor: [8, 8], 	tooltipAnchor: [0, -8]})
	var WarningIcon = 	L.icon({ iconUrl: '../images/TellTale.png',	 	iconSize: [20, 20 ],iconAnchor: [10, 10],  	tooltipAnchor: [0,-10]})
	for ( var i=0; i < markers.length; ++i ){
		$str='';$icon=TimerIcon;str='';$str1='';$CardColor="bg-primary";	$txtcolor="text-light"
		var str=markers[i].triggerInfo;
		if (markers[i].GNSS_latitude!=0){
			var localDate = new Date(markers[i].createdDateTime);
			var $Delay = (Date.parse(markers[i].receivedDateTime)-Date.parse(markers[i].createdDateTime));
			var $DelayTime = $Delay/1000/60;
			if (markers[i].triggerType=="TELL_TALE"){
				if (str.includes("RED"))	{$icon=WarningIcon;$CardColor="bg-danger"; $txtcolor='text-light';markers[i].triggerInfo=str.replace('->RED',' <i class="fad fa-engine-warning text-danger"></i> ')	}
				if (str.includes("YELLOW"))	{$icon=WarningIcon;$CardColor="bg-warning text-primary";$txtcolor='text-light';	}
				$str1=' <BR><span class="'+$txtcolor+'"><b> '+markers[i].triggerInfo+' </b></span>';
			}
			if (markers[i].triggerType=="DISTANCE_TRAVELLED")
				{ $icon=TripTimerIcon; }
			if (markers[i].triggerType=="DRIVER_1_WORKING_STATE_CHANGED")
				{ $str1='<BR><span class="'+$txtcolor+'"><b> '+markers[i].driver1Id_WSC+' </b></span>';
				markers[i].triggerType="TachoStatus"; }
			$str+='<div class=" card border-0 shadow"><div class="card-header '+$CardColor+' '+$txtcolor+' p-2 py-1 "><b>'+markers[i].triggerType+'</b>'+$str1+'</div><div class="card-body p-2">';
			$str+='<div class="TooltipLine"><i class="fad fa-clock tooltipicon"></i> '+dateFormat(localDate, "dd-mm-yyyy hh:MM")+'</div>';
			$str+='<div class="TooltipLine"><i class="fad fa-digital-tachograph tooltipicon"></i> '+round(markers[i].hrTotalVehicleDistance/1000,0)+' km</div>';
			$str+='<div class="TooltipLine"><i class="fad fa-tachometer-alt tooltipicon"></i> '+markers[i].wheelBasedSpeed+' km/u</div>';
			if ($DelayTime >= 15) {
				$str+='<div class="TooltipLine text-danger "><i class="fad fa-history tooltipicon text-danger"></i><strong> '+seconds2time($Delay)+' </strong>hh:mm</div>';
				$icon=TimerDIcon;
			}
			L.marker( [markers[i].GNSS_latitude, markers[i].GNSS_longitude], {rotationAngle: markers[i].GNSS_heading,icon:$icon,draggable: false	} )
			.bindTooltip($str, { direction: 'top',opacity:1.0})
			.addTo(rFMS_Markers);
		}
	}
	map.addLayer(rFMS_Markers);
	map.removeLayer(rFMS_Trip);
	map.removeLayer(rFMS_Trip_Delay);
	map.addLayer(rFMS_Trigger);
	map.invalidateSize(true);
	map.fitBounds(rFMS_Markers.getBounds(), {padding: [0, 50]});
}
function ShowTripLayer(){
	map.removeLayer(rFMS_Latest);
	map.removeLayer(rFMS_Markers);
	map.addLayer(rFMS_Trip);
}
function ShowMarkerLayer(){
	map.removeLayer(rFMS_Latest);
	map.addLayer(rFMS_Markers);
	map.removeLayer(rFMS_Trip);
}
function ShowLatestLayer(){
	map.removeLayer(rFMS_Markers);
	map.removeLayer(rFMS_Trip);
	map.addLayer(rFMS_Latest);
}
function ShowDBTrips(markers){
	var PolyArray = [];
//	map.scrollWheelZoom.disable();
	var TripTimerIcon = L.icon({ iconUrl: '../images/triparrow.png', iconSize: [16, 16], iconAnchor: [8, 8], popupAnchor: [0,-8]})
	var StartIcon = L.icon({ iconUrl: '../images/StartMarker.png', iconSize: [, 32 ], iconAnchor: [16, 32],  tooltipAnchor: [0, -36]})
	var Distance = 0;
	var $Driver=''; var $Driver2='';
	var StartTrip=0;
	var $Delay=0;
	var TimerCounter=0;
	var delayed=false;
	var TripDuration=0;var TripActive=false;
	var $LInfo = $('#VehicleInfo');
	var $VSInfo = $('#TripReport');
	$VSInfo.html('');var $DelayedTime=0;
	$VSInfo.append("<div><i class='fad fa-circle-notch fa-2x fa-pulse primary'></i></div>");
	for ( var t=0; t < markers.length; ++t )
	{
		var localDate = new Date(markers[t].createdDateTime);
		$Delay = (Date.parse(markers[t].receivedDateTime)-Date.parse(markers[t].createdDateTime));
		if ($Delay>$DelayedTime){$DelayedTime=$Delay;}
		$Delay= $Delay/1000/60;
		if ($Delay>=15){
			delayed=true;
		}
		if (markers[t].GNSS_latitude != 0){PolyArray.push([markers[t].GNSS_latitude, markers[t].GNSS_longitude]);}
		if (markers[t].triggerType == 'DISTANCE_TRAVELLED'){
			L.marker( [markers[t].GNSS_latitude, markers[t].GNSS_longitude], {rotationAngle: markers[t].GNSS_heading,icon:TripTimerIcon,draggable: false} )
			.bindTooltip('<div class="leafletpopup rounded"><div class="card-header bg-primary text-light TooltipLine p-2 py-1 "><b>'+markers[t].triggerType+'</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fad fa-clock tooltipicon"></i> '+dateFormat(localDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fad fa-tachometer-alt tooltipicon"></i> '+(markers[t].hrTotalVehicleDistance/1000).toFixed(0)+' km</div>'+
							'</div></div>', { direction: 'top',opacity:1.0})
			.addTo(rFMS_Trip);
		}
		if (markers[t].triggerType == 'IGNITION_ON'){
			L.marker( [markers[t].GNSS_latitude, markers[t].GNSS_longitude], {icon:StartIcon,draggable: false} )
			.bindTooltip('<div class="leafletpopup rounded"><div class="card-header bg-primary text-light TooltipLine p-2 py-1"><b>'+markers[t].triggerType+'</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fas fa-clock tooltipicon"></i> '+dateFormat(localDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fas fa-tachometer-alt tooltipicon"></i> '+(markers[t].hrTotalVehicleDistance/1000).toFixed(0)+' km</div>'+
							'</div></div>', { direction: 'top',opacity:1.0})
			.on('click', function(e){ZoomMarker([markers[t].GNSS_latitude, markers[t].GNSS_longitude]);})
			.addTo(rFMS_Trip);
			TimerCounter=0;
			TripActive=true;
		}
		if (markers[t].triggerType == 'IGNITION_OFF' && TripActive==true || t == markers.length-1){
			Distance = ((markers[t].hrTotalVehicleDistance/1000) - (markers[StartTrip].hrTotalVehicleDistance)/1000);
			$Driver ='1 '+markers[t].driver1Id_TDI.substr(0, 16)+' ';
			$Driver2 = '2 '+markers[t].driver2Id_TDI.substr(0, 16)+' ';
			TripDuration = (new Date(markers[t].createdDateTime) - new Date(markers[StartTrip].createdDateTime));
			var localEndDate = new Date(markers[t].createdDateTime);
			var localStartDate = new Date(markers[StartTrip].createdDateTime);
//			if (delayed==true){
//				delayed=false;
//				var polyline = new L.Polyline(PolyArray,{color:"red",weight:3})
//				.addTo(rFMS_Trip)
//				.bindTooltip('<div class="leafletpopup card rounded"><div class="card-header TooltipLine p-2 py-1"><b>Trip</b></div><div class="card-body p-2"> '+
//							'<div class="TooltipLine"><i class="fad fa-flag tooltipicon"></i> '+dateFormat(localStartDate, "dd-mm-yyyy hh:MM")+'</div>'+
//							'<div class="TooltipLine"><i class="fad fa-route  tooltipicon"></i> '+ round(Distance,2) +' km</div>'+
//							'<div class="TooltipLine"><i class="fad fa-clock tooltipicon"></i> '+ seconds2time(TripDuration) +' hh:mm</div>'+
//							'<div class="TooltipLine text-danger"><i class="fad fa-history tooltipicon"></i> '+ round($DelayedTime/1000/60,1) +' min</div>'+
//							'<div class="TooltipLine"><i class="fad fa-user  tooltipicon"></i> '+ $Driver +'</div>'+
//							'<div class="TooltipLine"><i class="fad fa-flag-checkered  tooltipicon"></i> '+ dateFormat(localEndDate, "dd-mm-yyyy hh:MM")+'</div>'+
//							'</div></div>', { direction: 'top',opacity:1.0,offset: [0, -26]})
//				.on('click', function(e) {var layer = e.target;this.openTooltip();layer.setStyle({color: 'orange', opacity: 1, weight: 8});	})
//				.on('mouseover', function(e) {var layer = e.target;	layer.setStyle({color: 'orange', opacity: 1, weight: 8});})
//				.on('mouseout', function(f) {var layer = f.target;layer.setStyle({color: "red", opacity: 1, weight: 3});});
//			}
//			else {
				var polyline = new L.Polyline(PolyArray,{color:"var(--primary)",weight:3})
				.addTo(rFMS_Trip)
				.bindTooltip('<div class="leafletpopup card"><div class="card-header bg-primary text-light p-2 py-1 TooltipLine"><b>Trip</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fad fa-flag tooltipicon"></i> '+dateFormat(localStartDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fad fa-route  tooltipicon"></i> '+ round(Distance,2) +' km</div>'+
							'<div class="TooltipLine"><i class="fad fa-clock tooltipicon"></i> '+ seconds2time(TripDuration) +' hh:mm</div>'+
							'<div class="TooltipLine"><i class="fad fa-user  tooltipicon"></i> '+ $Driver +'</div>'+
							'<div class="TooltipLine"><i class="fad fa-flag-checkered  tooltipicon"></i> '+ dateFormat(localEndDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'</div></div>', { direction: 'top',opacity:1.0,offset: [0, -26]})
				.on('click', function(e) {var layer = e.target;this.openTooltip();layer.setStyle({color: 'var(--success)', opacity: 1, weight: 5});	})
				.on('mouseover',function(e) {var layer = e.target;layer.setStyle({color: 'var(--success)', opacity: 1, weight: 5});})
				.on('mouseout', function(f) {var layer = f.target;layer.setStyle({color: "var(--primary)", opacity: 1, weight: 3});});
//			}
			StartTrip=t;PolyArray=[];Distance = 0;delayed=false;$Delay=0;TimerCounter=0;
			L.marker( [markers[t].GNSS_latitude, markers[t].GNSS_longitude], {icon:StartIcon,	draggable: false} )
			.bindTooltip('<div class="leafletpopup card rounded"><div class="card-header TooltipLine bg-primary text-light p-2 py-1"><b>'+markers[t].triggerType+'</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fad fa-clock tooltipicon"></i> '+dateFormat(localDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fad fa-tachometer-alt tooltipicon"></i> '+(markers[t].hrTotalVehicleDistance/1000).toFixed(0)+' km</div>'+
							'</div></div>', { direction: 'top',opacity:1.0})
			.addTo(rFMS_Trip);
			TripActive=true;
		}
		if (markers[t].triggerType == 'IGNITION_ON' && TripActive==true || t == markers.length-1){
			Distance = ((markers[t].hrTotalVehicleDistance/1000) - (markers[StartTrip].hrTotalVehicleDistance)/1000);
			$Driver ='1 '+markers[t].driver1Id_TDI.substr(0, 16)+' ';
			$Driver2 = '2 '+markers[t].driver2Id_TDI.substr(0, 16)+' ';
			TripDuration = (new Date(markers[t].createdDateTime) - new Date(markers[StartTrip].createdDateTime));
			var localEndDate = new Date(markers[t].createdDateTime);
			var localStartDate = new Date(markers[StartTrip].createdDateTime);
//			if (delayed==true){
//				delayed=false;
//				var polyline = new L.Polyline(PolyArray,{color:"red",weight:2})
//				.addTo(rFMS_Trip)
//				.bindTooltip('<div class="leafletpopup rounded"><div class="card-header TooltipLine p-2 py-1 "><b>Trip</b></div><div class="card-body p-2"> '+
//							'<div class="TooltipLine"><i class="fas fa-flag tooltipicon"></i> '+dateFormat(localStartDate, "dd-mm-yyyy hh:MM")+'</div>'+
//							'<div class="TooltipLine"><i class="fas fa-route  tooltipicon"></i> '+ round(Distance,2) +' km</div>'+
//							'<div class="TooltipLine"><i class="fas fa-clock tooltipicon"></i> '+ seconds2time(TripDuration) +' hh:mm</div>'+
//							'<div class="TooltipLine text-danger"><i class="fas fa-history tooltipicon"></i> '+ round($DelayedTime/1000/60,1) +' minutes</div>'+
//							'<div class="TooltipLine"><i class="fas fa-user  tooltipicon"></i> '+ $Driver +'</div>'+
//							'<div class="TooltipLine"><i class="fas fa-flag-checkered  tooltipicon"></i> '+ dateFormat(localEndDate, "dd-mm-yyyy hh:MM")+'</div>'+
//							'</div></div>', { direction: 'top',opacity:1.0,offset: [0, -26]})
//				.on('click', function(e) {var layer = e.target;this.openTooltip();layer.setStyle({color: 'orange', opacity: 1, weight: 8});	})
//				.on('mouseover', function(e) {var layer = e.target;	layer.setStyle({color: 'orange', opacity: 1, weight: 8});})
//			//	.on('mouseout', function(f) {var layer = f.target;layer.setStyle({color: "red", opacity: 1, weight: 3});});
//			}
//			else {
				var polyline = new L.Polyline(PolyArray,{color:"var(--primary)",weight:3})
				.addTo(rFMS_Trip)
				.bindTooltip('<div class="leafletpopup card "><div class="card-header bg-primary text-light TooltipLine p-2 py-1 "><b>Trip</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fas fa-flag tooltipicon"></i> '+dateFormat(localStartDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fas fa-route  tooltipicon"></i> '+ round(Distance,2) +' km</div>'+
							'<div class="TooltipLine"><i class="fas fa-clock tooltipicon"></i> '+ seconds2time(TripDuration) +' hh:mm</div>'+
							'<div class="TooltipLine"><i class="fas fa-user  tooltipicon"></i> '+ $Driver +'</div>'+
							'<div class="TooltipLine"><i class="fas fa-flag-checkered  tooltipicon"></i> '+ dateFormat(localEndDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'</div></div>', { direction: 'top',opacity:1.0,offset: [0, -26]})
				.on('click', function(e) {var layer = e.target;this.openTooltip();layer.setStyle({color: '#18e039', opacity: 1, weight: 5});	})
				.on('mouseover',function(e) {var layer = e.target;layer.setStyle({color: 'var(--success)', opacity: 1, weight: 5});})
				.on('mouseout', function(f) {var layer = f.target;layer.setStyle({color: "var(--primary)", opacity: 1, weight: 3});});
//			}
			StartTrip=t;PolyArray=[];Distance = 0;delayed=false;$Delay=0;TimerCounter=0;
			L.marker( [markers[t].GNSS_latitude, markers[t].GNSS_longitude], {icon:StartIcon,draggable: false} )
			.bindTooltip('<div class="leafletpopup card "><div class="card-header bg-primary text-light TooltipLine p-2 py-1"><b>'+markers[t].triggerType+'</b></div><div class="card-body p-2"> '+
							'<div class="TooltipLine"><i class="fas fa-clock tooltipicon"></i> '+dateFormat(localDate, "dd-mm-yyyy hh:MM")+'</div>'+
							'<div class="TooltipLine"><i class="fas fa-tachometer-alt tooltipicon"></i> '+(markers[t].hrTotalVehicleDistance/1000).toFixed(0)+' km</div>'+
							'</div></div>', { direction: 'top',opacity:1.0})
			.addTo(rFMS_Trip);
			TripActive=false;
		}

	}
	$VSInfo.html('');
	map.removeLayer(rFMS_Latest);
	//map.removeLayer(rFMS_Markers);
	map.addLayer(rFMS_Trip);
	map.fitBounds(rFMS_Trip.getBounds(),{paddingBottomRight: [40,40]});
}

function ShowLastMarkers($Search){
	map.removeLayer(rFMS_Trip);
	rFMS_Trip.clearLayers();
	var TripTimerIcon = L.icon({ iconUrl: '../images/triparrow.png', iconSize: [16, 16], iconAnchor: [8, 8], popupAnchor: [0,-8]})
	PolyArray=[];
	$.ajax({
		type: "GET",
		url: window.location.origin+'/scripts/GetLastMarkers?id='+$Search,
		dataType: "json",
		success: function(data) {
			$.each(data, function(key, val)	{
				if (val.GNSS_latitude != 0){
					PolyArray.push([val.GNSS_latitude, val.GNSS_longitude]);
					L.marker( [val.GNSS_latitude, val.GNSS_longitude], {rotationAngle:val.GNSS_heading,icon:TripTimerIcon,draggable: false} )
					.addTo(rFMS_Trip);
				}
			});
			var polyline = new L.Polyline(PolyArray,{color:"var(--secondary)",weight:3,dashArray: '10, 10', dashOffset: '0'})
			.addTo(rFMS_Trip)
			map.addLayer(rFMS_Trip);
			map.fitBounds(rFMS_Trip.getBounds(),{paddingBottomRight: [40,40]});
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}
	});
}
function ShowTripMap(tripno){

	var x = window.location.href;
	ShowPlaceholder('TripDetailsTimeline','graph');ShowPlaceholder('TripDriveTimeGraph','graph');
	var filename = x.split('/').pop();
	filename=filename.toLowerCase();
	$.ajax({
		type: "GET",
		url: window.location.origin+'/scripts/GetLastTripMarkers?tripno='+tripno,
		dataType: "json",
		success: function(data) {
			if (filename == 'map')		{ShowDBMarkers(data);ShowDBTrips(data);	}
			if (filename == 'tripsdb')	{ProcessTimeLineMarkers(data);DrawTellTaleGraph(data);	ShowTripDriveTime(data,'TripDriveTimeGraph');}
			if (filename == 'tripdb')	{
				var element=document.getElementById("TripTabs") ;
				element.classList.remove("hide");
				rFMS_Trip.clearLayers();
				rFMS_Markers.clearLayers();
				ShowDBMarkers(data);ShowDBTrips(data);ProcessTimeLineMarkers(data);DrawTellTaleGraph(data);ShowTripDriveTime(data,'TripDriveTimeGraph')}
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}
	});
}
function ShowDealersOnMap() {
	map.removeLayer(rFMS_Dealers);
	rFMS_Dealers.clearLayers();
	var LIcon=  L.AwesomeMarkers.icon({icon: 'fad fa-tools',markerColor: 'special',iconSize: [30, 36 ], iconAnchor: [15, 20], tooltipAnchor:[,-32], popupAnchor: [, -32]});
	$.ajax({
		type: "GET",
		url: window.location.origin+"/scripts/GetDealers",
		dataType: "json",
		success: function(data) {
			$.each(data, function(key, val)	{
				if (val.adr_gps_latitude!=0){
					L.marker([parseFloat(val.adr_gps_latitude), parseFloat(val.adr_gps_longitude)],  {icon: LIcon,draggable: false} )
					.bindTooltip(ShowDealerTooltip(val),{ direction: 'top',opacity:1})
					.on('click', function(e){ShowDealerInfo(val.id);})
					.addTo(rFMS_Dealers);
				}
			})
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}
	});
}

function ShowDealerTooltip(val){
	var $string ='<div class="card VehicleDetails ">';
		$string+='	<div class="card-header"> '+val.name+'</div>';
		$string+='	<div class="card-body py-1">';
		$string+='		<div class="StatusLine">'+val.adr_city+' / '+val.cnt_description+'</div>';
		$string+='		<div class="StatusLine d-flex">';
		if (val.its_24hours=="1"){	$string+='		<div class="badge border m-1 p-2"><b>ITS 24h</b></div> ';}
		if (val.its_trailer=="1"){	$string+='		<div class="badge border m-1 p-2"><b>ITS Trailer</b></div> ';}
		if (val.its_bus=="1")	 {	$string+='		<div class="badge border m-1 p-2"><b>ITS BUS</b></div> ';}
		$string+='		</div>';
		$string+='</div></div>';
	return $string;
}

function ShowDealerOpeninghours($data){
	var $LInfo = $('#DealerOpening');$LInfo.html('');
	var $Days= Array('','Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	if ($data==''){return};
	var $department='';var $str='';
	var $header='</div><div class="border-bottom py-1">';
	var $footer='</div>">';
	var headerAdded=false;
	var $deptext='';
	var $s='';
	$s+='<div class="tab-content p-2">';
	$s+='	<div class="card">';
	$s+='		<div class="card-title">openinghours</div>';
	$s+='		<div class="card-body" id="dealeropeningcontent"></div>';
	$s+='	</div>';
	$s+='</div>';
	$LInfo.append($s);
	var $LInfo = $('#dealeropeningcontent');$LInfo.html('');$s='';
	$data.forEach((val) => {
		if (val.dep_code==$department){
			$deptext='';
		}
		else {
			$deptext=val.dep_description;
			if (headerAdded==true){	$s+=$footer;}
			$s+=$header;
		}
		$s+='	<div class="d-flex">';
		$s+='		<div class="col-5 p-0">'+$deptext+'</div>';
		$s+='		<div class="_DealerInfo col p-0 text-center small"> '+ $Days[val.day_id_from] +' - '+ $Days[val.day_id_to]+'</div>';
		$s+='		<div class="_DealerInfo col p-0 text-center"> '+ val.oph_time_from_1 +' - '+ val.oph_time_to_1+'</div>';
		$s+='	</div>';
		$department=val.dep_code;
		headerAdded==true;
	});
	$LInfo.append($s);
}

function ShowDealerPerson($data){
	var $LInfo = $('#DealerPersonel');$LInfo.html('');
	if ($data==''){return}
	var $s='';
	$s+='<div class="tab-content p-2">';
	$s+='	<div class="card p-1 ">';
	$s+='		<div class="card-title">personel</div>';
	$s+='		<div class="card-body p-2" id="dealerpersonelcontent"></div>';
	$s+='	</div>';
	$s+='</div>';
	$LInfo.append($s);
	var $LInfo = $('#dealerpersonelcontent');$LInfo.html('');var $string='';
	for ( var t=0; t < $data.length; ++t ){
		$string+='	<div class="border-bottom mb-3 contact col-12 p-1 pb-3">';
		$string+='		<div class="bold text-primary">'+ $data[t].per_name+' <small>'+ $data[t].dep_description+'</small></div>';
		$string+='		<div class="">';
		$string+='			<div class="_DealerInfo">'+$data[t].fud_description+'</div>';
		if ($data[t].per_phone!="") {
			$string+='		<div class="_DealerInfo"><a href="callto:'+ $data[t].per_phone+' " > <i class="fad fa-phone fa-fw"> </i> '+ $data[t].per_phone +'</a></div>';
		}
		if ($data[t].per_email!="") {
			$string+='		<div class="_DealerInfo"><a href="mailto:'+ $data[t].per_email+' " > <i class="fad fa-envelope fa-fw"> </i> '+ $data[t].per_email +'</a></div>';
		}
		$string+='</div></div>';
	}

	$LInfo.append($string);
}

function ShowDealerBusiness($data){
	var $LInfo = $('#DealerServices');$LInfo.html('');
	var $string='<div class="dealerfeatures row py-1 px-3">';
	var $text = [
					{"BusinessCode": "NH","BusinessName": "Night Heater Service"},
					{"BusinessCode": "TA","BusinessName": "Tacho Repair"},
					{"BusinessCode": "LI","BusinessName": "Lubrication/Inspection (MOT)"},
					{"BusinessCode": "IS","BusinessName": "ITS"},
					{"BusinessCode": "PPFS","BusinessName": "PACCAR Parts Fleet Services"},
					{"BusinessCode": "TL","BusinessName": "Tail Lift Repair"},
					{"BusinessCode": "AV","BusinessName": "Bus Audio/Video Repair"},
					{"BusinessCode": "SR","BusinessName": "Service"     },
					{"BusinessCode": "SS","BusinessName": "Service Support"     },
					{"BusinessCode": "PA","BusinessName": "Parts Sales" },
					{"BusinessCode": "PS","BusinessName": "Paint Shop" },
					{"BusinessCode": "TS","BusinessName": "Truck Sales" },
					{"BusinessCode": "BS","BusinessName": "Body Shop" },
					{"BusinessCode": "TR","BusinessName": "Trailer Repair" },
					{"BusinessCode": "RE","BusinessName": "Truck Rental" },
					{"BusinessCode": "UT","BusinessName": "Used Trucks" },
					{"BusinessCode": "CU","BusinessName": "Cool Unit/Airco Repair"},
					{"BusinessCode": "CR","BusinessName": "Chassis Repair"},
					{"BusinessCode": "BC","BusinessName": "Touringcar & Bus"},
					{"BusinessCode": "TY","BusinessName": "Tyre Service"},
					{"BusinessCode": "TR","BusinessName": "Trailer Repair"},
					{"BusinessCode": "AT","BusinessName": "AT"},
					{"BusinessCode": "GS","BusinessName": "Petrol Station"},
					{"BusinessCode": "WB","BusinessName": "Wash Bay"}
				];

	for ( var t=0; t < $data.length; ++t ){
		$service=jQuery.grep($text, function (name) { return name.BusinessCode == $data[t].bus_code });
		$string+='<div class="card  m-1 p-1">'+ $service[0].BusinessName+'</div> ';
	}
	$string+='</div>';
	$LInfo.append($string);
}

function ShowDealerAddress($data){
	var $LInfo = $('#DealerAddress');
	var $s='<div class="row">';
	for (var t=0; t < $data.length; ++t ){
		$s+='<div class="col-12">';
		$s+='	<div class="pb-1">'+ $data[t].adr_address_1 +'</div>';
		$s+='	<div class="pb-1">'+ $data[t].adr_postalcode +' '+$data[t].adr_city+'</div>';
		$s+='	<div class="pb-1 ">'+ $data[t].cnt_description +'</div>';
		$s+='</div>';
	}
	$LInfo.append($s);
}
function ShowDealerName($data){
	var $LInfo = $('#dealername');
	$LInfo.append('<span class=""><i class=" fa fa-tools fa-fw"></i> '+$data.name+'</span>');
}

function ShowDealerCommunications($data){
	var $LInfo = $('#DealerAddress');
	if ($data==null){return}
	var $string='<div class="d-flex">';
	for ( var t=0; t < $data.length; ++t ){
		$string+='<div class="row ">';
		$string+='	<div class="col-12">';
		if ($data[t].cmm_phone!=""){
			$string+='	<div class="pb-1 text-primary"><a href="callto:'+ $data[t].cmm_phone +'"><i class="fad fa-phone fa-fw text-primary"></i> '+ $data[t].cmm_phone +'</a></div>';
		}
		if ($data[t].cmm_email1!=""){
			$string+='	<div class="pb-1 text-primary"><a href="mailto:'+$data[t].cmm_email1 +'?subject=rFMS Connect-Request"><i class="fad fa-envelope fa-fw text-primary"></i> '+ $data[t].cmm_email1 +'</a></div>';
		}
		$string+='</div>';
	}
	$string+='</div>';
	$LInfo.append($string);
}

function ShowDealerInfo($search,$View){
	if ($View!=1){
		var $LInfo = $('#VehicleDetails');
		var $str='';
		$LInfo.html('');openVehDet();
		var $HeaderInfo = $('#Not_Sidebar');
		$HeaderInfo.html('');
		$str+='<div class="vehicleDetailsCard h-100">';
		$str+='		<div class="card-header dealerinfoheader" id="dealername">';
		$str+='		 	<button class="closebtn close" onclick="closeVehDet();"><span class="text-primary" aria-hidden = "true"><i class="fad fa-times-circle text-primary"></i></span></button>';
		$str+='		</div>';
		$str+='<div class = "nav-tabs-custom " id = "PDCTabs">';
		$str+='	<ul class = "nav nav-tabs border-bottom">';
		$str+='		<li class = "nav-item"><a class = "nav-link active" href = "#DealerAddress" data-toggle="tab" title="dealer details"><i class="fad fa-address-card"></i></a></li>';
		$str+='		<li class = "nav-item"><a class = "nav-link" href = "#DealerPersonel" data-toggle = "tab" title="personel"><i class="fad fa-poll-people"></i></a></li>';
		$str+='		<li class = "nav-item"><a class = "nav-link" href = "#DealerOpening" data-toggle = "tab" title="openinghours"><i class="fad fa-business-time"></i></a></li>';
		$str+='	</ul>';
		$str+='</div>';
		$str+='<div class="tab-content">';
		$str+='	<div class="tab-pane active" id="" role="tabpanel">';
		$str+='		<div class="tab-content p-2">';
		$str+='			<div class="card p-1 mb-3 ">';
		$str+='				<div class="card-title">details</div>';
		$str+='				<div class="card-body p-2 small" id="DealerAddress"></div>';
		$str+='			</div>';
		$str+='			<div class="card p-1 ">';
		$str+='				<div class="card-title">services</div>';
		$str+='				<div class="card-body p-2 small" id="DealerServices"></div>';
		$str+='			</div>';		
		$str+='		</div>';
		$str+='	</div>';
		$str+='	<div class="tab-pane " id="DealerPersonel" role="tabpanel"></div>';
		$str+='	<div class="tab-pane " id="DealerOpening" role="tabpanel"></div>';
		$str+='</div>'
		$LInfo.append($str);
	}
	$.ajax({
		url: window.location.origin+"/scripts/GetDealerInfo?id="+$search,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			$.each(data, function(key, val){
				ShowDealerName(val);
				ShowDealerAddress(val.Address);
				ShowDealerCommunications(val.Communication);
				ShowDealerBusiness(val.CompanyBusiness);
				ShowDealerPerson(val.Person);
				ShowDealerOpeninghours(val.Openinghour);
				if ($View!=1){
					$loc=val.Address[0];
					map.setView([$loc.adr_gps_latitude,$loc.adr_gps_longitude], 18);
				}
			})
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}
	});
}
function LoadLatest($refresh){
	var $SelectedCustomer = document.getElementById("groups").value;
	if ($SelectedCustomer!=''){
		rFMS_Latest.clearLayers();
		if ($refresh=='1'){
			rFMS_Trip.clearLayers();
			ShowLatestLayer();
		}
		$.ajax({
			url: window.location.origin+'/scripts/GetVehiclesMap',
			dataType:'JSON',
			success:function(data){
				$.each(data, function(key, val){
					GM.push(val);
				})
				if ($refresh=='2'){
					return ShowLatestGeofence($refresh);
				} else {
					return ShowLatest($refresh);
				}
			},
			error:function(jqXHR, textStatus, errorThrown) {
				SessionAlert('Your Session has expired... <br><br>returning to login-page!');
			}
		});
	}
}
function ShowLatest($refresh){
	const keyword = document.getElementById("filtername").value;
	var $ss=[];
	if (document.getElementsByName("filter")[0].checked==true ){ $s0 =GM.filter(val=> val.status.driving == document.getElementsByName("filter")[0].checked );} else $s0=[];
	if (document.getElementsByName("filter")[1].checked==true ){ $s1 =GM.filter(val=> val.status.paused == document.getElementsByName("filter")[1].checked );} else $s1=[];
	if (document.getElementsByName("filter")[2].checked==true ){ $s2 =GM.filter(val=> val.status.stopped == document.getElementsByName("filter")[2].checked );} else $s2=[];
	if (document.getElementsByName("filter")[3].checked==true ){ $s3 =GM.filter(val=> val.status.alert == document.getElementsByName("filter")[3].checked );} else $s3=[];
	if (document.getElementsByName("filter")[4].checked==true ){ $s4 =GM.filter(val=> val.status.nolocation == document.getElementsByName("filter")[4].checked );} else $s4=[];
	if (document.getElementsByName("filter")[5].checked==true ){ $s5 =GM.filter(val=> val.status.delayed == document.getElementsByName("filter")[5].checked );} else $s5=[];
	if (document.getElementsByName("filter")[6].checked==true ){ $s6 =GM.filter(val=> val.status.geofence == document.getElementsByName("filter")[6].checked );} else $s6=[];
	if (document.getElementsByName("filter")[7].checked==true ){ $s7 =GM.filter(val=> val.status.drivingtoday == document.getElementsByName("filter")[7].checked );} else $s7=[];
	let selection=$ss.concat($s0,$s1,$s2,$s3,$s4,$s5,$s6,$s7);
	selection = selection.map(JSON.stringify);
	selection = new Set(selection);
	let $selection = Array.from(selection, JSON.parse);
	$ss=[];
	var $f0 = $selection.filter(val => { let opt = val.customerVehicleName.toLowerCase().includes(keyword.toLowerCase()); return opt; });
	var $f1 = $selection.filter(val => { let opt = val.LicensePlate.toLowerCase().includes(keyword.toLowerCase()); return opt;  });
	var $f3 = $selection.filter(val => { let opt = val.driver.toLowerCase().includes(keyword.toLowerCase()); return opt;  });
	selection=$ss.concat($f0,$f1,$f3);
	selection = selection.map(JSON.stringify);
	selection = new Set(selection);
	$selection = Array.from(selection, JSON.parse);
	$s0=$s1=$s2=$s3=$s4=$s5=$s6=$s7=$f0=$f1=$f2=$f3=$ss=selection=[];

	var DrivingMarker=  L.AwesomeMarkers.icon({icon: '',			markerColor: 'green'	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
	var PausedMarker=  	L.AwesomeMarkers.icon({icon: '',			markerColor: 'lightblue',iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
	var StoppedMarker=  L.AwesomeMarkers.icon({icon: '',			markerColor: 'blue'		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
	var DelayedMarker=  L.AwesomeMarkers.icon({icon: '',			markerColor: 'red' 		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
	var ErrorMarker  =  L.AwesomeMarkers.icon({icon: '',			markerColor: 'orange' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
	UpdateCounter("","VehicleDetails");
	UpdateCounter("","TotalVehicles");
	rFMS_Latest.clearLayers();
	var CountedVehicles=0;var CountedDriver1=0;var CountedDriver2=0;var CountedOverSpeed=0;var UnknownVehicles=0;var CountedMoving=0;var CountedIdle=0;var CountedStopped=0;var CountedNoLocation=0;var CountedAlert=0;var CountedToday=0;var CountedGE=0;
	var $LInfo = $('#VehicleInfoD');
	$LInfo.html('');$InActive='';$Damaged='';
	$.each($selection, function(key, val){
		CountedVehicles++;
		ETA_logo="";
		if (!!val.status.driving)		{VStatusClass="<i class='fas fa-play fa-fw'></i>";						LIcon=DrivingMarker;CountedMoving++;    VI_Notice="primary";VI_Icon="VehicleListIconOk";		VStatus=".. Driving";}
		if (!!val.status.paused)    	{VStatusClass="<i class='fas fa-pause fa-fw'></i>";val.last_Heading=0;	LIcon=PausedMarker; CountedIdle++;  	VI_Notice="stopped";VI_Icon="VehicleListIconStopped";	VStatus=".. Idling";}
		if (!!val.status.stopped)   	{VStatusClass="<i class='fas fa-stop fa-fw'></i>";val.last_Heading=0;	LIcon=StoppedMarker;CountedStopped++;   VI_Notice="stopped";VI_Icon="VehicleListIconStopped";	VStatus=".. Stopped";}
		if (!!val.status.alert)     	{VStatusClass="<i class='fas fa-exclamation-triangle fa-fw'></i>";		LIcon=ErrorMarker;  CountedAlert++;  	VI_Notice="danger";VI_Icon="VehicleListIconError";	    VStatus=".. text-warning";}
		if (!!val.status.delayed)   	{VStatusClass="<i class='fas fa-history fa-fw'></i>" ; ETA_logo="danger";LIcon=DelayedMarker;UnknownVehicles++;  VI_Notice="danger"; VI_Icon="VehicleListIconDelayed";	VStatus=".. delayed";}
		if (!!val.status.nolocation)	{VStatusClass="<i class='far fa-question-circle fa-fw'></i>";			CountedNoLocation++;VI_Notice="warning";VI_Icon="VehicleListIconError";		VStatus=".. location not updated";}
		if (!!val.status.geofence)		{CountedGE++;}
		if (!!val.status.drivingtoday)	{CountedToday++;} else {$InActive=' text-menu';}
		if (val.DamageCount>0) {$Damaged=' alertdanger';$DamageIcon='<i class="fad fa-tools text-danger fa-fw"></i>';} else {$Damaged='';$DamageIcon='';}
		if (!!val.TrailerName){$TN='<span > / '+val.TrailerName+'</span>';} else {$TN='';}
		if (val.status.nolocation==true){
			$LInfo.append('<a onclick="ShowVehicleInfo(`'+val.id+'`,true);" class="w-100 VL_list " title="'+val.LastActivity+'" >'+
						  '<div class="VehicleListing '+$InActive+''+$Damaged+'">'+
							'<div class="d-flex align-items-center">'+
								'<div class="'+VI_Icon+'">'+VStatusClass+''+$DamageIcon+'</div>'+
								'<div class="VehicleListName"> '+val.customerVehicleName+' '+$TN+'<br/><span class="VehicleListInfo small"></b>'+val.driver+'</span></div>'+
							'</div>'+
						'</div></a>');
			}
		else {
			$str= '<a onclick="ShowVehicleInfo(`'+val.id+'`,true);" class=" w-100 VL_list" title="'+val.LastActivity+'">';
			$str+='	<div class="VehicleListing '+$InActive+''+$Damaged+'">';
			$str+='		<div class="d-flex align-items-center">';
			$str+='			<div class="'+VI_Icon+'">'+VStatusClass+''+$DamageIcon+'</div>';
			$str+='			<div class="VehicleListName"> '+val.customerVehicleName+' '+$TN;
			if (val.driver!=null){	$str+='				<br/><span class="VehicleListInfo small"></b>'+val.driver+'</span>';}
			$str+='				</div>';
			$str+='			<div class="'+VI_Icon+' text-right ml-auto mx-1"><i class="fad fa-route fa-fw lager text-'+ETA_logo+'"></i></div>';
			$str+='		</div>';
			$str+='	</div></a>';
			$LInfo.append($str);
//			$LInfo.append('<div class="VehicleListingHR  "></div>');
			L.marker([val.last_Latitude, val.last_Longitude],  { rotationAngle: val.last_Heading,icon: LIcon,draggable: false } )
			.bindTooltip(ShowLatestTooltip(val),{ direction: 'top',opacity:1.0})
			.on('click', function(e){ ShowVehicleInfo(val.id,true);	ShowLastMarkers(val.id); })
			.addTo(rFMS_Latest);
		}
	});
	map.addLayer(rFMS_Latest);
	map.addLayer(rFMS_Trigger);
//	map.on('click', function(e) { closeVehDet(); });
	if ($refresh=='1'){
		map.fitBounds(rFMS_Latest.getBounds(),{padding: [40,40]});
		$('#LatestTabs a[href="#ShowInfo"]').tab('show');
	}
	UpdateCounter(CountedVehicles,"CTTO");UpdateCounter(CountedGE,"CTGE");UpdateCounter(CountedMoving,"CTDR");UpdateCounter(CountedStopped,"CTST");UpdateCounter(CountedIdle,"CTID");UpdateCounter(CountedAlert,"CTAL");UpdateCounter(CountedNoLocation,"CTNL");UpdateCounter(UnknownVehicles,"CTDE");UpdateCounter(CountedToday,"CTDT");
}
function ShowLatestGeofence($refresh){
	var DrivingMarker=  L.AwesomeMarkers.icon({icon: '',	markerColor: 'green'	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-18]});
	var PausedMarker=  	L.AwesomeMarkers.icon({icon: '',	markerColor: 'lightblue',iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-18]});
	var StoppedMarker=  L.AwesomeMarkers.icon({icon: '',	markerColor: 'blue'		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-18]});
	var DelayedMarker=  L.AwesomeMarkers.icon({icon: '',	markerColor: 'red' 		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-18]});
	var ErrorMarker  =  L.AwesomeMarkers.icon({icon: '',	markerColor: 'orange' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-18]});
	rFMS_Latest.clearLayers();
	$.each(GM, function(key, val){
		if (!!val.status.driving)		{LIcon=DrivingMarker;}
		if (!!val.status.paused)    	{LIcon=PausedMarker;val.last_Heading=0; }
		if (!!val.status.stopped)   	{LIcon=StoppedMarker;val.last_Heading=0;}
		if (!!val.status.alert)     	{LIcon=ErrorMarker;}
		if (!!val.status.delayed)   	{LIcon=DelayedMarker;}
		if (val.status.nolocation==false){
			L.marker([val.last_Latitude, val.last_Longitude],  {
				rotationAngle: val.last_Heading,
				icon: LIcon,
				draggable: false
			} )
				.bindTooltip(ShowGeofenceMapTooltip(val),{ direction: 'top',opacity:1.0})
				.on('click', function(e) {
						var $CurSelection=$SV.selected();
						var $V = (element) => element == val.VIN;
						var $CI=$CurSelection.findIndex($V);
						if ($CI==-1){
							$CurSelection.push(val.VIN);}
						else {
							$CurSelection.splice($CI, 1);
						}
						$SV.destroy();
						$SV= new SlimSelect({ select: '#vehicles' });
						$SV.set($CurSelection);
						})
				.addTo(rFMS_Latest);
		}
	});
	map.addLayer(rFMS_Latest);
	map.fitBounds(rFMS_Latest.getBounds(),{padding: [40,40]});
}

function LoadDashboard($refresh){
	if ($refresh=='1') {
		ShowPlaceholder("VehiclesStats");
		ShowPlaceholder("CountedDrivers");
		ShowPlaceholder("CountedTellTales");
		ShowPlaceholder("CountedDelayed");
		ShowPlaceholder("SevereDamages",'single');
		ShowPlaceholder("RedTellTales",'single');
		ShowPlaceholder("DashboardVehicles",'table');
	}
	$.ajax({
		url: window.location.origin+'/scripts/GetVehiclesDashboard',
		dataType:'JSON',
		method:'GET',
		cache: false,
		success:function(data){
			var CountedVehicles=0;var CountedDriver1=0;var CountedDriver2=0;var CountedDTEx=0 ;var CountedDamages=0;var CountedOverSpeed=0;var UnknownVehicles=0;var CountedMoving=0;var CountedStopped=0;
			var CountedNoLocation=0;var CountedAlert=0;var CountedToday=0;var CountedMDue=0;var CountedMOverDue=0;CountedActive=0;var CountedGE=0;
			var $LInfo = $('#VehicleInfoD');$LInfo.html('');
			var $Info = $('#DashboardVehicles');$Info.html('');
			ShowDashboard($refresh,data);
			DashboardVehicleList(data);
			$.each(data, function(key, val){
				CountedVehicles++;
				var localDate = new Date(val.LastActivity);
				if (val.status.driving==true)   { CountedMoving++; CountedActive++;}
				if (val.status.paused==true)    { CountedStopped++; CountedActive++;}
				if (val.status.stopped==true)   { CountedStopped++;	val.last_Heading=0; }
				if (val.status.alert==true)     { CountedAlert++; }
				if (val.status.delayed==true)   { UnknownVehicles++;}
				if (val.status.nolocation==true){ CountedNoLocation++;}
				if (val.status.drivingtoday==true){
					CountedToday++;
					if (!!val.Driver1_ID){CountedDriver1++};
					if (!!val.Driver2_ID){CountedDriver2++};
					if (!!val.status.overspeeding){CountedOverSpeed++};
				}
				if (val.status.geofence=='1')		{CountedGE++;}
				if (TimeToSeconds(val.RemainingDriveToday)<0){CountedDTEx++;}
				if (val.DamageCount>0){CountedDamages++};
				if ((val.serviceDistance/1000)<1000 && (val.serviceDistance/1000)!=0){ CountedMOverDue++;}
				if ((val.serviceDistance/1000)<15000 && (val.serviceDistance/1000)>1000){ CountedMDue++;}
				if ($type='cards'){
					ShowVehicleCard(val,'DashCards');
				}
			});
			var $Utilisation=(CountedToday/CountedVehicles)*100;			
			UpdateCounter('<i class="fad fa-truck fa-fw text-primary"></i> <span title="active today ">'+CountedToday+'</span> of <span title="Total vehicles">'+CountedVehicles+ '</span><small> ( '+$Utilisation.toFixed(0)+' %) </small>',"VehiclesStats");
			if (CountedDamages>0){UpdateCounter(' <div class="badge badge-pill border p-2 normalFont shadow-sm"> <i class="fad fa-tools text-danger fa-fw "></i> '+CountedDamages+'</b></span> </div> ',"SevereDamages");} else {UpdateCounter('',"SevereDamages");}
			UpdateCounter(' <i class="fad fa-wrench fa-fw text-warning"></i><span class="text-primary"> '+CountedMDue+'</span>',"CountedMDue");
			UpdateCounter(' <i class="fad fa-draw-polygon fa-fw text-primary "></i><span class="text-primary"> '+CountedGE+'</span>',"CountedGE");
			UpdateCounter(' <i class="fad fa-wrench fa-fw text-danger"></i><span class="text-primary"> '+CountedMOverDue+'</span>',"CountedMOverDue");
			UpdateCounter(' <a href="#" title="# vehicles with an active telltale "><i class="fad fa-exclamation-triangle fa-fw text-warning"></i> '+CountedAlert+'</a>',"CountedTellTales");
			UpdateCounter(' <div class="row">'+
						  ' <div class="col text-primary"><a href="#" class="text-primary" title="# drivers"><i class="fad fa-user fa-fw text-primary"> </i> '+ CountedDriver1+' </a><span class="mx-2"></span> <a href="#" class="text-primary" title="# second Drivers"><i class="far fa-user fa-fw gray"></i><i class="fad fa-user text-primary"></i> '+CountedDriver2+' </a></div>',"CountedDrivers");
			if (CountedOverSpeed>0)	{ UpdateCounter(' <div class="badge badge-pill border p-2 normalFont shadow-sm" > <i class="fad fa-tachometer-alt fa-fw "></i> '+CountedOverSpeed+'</b></span> </div> ',"SpeedingDrivers");}
			else					{ UpdateCounter('',"SpeedingDrivers");}
			if (CountedDTEx>0)	{ UpdateCounter(' <div class="badge badge-pill border p-2 normalFont shadow-sm" > <i class="fad fa-user-clock fa-fw "></i> '+CountedDTEx+'</b></span> </div> ',"DriversEXT");}
			else 				{ UpdateCounter('',"DriversEXT");}
			UpdateCounter(' <i class="fad fa-broadcast-tower fa-fw text-primary"></i> '+UnknownVehicles,"CountedDelayed");
			UpdateCounter(CountedNoLocation,"NoLocationVehicles");
			if ($refresh=='1') {
//				if (CountedMOverDue>0){	Notiflix.Notify.Warning('There are <b>'+CountedMOverDue+'</b> vehicles with overdue maintenance');	}
			}
//			if (CountedDTEx>0)		{ Notiflix.Notify.Warning('There are '+CountedDTEx+' drivers exceeding drivingtime');}
		},
		error:function() {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}
	});
}
function ShowDashboard($refresh,data){
	var DrivingMarker=  L.AwesomeMarkers.icon({icon: '',		markerColor: 'green'	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var PausedMarker=  	L.AwesomeMarkers.icon({icon: '',		markerColor: 'lightblue',iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var StoppedMarker=  L.AwesomeMarkers.icon({icon: '',		markerColor: 'blue'		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var DelayedMarker=  L.AwesomeMarkers.icon({icon: '',		markerColor: 'red' 		,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var ErrorMarker  =  L.AwesomeMarkers.icon({icon: '',		markerColor: 'orange' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var NoGPSMarker  =  L.AwesomeMarkers.icon({icon: 'question',markerColor: 'orange' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var DirectionMarker=L.AwesomeMarkers.icon({icon: ''		,	markerColor: 'white' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -32]});
	var $LInfo = $('#VehicleInfoD');$LInfo.html('');
	var $Info = $('#DashboardVehicles');$Info.html('');
	rFMS_Latest.clearLayers();
	$.each(data, function(key, val){
		var localDate = new Date(val.LastActivity);
		if (val.status.driving==true)   { VStatusClass="<i class='fas fa-play '></i>";      	LIcon=DrivingMarker;VI_Notice="primary";VI_Icon="VehicleListIconOk";			VStatus=".. Driving";}
		if (val.status.paused==true)    { VStatusClass="<i class='fas fa-pause '></i>";     	LIcon=PausedMarker;	val.last_Heading=0; VI_Notice="stopped";VI_Icon="VehicleListIconStopped";		VStatus=".. Idling";}
		if (val.status.stopped==true)   { VStatusClass="<i class='fas fa-stop '></i>";     		LIcon=StoppedMarker;val.last_Heading=0; VI_Notice="stopped";VI_Icon="VehicleListIconStopped";		VStatus=".. Stopped";}
		if (val.status.alert==true)     { VStatusClass="<i class='fas fa-exclamation'></i>";   	LIcon=ErrorMarker;  					VI_Notice="text-warning";VI_Icon="VehicleListIconError";	VStatus=".. text-warning";}
		if (val.status.delayed==true)   { VStatusClass="<i class='fas fa-history'></i>" ;  		LIcon=DelayedMarker;					VI_Notice="text-danger"; VI_Icon="VehicleListIconDelayed";	VStatus=".. delayed";}
		if (val.status.nolocation==true){ VStatusClass="<i class='far fa-question-circle'></i>";LIcon=NoGPSMarker;						VI_Notice="text-warning";VI_Icon="VehicleListIconError";	VStatus=".. location not updated";}
		if (!!val.LicensePlate){$LP='<span class="VehicleListInfo"></b><small>('+val.LicensePlate+')</small></span>';} else {$LP='';}
		if (!!val.Driver1_ID){
			if (!!val.Lastname)	{VStatusText=val.Surname+" "+val.Lastname;}
			else {VStatusText=val.Driver1_ID;}}
		else {VStatusText='';}
		if (val.status.nolocation==false){
			L.marker([val.last_Latitude, val.last_Longitude],  {
				rotationAngle: val.last_Heading,
				icon: LIcon,
				draggable: false
			} )
			.bindTooltip(ShowDashboardTooltip(val),{ direction: 'top',opacity:1.0})
			.addTo(rFMS_Latest);
		}
	});
	map.addLayer(rFMS_Latest);
	if ($refresh=='1'){
		if (rFMS_Latest.getLayers().length>0){
			map.fitBounds(rFMS_Latest.getBounds(),{padding: [40,40]});
		}
	}
}

function UpdateCounter (Counter,Reference){
	var $Info = $('#'+Reference);$Info.html('');$Info.append(Counter);
}

function ShowSpinner (Reference){
	var $Info = $('#'+Reference);$Info.html('');$Info.append("<div class='loader'></div>");
}
function ShowSpinnerSmall (Reference){
	var $Info = $('#'+Reference);$Info.html('');$Info.append("<span class='fas fa-loader fa-spin'></span>");
}

function ShowPlaceholder (Reference,$type){
	var $Info = $('#'+Reference);
	var $str='<div class="ph-item"><div class="ph-col-12"><div class="ph-row"><div class="ph-col-12 big"></div></div></div></div>';
	if ($type=='graph'){ var $str='<div class="ph-item h-100"><div class="ph-col-12"><div class="ph-picture"></div></div></div>';	}
	if ($type=='single'){ var $str='<div class="ph-item"><div class="ph-col-12"><div class="ph-row"><div class="ph-col-12 big"></div></div></div></div>';	}
	if ($type=='stats'){ var $str='<div class="ph-item"><div class="ph-col-12"><div class="ph-row"><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div><div class="ph-col-10 big empty"></div><div class="ph-col-2 "></div><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div></div></div></div>';	}
	if ($type=='table'){
		var $str='<div class="ph-item mt-3"><div class="ph-col-12">';
		$str += '<div class="ph-row mb-3"><div class="ph-col-2"></div><div class="ph-col-7 empty"></div></div>';
		for ( var t=0; t < 5; ++t ) {
			$str += '<div class="ph-row mb-2">';
			for ( var c=0; c < 5; ++c ) { $str += '<div class="ph-col-2 mr-2"></div>';	}
			$str += '<div class="ph-col-1 mr-0"></div></div>';
		}
		$str += '</div></div">';
	}
	if ($type=='form'){
		var $str='<div class="ph-item mt-3"><div class="ph-col-12">';
		for ( var t=0; t < 5; ++t ) {
			$str += '<div class="ph-row mb-2"><div class="ph-col-5"></div><div class="ph-col-2 empty"></div><div class="ph-col-5"></div></div>';
		}
		$str += '</div></div">';
	}
	if ($type=='trips'){
		var $str='<div class="ph-item mt-3"><div class="ph-col-12">';
		for ( var t=0; t < 5; ++t ) { $str += '	<div class="ph-row"><div class="ph-col-2 big"></div><div class="ph-col-1 empty"></div><div class="ph-col-6 big"></div><div class="ph-col-1 empty"></div><div class="ph-col-2 big"></div><div class="ph-col-1 empty"></div></div>';		}
		$str += '</div></div">';
	}
	if ($type=='sumreport'){
		var $str='<div class="ph-item">	<div class="ph-col-12"><div class="ph-row mb-2">';
		for ( var c=0; c < 6; ++c ) {$str += '<div class="ph-col-1 big"></div><div class="ph-col-1 empty"></div>';	}
		$str += '</div></div></div">';
	}
	$Info.html('');
	$Info.append($str);
}

function DashboardVehicleList($data){
	var table =$('#DashboardVehicles').DataTable( {
	oLanguage: {sProcessing: "<div class='animated-background' id='tableloader'></div>"}, processing: false,paging: true,bInfo:false,"lengthMenu": [[8, -1], [8, "All"]],"bLengthChange": false,
	language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
	autoWidth: false,destroy:true,order:[[6,'desc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,searching: false,responsive: true,
	buttons: {buttons: []},
	data : $data,
	columns: [
		{ "data": "NewVehicle",
			"render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-star "></i>';} else {return '';}},
			"title":" ","visible":true,"class":"dt-center",width:"1%" },
		{ "data": "tripActive",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-play "></i>';} else {return '<i class="fas fa-stop iconbox "></i>';}},
				"title":" ","visible":true,"class":"dt-center",width:"1%" },
		{ "data": "customerVehicleName","title":"vehicle","class":"dt-left ","render": function (data, type, row) { return '<a href="pages/vehicle_edit?id='+row.id+'" class="underline "> '+row.customerVehicleName+' </a>'}},
		{ "data": "TrailerName","title":"trailer","class":"dt-left","render": function (data, type, row) { if(!row.TrailerName ) {return ''} else {return '<a href="pages/trailer_edit?id='+row.TrailerId+'" class="underline "> '+row.TrailerName+' </a>'}}},
		{ "data": "Driver","title":"driver","defaultContent": "","class":"dt-left ","render": function (data, type, row) { if(!row.Driver) {return ''} else { return '<a href="pages/driver_edit?id='+row.DriverId+'" class="underline "> '+row.Driver+' </a>'}}},
		{ "data": "RemainingDriveToday","title":"rem.drive","defaultContent": "","class":"dt-center" },
		{ "data": "TotDistanceToday","title":"distance","defaultContent": "","class":"dt-center " },
		{ "data": "TotFuelUsedToday","title":"fuel ","defaultContent": "","class":"dt-center " },
		{ "data": "LastActivity","title":"Last Contact UTC","defaultContent": "", "class":"dt-left","visible":false },
		{ "data": null,"targets": -1,ordable: false,
			render: function(data, type, row) {
			$array='';
			if(row.LowOil > 0) 					{ $array+=' <i class="fad fa-oil-can text-danger text-danger fa-fw" title="OilLevel too low"></i>';} 		else { $array+='<i class="fa-fw"></i>'; }
			if(row.LowBattery > 0) 				{ $array+=' <i class="fad fa-battery text-warning fa-fw" title="BatteryLevel too low"></i>';} 				else { $array+='<i class="fa-fw"></i>'; }
			if(row.LowTPMS > 0) 				{ $array+=' <i class="fad fa-tire-pressure-warning text-danger fa-fw" title="TyrePressure too low"></i>';} 	else { $array+='<i class="fa-fw"></i>'; }
			if(parseInt(row.DamageCount) > 0) 	{ $array+=' <i class="fad fa-tools text-danger fa-fw" title="Severly damaged"></i>';} 						else { $array+='<i class="fa-fw"></i>'; }
			if(parseInt(row.CountWarning) > 0) 	{ $array+=' <i class="fad fa-exclamation-triangle fa-fw " title="Severe display warning"></i>';} 			else { $array+='<i class="fa-fw"></i>'; }
			if(parseInt(row.currentSpeed) > 85) { $array+=' <i class="fad fa-tachometer-alt fa-fw" title="Overspeeding"></i>';} 							else { $array+='<i class="fa-fw"></i>'; }
			if(row.Geofence == '1') 			{ $array+=' <i class="fad fa-draw-polygon fa-fw" title="Geofence triggered"></i>';} 						else { $array+='<i class="fa-fw"></i>'; }
			if(row.Maintenance == 'danger') 	{ $array+=' <i class="fad fa-wrench text-danger fa-fw" title="Overdue Maintenance"></i>';}					else { $array+='<i class="fa-fw"></i>'; }
			if(row.Maintenance == 'warning') 	{ $array+=' <i class="fad fa-wrench fa-fw" title="Due Maintenance"></i>';}									else { $array+='<i class="fa-fw"></i>'; }
			if ($array!=''){ return $array;}
			},	"title":"status","class":"dt-left", width:"10%" }
		]
	} );
	$('#DashboardVehicles').off('click');
	$('#DashboardVehicles').on('click', 'tr', function() {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		var data = table.row(this).data();
		map.flyTo([data['last_Latitude'],data['last_Longitude']],11,{duration :1,animate:true});
    });
}


function ShowLatestTooltip(val){
	var $string='';$LP='';
	var localDate = convertUTCDateToLocalDate(new Date(val.LastActivity));
	var $damaged = '';var $damageicon = '';
	if (val.TrailerName!=null)	{ $LP='<span class="VehicleListInfo"></b>('+val.TrailerName+')</span>';}
	if (val.DamageCount>0)			{ $damaged = ' alert-danger text-danger';$damageicon='&nbsp <small> <i class="fad fa-tools fa-fw"></i> '+val.DamageCount+'</small>';}
	$string+='<div class="card leafletpopup shadow">'+
					'<div class="card-header '+$damaged+'"><b>'+val.customerVehicleName+'</b> '+$LP+' '+$damageicon+'</div> '+
					'<div class="card-body p-3 py-1 small">';
	if (val.DriverId!=null){
		$string+='<span class="TooltipLine "><i class="fad fa-user text-primary tooltipicon"></i><span class="tooltiptxt"> '+val.driver+'</span></br>';
		if (!!val.RemainingDriveToday){
			$string+='<span class="TooltipLine "><i class="fad fa-user-clock  text-primary tooltipicon"></i></i><span class="tooltiptxt"> '+val.RemainingDriveToday+' </span></span></br>';
		}
		if (!!val.RemainingRestToday){$string+='<span class="TooltipLine "><i class="fad fa-utensils tooltipicon text-primary"></i><span class="tooltiptxt"> '+val.RemainingRestToday+' </span></span></br>';}
	}
	if (val.currentSpeed>-1){$string+='<span class="TooltipLine"><i class="fad fa-tachometer-alt text-primary tooltipicon"></i><span class="tooltiptxt"> '+val.currentSpeed+' km/u</span></span></br>';	}
	if (val.FuelLevel!=null){$string+='<span class=""><i class="fad fa-gas-pump  text-primary tooltipicon"></i><span class="tooltiptxt"> '+val.FuelLevel+' %</span></span></br>';}
	else {$string+='<span class="TooltipLine"><i class="fad fa-gas-pump  text-primary tooltipicon"></i>  %</span></span></br>';}
	if (val.CountWarning>0){$string+='<span class="TooltipLine"><i class="fad fa-exclamation-triangle text-danger tooltipicon"></i><span class="tooltiptxt "> '+val.CountWarning+' warning lamps</span></span></br>';}
	else {$string+='<span class="TooltipLine"><i class="fad fa-exclamation-triangle text-primary tooltipicon"></i><span class="tooltiptxt"> no warning lamps</span></span></br>';}
	if (val.status.delayed==true){$string +='<span class=" text-danger"><i class="fad fa-history text-danger tooltipicon"></i><span class="tooltiptxt text-danger"> <strong>'+ dateFormat(localDate, "yyyy-mm-dd HH:MM ")+'</span></span></br></div></div>';}
	else {
		$string +='<span class="TooltipLine"><i class="fad fa-clock text-primary tooltipicon"></i><span class="tooltiptxt "> <strong>'+ dateFormat(localDate, "yyyy-mm-dd HH:MM ")+'</span></span></br></div></div>';
	}
	return $string;
}
function ShowDashboardTooltip(val){
	var $string='';
	var tr=val.TrailerName;
	var localDate = new Date(val.LastActivity);
	$string+='<div class="leafletpopup rounded">';
	if (val.TrailerName!=null){
		$string+='	<div class="card-header p-2"> <i class="fad fa-trailer fa-fw"></i><i class="fad fa-truck-moving fa-fw"></i><b> '+val.customerVehicleName+' / '+val.TrailerName+'</b></div>';
	}
	else {
		$string+='	<div class="card-header p-2"><i class="fad fa-truck-moving fa-fw"></i><i class="fad fa-fw"></i><b> '+val.customerVehicleName+'</b> </div>';
	}
	$string+='	<div class="card-body p-2">';
	if ($SelectedGroup=='*') {
		$string+='<div class="TooltipLine"><i class="fad fa-building fa-fw"></i><i class="fad fa-fw"></i> '+val.name+'</div>';
	}
	$string+='<div class="TooltipLine"><i class="fad fa-user fa-fw"></i><i class="fad fa-fw"></i> '+val.Driver+' ( '+val.RemainingDriveToday+' )</div>';
	$string+='</div></div>';
	return $string;
}
function ShowGeofenceMapTooltip(val){
	var $string='';
	var tr=val.TrailerName;
	var localDate = new Date(val.LastActivity);
	$string+='<div class="card">';
	if (val.TrailerName!=null){
		$string+='	<div class="card-title p-2"> <i class="fad fa-trailer fa-fw"></i><i class="fad fa-truck-moving fa-fw"></i><b> '+val.customerVehicleName+' / '+val.TrailerName+'</b></div>';
	}
	else {
		$string+='	<div class="card-title p-2"><i class="fad fa-truck-moving fa-fw"></i><b> '+val.customerVehicleName+'</b> </div>';
	}
	$string+='</div>';
	return $string;
}

function ZoomMarker(lat,lng){
	map.flyto([lat, lng], 16);
	}

function ShowLatestButtons(){
	var options= {id: '00',position: 'topright', type: 'replace',leafletClasses: true, states:[{stateName: 'get-center',onClick: function(button, map){closeVehDet();},title: 'Fit Zoom',icon: 'fas fa-expand-arrows-alt text-primary' }]};
	var options1={id: '01',position: 'bottomright',type: 'replace', leafletClasses: true,states:[{stateName: 'get-center', onClick: function(button, map){closeVehDet();LoadLatest();},title: 'Refresh data',icon: 'fad fa-sync text-primary' }]};
	var buttons = [ L.easyButton(options1),	L.easyButton(options)];
	L.easyBar(buttons).addTo(map);
}

function ShowDBButtons($new){
	if ($new=='true') {
		// adding refresh button to controls
		var options1 = {id: '1',position: 'topright',	type: 'replace',leafletClasses: true,
			states: [{stateName: 'get-center', onClick: function (button, map) {map.fitBounds(rFMS_Trip.getBounds(), {padding: [40, 40]});	}, title: 'Zoom-Fit', icon: 'fad fa-expand-arrows-alt text-primary'	} ]	};
		var options2 = {id: '2',position: 'topright',type: 'replace',leafletClasses: true,
			states: [{stateName: 'get-center', onClick: function (button, map) {ShowTripLayer();}, title: 'Showing Trips', icon: 'fad fa-route text-primary'} ] };
		var options3 = {id: '4',position: 'topright',type: 'replace',leafletClasses: true,
			states: [{stateName: 'get-center', onClick: function (button, map) {ShowMarkerLayer();			}, title: 'Trip markers', icon: 'fad fa-map-marker-alt text-primary'} ] };
		var buttons = [
			L.easyButton(options3),
			L.easyButton(options2),
			L.easyButton(options1)
		];
		$TripButtons = L.easyBar(buttons, 'topright').addTo(map);
	} else {
		$TripButtons.remove();
	}
}

function Convert2Address($array){
	var $Info = $('#'+$array[2]);
	$Info.html('');
	if ($array[0]!=null){
	$.ajax({
		url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json?prox='+$array[0]+','+$array[1]+'&mode=retrieveAddresses&gen=9&language=eng&maxresults=1&app_id=Ch87ydlyXjsmnI5AcXho&app_code=z2HsXPK48vhNPwyfcFr5ew',
		type: 'GET',
		dataType: 'json',
		success: function (data) {
			$Info.append('<div class="d-flex"><div><i class="fad fa-map-marked-alt fa-fw "></i><i class="fas fa-fw"></i></div><div class="">'+data.Response.View[0].Result[0].Location.Address.Label+'</div></div>');
		},
		error: function (data) {
			$Info.append('no service available to retrieve location');
		}
	});
	}
}

function ReadDriverDetails(Search,s){
	var $LInfo = $('#'+s);
	var $Str='';
	$.ajax({
		url: window.location.origin+'/scripts/GetDriverDashboard?id='+Search,
		type:'GET',
		dataType:'json',
		success:function(data){
			$LInfo.html('');
			$Str+='<div class="d-flex  navbar-light">';
			if (s=='Driver1'){
				$Str+='<div class="p-0"><i class="fad fa-user fa-fw"></i><i class="fad fa-fw"></i>'+data.Driver+'</div>';

			} else {
				$Str+='<div class="p-0"><i class="fas fa-user fa-fw gray "></i><i class="fad fa-user fa-fw "></i>'+data.Driver+'</div>';
			}
			$Str+=	'<div class="ml-auto p-0"><i class="fad fa-user-clock "></i> <span id="DR_'+data.id+'"><i class="fa-solid fa-loader fa-spin"></i></span></div>';
			$Str+='</div>';
			$Str+='<div class="row">';
			if (data.email!='') {
				$Str += '	<div class="col-12"><i class="fad fa-envelope fa-fw"></i><i class="fad fa-fw"></i>' + data.email + '</div>';
			}
			if (data.Phone_Mobile!='') {
				$Str += '	<div class="col-12"><i class="fad fa-mobile fa-fw"></i><i class="fad fa-fw"></i>' + data.Phone_Mobile + '</div>';
			}
			$Str+='</div>';

			$LInfo.append($Str);
		},
		error:function(){
			$Str+='<div class="d-flex StatusLine navbar-light">';
			if (s=='Driver1'){
				$Str+='<div class="p-0"><i class="fad fa-user fa-fw"></i><i class="fad fa-fw"></i> - </div>';}
			else {
				$Str+='<div class="p-0"><i class="far fa-user fa-fw"></i><i class="fad fa-user fa-fw "></i> - </div>';}
			$Str+=	'<div class="ml-auto p-0"><i class="fad fa-user-clock "></i> xx:xx </></div>';
			$Str+='</div>';
			$LInfo.append($Str);
		}
	});
}
function ReadRedTellTales(){
	var $LInfo = $('#RedTellTales');
		$LInfo.html('');
	var $Str='';var $countActive=0;$countTotal=0;
	$.ajax({
		url: window.location.origin+'/scripts/CountTodayRedAlerts',
		type:'GET',
		dataType:'json',
		success:function(data){
			$LInfo.html('');
			$.each(data, function(key, val){				
				if (val.TripActive=="1"){$countActive++;}
				$countTotal++;
			});				
			$Str+='<div class="badge badge-pill border p-2 normalFont shadow-sm">';
			$Str+='		<i class="fad fa-exclamation-triangle text-danger fa-fw "></i> '+$countActive+' <span class="grey"><small>active </small> / </span>'+$countTotal+'<span class="grey"><small> total today</small> </span> </div> ';
			$Str+='</div></div>';
			$LInfo.append($Str);
		},
		error:function(jqXHR, textStatus, errorThrown) {
			SessionAlert('Your Session has expired... <br><br>returning to login-page!');
		}

	});
}
function ReadMapVehicleTrips($S,Reference){
	var $LInfo = $('#'+Reference);

	$LInfo.append('	<div class="card-body p-2"><div class="card shadow-sm mb-3 p-0" ><div class="card-title">trips</div><div id="'+Reference+'_content"></div></div>');
	var curdate= new Date();
	var $LInfo = $('#'+Reference+'_content');
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicleActivity?id='+$S+'&today',
		type:'GET',
		dataType:'json',
		success:function(data){			
			if (data.length==0){$LInfo.append('<div class="col-12 notice " >no trips available</div></div></div>');} 
			else {
				ShowMapVehicleTrips(data,Reference);
			}
		},
		error:function(){
			$LInfo.append('<div class="alert alert-danger">error reading data, please contact your application manager</div>');
		}
	})
}
function ShowMapVehicleTrips(data,ref){
	var $LInfo		= $('#'+ref+'_content');
	var $str		= '';
	var $expanded	= true;
	var d			= new Date();d.setHours(0,0,0,0);	
	var currentDT	= new Date();	
	var BreakDate	= "";
	var $delayed	= "";
	var refdate		= dateFormat(d, "y-m-d");
	$tripDate		= new Date(data[0].date);$tripDate.setHours(0,0,0,0);	
	if ($tripDate == d) {
		$str += '	<div class="col-12 small"><b>today</b></div>';
	} 
	$LInfo.append($str);
	$.each(data, function (key, val) {		
		val.StartDate = c_ToLocalDate(val.StartDate); //date
		val.EndDate = c_ToLocalDate(val.EndDate);		
		if (val.date != refdate) {
			$str = '<div class="col-12 d-flex"><div class="badge card-title text-center col-12 m-0 p-0 rounded-0 ">'+dateFormat(new Date(val.date), "dd mmmm yyyy")+'</div></div>';
			$LInfo.append($str); BreakDate=val.StartDate;
		}
		if (val.driver		== null) { val.driver = '';} else { val.driver = '<i class="fad fa-user tiny"></i> ' + val.driver;}
		if (val.TripDelayed == true) { $delayed = " text-danger "; } else {	$delayed = "";	}
		if (val.TripActive == 0) {
			if (val.Distance > -1) {
				var $BDuration = val.StartDate - BreakDate;
				if ($BDuration > 60000 && $BDuration < 86340000) {
					$str = '	<div class="TripBreakLine d-flex">';
					$str += '		<div class="mx-auto badge TripBreakText ">stopped <i class="fad fa-clock fa-fw"></i> ' + SecondsToTime($BDuration / 1000) + '</div>';
					$str += '	</div>';
					$LInfo.append($str);
				}
				$str = '<a href="javascript:ShowTripMap(' + val.Trip_NO + ');">';
				$str += '	<div class="TripListing">';
				$str += '		<div class="col-12 d-flex px-1 ' + $delayed + ' ">';
				$str += '			<div class="col-3 TripListName p-0 pr-3 " title="start time"><i class=" fad fa-fw fa-flag"></i> <b>' + dateFormat(val.StartDate, "HH:MM ") + '</b></div>';
				$str += '			<div class="mr-auto TripListNameS p-0 pr-3" title="registered driver">' + val.driver + ' </div>';
				$str += '			<div class="ml-auto TripListName  p-0 " title="end time"><b>' + dateFormat(val.EndDate, "HH:MM ") + '<i class=" fad fa-flag-checkered fa-fw "></b></i></div>';
				$str += '		</div>';
				if ($expanded == true) {
					$str += '		<div class="col-12 d-flex px-1 small ">';
					$str += '			<div class="col-3 TripListNameS p-0 pr-3 " title="distance in km"><i class="fas fa-route fa-fw "></i> ' + val.Distance + ' </div>';
					if (val.FuelUsage > 0) {
						$str += '			<div class="col-3 TripListNameS p-0 pr-3 " title="fuelusage L/100km"> <i class="fas fa-tint tiny fa-fw "></i> ' + val.FuelUsage + ' </div>';
					} else {
						$str += '			<div class="col-3 TripListNameS p-0 pr-3">  </div>';
					}
					if (val.FuelUsed > 0) {
						$str += '			<div class="col-3 TripListNameS p-0" title="fuel used in L"> <i class="fas fa-gas-pump fa-fw tiny "></i> ' + val.FuelUsed + ' </div>';
					} else {
						$str += '		<div class="col-3 TripListNameS p-0 pr-3">  </div>';
					}
					$str += '			<div class="ml-auto text-right TripListNameS p-0" title="duration in h:mm"> ' + val.Duration + ' <i class="fad fa-clock fa-fw "></i></div>';
					$str += '</div>';
				}
				$str += '</div></a>';
				$LInfo.append($str);
				BreakDate = val.EndDate;
			}
		} else {
			var $Duration = new Date() - new Date(val.StartDate);
			var $BDuration = new Date(val.StartDate + ' UTC') - new Date(BreakDate + ' UTC');
			if ($BDuration > 60000) {
				$str = '	<div class="TripBreakLine d-flex">';
				$str += '		<div class="mx-auto badge TripBreakText text-secondary">stopped <i class="fad fa-clock fa-fw"></i> ' + dateFormat($BDuration, "HH:MM UTC") + '</div>';
				$str += '	</div>';
				$LInfo.append($str);
			}
			//				$Distance=($C.OdoMeter-val.start_odometer)/1000;
			$str = '<a href="javascript:ShowTripMap(' + val.Trip_NO + ');">';
			$str += '	<div class="TripListingActive">';
			$str += '		<div class="col-12 d-flex px-1 ' + $delayed + '">';
			$str += '			<div class="col-3 TripListName p-0 pr-3 "><i class="fad fa-flag fa-fw "></i> <b>' + dateFormat(val.StartDate, "HH:MM ") + '</b></div>';
			$str += '			<div class="mr-auto TripListNameS p-0 pr-3"> ' + val.driver + ' </div>';
			$str += '			<div class="ml-auto TripListName text-warning "><b>' + dateFormat(currentDT, "HH:MM ") + '</b> <i class="fas fa-flip-horizontal fa-wind fa-sm fa-fw " ></i><i class="fas fa-truck-moving"></i></div>';
			$str += '		</div>';
			if ($expanded == true) {
				$str += '		<div class="col-12 d-flex px-1 small">';
				$str += '			<div class="col-4 mr-auto TripListNameS no-padding "><i class="fas fa-route fa-fw "></i> ' + $Distance + ' km </div>';
				$str += '			<div class="col-3 text-right TripListNameS no-padding "> ' + dateFormat($Duration, "UTC:HH:MM ") + ' <i class="far fa-clock fa-fw "></i></div>';
				$str += '		</div>';
			}
			$str += '	</div></a>';
			$LInfo.append($str);
			BreakDate = val.EndDate;
		}
		refdate=val.date;			
	});


}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function ShowVehicleTable(){
	table=$('#tableVehicles').DataTable( {
		ajax: {url: window.location.origin+'/scripts/GetVehicles',   dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		oLanguage: {processing: '<span id="loading-image" class="fad fa-spinner fa-spin fa-2" ></span>'},
		processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[12,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,responsive: true,
		buttons: [
				{ extend: 'excel',  title: 'rFMS Reader VehicleOverview' },
				{ extend: 'pdf',   orientation: 'landscape',  pageSize: 'A3', messageTop: 'Active Vehicles',title: 'rFMS-Connect _VehicleOverview', customize: function(doc) { doc.defaultStyle.fontSize = 8; } },
//				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print',orientation: 'landscape',  pageSize: 'A4', messageTop: 'Active Vehicles',title: 'rFMS-Connect _VehicleOverview'},
//				{ extend:'colvis',class: 'btn btn-primary btn-sm'},
			],
		columns: [
			{ "data": "NewVehicle",
                    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-star rest"></i>';} else {return '';}},
					"title":" ","visible":true,"width": "1%","class":"dt-right" },
			{ "data": "tripActive",
                    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-play gray"></i>';} else {return '<i class="fas fa-stop gray"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-right" },
			{ "data": "customerVehicleName","title":"Vehicle Name","class":"dt-left"},
			{ "data": "TrailerName","title":"Trailer","class":"dt-left","render": function (data, type, row) { if(!row.TrailerName ) {return ''} else {return '<a href="trailer_edit?id='+row.TrailerId+'" class="underline "> '+row.TrailerName+' </a>'}}},
			{ "data": "Driver","title":"Driver","defaultContent": "","class":"dt-left ","render": function (data, type, row) { if(!row.Driver) {return ''} else { return '<a href="driver_edit?id='+row.DriverId+'" class="underline "> '+row.Driver+' </a>'}}},
			{ "data": "LicensePlate","title":"License Plate","defaultContent": "" ,"class":"dt-left","width": "6%"},
			{ "data": "OdoMeter","title":"ODO Meter","defaultContent": "", "class":"dt-right","width": "4%" },
			{ "data": "serviceDistance","title":"Service Distance","defaultContent": "", "class":"dt-center","width": "4%"  },
			{ "data": "LastActivity","title":"Last Contact UTC","defaultContent": "","class":"dt-left" },
			{ "data": "FuelLevel","title":"Fuel level (%)","defaultContent": "" ,"class":"dt-center","width": "3%"},
			{ "data": "CatalystFuelLevel","title":"Adblue level (%)","defaultContent": "" ,"class":"dt-center","width": "3%" },
			{ "data": "Warnings",
					"render": function(data, type, row) {if(data >= '1') {return '<i class="fad fa-exclamation-triangle text-danger"></i><span class="danger"><b> '+data+' </b></span>';} else {return '<i class="fad fa-exclamation-triangle hide"></i>';}},
					"title":"RED text-warning","defaultContent": "" ,"class":"dt-center","width": "3%" },
			{ "data": "DamageCount",
					"render": function(data, type, row) {if(data >= '1') {return '<i class="fad fa-tools text-danger"></i><span class="danger"><b> '+data+' </b></span>';} else {return '<i class="fad fa-tools hide"></i>';}},
					"title":"Severe Damage","defaultContent": "" ,"class":"dt-center","width": "3%" },
			{ "data": "grossCombinationVehicleWeight","title":"Current Weight","defaultContent": "" ,"class":"dt-right","width": "3%" },
			{ "data": null,"targets": -1,"width": "3%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="vehicle_edit?id='+row.id+'" title="View/Edit Vehicle details"><i class="fad fa-edit fa-fw"></i></a>'}},

			]
	} );

	$('#tableVehicles').off('click');
	$('#tableVehicles').on('click', 'tr', function () {
        var data = table.row( this ).data();
		$(window).attr("location","/pages/vehicle_edit?id="+data['id']);
    });
}
function ShowPDCTable(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table=$('#tablePDC').DataTable( {
		ajax: {
			url: window.location.origin+'/scripts/GetPDC?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
			dataSrc: ''
		},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search',zeroRecords:"no PDC checks found for selected period or group"	},
		processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[0,'desc' ],[7,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		rowGroup: {
			dataSrc: "groupName",
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="12">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: {buttons: [
				{ extend: 'excel',  title: 'rFMS Reader VehicleOverview' },
				{ extend: 'pdf',   orientation: 'landscape',  pageSize: 'A3', messageTop: 'Active Vehicles',title: 'rFMS-Connect _VehicleOverview', customize: function(doc) { doc.defaultStyle.fontSize = 8; } },
				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';', },
				{ extend: 'print',orientation: 'landscape',  pageSize: 'A4', messageTop: 'Active Vehicles',title: 'rFMS-Connect _VehicleOverview'},
			]},
		createdRow: function( row, data, dataIndex){
			if( data.critical_damages>0){
				$(row).addClass('alert-danger');
			}
		},
		columns: [
			{ "data": "date","title":"Date","class":"dt-left"},
			{ "data": "customerVehicleName","title":"Vehicle Name","class":"dt-left"},
			{ "data": "TrailerName","title":"Trailer","class":"dt-left"},
			{ "data": "driver","title":"Driver","class":"dt-left"},
			{ "data": "groupName","title":"Group","class":"dt-left"},
			{ "data": "templateName","title":"Check","class":"dt-left"},
			{ "width": "5%" ,"bSortable" : false,mRender:
					function (data, type, row) { return '<btn onclick="ShowPDC_PDFReport(`'+row.id+'`,`'+row.customerVehicleName+'`,`'+row.date+'`);" data-toggle="modal" data-target="#PDFReport" class="btn text-danger" title="download pdf"><i class="fas fa-fw fa-file-pdf "></i></btn>'	}
			},
			{ "data": "critical_damages",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fas fa-exclamation fa-fw text-danger"></i>'+data;} else {return '<i class="fa-fw"></i>'+data;}},
				"title":"critical damages ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "damages","title":"damages","defaultContent": "" ,"width": "2%","class":"dt-center"},
			{ "data": "add_notes","title":"notes","defaultContent": "", "class":"dt-left" },
			{ "data": "checked","title":"checked","defaultContent": "","class":"dt-center","width": "2%" },
			{ "data": null,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="vehicle_edit?id='+row.id+'" title="edit PDC" ><i class="fad fa-edit fa-fw"></i></a>'}},
		]
	} );

	$('#tableVehicles').off('click');
	$('#tableVehicles tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
	$('#tableVehicles tbody').on('click', 'tr.dtrg-group.dtrg-start.dtrg-level-0', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(true);
	});
}
function ShowAlertHistoryTable($group){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var table=$('#tableAlertHistory').DataTable( {
		ajax: {
			url: window.location.origin+'/scripts/GetAlertsHistory?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
			dataSrc: ''
		},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search',"zeroRecords": "<i>no alert-records available for selected period or group</i>",	},
		processing: false,paging: true,pageResize: true,"bLengthChange": false,"pagingType": "numbers",
		autoWidth: false,destroy:true,order:[[1,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {buttons: [
				{ extend: 'excel', title: 'rFMS Reader VehicleOverview' },
				{ extend: 'pdf',   orientation: 'portrait',  pageSize: 'A4', messageTop: 'Active Vehicles',title: 'rFMS-Connect Overview vehicles with warnings', customize: function(doc) { doc.defaultStyle.fontSize = 8; } },
				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';', },
				{ extend: 'print', orientation: 'portrait',  pageSize: 'A4', messageTop: 'Active Vehicles',title: 'rFMS-Connect Overview vehicles with warnings'},
			]},
		columns: [
			{ "data": "CountedYellow",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fas fa-exclamation-triangle fa-fw text-warning"></i><span class="warning">'+data+'</span>';} else {return '<i class="fa-fw"></i>';}},
				"title":"Yellow warnings ","visible":true,"width": "6%","class":"dt-center" },
			{ "data": "CountedRed",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fas fa-exclamation-triangle fa-fw text-danger"></i><span class="danger">'+data+'</span>';} else {return '<i class="fa-fw"></i>';}},
				"title":"Red warnings ","visible":true,"width": "6%","class":"dt-center" },
			{ "data": "vehicle",		"title":"vehicle","defaultContent": "" ,"class":"dt-left"},
			{ "data": "customerName",	"title":"Group","class":"dt-left"},
			{ "data": "LicensePlate",	"title":"License plate","class":"dt-left"},
			{ "data": "brand",			"title":"brand","class":"dt-left"},
			{ "data": "model",			"title":"model","class":"dt-left"},
		]
	} );
	$('#tableVehicles').off('click');
}
function ShowMaintenanceTable($Vehicles){
	var table=$('#tableMaintenance').DataTable({
		ajax: {url: window.location.origin+'/scripts/GetMaintenanceVehicles', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[3,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,"lengthMenu": [[8, -1], [8, "All"]],
		createdRow: function( row, data, dataIndex){
            if( data.Due==true){
                $(row).addClass('delayedrow');
            }
		},
		buttons: {
			buttons: [
				{ extend: 'excel', text: 'excel',  title: 'Vehicles Due Maintenance Overview_'+dateFormat(new Date(), "yyyy-mm-dd") },
				{ extend: 'pdf', text: 'pdf',  orientation: 'landscape',  pageSize: 'A4', messageTop: 'Maintenance Vehicles',title: 'rFMS Reader Vehicles Due Maintenance Overview',
				 customize: function(doc) { doc.defaultStyle.fontSize = 8; } },
//				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';',title: 'rFMS Reader Vehicles Due Maintenance Overview', },
//				{ extend: 'print', text: 'print', orientation: 'landscape',  pageSize: 'A4', messageTop: 'Maintenance Vehicles',title: 'rFMS Reader Vehicles Due Maintenance Overview'},
			]
		},
		columns: [
			{ "data": "Name",			"title":"Name"			,"class":"dt-left"},
			{ "data": "LicensePlate",	"title":"License Plate"	,"class":"dt-left"},
			{ "data": "type",			"title":"Type"			, "class":"dt-left"},
			{ "data": "OdoMeter","title":"Current Milage","class":"dt-center"},
			{ "data": "serviceDistance","title":"Service Distance","class":"dt-center"},
			{ "data": "ServiceDue",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fad fa-circle fa-fw text-danger"></i>';} else {return '<i class="far fa-circle fa-fw "></i>';}},
				"title":"Service Overdue ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "MOTDue",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fad fa-circle fa-fw text-danger"></i>';} else {return '<i class="far fa-circle fa-fw "></i>';}},
				"title":"MOT due ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "TachoDue",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fad fa-circle fa-fw text-danger"></i>';} else {return '<i class="far fa-circle fa-fw "></i>';}},
				"title":"Tacho due ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "Damages",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fad fa-circle fa-fw text-danger"></i> '+data;} else {return '<i class="far fa-circle fa-fw "></i>';}},
				"title":"Damages ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "VIN", 			"title":"vin"			, "class":"dt-left","visible":false  },
			{ "data": "Country", 		"title":"Customer Country"},
			{ "data": "Service_Homedealer", "title":"Workshop" },

			],

	} );
}
function ShowDriverTable(){
	var table =$('#tableDrivers').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetDrivers', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		oLanguage: {sProcessing: "<span class='loader'></span>"}, processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[1,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel', 	title: 'rFMS-Connect  Drivers_Overview' },
				{ extend: 'pdf', 	 orientation: 'landscape', pageSize: 'A4', messageTop: 'Driver Overview',title: 'rFMS-Connect  Drivers_Overview'},
			]
		},
		columns: [
		{ "data": "tripActive",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-play grey iconbox"></i>';} else {return '<i class="fas fa-stop grey iconbox"></i>';}},
				"title":" ","visible":true,"width": "1%","class":"dt-center" },
			{ "data": "Lastname",					"title":"Lastname",				"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "Surname",					"title":"Name",					"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "Address",					"title":"Address",				"defaultContent": "", "visible":false,"class":"dt-left" },
			{ "data": "Country",					"title":"Country",				"defaultContent": "", "visible":false,"defaultContent": "" },
			{ "data": "City",						"title":"City", 				"defaultContent": "", "visible":false,"class":"dt-left"},
			{ "data": "Phone_Mobile",				"title":"Mobile",				"defaultContent": "", "visible":true},
			{ "data": "eMail",						"title":"eMail",				"defaultContent": "", "visible":false },
			{ "data": "tachoDriverIdentification",	"title":"Tacho card ID",		"defaultContent": "", "visible":true, "class":"dt-left","width": "10%"  },
			{ "data": "Validity",					"title":"Valid until",			"defaultContent": "", "visible":true, "class":"dt-left" },
			{ "data": "RemainingDriveToday",		"title":"Remaining Today",		"defaultContent": "", "visible":true,  "class":"dt-left" },
			{ "data": "LastDriverActivity",			"title":"Last Activity",		"defaultContent": "", "visible":true,  "class":"dt-left" },
			{ "data": "customerVehicleName",		"title":"Driving on",			"defaultContent": "", "visible":true,  "class":"dt-left" },
			{ "data": "CurrentStatus",				"title":"TachographState",		"defaultContent": "", "visible":true,  "class":"dt-left" },
			{ "data": null,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="driver_edit?id='+row.id+'"> <i class="fad fa-edit fa-fw"></i></a>'}},
			{ "data": null,"width": "2%" ,"bSortable" : false,mRender: function (data, type, row) { return '<btn onclick="DeleteDriver(`'+row.id+'`,`'+row.tachoDriverIdentification+'`);"><i class="fad fa-trash-alt fa-fw"><i></btn>'}},
			]
	} );
	$('#tableDrivers').off('click');
	$('#tableDrivers tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowVehicleGeofencingTable(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tableGeofencingActivity').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetGeofencingReport?startDate='+SelectedDate+'&endDate='+SelectedDateEnd, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 400px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[5,'asc' ]],
		rowGroup: {
			dataSrc: "GeofenceName",
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="9">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: {
			buttons: [
				{ extend: 'excel',title: 'rFMS-Connect Geofencing_Report' },
				{ extend: 'pdf',  orientation: 'landscape', pageSize: 'A4', messageTop: 'Geofencing Overview',title: 'rFMS-Connect  Geofencing_Overview'},
//				{ extend: 'csv',  extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print' },
			]
		},
		columns: [
			{ 								"title":"-",			"defaultContent": "","class":"dt-left","visible":true,width:"1%"},
			{ "data": "customerVehicleName","title":"Vehicle",		"defaultContent": "","class":"dt-left"},
			{ "data": "groupName",			"title":"Group",		"defaultContent": "","class":"dt-left"},
			{ "data": "CurrentStatus",		"title":"Status",		"defaultContent": ""},
			{ "data": "registration",		"title":"trigger when",	"defaultContent": ""},
			{ "data": "countTrigger",		"title":"countTrigger",	"defaultContent": ""},
			{ "data": "GeofenceName",		"title":"Geofence",		"defaultContent": "","class":"dt-left"},
			{ "data": "alert",				"title":"Alerts",		"defaultContent": "","class":"dt-left"},
			{ "data": "latitude",			"title":"Lat", 			"defaultContent": "","class":"dt-left",visible:false},
			{ "data": "longitude",			"title":"Long",			"defaultContent": "",visible:false},
			]
	} );
	$('#tableGeofencingActivity').off('click');
	$('#tableGeofencingActivity tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	} );

}
function ShowTrailerTable(){
	var table =$('#tableTrailers').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetTrailers', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel', 	title: 'rFMS-Connect _Trailers_Overview' },
				{ extend: 'pdf', 	 orientation: 'landscape', pageSize: 'A4', messageTop: 'Trailer Overview',title: 'rFMS-Connect _Trailers_Overview'},
//				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print' 	 },
			]
		},
		columns: [
			{ "data": "copplingStatus",
                    "render": function(data, type, row) {if(data =="1") {return '<i class="fas fa-link rest"></i>';} else {return '<i class="fas fa-unlink work"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "trailerName",		"title":"Trailer",		"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "LicensePlate",		"title":"Licenseplate",	"defaultContent": "", "visible":true, "defaultContent": "" },
			{ "data": "customerVehicleName","title":"Vehicle",		"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "LastActivity",		"title":"LastActivity","defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "brand",				"title":"Trailer brand","defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "model",				"title":"Trailer model","defaultContent": "", "visible":true, "class":"dt-left" },
			{ "data": "grossCombinationVehicleWeight",	"title":"TotalWeight","defaultContent": "", "visible":true, "class":"dt-left","width": "3%"},
			{ "data": null,"width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="trailer_edit?id='+row.id+'" class="btn btn-default "><i class="fad fa-edit"></i></a>'}},
			{ "data": null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="trailer_delete?id='+row.id+'" class="btn "><i class="fad fa-trash-alt "><i></a>'}},

			]
	} );
	$('#tableTrailers').off('click');
	$('#tableTrailers tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowAPITable(){
	var table =$('#tableAPI').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetAPICollectors', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"lengthMenu": [[8, -1], [8, "All"]],
		autoWidth: false,destroy:true,order:[[0,'asc' ],[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,"bLengthChange": false,searching: false,
		buttons: {
			buttons: [
				{ extend: 'excel', title: 'rFMS-Connect _APICollectors_Overview' },
				{ extend: 'pdf', orientation: 'landscape', pageSize: 'A4', messageTop: 'API CollectorsOverview',title: 'rFMS-Connect _APICollectors_Overview'},
			]
		},
		columns: [
			{ "data": "active",
                    "render": function(data, type, row) {if(data =="1") {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off gray"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "name",			"title":"Name",		"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "TypeName",		"title":"API Type",	"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "vendor",			"title":"Vendor",	"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "apiCustomerName","title":"Account at vendor",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "auth_type",		"title":"Authentication",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "CountFI",
				"render": function(data, type, row) {if(data ==0) {return '<i class="fad fa-check "></i>';} else {return '<i class="fad fa-exclamation-triangle "></i>';}},
				"title":"Quality ","visible":true,"width": "2%","class":"dt-center " },
			{ "data": "EndPoints",		"title":"EndPoints","defaultContent": "", "visible":true, "class":" dt-center","width": "3%"},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<a href="'+window.location.origin+'/pages/api_edit?id='+row.id+'" ><i class="fad fa-edit fa-fw"></i></a>'}},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<span onclick="DeleteAPICollector(`'+row.id+'`,`'+row.name+'`);" title="delete"><i class="fad fa-trash fa-fw"></i></span>'}},
			]
	} );
	$('#tableAPI').off('click');
	$('#tableAPI tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowAPISchedulerTable(){
	var table =$('#tableAPIScheduler').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetAPIScheduler', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"lengthMenu": [[8, -1], [8, "All"]],
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,"bLengthChange": false,searching: false,
		buttons: {
			buttons: [
				{ extend: 'excel', title: 'rFMS-Connect _APICollectors_Overview' },
				{ extend: 'pdf', orientation: 'landscape', pageSize: 'A4', messageTop: 'API CollectorsOverview',title: 'rFMS-Connect _APICollectors_Overview'},
			]
		},
		columns: [
			{ "data": "active",
                    "render": function(data, type, row) {if(data =="1") {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off gray"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "name",			"title":"Name",		"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "TypeName",		"title":"API Type",	"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "vendor",			"title":"Vendor",	"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "apiCustomerName","title":"Account at vendor",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "auth_type",		"title":"Authentication",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "CountFI",
				"render": function(data, type, row) {if(data ==0) {return '<i class="fad fa-check "></i>';} else {return '<i class="fad fa-exclamation-triangle "></i>';}},
				"title":"Quality ","visible":true,"width": "2%","class":"dt-center " },
			{ "data": "EndPoints",		"title":"EndPoints","defaultContent": "", "visible":true, "class":" dt-center","width": "3%"},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<a href="api_edit?id='+row.id+'"   class="btn btn-default "><i class="fad fa-edit"></i></a>'}},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<a href="api_delete?id='+row.id+'" class="btn btn-default "><i class="fad fa-trash-alt "><i></a>'}},
			]
	} );
	$('#tableAPIScheduler').off('click');
	$('#tableAPIScheduler tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowAPIScripts(){
	var table =$('#tableAPIScripts').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetAPIScriptTypes', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"lengthMenu": [[8, -1], [8, "All"]],
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,"bLengthChange": false,searching: false,
		buttons: {
			buttons: [
				{ extend: 'excel', title: 'rFMS-Connect _APICollectors_Overview' },
				{ extend: 'pdf', orientation: 'landscape', pageSize: 'A4', messageTop: 'API CollectorsOverview',title: 'rFMS-Connect _APICollectors_Overview'},
			]
		},
		columns: [
			{ "data": "installed",
                    "render": function(data, type, row) {if(data =="1") {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off gray"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "name",			"title":"Name",			"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "description",	"title":"description",	"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "protocol",		"title":"protocol",		"defaultContent": "", "visible":true, "class":"dt-left " },
			{ "data": "version",		"title":"version",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "script",			"title":"script",		"defaultContent": "", "visible":true, "class":"dt-left "},
			{ "data": "url_ext",		"title":"url_ext",		"defaultContent": "", "visible":true, "class":" dt-left"},
			{ "data": "header",			"title":"header",		"defaultContent": "", "visible":true, "class":" dt-left"},
			{ "data": "ApiTypeName",	"title":"ApiTypeName",	"defaultContent": "", "visible":true, "class":" dt-left","width": "3%"},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<a href="api_type_edit?id='+row.id+'"   class="btn btn-default "><i class="fad fa-edit"></i></a>'}},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center ","bSortable" : false,render: function (data, type, row) { return '<a href="api_type_delete?id='+row.id+'" class="btn btn-default "><i class="fad fa-trash-alt "><i></a>'}},
			]
	} );
	$('#tableAPIScripts').off('click');
	$('#tableAPIScripts tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowAPIScheduler($SearchId){
	if ($SearchId > 0){	$search='?id='+$SearchId;} else { $search='';}
	var table =$('#APIScheduleTable').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetAPIScheduler'+$search, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"searching": false,"bLengthChange": false,
		autoWidth: false,destroy:true,dom: 'Bfltirp',bInfo: false, scrollCollapse: true,scrollX: false,deferRender: true,sorting:true,order:[[6,'desc' ]],"lengthMenu": [[10, -1], [10, "All"]],
		buttons: {buttons: []},
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data =="1") {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off gray"></i>';}},
				"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "collector_id",	"title":"collector_id",	"defaultContent": "", "visible":false,"class":"dt-left"},
			{ "data": "Description",	"title":"Description",	"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "name_EndPoint",	"title":"Endpoint",		"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "typeId",			"title":"typeId",		"defaultContent": "", "visible":false, "class":"dt-left" },
			{ "data": "frequency",		"title":"frequency in min",	"defaultContent": "", "visible":true, "defaultContent": "" },
			{ "data": "lastExecution",	"title":"lastExecution","defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "lastStatus",
				"render": function(data, type, row) {if(data =="200") {return '<i class="fas fa-check text-success"></i>';} else {return '<i class="fas fa-exclamation-triangle text-danger"></i>';}},
				"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ "data": "lastStatus",		"title":"lastStatus",	"defaultContent": "", "visible":false, "class":"dt-left" },
			{ "data": "Script",			"title":"Script",	"defaultContent": "", "visible":false, "class":"dt-left" },
			{ "data": null,"targets": -1,"width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="'+window.location.origin+'/pages/api_edit?id='+row.id+'" class="btn btn-default "><i class="fad fa-edit"></i></a>'}},
			{ "data": null,"targets": -1,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="apischedule_delete?id='+row.id+'" class="btn btn-default editor_delete text-danger"><i class="fad fa-trash-alt text-danger"><i></a>'}},
			]
	} );
	$('#APIScheduleTable').off('click');
	$('#APIScheduleTable tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowAdminAuditTable(){
	var table =$('#AuditTable').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetAdminAuditLogs', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
		 processing: false,paging: true,pageResize: true,"searching": false,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: false, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {buttons: []},
		columns: [
			{ "data": "id",		"title":"id",	"defaultContent": "", "class":"dt-left"},
			{ "data": "user",	"title":"user",	"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "page",	"title":"page",	"defaultContent": "", "visible":true, "class":"dt-left"},
			{ "data": "timestamp",	"title":"time",		"defaultContent": "","class":"dt-left" },
			{ "data": "ip",		"title":"ip-address",	"defaultContent": "", "visible":true, "defaultContent": "" },
			{ "data": null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="/scripts/delete_auditlog?id'+row.id+'" class="btn btn-default editor_delete text-danger"><i class="fad fa-trash-alt text-danger"><i></a>'}},
			]
	} );
	$('#AuditTable').off('click');
//	$('#AuditTable').on('click', 'tr', function () {
//        var data = table.row( this ).data();
//		$(window).attr("location","/scripts/delete_auditlog?id="+data['id']);
//    });
}
function ShowPagesPermissionsTable(){
	var table =$('#tablePagePermissions').DataTable( {
		ajax: {
        url: window.location.origin+'/scripts/GetPagesPermissions',
        dataSrc: ''
		},
	language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: ' search'	},
	 processing: false,paging: true,bInfo:false,"lengthMenu": [[10, -1], [10, "All"]],"bLengthChange": false,
	autoWidth: false,destroy:true,order:[[3,'desc' ]],dom: 'Bfltirp',scrollX: true,deferRender: true, pageResize: true, "searching": true,
	buttons: {			buttons: [
				{ extend: 'excel',title: 'rFMS-Connect _APICollectors_Overview' },
				{ extend: 'pdf',  orientation: 'landscape', pageSize: 'A4', messageTop: 'API CollectorsOverview',title: 'rFMS-Connect _APICollectors_Overview'},
//				{ extend: 'csv', extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print' },
			]},
	columns: [
		{ "data": "private",
			"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-check-square rest"></i>';} else {return '<i class="far fa-square "></i>';}},
			"title":" private","visible":true,"width": "1%","class":"dt-center" },
		{ "data": "page","title":"Page Name","class":"dt-left"},
		{ "data": "Permissions","title":"Permissions","defaultContent": "", "class":"dt-left","width":"70%" },
		{ "data": "id","title":"","class":"dt-left","width":"2%"},
		{ "data": null,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="../users/admin_page?id='+row.id+'"><i class="fad fa-edit fa-fw"></i></a>'}},
		]
	} );
	$('#tablePagePermissions').off('click');
}
function ShowAdminNotifications(){
	var table =$('#tableNotifications').DataTable( {
	ajax: {url: window.location.origin+'/scripts/GetAdminNotifications',  dataSrc: ''},
	language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
	processing: false,paging: true,bInfo:true,"lengthMenu": [[10, -1], [10, "All"]],"bLengthChange": false,
	autoWidth: true,destroy:true,order:[[2,'desc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": true,
	buttons: {			buttons: [
			{ extend: 'excel',title: 'rFMS-Connect _APICollectors_Overview' },
			{ extend: 'pdf',  orientation: 'landscape', pageSize: 'A4', messageTop: 'API CollectorsOverview',title: 'rFMS-Connect _APICollectors_Overview'},
//			{ extend: 'csv', extension: '.csv', fieldSeparator: ';', },
//			{ extend: 'print' },
		]},
	columns: [
		{ "data": "name","title":"name","class":"dt-left"},
		{ "data": "description","title":"description","class":"dt-left"},
		{ "data": "groupName","title":"group/domain","class":"dt-left"},
		{ "data": "public",
				"render": function(data, type, row) {if(data == '1') {return '<i class="far fa-check-square "></i>';} else {return '<i class="far fa-square gray"></i>';}},
				"title":"@login","visible":true,"class":"dt-center",width:'3%' },
		{ "data": "desktop",
				"render": function(data, type, row) {if(data == '1') {return '<i class="far fa-check-square "></i>';} else {return '<i class="far fa-square gray"></i>';}},
				"title":"@dashboard","visible":true,"class":"dt-center",width:'3%' },
		{ "data": "startPublish","title":"Start Publish","defaultContent": "", "class":"dt-left" },
		{ "data": "endPublish","title":"End Publish","defaultContent": "", "class":"dt-left" },
		{ "data": null,"width": "2%" ,"class":"dt-center","bSortable" : false,render: function (data, type, row) { return '<a href="../pages/notifications_edit?id='+row.id+'" class=" " title="edit"><i class="fad fa-edit fa-fw"></i></a>'}},
		{ "data": null,"width": "2%" ,"class":"dt-center","bSortable" : false,render: function (data, type, row) { return '<span onclick="DeleteNotification(`'+row.id+'`,`'+row.name+'`);" title="delete"><i class="fad fa-trash fa-fw"></i></span>'}},

		]
	} );
	$('#tableNotifications').off('click');
	$('#tableNotifications tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowUserTable($data){
	var table =$('#tableUsers').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetUsers', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		 processing: false,paging: true,pageResize: true,"bLengthChange": false,
		autoWidth: false,destroy:true,order:[[1,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel', 	title: 'rFMS-Connect  Users_Overview' },
				{ extend: 'pdf', 	 orientation: 'landscape', pageSize: 'A4', messageTop: 'Users Overview',title: 'rFMS-Connect  Users_Overview'},
//				{ extend: 'csv', 	 extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print' },
			]
		},
	columns: [
			{ "data": "permissions",
			"render": function(data, type, row) {if(data =="1") {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off gray"></i>';}},
			"title":" ","visible":true,"width": "2%","class":"dt-center" },
			{ data: "lname",		title:"Lastname",	defaultContent: "", "class":"dt-left"},
			{ data: "fname",		title:"Name",		defaultContent: "", "class":"dt-left"},
			{ data: "email",		title:"e-Mail",		defaultContent: "-", "class":"dt-left"},
			{ data: "roles",		title:"Roles",		defaultContent: "-"},
			{ data: "driver_id",
                    "render": function(data, type, row) {if(data.length>1) {return '<i class="fad fa-check rest"></i>';} else {return '';}},
					"title":"Driver ","visible":true,"width": "2%","class":"dt-center" },
			{ data: "pwa",
                    "render": function(data, type, row) {if(data==0) {return '<i class="fad fa-user-shield rest"></i> ';} else {return '<i class="fad fa-history text-danger" title="password expired"></i>';}},
					"title":"Password","visible":true,"width": "2%","class":"dt-center" },
			{ data: "creator",		title:"Created by",defaultContent: "" },
			{ data: "join_date",	title:"Join Date",	defaultContent: "",visible: false },
			{ data: "last_login",	title:"Last Login",	defaultContent: "" },
			{ data: null,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="../users/admin_user?id='+row.id+'"><i class="fad fa-edit fa-fw"></i></a>'}},
			{ data: null,"width": "2%",mRender: function (data, type, row) { return '<a href="" onclick="DeleteUser(`'+row.id+'`,`'+row.email+'`);"><i class="fad fa-trash-alt fa-fw"><i></a>'}},
			]
	} );
	$('#tableUsers').off('click');
	$('#tableUsers tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowVehicleRDTable($SearchVin){
	var table =$('#tableDamage').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetVehicleDamages?id='+$SearchVin, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		 processing: false,paging: false,pageResize: true,
		autoWidth: true,destroy:true,order:[[0,'desc' ]] ,dom: 'Bfltirp',bInfo: false, bSort:false, scrollCollapse: false,scrollX: true,deferRender: true, searching: false,
		buttons: {buttons: [],},
		columns: [
			{ data: "DamageCreated",	title:"registered",	defaultContent: "-", "class":"dt-left"},
			{ data: "title",			title:"title",		defaultContent: "", "class":"dt-left"},
			{ data: "event_description",title:"event",		defaultContent: "", "class":"dt-left"},
			{ data: "description",		title:"description",defaultContent: "", "class":"dt-left",visible:false},
			{ data: "status",			title:"status",		defaultContent: "", "class":"dt-left",visible:false},
			{ data: "repairStatus",		title:"repairstatus",defaultContent: "-",visible:false},
			{ data: "driver",			title:"Driver",		defaultContent: "<i class='far fa-square'></i>",visible:false },
			{ data: "id",				title:"is",			defaultContent: "", "class":"dt-left",visible:false },
			{ data: null,width: "1%" ,mRender: function (data, type, row) { return '<a href="../pages/pdc_edit?id='+row.id+'" class="btn btn-default"><i class="fard fa-edit"><i></a>'}},
			{ data: null,width: "1%",mRender: function (data, type, row) { return '<a href="../pages/pdc_delete?id='+row.id+'" class="btn btn-default "><i class="fad fa-trash-alt "><i></a>'}},
			]
	} );
	$('#tableDamage').off('click');
	$('#tableDamage tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowGroupAdminTable() {
	var table =$('#tableGroups').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetGroupAdmin', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records',zeroRecords:"no group created yet!"	},
		processing: false,paging: true,pageResize: false,"lengthMenu": [[8, -1], [8, "All"]],
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel', 	title: 'rFMS-Connect  Groups_Overview' },
				{ extend: 'pdf', 	orientation: 'landscape', pageSize: 'A4', messageTop: 'Groups Overview',title: 'rFMS-Connect  Groups_Overview'},
				{ extend: 'csv',  	extension: '.csv', fieldSeparator: ';', },
				{ extend: 'print',	class: 'btn btn-primary btn-sm' },
			]
		},
		columns: [
			{ data: "name",					title:"Groupname",	defaultContent: "", "class":"dt-left"},
			{ data: "accountnumber",		title:"Code",		defaultContent: "", "class":"dt-left","width": "10%"},
			{ data: "Country",				title:"Country",	defaultContent: "", "class":"dt-left"},
			{ data: "TotalDrivers",			title:"Drivers",	defaultContent: "" ,"class":"dt-left"},
			{ data: "TotalVehicles",		title:"Vehicles",	defaultContent: "" ,"class":"dt-left" },
			{ data: "Service_Homedealer",	title:"Home Dealer",defaultContent: ""},
			{ data: "IsDealer",
					"render": function(data, type, row) {if (data >0) {return '<span><i class="fad fa-check rest" ></i></span>';} else {return '';}},
					"title":"Dealer","defaultContent": "" ,"class":"dt-center","width": "3%" },
			{ data: null,"width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="../users/admin_group?id='+row.id+'" class="btn btn-default "><i class="fad fa-edit"></i></a>'}},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="../users/admin_group_delete?id='+row.id+'" class="btn btn-default"><i class="fad fa-trash-alt"><i></a>'}},
			]
	} );
	$('#tableGroups').off('click');
	$('#tableGroups tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowGroupTable(){
	var table =$('#tableGroups').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetGroupAdmin', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		 processing: false,paging: true,
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel', 	title: 'rFMS-Connect  Groups_Overview' },
				{ extend: 'pdf', 	 orientation: 'landscape', pageSize: 'A4', messageTop: 'Groups Overview',title: 'rFMS-Connect  Groups_Overview'},
//				{ extend: 'csv',   extension: '.csv', fieldSeparator: ';', },
//				{ extend: 'print'},
			]
		},
		columns: [
			{ data: "name",					title:"Groupname",	defaultContent: "", "class":"dt-left"},
			{ data: "accountnumber",		title:"Code",		defaultContent: "", "class":"dt-left","width": "10%"},
			{ data: "Country",				title:"Country",	defaultContent: "", "class":"dt-left"},
			{ data: "TotalDrivers",			title:"Drivers",	defaultContent: "" ,"class":"dt-left"},
			{ data: "TotalVehicles",		title:"Vehicles",	defaultContent: "" ,"class":"dt-left" },
			{ data: "Service_Homedealer",	title:"Home Dealer",defaultContent: ""},
			{ "data": "IsDealer",
					"render": function(data, type, row) {if (data >0) {return '<span class="fa-stack"><i class="far fa-square fa-stack-1x gray"></i></i><i class="fas fa-check fa-stack-1x" ></i></span>';} else {return '<span class="fa-stack"><i class="far fa-square fa-stack-1x gray"></i></span>';}},
					"title":"Dealer?","defaultContent": "" ,"class":"dt-right","width": "3%" },
			{ "width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="group_edit?id='+row.id+'" title="edit"><i class="fad fa-edit fa-fw"></i></a>'}},
			]
	} );
	$('#tableGroups').off('click');
	$('#tableGroups tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowDealerTable(){
	var table =$('#tableDealer').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetDealers', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	pageResize: true,lengthMenu: [[10, -1], [10, "All"]],
		autoWidth: false,destroy:true,order:[[1,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,scrollX: true,deferRender: true,
		buttons: {
			buttons: [
				{ extend: 'excel',title: 'rFMS-Connect _Dealers_Overview' },
				{ extend: 'pdf'	 ,orientation: 'landscape',  pageSize: 'A4', messageTop: 'rFMS Reader Group overview',title: 'rFMS-Connect _Dealers_Overview'},
				{ extend: 'csv'	 ,extension: '.csv', fieldSeparator: ';', },
				{ extend: 'print' },
			]
		},
		columns: [
			{ "data": "Dealer_ID","title":"ID","defaultContent": "-" ,"class":"dt-left","width": "2%", "visible":false},
			{ "data": "Category","title":"Category","defaultContent": "-" ,"class":"dt-left", "visible":true},
			{ "data": "location","title":"Address","defaultContent": "-" ,"class":"dt-left", "visible":false},
			{ "data": "name","title":"Name","defaultContent": "" , "class":"dt-left"},
			{ "data": "adr_address_1","title":"Address","defaultContent": "" , "class":"dt-left"},
			{ "data": "adr_postalcode","title":"Postal","defaultContent": "" , "class":"dt-left"},
			{ "data": "adr_city","title":"City","defaultContent": "" , "class":"dt-left"},
			{ "data": "cnt_description","title":"Country","defaultContent": "" , "class":"dt-left"},
			{ "data": "Dealer_Phone","title":"Phone","defaultContent": "" , "class":"dt-left", "visible":false},
			{ "data": "its_24hours","title":"ITS 24 hours","defaultContent": "" , "class":"dt-left","width": "4%", "visible":false},
			{ "data": "its_trailer","title":"ITS trailer","defaultContent": "" , "class":"dt-left","width": "4%", "visible":false},
			{ "data": null,"targets": -1,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="dealer_view?id='+row.id+'" title="view details "><i class="fad fa-edit fa-fw"></i></a>'}},
			]
	} );
	$('#tableDealer').off('click');
	$('#tableDealer tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowReportTable(){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tableReport').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetReports', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records',zeroRecords: "No report scheduled"	},
		processing: false,paging: true,pageResize: true,lengthMenu: [[8, -1], [8, "All"]],
		autoWidth: false,destroy:true,order:[[5,'asc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,scrollX: true,deferRender: true,responsive: { details: false },
		rowGroup: {
			dataSrc: "ReportName",
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="12">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: [
			{
                text: '<i class="fad fa-cogs"></i>',
				class:"btn btn-primary",
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                },
				split:['pdf','excel','print']
            }
		],	
		columns: [
			{ "data": "status",
                     "render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
					 "title":" ","visible":true,"width": "2%","class":"dt-right",responsivePriority: 1,},
			{ "data": "name",			"title":"ReportName","defaultContent": "-" ,"class":"dt-left", "visible":true,responsivePriority: 1},
			{ "data": "description",	"title":"Description","defaultContent": "-" ,"class":"dt-left", "visible":false},
			{ "data": "owner",			"title":"Owner","defaultContent": "" , "class":"dt-left"},
			{ "data": "CustomerName",	"title":"Group","defaultContent": "" , "class":"dt-left"},
			{ "data": "ReportName",		"title":"Report","defaultContent": "" , "class":"dt-left"},
			{ "data": "extra_email",	"title":"receivers","defaultContent": "" , "class":"dt-left", "visible":false},
			{ "data": "reporting_frequency","title":"Frequency","defaultContent": "" , "class":"dt-left"},
			{ "data": "reporting_period","title":"Period","defaultContent": "" , "class":"dt-left"},
			{ "data": "lastRunDateTime","title":"Last Executed","defaultContent": "" , "class":"dt-left"},
			{ "data": "nextRunDateTime","title":"Planned Execution","defaultContent": "" , "class":"dt-left"},
			{ data: null,"width": "2%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a class="pointer" data-toggle="modal" data-target="#myModal" ><i class="fad fa-edit fa-fw"></i></a>'}},
			{ data: null,"width": "2%" ,"bSortable" : false,mRender: function (data, type, row) {return '<a class="pointer" onclick="DeleteReport(`'+row.id+'`,`'+row.name+'`);"><i class = "fad fa-trash-alt fa-fw"><i></a>';} }
			]
	} );
	$('#tableReport').off('click');
	$('#tableReport tbody').on( 'click', 'tr', function () {
//		var idx = table.order();
//		var title = idx[0][2];
//		alert( 'Column '+title+' is the ordering column' );
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
	$('#tableReport tbody').on('click', 'tr.dtrg-group', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});

}

function ShowDriverActivityTable(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tableDriverActivity').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetDriverActivity?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,"scrollY":  "calc(100vh - 450px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[0,'asc' ]],
		rowGroup: {
			dataSrc: "driver",
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="9">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: {buttons: []},
		columns: [
			{ "data": "driver",				"title":"Driver",		"defaultContent":  "-","class":"dt-left","visible":false},
			{ "data": "date",				"title":"Date",			"defaultContent":  "-","class":"dt-left"},
			{ "data": "customerVehicleName","title":"Vehicle",		"defaultContent":  " ","class":"dt-left"},
			{ "data": "LicensePlate",		"title":"Licenseplate",	"defaultContent":  " ","class":"dt-left","visible":true},
			{ "data": "amountTrips",		"title":"Trips",		"defaultContent":  " ","class":"dt-right"},
			{ "data": "Distance",			"title":"Total Distance","defaultContent": " ","class":"dt-right"},
			{ "data": "Duration",			"title":"Total Duration","defaultContent": " ","class":"dt-center"},
			{ "data": "DriveTime",			"title":"Total DriveTime",	"class":"dt-center"},
			{ "data": "FuelUsed",			"title":"Total FuelUsed","defaultContent": " ","class":"dt-right"},
			{ "data": "FuelUsage",			"title":"Average FuelUsage","defaultContent": " ","class":"dt-right"},
			]
	} );
	$('#tableDriverActivity').off('click');
	$('#tableDriverActivity tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});
}
function ShowPDCReport(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	$('#tableDriverActivity').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCReport?startDate='+SelectedDate+'&endDate='+SelectedDateEnd, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: false,
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[0,'asc' ]],rowGroup: {dataSrc: "driver"},
		buttons: {buttons: []},
		columns: [
			{ "data": "driver",				"title":"Driver",		"defaultContent":  "-","class":"dt-left","visible":false},
			{ "data": "date",				"title":"Date",			"defaultContent":  "-","class":"dt-left"},
			{ "data": "customerVehicleName","title":"Vehicle",		"defaultContent":  " ","class":"dt-left"},
			{ "data": "LicensePlate",		"title":"Licenseplate",	"defaultContent":  " ","class":"dt-left","visible":true},
			{ "data": "amountTrips",		"title":"Trips",		"defaultContent":  " ","class":"dt-right"},
			{ "data": "Distance",			"title":"Total Distance","defaultContent": " ","class":"dt-right"},
			{ "data": "Duration",			"title":"Total Duration","defaultContent": " ","class":"dt-center"},
			{ "data": "DriveTime",			"title":"Total DriveTime",	"class":"dt-center"},
			{ "data": "FuelUsed",			"title":"Total FuelUsed","defaultContent": " ","class":"dt-right"},
			{ "data": "FuelUsage",			"title":"Average FuelUsage","defaultContent": " ","class":"dt-right"},
			]
	} );
	$('#tableReport').off('click');
	$('#tableReport tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowReportTypes(){
	var table=$('#tableReporting').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetReportTypes', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: false,
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[0,'asc' ]],
		buttons: {buttons: []},
		columns: [
			{ "data": "name",				"title":"Name",			"class":"dt-left"},
			{ "data": "description",		"title":"Description",	"class":"dt-left"},
			{ "data": "type",				"title":"Type",			"class":"dt-left","visible":true},
			{ "data": "orientation",
				"render": function(data, type, row) {if(data == 'L') {return '<i class = "far fa-rectangle-wide"></i>';} else {return '<i class="far fa-rectangle-portrait"></i>';}},
				"title":"orientation","visible":true,"width": "2%","class":"dt-center",responsivePriority: 1,},
			{ "data": "mailsubject",		"title":"subject",		"class":"dt-left"},
			{ "data": "colorSchema",		"title":"color",		"class":"dt-center"},
			{ "data": "attachmentType",		"title":"attachement","class":"dt-center"},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="EditReportType(`'+row.id+'`);" title="edit report type"><i class="fad fa-edit"></i></span>'}},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<span onclick="DeleteReportType(`'+row.name+'`,`'+row.id+'`);" title="delete report type"><i class="fad fa-trash-alt fa-fw "><i></span>'}}
			]
	} );
	$('#tableReporting').off('click');
	$('#tableReporting tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowReportQueries(){
	var table=$('#tableReportQueries').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetReportQueries', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: false,"lengthMenu": [[5, -1], [5, "All"]],
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[0,'asc' ]],rowGroup: {dataSrc: "ReportName"},
		buttons: {buttons: []},
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":" ","visible":true,"width": "2%","class":"dt-right",responsivePriority: 1,},
			{ "data": "queryName",				"title":"Name",			"class":"dt-left"},
			{ "data": "queryDescription",		"title":"Description",	"class":"dt-left"},
			{ "data": "sequence",				"title":"sequence",		"class":"dt-left","visible":true},
			{ "data": "Creator",				"title":"Created by",	"class":"dt-left","visible":true},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="reportqueries_delete?id='+row.id+'" title="delete"><i class="fad fa-trash-alt fa-fw"><i></a>'}}
			]
	} );
	$('#tableReportQueries').off('click');
	$('#tableReportQueries tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowPDCTemplates(){
	var table=$('#tablePDCTemp').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCTemplates', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 550px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[0,'asc' ]],
		buttons: [{text:"New template", action: function ( e, dt, node, config ) { CreateNewPDCTemplate();}}],
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":" ","visible":true,"width": "2%","class":"dt-right",responsivePriority: 1,},
			{ "data": "templateName",		"title":"Name",			"class":"dt-left"},
			{ "data": "shortdescription",	"title":"Description",	"class":"dt-left"},
			{ "data": "CountedCat",			"title":"# categories",	"class":"dt-left"},
			{ "data": "CountedSubCat",		"title":"# sub categories",	"class":"dt-left"},
			{ "data": "CountedCheckItems",	"title":"# checks items",	"class":"dt-left"},
			{ "data": "CountedDamageItems",	"title":"# damage items",	"class":"dt-left"},
			{ "data": "group",				"title":"group",		"class":"dt-left","visible":true},
			{ "data": "username",			"title":"Created by",	"class":"dt-left","visible":false},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender:
					function (data, type, row) {
						if (row.active==true) {
							return '<btn onclick="ShowTemplateExample(`'+row.id+'`);" class="btn text-danger "><i class="far fa-file-pdf"></i></btn>'
						} else { return ''}
					}
			},
			{ data: null,"width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_template_edit?id='+row.id+'" title="edit"><i class="fad fa-edit fa-fw"></i></a>'}},
			{ data: null,"width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_temp_delete?id='+row.id+'" title="delete"><i class="fad fa-trash-alt fa-fw"><i></a>'}}
			]
	} );
	$('#tablePDCTemp').off('click');
	$('#tablePDCTemp tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowPDCCat(){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tablePDCC').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCCat', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 550px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[3,'asc' ]],
		rowGroup: {
			dataSrc: "templateName",
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: [{text:"New categorie", action: function ( e, dt, node, config ) { CreateNewPDCCategory();}}],
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":" ","visible":true,"width": "2%",			"class":"dt-right",responsivePriority: 1,},
			{ "data": "cat_name",			"title":"name",			"class":"dt-left"},
			{ "data": "cat_type",			"title":"type",			"class":"dt-left"},
			{ "data": "templateName",		"title":"template",		"class":"dt-left"},
			{ "data": "languageName",		"title":"language",		"class":"dt-left"},
			{ "width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_cat_edit?id='+row.id+'" title="edit"><i class="fad fa-edit fa-fw"></i></a>'}},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_cat_delete?id='+row.id+'" title="delete"><i class="fad fa-trash-alt fa-fw"><i></a>'}}
			],
	} );
	$('#tablePDCC').off('click');
	$('#tablePDCC tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});

}
function ShowPDCSubCat(){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tablePDCSC').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCSubCat', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 550px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[2,'asc' ]],
		rowGroup: {
			dataSrc: ["templateName","cat_name"],
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		} ,
		buttons: [{text:"New subcategory", action: function ( e, dt, node, config ) { CreateNewPDCSubCategory();}}],
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":" ","visible":true,"width": "2%",			"class":"dt-right",responsivePriority: 1,},
			{ "data": "subcat_name",		"title":"name",			"class":"dt-left"},
		//	{ "data": "cat_name",			"title":"category",		"class":"dt-left"},
			{ "data": "templateName",		"title":"template",		"class":"dt-left"},
			{ "data": "languageName",		"title":"language",		"class":"dt-left"},
			{ "data": "createdby",			"title":"created",		"class":"dt-left"},
			{ "width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_subcat_edit?id='+row.id+'" title="Edit"><i class="fad fa-edit fa-fw"></i></a>'}},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_subcat_delete?id='+row.id+'" Title="Delete"><i class="fad fa-trash-alt  fa-fw"><i></a>'}}
			]
	} );
	$('#tablePDCSC').off('click');
	$('#tablePDCSC tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});
}
function ShowPDCCheckItems() {
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table = $('#tablePDCCI').DataTable({
		ajax: {url: window.location.origin + '/scripts/GetPDCCheckItems', dataSrc: ''},
		language: {
			search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',
			searchPlaceholder: 'filter records'
		},
		processing: false,autoWidth: false,destroy: true,searching: true,
		dom: 'Bfltirp',	bInfo: true,scrollCollapse: true,paging: false,deferRender: true,pageResize: true,order: [[4, 'asc']],"scrollY":  "calc(100vh - 550px)",
		rowGroup: {
			dataSrc: ["templateName","cat_name"],
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = group;middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: [
			{ extend: 'pdf'	 ,orientation: 'landscape',  pageSize: 'A4', messageTop: 'rFMS Reader Group overview',title: 'rFMS-Connect _Dealers_Overview'},
			{ text:"New Check", action: function ( e, dt, node, config ) { CreateNewPDCCHeckItem();}},
		],
		columns: [
			{	"data": "active","render": function (data, type, row) {if (data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';	} else {return '<i class="fad fa-toggle-off grey"></i>';} },
				"title": " ", "visible": true, "width": "2%", "class": "dt-right", responsivePriority: 1 },
			{"data": "check", 		"title": "name", 		"class": "dt-left"},
			{"data": "cat_name", 	"title": "category", 	"class": "dt-left",visible:false},
			{"data": "subcat_name", "title": "subcategory", "class": "dt-left"},
			{"data": "templateName","title": "template", 	"class": "dt-left",visible:true,width: "15%"},
			{"data": "languageName","title": "language", 	"class": "dt-left"},
			{"data": "createdby", 	"title": "created", 	"class": "dt-left"},
			{"width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_checkitem_edit?id='+row.id+'" class=""><i class="fad fa-edit fa-fw"></i></a>'}},
			{"width": "1%", "bSortable": false, mRender: function (data, type, row) {
					return '<a href="pdc_checkitem_delete?id=' + row.id + '" class=""><i class="fad fa-trash-alt fa-fw"><i></a>'
				}
			}
		]
	});
	$('#tablePDCCI').off('click');
	$('#tablePDCCI tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});
}
function ShowPDCPolicies(){
	$('#tablePDCP').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCPolicies', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 550px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[0,'asc' ]],
		buttons: [{text:"New Policy", action: function ( e, dt, node, config ) { CreateNewPDCPolicy();}}],
		columns: [
			{ "data": "active",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-toggle-on text-success"></i>';} else {return '<i class="fad fa-toggle-off"></i>';}},
				"title":" ","visible":true,"width": "2%",			"class":"dt-right",responsivePriority: 1,},
			{ "data": "name",				"title":"name",			"class":"dt-left"},
			{ "data": "description",		"title":"description",	"class":"dt-left"},
			{ "data": "GroupName",			"title":"group",	"class":"dt-left"},
			{ "data": "time_policy_days",
				"render": function(data, type, row) {if(data !='0') {return '<i class="fad fa-toggle-on text-success fa-fw"></i> '+data+' days';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":"days ","visible":true,"width": "10%",			"class":"dt-left",responsivePriority: 2,},
			{ "data": "trip_policy_count",
				"render": function(data, type, row) {if(data !='0') {return '<btn class="fad fa-toggle-on text-success fa-fw"></btn> '+data+' #';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":"trips","visible":true,"width": "10%",			"class":"dt-left",responsivePriority: 3,},
			{ "data": "distance_policy_km",
				"render": function(data, type, row) {if(data != '0') {return '<i class="fad fa-toggle-on text-success fa-fw"></i> '+data+' km';} else {return '<i class="fad fa-toggle-off grey"></i>';}},
				"title":"distance ","visible":true,"width": "10%",			"class":"dt-left",responsivePriority: 4,},
			{ "data": "reportuser",		"title":"report to",	"class":"dt-left"},
			{ "width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_policy_edit?id='+row.id+'" class=""><i class="fad fa-edit fa-fw"></i></a>'}},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_policy_delete?id='+row.id+'" class=""><i class="fad fa-trash-alt fa-fw"><i></a>'}}
			]
	} );
	$('#tablePDCP').off('click');
	$('#tablePDCP tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ShowPDCDamageItems(){
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tablePDCDI').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetPDCDamageItems', dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,searching: true,"scrollY":  "calc(100vh - 550px)",
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: false,deferRender: true,	pageResize: true,order:[[3,'asc' ]],
		rowGroup: {
			dataSrc: ["templateName","cat_name"],
			startRender: function(rows, group, level) {
				var all;
				if (level === 0) { top = group;	all = '';middle = '';
				} else {
					if (!!collapsedGroups[top]) {	return;	}
					if (level ===1) {middle = group	}
					all = top + middle + group;
				}
				var collapsed = !!collapsedGroups[all];
				rows.nodes().each(function(r) { r.style.display = collapsed ? 'none' : '';});
				// Add category name to the <tr>. NOTE: Hardcoded colspan
				return $('<tr/>')
					.append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
					.attr('data-name', all)
					.toggleClass('collapsed', collapsed);
			}
		},
		buttons: [{text:"New DamageItem", action: function ( e, dt, node, config ) { CreateNewPDCDamageItem();}}],
		columns: [
			{ "data": "title",				"title":"title",		"class":"dt-left"},
			{ "data": "severity",			"title":"severity",		"class":"dt-left"},
//			{ "data": "cat_name",			"title":"category",		"class":"dt-left"},
			{ "data": "subcat_name",		"title":"subcategory",	"class":"dt-left"},
			{ "data": "templateName",		"title":"template",		"class":"dt-left"},
			{ "data": "languageName",		"title":"language",		"class":"dt-left"},
			{ "data": "createdby",			"title":"created",		"class":"dt-left"},
			{ "width": "1%" ,"class":"dt-center","bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_damageitem_edit?id='+row.id+'" class=" "><i class="fad fa-edit fa-fw"></i></a>'}},
			{ "width": "1%" ,"bSortable" : false,mRender: function (data, type, row) { return '<a href="pdc_damageitem_delete?id='+row.id+'" class=""><i class="fad fa-trash-alt fa-fw "><i></a>'}}
			]
	} );
	$('#tablePDCDI').off('click');
	$('#tablePDCDI tbody').on('click', 'tr.dtrg-start', function() {
		var name = $(this).data('name');
		collapsedGroups[name] = !collapsedGroups[name];
		table.draw(false);
	});
}
function ShowTemplateExample($id){
	Notiflix.Notify.Success('PDF will be created, please wait....');
	$.ajax({
		url: window.location.origin + '/scripts/GetPDCTemplateExample?id=' + $id,
		type: 'GET',
		dataType: 'html',
		success: function (data) {
			var element = data;
			var opt = {
				margin:       4,
				filename:     'TemplateExample.pdf',
				image:        { type: 'png', quality: 1.0 },
				html2canvas:  { scale: 3 },
				jsPDF:        { unit: 'mm', format: 'A4', orientation: 'portrait' }
			};
			html2pdf().set(opt).from(element).save();
		},
		error: function (data) {},
	});
}
function ShowPDC_PDFReport($id,$name,$date){
	var $str='<button type="button" class="btn btn-primary mr-5" id="CreatePDFButton" onclick="CreatePDC_PDFReport(`'+$id+'`,`'+$name+'`,`'+$date+'`);">Create PDF <i class="fa-solid fa-loader fa-spin"></i></button>';
	$.ajax({
		url: window.location.origin + '/scripts/GetPDC_PDFReport?id=' + $id,
		type: 'GET',
		cache: false,
		dataType: 'html',
		success: function (data) {
			UpdateCounter(data,'Show_PDF_report');
			UpdateCounter($str,'modalCreatePDFButton');
		},
		error: function (data) {
			$str='<div class="p-3 alert alert-danger border border-danger">error collecting data </div>'
			UpdateCounter($str,'Show_PDF_report');
		},
	});
}
function _NEW_CreatePDC_PDFReport($id,$name,$date){
	$('#PDFReport').removeClass('show');
	Notiflix.Loading.standard('PDF will be created, please wait....', );
	$.ajax({
		url: window.location.origin + '/scripts/GetPDC_PDFReport?id=' + $id+'&PDF',
		type: 'GET',cache: false,
		dataType: 'PDF',
		success: function (data) {
			$('.close').click();
			Notiflix.Loading.remove();
		},
		error: function (data) {
			Notiflix.Loading.remove();
		},
	});
}
function CreatePDC_PDFReport($id,$name,$date){
	$('#PDFReport').removeClass('show');
//	Notiflix.Notify.success('PDF will be created, please wait....');
	Notiflix.Loading.standard('PDF will be created, please wait....', );
	$.ajax({
		url: window.location.origin + '/scripts/GetPDC_PDFReport?id=' + $id,
		type: 'GET',
		dataType: 'html',
		success: function (data) {
			var element = data;
			var opt = {
				margin:       8,
				filename:     'PDC_Vehicle_'+$date+'_'+'_'+$name,
				image:        { type: 'png', quality: 1.0 },
				html2canvas:  { scale: 3.0 },
				jsPDF:        { unit: 'mm', format: 'A4', orientation: 'portrait' }
			};
			html2pdf().set(opt).from(element).save();
			$('.close').click();
			Notiflix.Loading.remove();
		},
		error: function (data) {},
	});
}

function ShowVehicleActivityTable($vin){
	if ($vin== 'undefined' || $vin == null){
		var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
		var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
		var $s='startDate='+SelectedDate+'&endDate='+SelectedDateEnd;
	}
	else { var $s='&VIN='+$vin; }
	var collapsedGroups = {};var top = '';var middle = '';var parent = '';
	var table =$('#tableDriverActivity').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetVehicleActivity?'+$s, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		processing: false,	autoWidth: false,destroy:true,"lengthMenu": [[10, -1], [10, "All"]],
		dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,pageResize: true,order:[[0,'asc' ]],rowGroup: {dataSrc: "customerVehicleName"},
		buttons: {buttons: [] },
		columns: [
			{ "data": "customerVehicleName",		"title":"Vehicle",	"defaultContent": "-" ,"class":"dt-left","visible":false},
			{ "data": "date",						"title":"Date",		"defaultContent": "-" ,"class":"dt-left"},
			{ "data": "driver",						"title":"Driver",	"defaultContent": "" , "class":"dt-left"},
			{ "data": "amountTrips",				"title":"Trips",	"defaultContent": "" , "class":"dt-left"},
			{ "data": "Distance",					"title":"Total Distance","defaultContent": "" ,"class":"dt-left"},
			{ "data": "Duration",					"title":"Total Duration","defaultContent": "" ,"class":"dt-left"},
			{ "data": "FuelUsed",					"title":"Total FuelUsed","defaultContent": "" ,"class":"dt-left"},
			{ "data": "FuelUsage",					"title":"Average FuelUsage","defaultContent": "" ,"class":"dt-left"},
			{ "data": "has_PDC",					"title":"PDC","defaultContent": "" ,"class":"dt-left",width:"2%"},
			{ "data": "Count_Damages",				"title":"Damages","defaultContent": "" ,"class":"dt-left",width:"2%"},
			]
	} );
	$('#tableDriverActivity').off('click');
}
function ShowDriverTripTable($date,$driverid){
	UpdateCounter('','tableDriverTrips');
	$('#tableDriverTrips').DataTable( {
		ajax: { url: window.location.origin+'/scripts/GetDriverTrips?id='+$driverid+'&date='+$date, dataSrc: ''},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		oLanguage: {sProcessing: "<span class='fad fa-refresh fa-refresh-animate'></span>"},processing: false,	autoWidth: false,destroy:true,searching: false,
		dom: 'HRSQfltrp',bInfo: true, scrollCollapse: true,paging: true,deferRender: true,	pageResize: true,order:[[0,'asc' ]],"lengthMenu": [[5, -1], [5, "All"]],
		buttons: {buttons: []},
		columns: [
			{ "data": "driver",				"title":"Driver",			"class":"dt-left",		visible:false},
			{ "data": "StartDate",			"title":"Start Trip",		"class":"dt-left"},
			{ "data": "customerVehicleName","title":"Vehicle",			"class":"dt-left",	width:"25%"},
			{ "data": "Distance",			"title":"Distance",			"class":"dt-center"},
			{ "data": "DriveTime",			"title":"Driving",			"class":"dt-center"},
			{ "data": "IdleTime",			"title":"Idle",				"class":"dt-center"},
			{ "data": "PreDepIdle",			"title":"Pre Trip Idle",	"class":"dt-center"},
			{ "data": "Duration",			"title":"Duration",			"class":"dt-center",	visible:false},
			{ "data": "FuelUsed",			"title":"Fuel Used",		"class":"dt-center",	},
			{ "data": "FuelUsage",			"title":"Fuel Usage",		"class":"dt-center"},
			{ "data": "EndDate",			"title":"End Trip",			"class":"dt-right"},
		]
	} );
	$('#tableDriverTrips').off('click');
}

function Count_warning($arr){
		$warning=0;
		if ($arr.TT_PAR_BRA=='YELLOW' )	{$warning++;}
		if ($arr.TT_FUE_LEV=='YELLOW' )	{$warning++;}
		if ($arr.TT_ENG_COO_TEM=='YELLOW' ){$warning++;}
		if ($arr.TT_ENG_MIL_IND=='YELLOW' ){$warning++;}
		if ($arr.TT_ENG_EMI_FAI=='YELLOW' ){$warning++;}
		if ($arr.TT_ENG_OIL=='YELLOW')		{$warning++;}
		if ($arr.TT_ADB_LEV=='YELLOW')		{$warning++;}
		if ($arr.TT_PAR_BRA=='RED' )		{$warning++;}
		if ($arr.TT_FUE_LEV=='RED' )		{$warning++;}
		if ($arr.TT_ENG_COO_TEM=='RED' )	{$warning++;}
		if ($arr.TT_ENG_MIL_IND=='RED' )	{$warning++;}
		if ($arr.TT_ENG_EMI_FAI=='RED' )	{$warning++;}
		if ($arr.TT_ENG_OIL=='RED')		{$warning++;}
		if ($arr.TT_ADB_LEV=='RED')		{$warning++;}
		return $warning;
}

function DriveTimeMgt(val){
	var $LInfo = $('#DTM');
	$LInfo.html('');
	$LInfo.append('<div class="DriverInfo p-2"><div id="Driver1" > - </div><div id="DTM1"></div><div id="TripDT1" class="rFMSDriverGraph "></div></div>');
	ReadDriverDetails(val.DriverId,'Driver1','DTM1','TripDT1');
	ShowDriveTime(val.DriverId,'TripDT1','DTM1');
	$LInfo.append('<div id="TripInfo" class="rFMSTripInfo"></div>');
	ReadTodayDriverTrips(val,'TripInfo');
}

function FilterDBDelayed(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $VSInfo = $('#TripReport');		$VSInfo.html('');
	var $SInfo = $('#DelayedTables');  $SInfo.html('');
	var $VInfo = $('#GraphDelayedVehicles');$VInfo.html('');
	var $Info = $('#DelayedVehicles');$Info.html('');
	var $Info1 = $('#TitleDay');$Info1.html('');
	ShowSpinner("TripReport");
	$counter=0;
	$vehicles=[];
	map.removeLayer(heat);
	$.ajax({
		url: window.location.origin+'/scripts/GetDelayedVehicles?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				$vehicles.push(val);
			})
			if ($vehicles.length>0){
				$VSInfo.html('');
				$VSInfo.append('<div class="col-12 card-title primary">Delayed vehicles per day</div>');
				ShowHeatMap($vehicles);
				ReadDelayedChart();
				ReadTodayDelay(SelectedDateEnd);
				ReadDelayedVehicles(SelectedDateEnd);
			}
			else {
				$VSInfo.html('');
				$VSInfo.append("<div class='notice-danger' ><strong>No data found !</div>");
			}
		}
	});
}
function ShowDelayedVehicles($Vehicles){
	var $VSInfo = $('#DelayedTables');
	$VSInfo.html('');
	$('#DelayedTables').DataTable( {
		data:$Vehicles,autoWidth: false,destroy:true,order:[[7,'desc' ]],dom: 'Bfltirp',	scrollY: '45vh',bInfo: true,scrollCollapse: true,paging: false,	scrollX: true,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
	    buttons: {buttons: [
			{extend: 'excel', 	class: 'btn btn-group btn-primary btn-sm' ,filename: 'rFMSConnect_Delayed_Vehicles'},
			{extend: 'pdf',   	class: 'btn btn-group btn-primary btn-sm',orientation: 'landscape', pageSize: 'A4',messageTop: 'Delayed vehicles'},
			{extend: 'csv',  	extension: '.csv', fieldSeparator: ';', },
			{extend: 'print', 	class: 'btn btn-group btn-primary btn-sm',messageTop: 'Delayed vehicles'	}
			]},
		columns: [
			{ "data": "createdDate",		"title":"Measure Date","defaultContent": "" , "class":"dt-left"},
			{ "data": "customerVehicleName","title":"Name","class":"dt-left"},
			{ "data": "name",				"title":"Group","defaultContent": "" ,"class":"dt-left"},
			{ "data": "vin",				"title":"Vin","class":"dt-left","visible":false },
			{ "data": "add_toDB",			"title":"Adde_to_DB","defaultContent": "" ,"class":"dt-left","visible":false },
			{ "data": "obu_sw_version",		"title":"OBU","defaultContent": "" ,"class":"dt-left","visible":false },
			{ "data": "OBU_Serial",			"title":"OBU Serial","class":"dt-center","defaultContent": "","visible":false  },
			{ "data": "receivedDateTime",	"title":"first delay","class":"dt-center","defaultContent": "","visible":false },
			{ "data": "delay",				"title":"delay (in min)","class":"dt-center","defaultContent": "","visible":false },
			{ "data": "address",			"title":"location","class":"dt-center","defaultContent": "","visible":false  },
			{ "data": "country",			"title":"country","class":"dt-center","defaultContent": "","visible":false  },
			{ "data": "latitude",			"title":"lat","class":"dt-center","defaultContent": "","visible":false  },
			{ "data": "longitude",			"title":"long","class":"dt-center","defaultContent": "" ,"visible":false }]
	});
	$('#DelayedTables tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	} );
}
function ReadBackLogValues(){
	$.ajax({
		type: "GET",
		url: window.location.origin+"/data/backlog.json",
		dataType: "json",
		success: function(data) {
			var RequestDate = new Date(data.requestTime);
			var MessageDate = new Date(data.messageTime);
			var ReceivedDate = new Date(data.receivedTime);
			var $APIbacklog = ((RequestDate.getTime() - ReceivedDate.getTime()) / 1000) /3600;
			var $GPRSbacklog = ((ReceivedDate.getTime() - MessageDate.getTime()) / 1000) /3600;
			var $WInfo = $('#warningmessage');$message='';
			if ($GPRSbacklog<1){
				var $GPRSbl = ((ReceivedDate.getTime() - MessageDate.getTime()) / 1000) /60;
				$message+='<div class="info-box-number primary row"><div class="col-6">Vehicle-M2M </div><div class="col-6"><i class="fas fa-history"></i> '+$GPRSbl.toFixed(2)+'<small> minutes</small></div></div>';
			}
			else {$message+='<div class="info-box-number text-danger row"><div class="col-6">Vehicle-M2M </div><div class="col-6"><i class="fas fa-history"></i> '+$GPRSbacklog.toFixed(2)+'<small> hours </small></div></div>';			}
			if ($APIbacklog<1){
				var $APIbl = ((RequestDate.getTime() - ReceivedDate.getTime()) / 1000) /60;
				$message+='<div class="info-box-number primary row"><div class="col-6">M2M-API </div><div class="col-6"><i class="fas fa-clock "></i> '+$APIbl.toFixed(2)+'<small> minutes </small></div></div>';
			}
			else {$message+='<div class="info-box-number text-danger row"><div class="col-6">M2M-API </div><div class="col-6"><i class="fas fa-history "></i> '+$APIbacklog.toFixed(2)+'<small> hours</small></div></div>';	}
			$WInfo.html('');
			$WInfo.append('<span><strong>'+$message+'</strong></span>');
			return $APIbacklog;
		}
	});
}
function ShowVehicleStats(){
	$.ajax({
		url: window.location.origin+'/scripts/GetVehiclesStats',
		dataType:'JSON',
		success:function(data){
			$message='';var $LInfo = $('#statsmessage');
			$message+='<div class="primary row"><div class="col-12">Data Reliability</div>';
			$message+='		<div class="col-6 grey"><li>newest </li></div><div class="col-6 white">'+data[0].D_Min+'<small> (h:m:s)</small></div>';
			$message+='		<div class="col-6 grey"><li>average</li></div><div class="col-6 primary">'+data[0].D_Average+'<small> (h:m:s)</small></div>';
			$message+='		<div class="col-6 grey"><li>oldest </li></div><div class="col-6 white">'+data[0].D_Max+'<small> (h:m:s)</small></div>';
			$message+='		<div class="col-6 grey"><li>Non-Communicating</li></div><div class="col-6 primary">'+data[0].V_CountNC+'</div>';
			$message+='		<div class="col-6 grey"><li>Delayed</li></div><div class="col-6 white">'+data[0].V_Delayed+'</div>';
			$message+='		<div class="col-6 grey"><li>Actived via API</li></div><div class="col-6 primary">'+data[0].V_CountActive+'</div>';
			$message+='	</div>';
			$LInfo.html('');
			$LInfo.append($message);
		}
	});
}
function ShowAlertReport(){
	var $LInfo = $('#VehicleAlertDetails');	$LInfo.html('');
	var $LInfo = $('#AdviseData');	$LInfo.html('');
	ShowPlaceholder("VehicleATPH",'table');
	$.ajax({
		url: window.location.origin+'/scripts/GetVehiclesAlertsReport',
		dataType:'JSON',
		success:function(data){
			var $VehicleData=[];
			$.each(data, function(key, val){
				if (val.status.alert==true || val.YellowWarnings>0 || val.RedWarnings>0) {
					$VehicleData.push(val);
				}
			});
			setTimeout(500);
			ShowAlertReportTable($VehicleData);
		},
		error:function(){$LInfo = $('#VehicleAlertTable');$LInfo.html('');$LInfo.append('Failed to read database');}
	});
}
function ShowAlertReportTable($Vehicles){
		UpdateCounter('<table id="VehicleAlertTable" class="display table noWrap " width="100%"></table>','VehicleATPH');
		var table=$('#VehicleAlertTable').DataTable( {
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		data:$Vehicles,	autoWidth: false,destroy:true,order:[[4,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true, searching: false,	scrollX: true,
		buttons: {buttons: []	},
		columns: [
			{ "data": "VIN", "title":"","visible":false,"searchable":true,"width": "25%"}	,
			{ "data": "customerVehicleName","title":"Vehicle Name","class":"dt-left","width": "20%"},
			{ "data": "LastActivity","title":"Last Contact UTC","visible":false,"defaultContent": "" },
	 		{ "data": "LastDriver","title":"Last Driver","defaultContent": "", "class":"dt-left","width": "20%"},
			{ "data": "CountedWarning","title":"active","defaultContent": "", "class":"dt-center","width": "5%"},
			{ "data": "YellowWarnings","title":"# yellow","defaultContent": "", "class":"dt-center","width": "5%"},
			{ "data": "RedWarnings","title":"# red","defaultContent": "", "class":"dt-center","width": "5%"},
			]
	} );
	$('#VehicleAlertTable').off('click');
	$('#VehicleAlertTable').on('click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		var data = table.row( this ).data();
		ShowAlertVehicleInfo(data['id']);
	});
}
function ShowAlertVehicleInfo(SearchVin){
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicleDetails?id='+SearchVin,
		dataType:'JSON',
		success: function (data) {
			var LIcon  =  L.AwesomeMarkers.icon({icon: '',	markerColor: 'orange' 	,iconSize: [30, 36], iconAnchor: [15, 20], tooltipAnchor:[,-22], popupAnchor: [, -22]});
			$.each(data, function(key, val){
				rFMS_Latest.clearLayers();
				L.marker([val.last_Latitude, val.last_Longitude],  {
					icon: LIcon,
					rotationAngle: val.last_Heading,
					draggable: false
				} )
				.bindTooltip('<div class="card"><div class="card-header">vehicle with alert(s)</div><div class="card-body card-title">'+val.customerVehicleName+'</div></div>',{ direction: 'top',opacity:1})
				.addTo(rFMS_Latest);
				map.addLayer(rFMS_Dealers);
				map.addLayer(rFMS_Latest);
				map.setView([val.last_Latitude, val.last_Longitude], 10);
				ShowAlertAdvise(val);
				ShowVehicleInfo(SearchVin,'a');
			});
			ReadAlertsHistory(SearchVin);
		},
	});
}
function ShowAlertAdvise($data){
	var $LInfo = $('#AdviseData');
	$LInfo.html('');
	if ($data.TT_PAR_BRA=='RED' )			{AddAdvise('TT_PAR_BRA','RED',$LInfo);}
	if ($data.TT_FUE_LEV=='RED' )			{AddAdvise('TT_FUE_LEV','RED',$LInfo);}
	if ($data.TT_ENG_OIL=='RED')			{AddAdvise('TT_ENG_OIL','RED',$LInfo);}
	if ($data.TT_ADB_LEV=='RED')			{AddAdvise('TT_ADB_LEV','RED',$LInfo);}
	if ($data.TT_ENG_COO_TEM=='RED' )		{AddAdvise('TT_ENG_COO_TEM','RED',$LInfo);}
	if ($data.TT_ENG_MIL_IND=='RED' )		{AddAdvise('TT_ENG_MIL_IND','RED',$LInfo);}
	if ($data.TT_ENG_EMI_FAI=='RED' )		{AddAdvise('TT_ENG_EMI_FAI','RED',$LInfo);}
	if ($data.TT_PAR_BRA=='YELLOW' )		{AddAdvise('TT_PAR_BRA','YELLOW',$LInfo);}
	if ($data.TT_FUE_LEV=='YELLOW' )		{AddAdvise('TT_FUE_LEV','YELLOW',$LInfo);}
	if ($data.TT_ENG_OIL=='YELLOW')			{AddAdvise('TT_ENG_OIL','YELLOW',$LInfo);}
	if ($data.TT_ADB_LEV=='YELLOW')			{AddAdvise('TT_ADB_LEV','YELLOW',$LInfo);}
	if ($data.TT_ENG_COO_TEM=='YELLOW' )	{AddAdvise('TT_ENG_COO_TEM','YELLOW',$LInfo);}
	if ($data.TT_ENG_MIL_IND=='YELLOW' )	{AddAdvise('TT_ENG_MIL_IND','YELLOW',$LInfo);}
	if ($data.TT_ENG_EMI_FAI=='YELLOW' )	{AddAdvise('TT_ENG_EMI_FAI','YELLOW',$LInfo);}
	if ($data.TT_PAR_BRA=='INFO' )			{AddAdvise('TT_PAR_BRA','INFO',$LInfo);}
}
function AddAdvise($Alert,$Status,$String){
	if  ($Status=="YELLOW"){var $n='warning';var $b='text-warning';}
	if  ($Status=="RED"){var $n='danger';var $b='text-danger';}
	if  ($Status=="INFO"){var $n='primary';var $b='text-primary';}
	$.ajax({
		url: window.location.origin+'/scripts/GetWarningAdvise?warning='+$Alert+'&status='+$Status,
		dataType:'JSON',
		type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				$String.append('<div class="col-12 "><div class="card AdviseBox mb-2">'+
					   '	<div class="card-header alert-'+$n+' ">'+val.Description+'</div>'+
					   '		<div class="card-body p-1 px-3"><small>'+val.Advise+'</small></div>'+
					   '	</div>'+
					   ' </div>');
			});
		}
	});
}
function ReadAlertsHistory(SV,SD,SDE){
	var $TellTales=[];
	$.ajax({url: window.location.origin+'/scripts/GetVehicleAlerts?id='+SV,
	dataType:'JSON',
	type: "GET",
	success:function(data){
		$.each(data, function(key, val){
			$TellTales.push(val);}
		)
		if ($TellTales.length>0){ShowTellTaleTable($TellTales);}
	}
	});
}
function ShowTellTaleTable($Data){
	var table=$('#Alertdata').DataTable( {
		data:$Data,		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		oLanguage: {sProcessing: "<span class='fad fa-refresh fa-refresh-animate'></span>"},processing: false,paging: true,	autoWidth: false,destroy:true,searching: false,
		bInfo: true, scrollCollapse: true,deferRender: true,order:[[2,'desc' ]],
		columns: [
			{ "data": "selected",
				"render": function(data, type, row) {if(data == '1') {return '<i class="fad fa-check-square text-success"></i>';} else {return '<i class="far fa-square grey"></i>';}},
				"title":" ","visible":true,"width": "2%",			"class":"dt-right",responsivePriority: 1,},
			{ "data": "vin","title":"Vehicle ","className":"dt-left","visible":false,"width": "20%" },
			{ "data": "createdDateTime","title":"Date","defaultContent": "","width": "33%"  },
			{ "data": "Driver_ID","title":"Driver","defaultContent": "", "className":"dt-left","width": "33%"  },
			{ "data": "tellTale","title":"Warning","defaultContent": "", "className":"dt-left","width": "33%"  },
			{ "data": "state","title":"Status","defaultContent": "" ,"className":"dt-right","width": "33%"},
			{ "data": "latitude","title":"","defaultContent": "" ,"className":"dt-right","width": "33%","visible":false},
			{ "data": "longitude","title":"","defaultContent": "" ,"className":"dt-right","width": "33%","visible":false},
		]
	});
	$('#Alertdata').off('click');
	$('#Alertdata').on('click', 'tr', function () {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
		var data = table.row( this ).data();
		//ShowAlertOnMap(data['id']);
	});
}
function InitializeSimpleMAP() {
	HEREGrey = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/reduced.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg=eng&ppi=320', {
		attribution: '<a href="http://developer.here.com">HERE</a>',subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',app_code:'z2HsXPK48vhNPwyfcFr5ew',base: 'traffic',maxZoom: 20,type: 'streettile',language: 'eng',format: 'png8',	size: '512'
	});
	rFMS_Latest = new L.MarkerClusterGroup({
		spiderfyOnMaxZoom: true,
		showCoverageOnHover: false,
		zoomToBoundsOnClick: true
	});
	map = L.map('map', {
		center: [46.00, 8.65],
		zoomSnap: 0.25,
		minZoom: 2,
		maxZoom: 20,
		zoom: 5,
		fadeAnimation: false,
		scrollWheelZoom: true,
		dragging: false
	});
	map.removeControl(map.zoomControl);
	map.addLayer(HEREGrey);
}
function ReadTripCounter($location){
	var $LInfo = $('#'+$location);
	$str='<div class="ph-item">';
	$str+='	<div class="ph-col-12">';
	$str+='		<div class="ph-row">'; 
	$str+='			<div class="ph-col-4 big"></div><div class="ph-col-2 empty"></div><div class="ph-col-6 big"></div>';
	$str+='		</div>';
	$str+='		<div class="ph-row">'; 
	$str+='			<div class="ph-col-6 empty"></div><div class="ph-col-6 big"></div>';
	$str+='		</div>';
	$str+='		<div class="ph-row">'; 
	$str+='			<div class="ph-col-4 big"></div><div class="ph-col-2 empty"></div><div class="ph-col-6 big"></div>';
	$str+='		</div>';
	$str+='	</div>';
	$str+='</div>';
	$LInfo.append($str);
	$.ajax({url: window.location.origin+'/scripts/CountTodayTrips',
	dataType:'JSON',
	type: "GET",
	success:function(data){
		$LInfo.html('');
		$.each(data, function(key, val){
			if (val.TotTripsToday!=0){
				var $AFC=100/(val.TotDistanceToday/val.TotFuelToday);
				var $CFP=val.TotFuelToday/2.64;				// result is CO2 based per liter diesel
				var $string = '<div class="d-flex">';
				$string+='	<div class="col-md-6  px-1" title="average fuelusage">';
				$string+='		<i class="fad fa-tint gray" ></i> '+formatNumber(round($AFC,1))+'<br><br><span title="CO2 produced"><small>CO<sup>2</sup> - </small> '+formatNumber(round($CFP,0))+' <small>kg</small></span>';
				$string+='	</div>';
//				$string+='	<div class="col-md-1 col-lg-6 p-0 DashGraph normal small" id="FleetTripReport"></div>';
				$string+='	<div class="col-md-6 px-1 text-right ">';
				$string+='		<div class="" title="total trips">'+formatNumber(val.TotTripsToday)+' - <i class="fad fa-flag-checkered fa-fw gray tiny"></i></div>';
				$string+='		<div class="" title="total distance (km)">'+formatNumber(round(val.TotDistanceToday,0))+' - <i class="fad fa-route  fa-fw gray tiny"></i></a></div>';
				$string+='		<div class="" title="total fuel used (L)">'+formatNumber(round(val.TotFuelToday,0))+' - <i class="fad fa-gas-pump  fa-fw gray tiny"></i></a></div>';
				$string+='	</div>'; 
				$string+='</div>';
				$LInfo.append($string);
			} else {
				var $string = '<div class="row"><div class="col-12 "><a href="#" title="Fuel Economy l / 100km"><i class="fas fa-tint "></i> - </a></div></div>';
				$string+='<div class="row"><div class="col-12 text-right">';
				$string+='<div class=""><a href="#" title="# amount of trips">0 - <i class="fas fa-flag-checkered  tiny"></i></a></small></div>';
				$string+='<div class=""><a href="#" title="distance in km">0 - <i class="fas fa-route  tiny"></i></small></a></div>';
				$string+='<div class=""><a href="#" title="fuel in liters">0 - <i class="fas fa-gas-pump  tiny"></i></small></a></div></div></div>';
				$LInfo.append($string);
			}
		});
//		ShowPlaceholder('FleetTripReport','graph');
//		FleetTripData();
	}
	});
}
function Delayed(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $VSInfo = $('#TripReport');
	ShowSpinnerSmall("TripReport");
	$counter=0;
	$vehicles=[];
	map.removeLayer(heat);
	$.ajax({
		url: window.location.origin+'/scripts/GetDelayedVehicles?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				$vehicles.push(val);
			})
			if ($vehicles.length>0){
				$VSInfo.html('');
				ShowDelayedVehicles($vehicles);
				ShowHeatMap($vehicles);
			}
			else {
				$VSInfo.html('');
				$VSInfo.append("<div class='notice-danger' ><strong>No data found !</div>");
			}
		}
	});
}
function SaveSelectedGroup_oud(){
	var sel = document.getElementById("groups");
	var selectedId = sel.options[sel.selectedIndex].value;
	var selectedText = sel.options[sel.selectedIndex].text;
	globalThis.SelectedGroup=selectedId;
	var xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){}
	urlString=window.location.origin+"/users/setSessionVariable?choiceId=" + selectedId+"&choiceText="+selectedText;
	xmlhttp.open("GET",urlString,true);
	xmlhttp.send();
	UpdateCounter(selectedText,'CustomerName');
	location.reload();
}
function SaveSelectedGroup(){
	var sel = document.getElementById("groups");
	var selectedId = sel.options[sel.selectedIndex].value;
	var selectedText = sel.options[sel.selectedIndex].text;
	$.ajax({
		url: window.location.origin + '/users/setSessionVariable?choiceId=' + selectedId + '&choiceText=' + selectedText,
		dataType: 'JSON', type: "POST",
		success: function (data) {
			UpdateCounter(selectedText,'Groups');
			$SelectedGroup=selectedId;
		}
	});
	wait(200);
}
function SaveSelectedReportDate(){
	var $sd =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var $ed =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	$.ajax({
		url: window.location.origin + '/users/setSessionVariable?choiceStart=' + $sd + '&choiceEnd=' + $ed,
		dataType: 'JSON', type: "POST",
		success: function (data) {
		}
	});
	wait(200);
}
function FleetUtil(){
	ShowPlaceholder('FleetUtilReport','graph');
	ShowPlaceholder('FleetmodelChart','graph');
	ShowPlaceholder('FleetTripReport','graph');
	ShowPlaceholder('FleetFuelReport','graph');
	ShowPlaceholder('FleetTripTable','table');
	ShowPlaceholder('FleetSumReport','sumreport');
	FleetUtilData();
	ShowFleetTable();
	ReadFleetmodels();
	FleetTripData();
	ReadFleetTable();
}
function FleetUtilData(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $VSInfo = $('#FleetUtilReport');
	var dataPoints = [];
	var dataPoints1 = [];
	$.ajax({
		url: window.location.origin+'/scripts/CustomerVehicleCountPerDay?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				if (val['VehicleCount'] === undefined){
					dataPoints.push([new Date(val.createDate), parseFloat(val.Active)]);
					dataPoints1.push([new Date(val.createDate),0]);
				} else {
					dataPoints.push([ new Date(val.createDate), parseFloat(val.Active) ]);
					dataPoints1.push([ new Date(val.createDate), parseFloat(val.VehicleCount)]);
				}
			})
			FleetUtilChart(dataPoints,dataPoints1);
		},
		error:function(){
			$VSInfo.html('');
			$VSInfo.append("<i class='fas fa-exclamation text-danger'> error searching data</i>");
		}
	});
}
function FleetTripData(){
	var $sd =  document.getElementById("SelectDate");
	var $ed =  document.getElementById("SelectDateEnd");
	if($ed){ 
		$sd= new Date($sd.value).format("yyyy/mm/dd");
		$ed= new Date($ed.value).format("yyyy/mm/dd");
		var $url=window.location.origin+'/scripts/CustomerTripCountPerDay?StartDate='+dateFormat($sd,"yyyy/mm/dd")+'&EndDate='+dateFormat($ed,"yyyy/mm/dd");
	}
	else {
		var $url=window.location.origin+'/scripts/CustomerTripCountPerDay';
		var $dash=true;
	} 
	var $VSInfo = $('#FleetTripReport');	
	var $FInfo = $('#FleetFuelReport');
	var $SumInfo = $('#FleetSumReport');
	var dataPoints = [];	
	var dataPoints1 = [];
	var dataPoints2 = [];
	var dataPoints3 = [];
	var dataPoints4 = [];
	var $TFU=0;var $TD=0;$TCO2=0;var $TNT=0;$TFE=0;$counter=0;$Str='';
	var diffDays=DateDiff($sd,$ed);
	$.ajax({
		url: $url,
		dataType:'JSON',type: "POST",
		success:function(data){
			$.each(data, function(key, val){
				dataPoints.push([ new Date(val.Date), parseFloat(val.Total) ]); 	$TNT+=parseFloat(val.Total);
				dataPoints1.push([ new Date(val.Date), parseFloat(val.Distance) ]);	$TD+=parseFloat(val.Distance);
				dataPoints2.push([ new Date(val.Date), parseFloat(val.Fuelused) ]); $TFU+=parseFloat(val.Fuelused);
				$FuelUsage=100/(val.Distance/val.Fuelused);
				dataPoints3.push([ new Date(val.Date), parseFloat($FuelUsage.toFixed(2)) ]);$TFE+=parseFloat($FuelUsage);
				$CO2=2.64*val.Fuelused;
				dataPoints4.push([ new Date(val.Date), parseFloat($CO2.toFixed(2))]);$TCO2+=parseFloat($CO2);
				$counter++;
			})
			$TFE=parseFloat($FuelUsage);
			if ($dash==true){ 
				FleetTripChart(dataPoints,dataPoints1,dataPoints4);}
			else {
				FleetTripChart(dataPoints,dataPoints1,dataPoints4);
				FleetFuelChart(dataPoints2,dataPoints3, dataPoints4);
				$SumInfo.html('');
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="trips p-2">		<span><small>Trips</small>	</span></br><span class="info-box-number">'+formatNumber($TNT)+'</span></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="distance p-2">	<span><small>Distance </small></span></br><span class="info-box-number">'+formatNumber($TD.toFixed(0))+'</b> <small>km</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="fuelused p-2">	<span><small>Fuel used </small></span></br><span class="info-box-number">'+formatNumber($TFU.toFixed(0))+'</b> <small>L</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="fuelusage p-2">	<span><small>Fuel Economy </small></span></br><span class="info-box-number">'+formatNumber($TFE.toFixed(2))+' </b> <small>L/100km</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="co2 p-2">		<span><small>CO2 </small></span></br><span class="info-box-number">'+formatNumber($TCO2.toFixed(0))+'</b> <small>kg</small></div></div>';
				$Str+='<div class="form-group col-xl-2 col-lg-4 col-6 "><div class="days p-2">		<span><small>Days </small></span></br><span class="info-box-number">'+diffDays+'</b></div></div>';
				$SumInfo.append($Str);
			}
		},
		error:function(){
			$VSInfo.html('');
			$VSInfo.append("<i class='fas fa-exclamation text-danger'> error searching data</i>");
			$FInfo.html('');
			$FInfo.append("<i class='fas fa-exclamation text-danger'> error searching data</i> ");
		}
	});
}
function FleetUtilChart(dataPoints,dataPoints1){
	Highcharts.chart('FleetUtilReport',{
		chart: { zoomType: 'x', marginTop: 30,marginLeft: 10, marginRight: 10,styledMode: true },
		title: { text: 'Number of vehicles per day',margin: 0, style: {color: '#ddd',fontSize: '.6rem',fontWeight: 'bold' } },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {day: '<b>%e-%b</b>'},title: { text: ''},tickInterval: 86400000,units:[['week',[1]]],labels: {autoRotation: [-30, -40] }},
		yAxis: [{title: { text:'vehicles'}, min: 0,visible: true},
				{title: { text:''}, min: 0,visible: false, linkedTo: 0}],
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{series.name} </br><b>{point.y}</b></span>',
				 crosshairs: false,useHTML:true,shared:true},
		plotOptions: { 	column: {pointPadding: 5,  pointWidth: 10, borderWidth: 0},
						spline: {marker: {enabled: true}},
						series: {turboThreshold: 1,animation: true, lineWidth: 3}},
		credits:{enabled: false},legend: {enabled: false},
		series:[{name:'Total Vehicles',	 yAxis: 0,data: dataPoints1,pointPlacement: 0.3,type: 'column'},
			    {name:'Active Vehicles', yAxis: 1,data: dataPoints, type: 'column',pointWidth: 6}]
	});
}
function FleetTripChart(dataPoints,dataPoints1,dataPoints2){
	Highcharts.chart('FleetTripReport',{
		chart: { zoomType: 'x', marginTop: 30,marginLeft:10, marginRight: 10,styledMode: true },
		title: { text: 'Trips, Distance & Fuel report',margin: 0},
		xAxis: { type: 'datetime', dateTimeLabelFormats: {day: '<b>%e-%b</b>'},title: { text: ''},tickInterval: 86400000,units:[['week',[1]]],labels: {autoRotation: [-30, -40] }},
		yAxis:[{min: 1, visible: true},
			   {min: 1, visible: false},
			   {min: 1, visible: false}],
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{series.name} </br><b>{point.y}</b></span>',
				 crosshairs: true,useHTML:true,shared:true},
		plotOptions: { column: {pointPadding: 5,  pointWidth: 10, borderWidth: 0},
					   spline: {marker: {enabled: true}},
					   series: {turboThreshold: 1,animation: true}},
		credits:{enabled: false},
		legend: {enabled: false},
		series:[{name: 'Trips',   		yAxis: 0,data: dataPoints,  type: 'column' },
				{name: 'Distance (km)',	yAxis: 1,data: dataPoints1, type: 'spline', lineWidth: 4 },
				{name: 'CO2 (tons)',	yAxis: 2,data: dataPoints2, type: 'spline', lineWidth: 4 }]
	});
}
function FleetFuelChart(dataPoints,dataPoints1,dataPoints2){
	Highcharts.chart('FleetFuelReport',{
		chart: { zoomType: 'x', marginTop: 30,marginLeft: 10, marginRight: 10,styledMode: true },
		title: { text: 'Fuel Used, Fuel Usage & CO2 report',margin: 0 },
		xAxis: { type: 'datetime', dateTimeLabelFormats: {day: '<b>%e-%b</b>'},title: { text: ''},tickInterval: 86400000,units:[['week',[1]]],labels: {autoRotation: [-30, -40] }},
		yAxis:[{min: 0, visible: true},
			   {min: 0, visible: false},
			   {min: 0, visible: false}],
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{series.name} </span></br><b>{point.y}</b><br/>{series.label}',
				 crosshairs: true,useHTML:true,shared:false},
		plotOptions: { column: {pointPadding: 5,  pointWidth: 10, borderWidth: 0},
					   spline: {marker: {enabled: true}},
					   series: {turboThreshold: 1,animation: true}},
		credits:{enabled: false},
		legend: {enabled: false},
		series:[{name: 'Fuel Used', yAxis: 0,data: dataPoints,  type: 'column' },
				{name: 'Fuel Usage',yAxis: 1,data: dataPoints1, type: 'spline', lineWidth: 4 },
				{name: 'CO2 (tons)',yAxis: 2,data: dataPoints2, type: 'spline', lineWidth: 4 },
			    ]
	});
}
function FleetmodelChart_origineel(dataPoints){
	Highcharts.chart('FleetmodelChart',{
		chart: { type: 'pie', zoomType: 'x', marginTop: 50,marginLeft: 40, marginRight: 40,styledMode: true },
		title: { text: 'Vehicle models ',margin: 0 },
		xAxis: { type: 'category', title: { text: ''},labels: {autoRotation: [-30, -40] }},
		yAxis: { title: { text:'' }, min: 0,visible: false },
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{point.name} </span></br><b>{point.y}</b><br/>',
				 crosshairs: false,useHTML:true,shared:true},
		plotOptions: {pie: { allowPointSelect: true,cursor: 'pointer',
							 dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %'},
		},
		series: {turboThreshold: 1,animation: false,fillColor: '#ddd'}},
		colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
		credits:{enabled: false},
		legend: {enabled: false},
		series: [{ name: 'Count of vehicles per model', data: dataPoints }]
	});
}
function FleetmodelChart(dataPoints){
	Highcharts.chart('FleetmodelChart',{
		chart: { zoomType: 'x', marginTop: 30,marginLeft: 10, marginRight: 10,styledMode: true },
		title: { text: 'Vehicle models',margin: 20 },
		xAxis: { type: 'category',visible: true},
		yAxis: {min: 0, visible: true},
		plotOptions: { column: {pointPadding: 5,  pointWidth: 25, borderWidth: 0}},
		credits:{enabled: false},legend: {enabled: false},
		series:[{name: 'Types', data: dataPoints,  type: 'column',animation: false, dataLabels: {enabled: true,format: ' <b>{point.y}</b>', y:20 }}]
	});
}


function ReadFleetTable(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $VSInfo = $('#FleetTripTable');
	$.ajax({
		url: window.location.origin+'/scripts/GetTripSummary?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',type: "GET",
		success:function(dataPoints){
			UpdateCounter('','FleetTripTable');
			$VSInfo.append('<table class="display table noWrap" id="FleetVehicleReport"></table>');
			ShowFleetTable(dataPoints);
		},
		error:function(){
			$VSInfo.html('');
			$VSInfo.append("<i class='fas fa-exclamation text-danger'> error searching data</i>");
		}
	});
}
function ShowFleetTable($Vehicles){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	table=$('#FleetVehicleReport').DataTable( {
		data:$Vehicles,autoWidth: false,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		destroy:true,
		lengthMenu: [[8,75,200,-1],[8,75,200,"All"]],
		dom: 'Bfltirp',
		searching: true, scrollCollapse: true,paging: true,scrollX: true,
		buttons: {buttons: [
			{ 	extend: 'excel',filename: 'rFMSReader_FleetReport'},
			{	extend: 'pdf', 	orientation: 'portrait',pageSize: 'A4',messageTop: 'Trip-data for selected Group'		},
			{ 	extend: 'csv',  extension: '.csv', fieldSeparator: ';', },
			{	extend: 'print',messageTop: 'Trip-data for selected group '}]
		},
		columns: [
			{ "data": "customerVehicleName","title":"Vehicle",		"class":"dt-left"},
			{ "data": "amountTrips",		"title":"Trips",		"class":"dt-right"},
			{ "data": "Duration",			"title":"Duration",		"class":"dt-center"},
			{ "data": "Distance",			"title":"Distance",		"class":"dt-center"},
			{ "data": "FuelUsed",			"title":"Fuelused",		"class":"dt-center"},
			{ "data": "FuelUsage",			"title":"Fuelusage",	"class":"dt-center"},
			{ "data": "DriveTime",			"title":"DriveTime",	"class":"dt-center"},
			{ "data": "CO2_emission",		"title":"Co2",			"class":"dt-center"},
			]
	} );
}

function ReadFleetmodels(){
	var $VSInfo = $('#FleetmodelChart');
	var dataPoints = [];
	$.ajax({
		url: window.location.origin+'/scripts/CustomermodelsFleet',
		dataType:'JSON',type: "POST",
		success:function(data){
			$.each(data, function(key, val){
				dataPoints.push([val.model, parseFloat(val.TotalPermodel) ]);
			})
			FleetmodelChart(dataPoints);
		},
		error:function(){
			$VSInfo.html('');
			$VSInfo.append("<i class='fas fa-exclamation text-danger'> error searching data</i>");
		}
	});
}

function ShowDriveTime($Search, $Info,$c,$Date){
	UpdateCounter('',$Info);UpdateCounter('',$c);
	ShowPlaceholder($Info,'graph');
	if ($Date==null){
		$Date=new Date().format("yyyy-mm-dd");
	}
	$.ajax({
		url: window.location.origin+'/scripts/GetDriveTime?id='+$Search+'&date='+$Date,
		dataType:'JSON',
		type: "GET",
		success:function(data){
			UpdateCounter('',$Info);
			DriveTime(data,$Info,$c,$Search);
		}
	});
}
function ShowTripDriveTime(val, $Info){
	UpdateCounter('',$Info);
	var $data=[];
	for ( var t=0; t < val.length; ++t ) {
		if (val[t].driver1Id_WSC!='') {
			$DriverData = {'workingstate':val[t].driver1Id_WSC,'createdDateTime':val[t].createdDateTime};
			$data.push($DriverData);
		}
	}
	DriveTime($data,$Info);
}

function setTTColor($s){
	$color="6";
	if ($s=='YELLOW'){$color="#ffc107";}
	if ($s=='INFO'){$color="#efefef";}
	if ($s=='OFF'){$color="transparent";}
	if ($s=='RED'){$color="#FF0000";}
	if ($s=='DRIVE'){$color="0";}
	if ($s=='WORK'){$color="1";}
	if ($s=='REST'){$color='2';}
	if ($s=='DRIVER_AVAILABLE'){$color="10";}
	if ($s=='NOT_AVAILABLE'){$color="transparent";}
	return $color;
}



function DriveTime(val,$DivId,c,$id){
	var $Count = {'REST':0,'DRIVE':0,'WORK':0,'DRIVER_AVAILABLE':0};
	var TTData = [];var $row=3;var $PW='15';var $color=''
	var $DriverData = {'x': c_ToLocalDate(val[0].createdDateTime),'x2':'','y':$row,'diff':0,'status':val[0].workingstate,'pointWidth':$PW,'colorIndex':'2'};
	for ( var t=0; t < val.length; ++t ){
		if (val[t].workingstate=='NOT_AVAILABLE') { val[t].workingstate = 'REST';}
		var $state=val[t].workingstate;
		if ($DriverData.status != $state){
			$DriverData.x2 = c_ToLocalDate(val[t].createdDateTime);
			$DriverData.diff=($DriverData.x2-$DriverData.x);
			$DriverData.y=$row;
			TTData.push($DriverData);
			if ($state == 'REST')				{ $row = 3;$color='2'; }
			if ($state == 'DRIVE')				{ $row = 0;$color='6'; }
			if ($state == 'WORK')				{ $row = 1;$color='1'; }
			if ($state == 'DRIVER_AVAILABLE') 	{ $row = 2;$color='10';}
			$Count[$DriverData.status]+=$DriverData.diff;
			$DriverData={'x':c_ToLocalDate(val[t].createdDateTime),'x2':'','y':$row,'diff':0,'status':$state,'pointWidth':$PW,'colorIndex':$color};
		}
	}
	$DriverData.x2=c_ToLocalDate(val[val.length-1].createdDateTime);
	$DriverData.diff=($DriverData.x2-$DriverData.x);
	TTData.push($DriverData);
	$Count[$DriverData.status]+=$DriverData.diff;
	var $str='<div class="d-flex text-primary mt-2 small ">'+
			'<div class="p-auto mr-1 col border"><i class="fad fa-user-circle drive">	</i> '	+SecondsToTime($Count['DRIVE']/1000)+'</></div>'+
			'<div class="p-auto mr-1 col border"><i class="fad fa-tools work">		 	</i> '	+SecondsToTime($Count['WORK']/1000)+ '</div>';
		$str+='<div class="p-auto mr-1 col border"><i class="fad fa-square available"></i> '	+SecondsToTime($Count['DRIVER_AVAILABLE']/1000)+'</div>';
	$str+='<div class="p-auto col border"><i class="fad fa-bed rest"> 			</i> '			+SecondsToTime($Count['REST']/1000)+'</div>';
	UpdateCounter($str,c);
	var $RefId='DR_'+$id;
	var $RemToday=Math.abs(32400000-$Count['DRIVE']);
	$RemToday=SecondsToTime($RemToday/1000);
	UpdateCounter($RemToday,$RefId);
	Highcharts.chart($DivId, {
		chart: 	{ type: 'xrange', zoomType: 'x',marginLeft: 30, marginRight: 0, marginBottom: 45, marginTop: 10, spacingBottom: 50, spacingTop:20,spacingLeft: 10, spacingRight: 10,
				animation:true,styledMode: true  },
		title: 	{ text: '',margin: 20 },
		xAxis: 	{ type: 'datetime',dateTimeLabelFormats: {hour: '%H:00',minute: '%H:%M' },title: { text: ''},minorTickInterval: 3600000,tickInterval: 10800000,labels: {autoRotation: [-30, -0], overflow: 'justify' }},
		yAxis: 	{ visible:true,  maxPadding: 2, categories: ['D','W','A','R'],reversed: true},
		tooltip:{ xDateFormat: '%H:%M:%S',headerFormat:'<small>( {point.x:%H:%M} - {point.x2:%H:%M} )</small> ',pointFormat: ' </br>{point.status}: for <b> {point.diff:%H:%M:%S}</b> ',
                footerFormat: '',crosshairs: false,shared: true,useHTML:false, backgroundColor: 'transparent',borderWidth: 0,padding:3, shadow: true},
		plotOptions: {series: {animation: false}},
		credits:{enabled: false},legend:{enabled: false},
		series: [{borderRadius:0,borderWidth:0,pointWidth: 25,data: TTData}]
	});
	return $Count;
}
// start HEATmap -functions
function InitializeHeatMAP(){
	map = L.map('map', {
		center: [46.00,8.65],
		zoomSnap:0.25,
		minZoom: 2,
		maxZoom: 18,
		zoom: 4,
		fadeAnimation: false
	});

	HERE = L.tileLayer('https://{s}.{base}.maps.cit.api.here.com/maptile/2.1/{type}/{mapID}/normal.traffic.day/{z}/{x}/{y}/{size}/{format}?app_id={app_id}&app_code={app_code}&lg={language}', {
		attribution: 'Map <a href="http://developer.here.com">HERE</a>',
		subdomains: '1234',	mapID: 'newest',app_id: 'Ch87ydlyXjsmnI5AcXho',	app_code: 'z2HsXPK48vhNPwyfcFr5ew',	base: 'traffic',maxZoom: 20,type: 'traffictile',language: 'eng',format: 'png8',	size: '256',  reuseTiles: true, unloadInvisibleTiles: false
	}).addTo(map);

}

function ShowHeatMap(addressPoints){
	addressPoints = addressPoints.map(function (p) { return [p.latitude, p.longitude]; });
	heat = L.heatLayer(addressPoints).addTo(map);
}

function ReadDelayedChart(){
	var SelectedDate =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var SelectedDateEnd =  new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $VSInfo = $('#GraphDelayedVehicles');
	$VSInfo.html('');
	ShowSpinnerSmall("DelayedVehicles");
	var $Info = $('#DelayedVehicles');$Info.html('');
	dataPoints=[];
	$.ajax({
		url: window.location.origin+'/scripts/GetCountDelayedVehicles?StartDate='+SelectedDate+'&EndDate='+SelectedDateEnd,
		dataType:'JSON',type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				dataPoints.push([new Date(val.createdDate), parseFloat(val.CountVin) ]);
			})
			ShowDelayedChart(dataPoints);

		}
	});
}
function ShowDelayedChart(dataPoints){
	Highcharts.chart('GraphDelayedVehicles',{
		chart: { zoomType: 'x', marginTop:0 ,marginLeft: 10, marginRight: 40,backgroundColor:'rgba(255, 255, 255, 0.0)'},
		title: { text: '',margin: 0},
		xAxis: { type: 'datetime', dateTimeLabelFormats: {day: '<b>%e-%b</b>'},title: { text: ''},tickInterval: 86400000,labels: {autoRotation: [-30, -40] }},
		yAxis: {min: 0, opposite: true, title: {text: '# vehicles'}},
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{series.name} </span></br><b>{point.y}</b><br/>{series.label}',
				 crosshairs: false,useHTML:true,shared:true},
      	plotOptions: { column: {pointPadding: 5,  pointWidth: 10, borderWidth: 0},
					   series: {turboThreshold: 1,animation: true,dataLabels:{enabled:true,rotation: 315,x:10,y:-20},
								point: {events: {click: function () {ReadTodayDelay(this.x);ReadDelayedVehicles(this.x); }}}
								}},
		credits:{enabled: false},
		legend: {enabled: false},
		series:[{name: 'Count of delayed Vehciles per day',	data: dataPoints, type: 'column' }]
	});
}
// end HEATmap -functions
function ShowTermsTxt(){
	$.ajax({
		url: window.location.origin+'/users/licenses/terms.txt',
		dataType:'TEXT',
		success:function(data){
			$('#TermsAndConditions').modal("show");
			var $LInfo = $('#TermsAndConditionsText');
			$LInfo.html('');
			$LInfo.append(data);
		},
		error:function(){
		}
	});
}

function DriverUtil(){
	var $DTInfo = $('#DriveTimeTable');
	var $ID = document.getElementById("drivers").value;
	var $Start =  new Date(document.getElementById("SelectDate").value).format("yyyy-mm-dd");
	var $End =    new Date(document.getElementById("SelectDateEnd").value).format("yyyy-mm-dd");
	var $DT = [];
	GetDriveTime($ID);
}

function DriverUtil_oud(){
	var $Info = $('#DriveTimeGraph');var $DTInfo = $('#DriveTimeTable');var $SumInfo = $('#SumReport');
	var $tachoID = document.getElementById("drivers").value;
	var $Start =  new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
	var $End =    new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
	var $DT = [];
	$DTInfo.html('');$SumInfo.html('');
	ShowSpinner("DriveTimeGraph");
	var $dc=0;
	$.ajax({
		url: window.location.origin+'/scripts/GetBiWDriveTime?id='+$tachoID+'&StartDate='+$Start+'&EndDate='+$End,
		dataType:'JSON',
		success:function(data){
			$Info.html('');	var $DailyDTM=[];
			var $Day=dateFormat(new Date($Start), "yyyy-mm-dd");
			for ( var t=0; t < data.length; ++t ){
				var $CD=dateFormat(new Date(data[t].createdDateTime), "yyyy-mm-dd");
				if ($CD == $Day ){$DailyDTM.push(data[t]);	}
				else {
					var $Graph='DTMGraph'+$dc.toString();var $Sum='DTMSum'+$dc.toString();
					$Info.append('<div class="row"><div class="col-12 StatusLine rFMSDriverGraph" id="'+$Graph+'"></div></div>');
					$DT.push(ReportDriveTime($DailyDTM,$Graph,$Sum));
					var $DailyDTM=[];$DailyDTM.push(data[t]);
					$Day=new Date($Day);
					$dc++;$Day.setDate($Day.getDate() + 1);
					$Day=dateFormat(new Date($Day), "yyyy-mm-dd");
				}
			}
			var $Graph='DTMGraph'+$dc.toString();var $Sum='DTMSum'+$dc.toString();
			$Info.append('<div class="row"><div class="col-12 StatusLine rFMSDriverGraph" id="'+$Graph+'"></div></div>');
//			$DT.push(ReportDriveTime($DailyDTM,$Graph,$Sum));
//			download(JSON.stringify($DT),'json.txt', 'text/plain');
			ShowDTTable($DT,'SumReport');
		},
		error:function(){
			$Info.html('');
			$Info.append('<span class="bg-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});
}
function ReadDTTable($id){
	var $Info = $('#DriveTimeTable');
	var $DT = [];
	$Info.html('');
	var $Start=moment().subtract(6, 'days').toDate();
	var $End=moment().toDate();
	ShowSpinner("DriveTimeGraph");
	var $dc=0;
	$.ajax({
		url: window.location.origin+'/scripts/GetBiWDriveTime?id='+$id+'&StartDate='+$Start.format("yyyy/mm/dd")+'&EndDate='+$End.format("yyyy/mm/dd"),
		dataType:'JSON',
		success:function(data){
			$Info.html('');	var $DailyDTM=[];
			var $Day=dateFormat(new Date($Start), "yyyy-mm-dd");
			for ( var t=0; t < data.length; ++t ){
				var $CD=dateFormat(new Date(data[t].createdDateTime), "yyyy-mm-dd");
				if ($CD == $Day ){$DailyDTM.push(data[t]);	}
				else {
					var $Graph='DTMGraph'+$dc.toString();var $Sum='DTMSum'+$dc.toString();
					$DT.push(ShowDrivingTime($DailyDTM,$Graph,$Sum));
					var $DailyDTM=[];$DailyDTM.push(data[t]);
					$Day=new Date($Day);
					$dc++;$Day.setDate($Day.getDate() + 1);
					$Day=dateFormat(new Date($Day), "yyyy-mm-dd");
				}
			}
			var $Graph='DTMGraph'+$dc.toString();var $Sum='DTMSum'+$dc.toString();
			$DT.push(ShowDrivingTime($DailyDTM,$Graph,$Sum));
//			download(JSON.stringify($DT),'json.txt', 'text/plain');
			ShowDTTable($DT,'SumReport');
		},
		error:function(){
			$Info.html('');
			$Info.append('<span class="bg-danger">Failed to retrieve data from webservice, please contact your application administrator</span>');
		}
	});
}

function ReportDriveTime(val,$String,c){
	var l = val.length-1;
	var d = new Date(val[0].createdDateTime);var e = new Date(val[l].createdDateTime);
	d.setHours(0,0,0,0);e.setHours(23,59,59);
	var $Count={'Date':Date.parse(d+'UTC'),'REST':0,'DRIVE':0,'WORK':0,'DRIVER_AVAILABLE':0};
	var TTData = [];var $DriverData={'x':Date.parse(d+'UTC'),'x2':Date.parse(e+'UTC'),'y':0,'diff':0,'status':'REST','Driver':'','colorIndex':"6",'pointWidth': '15',};
	TTData.push($DriverData);
	$DriverData={'x':Date.parse(d+'UTC'),'x2':'','y':0,'diff':0,'status':'REST','Driver':'','colorIndex':"6",'pointWidth': '15',};
	for ( var t=0; t < val.length; ++t ){
		if (val[t].workingstate=='NOT_AVAILABLE') {val[t].workingstate='REST';}
		var $state=val[t].workingstate;
		var $CD=dateFormat(new Date(val[t].createdDateTime), "yyyy-mm-dd");
		if ($DriverData.status!=$state){
			$DriverData.x2=Date.parse(val[t].createdDateTime);$DriverData.diff=($DriverData.x2-$DriverData.x);$DriverData.y=0;TTData.push($DriverData);
			$Count[$DriverData.status]+=$DriverData.diff;
			if ($state=='REST'){var $PW='15';} else {var $PW='25';}
			$DriverData={'x':Date.parse(val[t].createdDateTime),'x2':'','y':0,'diff':0,'status':$state,'pointWidth':$PW,'colorIndex':setTTColor($state)};
		}
	}
	var $TD=dateFormat(new Date(), "yyyy-mm-dd");
	var $CDT=dateFormat(new Date(val[l].createdDateTime), "yyyy-mm-dd");
	if ($TD==$CDT){var e = new Date(val[l].createdDateTime); }
	$DriverData.x2=Date.parse(e+'UTC');$DriverData.diff=($DriverData.x2-$DriverData.x);$DriverData.y=0;TTData.push($DriverData);
	$Count[$DriverData.status]+=$DriverData.diff;
	var $string='<div class="row "><div class="col-12 col-md-3 primary d-flex align-items-center"><B>'+ dateFormat(new Date(e), "UTC:dddd yyyy-mm-dd")+"</b></div>"+
			 '<div class="col-3 col-md-2"><i class="far fa-user-circle drive"></i> <b>'+dateFormat(new Date($Count['DRIVE']), "UTC:HH:MM:ss")+'</b></div>'+
		     '<div class="col-3 col-md-2"><i class="fas fa-tools work"> 	  </i> <b>'+dateFormat(new Date($Count['WORK']), "UTC:HH:MM:ss")+ '</b></div>';
	$string+='<div class="col-3 col-md-2"><i class="far fa-square available"> </i> <b>'+dateFormat(new Date($Count['DRIVER_AVAILABLE']), "UTC:HH:MM:ss")+'</b></div>';
	$string+='<div class="col-3 col-md-2"><i class="fas fa-bed rest"> 		  </i> <b>'+dateFormat(new Date($Count['REST']), "UTC:HH:MM:ss")+'</b></div>';$string+='<div class="col-3 col-md-2"><i class="fas fa-bed rest"> 		  </i> <b>'+dateFormat(new Date($Count['REST']), "UTC:HH:MM:ss")+'</b></div>';
//	UpdateCounter($string,c);
	Highcharts.chart($String, {
		chart: 	{type: 'xrange', zoomType: 'x',marginLeft: 10, marginRight: 0, marginBottom: 45, spacingBottom: -20,spacingTop:-10,spacingLeft: 10, spacingRight: 10,animation:false},
		title: 	{text: '',margin: 0},
		xAxis: 	{type: 'datetime',dateTimeLabelFormats: {hour: '%H', },title: { text: ''},tickInterval: 7200000,labels: {autoRotation: [-30, -40], overflow: 'justify' }},
		yAxis: 	{visible:false,   maxPadding: 2,categories: ''},
		tooltip:{xDateFormat: '%H:%M:%S',headerFormat: '<span style="color:{series.colorIndex}">{point.x} - {point.x2}</span> ',pointFormat: '<span style="color:{series.colorIndex}"><br/> <b>{point.status}</b> {point.diff:%H:%M:%S}</span>',crosshairs: true,shared: true},
		plotOptions: {series: {animation: false}},
		credits:{enabled: false},legend:{enabled: false},
		series: [{borderRadius:0,borderWidth:0,pointWidth: 25,data: TTData,turboThreshold: 0, colorIndex:'1'}]
	});
	return $Count;
}
function ShowDrivingTime(val,$String,c){
	var l = val.length-1;
	var d = new Date(val[0].createdDateTime);var e = new Date(val[l].createdDateTime);
	d.setHours(0,0,0,0);e.setHours(23,59,59);
	var $Count={'Date':Date.parse(d+'UTC'),'REST':0,'DRIVE':0,'WORK':0,'DRIVER_AVAILABLE':0};
	var TTData = [];var $DriverData={'x':Date.parse(d+'UTC'),'x2':Date.parse(e+'UTC'),'y':0,'diff':0,'status':'REST','Driver':'','colorIndex':"6",'pointWidth': '15',};
	TTData.push($DriverData);
	$DriverData={'x':Date.parse(d+'UTC'),'x2':'','y':0,'diff':0,'status':'REST','Driver':'','colorIndex':"6",'pointWidth': '15',};
	for ( var t=0; t < val.length; ++t ){
		if (val[t].workingstate=='NOT_AVAILABLE') {val[t].workingstate='REST';}
		var $state=val[t].workingstate;
		var $CD=dateFormat(new Date(val[t].createdDateTime), "yyyy-mm-dd");
		if ($DriverData.status!=$state){
			$DriverData.x2=Date.parse(val[t].createdDateTime);$DriverData.diff=($DriverData.x2-$DriverData.x);$DriverData.y=0;TTData.push($DriverData);
			$Count[$DriverData.status]+=$DriverData.diff;
			if ($state=='REST'){var $PW='15';} else {var $PW='25';}
			$DriverData={'x':Date.parse(val[t].createdDateTime),'x2':'','y':0,'diff':0,'status':$state,'pointWidth':$PW,'colorIndex':setTTColor($state)};
		}
	}
	var $TD=dateFormat(new Date(), "yyyy-mm-dd");
	var $CDT=dateFormat(new Date(val[l].createdDateTime), "yyyy-mm-dd");
	if ($TD==$CDT){var e = new Date(val[l].createdDateTime); }
	$DriverData.x2=Date.parse(e+'UTC');$DriverData.diff=($DriverData.x2-$DriverData.x);$DriverData.y=0;TTData.push($DriverData);
	$Count[$DriverData.status]+=$DriverData.diff;
	var $string='<div class="row "><div class="col-12 col-md-3 primary d-flex align-items-center"><B>'+ dateFormat(new Date(e), "UTC:dddd yyyy-mm-dd")+"</b></div>"+
			 '<div class="col-3 col-md-2"><i class="far fa-user-circle drive"></i> <b>'+dateFormat(new Date($Count['DRIVE']), "UTC:HH:MM:ss")+'</b></div>'+
		     '<div class="col-3 col-md-2"><i class="fas fa-tools work"> 	  </i> <b>'+dateFormat(new Date($Count['WORK']), "UTC:HH:MM:ss")+ '</b></div>';
	$string+='<div class="col-3 col-md-2"><i class="far fa-square available"> </i> <b>'+dateFormat(new Date($Count['DRIVER_AVAILABLE']), "UTC:HH:MM:ss")+'</b></div>';
	$string+='<div class="col-3 col-md-2"><i class="fas fa-bed rest"> 		  </i> <b>'+dateFormat(new Date($Count['REST']), "UTC:HH:MM:ss")+'</b></div>';
//	UpdateCounter($string,c);
	return $Count;
}

function ShowDTTable($Count,$DivID){
	var $TotCount={'REST':0,'DRIVE':0,'WORK':0,'DRIVER_AVAILABLE':0,'EXTRATIME':0,'SHORTREST':0,'INFRIGMENT':0};
	var $SumInfo = $('#'+$DivID);$Str='';
	for( var t=0; t < $Count.length; ++t ){
		$Count[t].Date=dateFormat($Count[t].Date, "ddd yyyy-mm-dd");
		$TotCount.DRIVER_AVAILABLE+=$Count[t].DRIVER_AVAILABLE;
		$TotCount.WORK+=$Count[t].WORK;
		$TotCount.REST+=$Count[t].REST;
		$TotCount.DRIVE+=$Count[t].DRIVE;
		if ($Count[t].DRIVE>32400000){$Count[t].EXTRATIME++;}
		if ($Count[t].REST< 39600000){$Count[t].SHORTREST++;}
		if ($Count[t].DRIVE>36000000){$Count[t].INFRIGMENT++;}
		if ($Count[t].REST< 32400000){$Count[t].INFRIGMENT++;}
		$Count[t].DRIVER_AVAILABLE=dateFormat(new Date($Count[t].DRIVER_AVAILABLE), "UTC:HH:MM");
		$Count[t].WORK=dateFormat(new Date($Count[t].WORK), "UTC:HH:MM");
		$Count[t].REST=dateFormat(new Date($Count[t].REST), "UTC:HH:MM");
		$Count[t].DRIVE=dateFormat(new Date($Count[t].DRIVE), "UTC:HH:MM");
	}
	var table=$('#DriveTimeTable').DataTable( {
 		data:$Count,autoWidth: false,
		createdRow: function( row, data, dataIndex ) {
			if (TimeToSeconds(data.DRIVE) != 0 ) 			{$('td:eq(1)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.WORK) != 0 ) 			{$('td:eq(2)', row).addClass('cell_bold');}
 			if (TimeToSeconds(data.DRIVER_AVAILABLE) != 0 ) {$('td:eq(3)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.REST) != 0 ) 			{$('td:eq(4)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.DRIVE) > 540 )			{$('td:eq(1)', row).addClass('cell_warning');}
			if (TimeToSeconds(data.DRIVE) > 600 ) 			{$('td:eq(1)', row).addClass('cell_danger');}
			if (TimeToSeconds(data.REST) < 540 ) 			{$('td:eq(4)', row).addClass('cell_danger');}
		},
		destroy:true,order:[[0,'asc' ]],
		lengthMenu: [[14, -1], [14,"All"]],
		dom: 'Bftirp',
		searching: false,ordering: false, footer:false,scrollCollapse: true,paging: false,scrollX: true,
		buttons: {buttons: []},
		columns: [
			{ "data": "Date",				"title":"Date",			"class":"dt-left"},
			{ "data": "DRIVE",				"title":"Drive",		"class":"dt-right"},
			{ "data": "WORK",				"title":"Work",			"class":"dt-right"},
			{ "data": "DRIVER_AVAILABLE",	"title":"Available",	"class":"dt-right"},
			{ "data": "REST",				"title":"Rest",			"class":"dt-right"},
			{ "data": "COMP",    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-circle rest"></i>';} 		 else {return '<i class="far fa-circle gray"></i>';}},"title":"Compliant","visible":true,"class":"dt-center" },
			{ data: "EXTRATIME", "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-circle text-warning"></i>';} else {return '<i class="far fa-circle gray"></i>';}},"title":"Extra"	 ,"visible":true,"class":"dt-center" },
			{ data: "SHORTREST", "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-circle text-warning"></i>';} else {return '<i class="far fa-circle gray"></i>';}},"title":"Short"	 ,"visible":true,"class":"dt-center" },
			{ data: "INFRIGMENT","render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-circle text-danger"></i>';}  else {return '<i class="far fa-circle gray"></i>';}},"title":""		 ,"visible":true,"class":"dt-center" },
			]
	} );
	$('#DriveTimeTable').on('click', 'tr', function() {
		if ( $(this).hasClass('active') ) {	$(this).removeClass('active');	}
		else {	table.$('tr.active').removeClass('active');$(this).addClass('active');}
	});
	$SumInfo.html('');
	$Str+='<div class="row pt-2">';
	$Str+='<div class="form-group col-xl-3 col-lg-4 col-6"><div class="sum-drive">	<span>drive	</span><div class="info-box-number ml-auto">'+seconds2time($TotCount.DRIVE)+'</div></div></div>';
	$Str+='<div class="form-group col-xl-3 col-lg-4 col-6"><div class="sum-work">	<span>work	</span><div class="info-box-number ml-auto">'+seconds2time($TotCount.WORK)+'</div></div></div>';
	$Str+='<div class="form-group col-xl-3 col-lg-4 col-6"><div class="sum-rest">	<span>rest	</span><div class="info-box-number ml-auto">'+seconds2time($TotCount.REST)+'</div></div></div>';
//	$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="sum-avail"> 	<span>Available	</span></br><span class="info-box-number">'+seconds2time($TotCount.DRIVER_AVAILABLE)+'</span></div></div>';
//	$Str+='<div class="form-group col-xl-2 col-lg-4 col-6"><div class="sum-today">	<span>Remaining Today</span></br><span class="info-box-number">xx:xx</span></div></div>';
//	$Str+='<div class="form-group col-xl-3 col-lg-4 col-6"><div class="sum-week">	<span>Remaining Weekly</span><div class="info-box-number ml-auto">xx:xx</div></div></div>';
	$Str+='</div>';
	$SumInfo.append($Str);
}

function GetDriveTime($Search){
	var myEle = document.getElementById("SelectDate");
	if(myEle) {
		var $Start 	= new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
		var $End 	= new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
		var $Search = document.getElementById("drivers").value;
		$str='&StartDate='+$Start+'&EndDate='+$End;
	} else {
		$str='';
	}
	table=$('#DriveTimeTable').DataTable( {
		ajax: {	url: window.location.origin+'/scripts/GetDriveTimes?id='+$Search+$str,dataSrc: ''	},
 		autoWidth: false,processing: false,
		createdRow: function( row, data, dataIndex ) {
			if (TimeToSeconds(data.drive) != 0 ) 		{$('td:eq(1)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.work) != 0 ) 		{$('td:eq(2)', row).addClass('cell_bold');}
 			if (TimeToSeconds(data.available) != 0 ) 	{$('td:eq(3)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.rest) != 0 ) 		{$('td:eq(4)', row).addClass('cell_bold');}
			if (TimeToSeconds(data.drive) > 540 )		{$('td:eq(1)', row).addClass('cell_warning');data.EXTRATIME='1'}
			if (TimeToSeconds(data.drive) > 600 ) 		{$('td:eq(1)', row).addClass('cell_danger');data.INFRIGMENT='1'}
			if (TimeToSeconds(data.rest)  < 540 ) 		{$('td:eq(4)', row).addClass('cell_danger');data.INFRIGMENT='1'}
			if (TimeToSeconds(data.rest)  < 660 ) 		{data.INFRIGMENT='1';}
			if (data.Total==1){
				$(row).addClass('rowTotal');
				$('td:eq(1)', row).removeClass('cell_warning');
				$('td:eq(1)', row).removeClass('cell_danger');
				$('td:eq(1)', row).removeData('cell_danger');
				$('td:eq(0)', row).text('Weekly Total');
			}
		},
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		autoWidth: false,destroy:true,order:[[0,'asc' ]],dom: 'Bfltirp',scrollX: false,deferRender: true,  "searching": false,
		 footer:false,scrollCollapse: true,paging: true, bInfo:false,order:[[0,'asc' ]],"lengthMenu": [[8, -1], [8, "All"]],
		buttons: {buttons: []},
		columns: [
			{ data: "DriveDate",	"title":"Date ",			"class":"dt-left"},
			{ data: "drive",		"title":"Drive <i class=\"fad fa-user-circle drive \">",		"class":"dt-center"},
			{ data: "work",			"title":"Work <i class=\"fad fa-tools work small\">",			"class":"dt-center"},
			{ data: "available",	"title":"Available <i class=\"fad fa-square available border\"></i>",	"class":"dt-center"},
			{ data: "rest",			"title":"Rest <i class=\"fad fa-bed rest\"> ",			"class":"dt-center"},
			{ "data": "COMP",
				"render": function(data, type, row) {if(data != '0') {return '<i class="fad fa-circle rest"></i>';} else {return '<i class="far fa-circle "></i>';}},
				"title":"","visible":true,"class":"dt-center","width":"1%" },
			{ data: "EXTRATIME",
                    "render": function(data, type, row) {if(data != '0') {return '<i class="fad fa-circle text-warning"></i>';} else {return '<i class="far fa-circle "></i>';}},
					"title":"Extra","visible":true,"class":"dt-center","width":"1%" },
			{ data: "SHORTREST",
                   "render": function(data, type, row) {if(data != '0') {return '<i class="fad fa-circle text-warning"></i>';} else {return '<i class="far fa-circle "></i>';}},
					"title":"Short","visible":true,"class":"dt-center","width":"1%" },
			{ data: "INFRIGMENT",
					"render": function(data, type, row) {if(data != '0') {return '<i class="fad fa-circle text-danger"></i>';} else {return '<i class="far fa-circle "></i>';}},
					"title":"","visible":true,"class":"dt-center","width":"1%" },

			]
	} );
	$('#DriveTimeTable').off('click');
	$('#DriveTimeTable').on('click', 'tr', function () {
		var data = table.row( this ).data();
		ShowDriveTime(data.DriverId,'DriveTimeGraph','DriveTimeSummary',data.DriveDate);
		UpdateCounter('-','tableDriverTrips');
		ShowDriverTripTable(data.DriveDate, data.DriverId, 'TripsCol');
	});
}

function Show1Trip($vin,$StartDate,$Enddate){
	var PolyArray = [];
	rFMS_Trip.clearLayers();
	var TripTimerIcon = L.icon({ iconUrl: '../images/triparrow.png', iconSize: [16, 16], iconAnchor: [8, 8], popupAnchor: [0,-8]})
	var StartIcon = L.icon({ iconUrl: '../images/StartMarker.png', iconSize: [, 32 ], iconAnchor: [16, 32],  tooltipAnchor: [0, -36]})
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicle1Trip?vin='+$vin+'&StartDate='+$StartDate+'&EndDate='+$EndDate,
		dataType:'JSON',
		success:function(data){
			$.each(data, function(key, val){
				if (val.triggerType == 'DISTANCE_TRAVELLED'){
					L.marker( [val.GNSS_latitude, val.GNSS_longitude], {rotationAngle: val.GNSS_heading,icon:TripTimerIcon,draggable: false} )
					.addTo(rFMS_Trip);
				}
				if (val.triggerType == 'IGNITION_OFF'){
					var polyline = new L.Polyline(PolyArray,{color:"#00529c",weight:6})
					.addTo(rFMS_Trip)
					StartTrip=t;PolyArray=[];Distance = 0;delayed=false;$Delay=0;TimerCounter=0;
					L.marker( [val.GNSS_latitude, val.GNSS_longitude], {icon:StartIcon,	draggable: false} )
					.addTo(rFMS_Trip);
				}
				if (val.triggerType == 'IGNITION_ON' ){
					L.marker( [val.GNSS_latitude, val.GNSS_longitude], {icon:StartIcon,	draggable: false} )
					.addTo(rFMS_Trip);
				}
			});
			if (val.GNSS_latitude != 0){PolyArray.push([val.GNSS_latitude, val.GNSS_longitude]);}
		}
	});
	map.addLayer(rFMS_Trip);
	map.fitBounds(rFMS_Trip.getBounds(),{padding: [50,50]});
}
function ReadDelayedVehicles($Search){
	var SelectedDate =  new Date($Search).format("yyyy/mm/dd");
	var $Info = $('#DelayedTables');$Info.html('');ShowSpinner("DelayedTables");
	$vehicles=[];
	$.ajax({
		url: window.location.origin+'/scripts/GetDelayedVehicles?StartDate='+SelectedDate,
		dataType:'JSON',type: "GET",
		success:function($vehicles){
			ShowDelayedVehicles($vehicles);
		}
	});
}
function ReadTodayDelay($Search){
	var dataPoints = [];
	var dataPoints1 = [];
	var $Info = $('#DelayedVehicles');$Info.html('');ShowSpinnerSmall("DelayedVehicles");
	var $TInfo = $('#TitleDay');$TInfo.html('');
	if (!$Search){
		var $localDate=new Date();
	} else {
		var $localDate=new Date($Search);
	}
	$.ajax({
		url: window.location.origin+'/scripts/CountDelayedVehiclesDay?StartDate='+$localDate.format("yyyy-mm-dd"),
		dataType:'JSON',type: "GET",
		success:function(data){
			$.each(data, function(key, val){
				dataPoints.push([parseFloat(val.Hours),parseFloat(val.new)]);
				dataPoints1.push([parseFloat(val.Hours),parseFloat(val.count)]);
			})
			$Info.html('');
			if (!$Search){
				$Info.append('<div id="MonitorDelayedVehicles"></div>');$String='MonitorDelayedVehicles';
			} else {
				$Info.append('<div class="dgpad" id="ShowHourlyDelayed"></div>');$String='ShowHourlyDelayed';
			}
			$TInfo.append('Delayed vehicles for day :  <b>'+$localDate.format("dddd dd  mmm yyyy")+'</b> (per Hour)');
			DelayedChart(dataPoints,dataPoints1,$String);
		}
	})
}

function DelayedChart($dataPoints,$dataPoints1,Str){
	Highcharts.chart(Str,{
		chart: { marginTop:0, marginBottom:30 ,marginLeft: 10, marginRight: 10,styledMode: true},
		title: { text: '',margin: 0 },
		xAxis: { title: { text: ''},tickInterval: 1,labels: {autoRotation: [-30, -40], overflow: 'justify' }},
		yAxis: {},
		tooltip:{headerFormat: '',
				 pointFormat: '<span style="color:{series.color}">{series.name} </span> - <b> {point.y}</b><br/>{series.label}',
				 crosshairs: false,useHTML:true,shared:true},
		plotOptions: {column: {pointPadding: .5,  pointWidth: 10, borderWidth: 0},
					  series: {turboThreshold: 1000,animation: false,dataLabels:{enabled:true,rotation: 315,x:10,y:-20}}},
		credits:{enabled: false},
		legend: {enabled: false},
		series:[{data: $dataPoints1,type:'column',name:'delayed',colorIndex:"0",dataLabels:{enabled:true,rotation: 270,x:0,y:-20} },
				{data: $dataPoints ,type:'spline',name:'new '    ,colorIndex:"1",dataLabels:{enabled:false}}],
	});
}
function ProcessTimeLineMarkers(data){
	var dataPoints = [];var dataPoints1 = [];var $DriverData;var $SpeedData;$distance:40;
	$.each(data, function(key, val){
		$color='1';$WS=val.driver1Id_WSC;
		if (val.triggerType=='TIMER')											{$color='0'; $distance=40; $label='Timer <br><small>('+val.driver1Id_WSC+')</small>';}
		if (val.triggerType=='ENGINE_ON'||val.triggerType=='ENGINE_OFF')		{$color='3'; $distance=80; $label=val.triggerType;}
		if (val.triggerType=='IGNITION_ON'||val.triggerType=='IGNITION_OFF')	{$color='8'; $distance=20; $label=val.triggerType;}
		if (val.triggerType=='TELL_TALE')										{$color='2'; $distance=60; $label='Display<BR><small>('+val.triggerInfo+')</small>';}
		if (val.triggerType=='DRIVER_1_WORKING_STATE_CHANGED')					{$color='2'; $distance=60; $label='Tacho1<br><small>('+val.driver1Id_WSC+')</small>';}
		if (val.triggerType=='TachoStatus')										{$color='2'; $distance=60; $label='Tacho1<br><small>('+val.driver1Id_WSC+')</small>';}
		if (val.triggerType=='DISTANCE_TRAVELLED')								{$color='5'; $distance=80; $label=val.triggerType;}
		if (val.triggerType=='DRIVER_LOGOUT'||val.triggerType=='DRIVER_LOGIN')	{$color='4'; $distance=80; $label=val.triggerType;}
		$DriverData={'x':Date.parse(val.createdDateTime+' UTC'),'label':$label+' ','colorIndex':$color}
		$SpeedData={'x':Date.parse(val.createdDateTime+' UTC'),'label':'<span class="text-right">'+parseFloat(val.wheelBasedSpeed)+' km/u</span>','y':parseFloat(val.wheelBasedSpeed),'colorIndex':10};
		dataPoints.push($DriverData);
		dataPoints1.push($SpeedData);
	})
	ShowMarkersTimeLine(dataPoints,dataPoints1);
}
function ShowMarkersTimeLine($datapoints,$data1){
	Highcharts.chart('TripDetailsTimeline', {
		chart: 	{ zoomType: 'x', styledMode: true,marginTop:10, marginBottom:30 ,marginLeft: 30, marginRight: 0 },
		xAxis: 	{ type: 'datetime',dateTimeLabelFormats: {hour: '%H:00',minute: '%H:%M' }, visible: true,tickInterval: 1800000,  },
		yAxis: [{ gridLineWidth: 1, title: '', visible: false, labels: { enabled: true}  },{ title:'',visible: false, tickPixelInterval: 30}],
		legend:	{ enabled: true},
		credits:{ enabled: false},
		title: 	{ text: '' },
		tooltip:{ crosshairs: true,useHTML:true,shared:true,
			headerFormat:'<span style="color:{series.colorIndex}">{point.x:%Y-%m-%d %H:%M:%S}</span>',
			pointFormat: '<br><span style="color:var(--primary)"><b>{point.label} </b></span>',
		},
		plotOptions: {
				series: { turboThreshold: 1000,animation: true,dataLabels:{enabled:false,rotation: 0,x:10,y:-20,allowOverlap: true}},
				spline:	{ marker: {enabled: true, symbol: 'circle', radius: 2, states: {hover: {enabled: true}}}		}
				},
		series:[
				{ showInNavigator: true,animation: false, dataLabels: { allowOverlap: false,distance:45,fontsize:'12px',name:'data message', enabled:true,rotation: 0,borderWidth:1,format: '<BR> <p style="color:var(--primary)"><b>{point.label}</b></p>'},
				 marker: { symbol: 'circle'}, type: 'timeline', lineColor: 'var(--primary)',lineWidth:4,yAxis:0,data: $datapoints,colorIndex:'7'},
				{ data: $data1 ,type:'areaspline', yAxis:1 ,colorIndex:'0'}
		]
	});
}

function ShowMarkersGraph($data1){
	Highcharts.chart('TripDetailsGraph', {
		chart: 	{marginTop:0, marginBottom:30 ,marginLeft: 10, marginRight: 10,styledMode: true,zoomType:'x'},
		xAxis: 	{type: 'datetime', visible: true  },
		yAxis:	{title: 'km/u', visible: true, labels: { enabled: true} } ,
		legend:	{enabled: false},
		credits:{enabled: false},
		plotOptions: {
				area: {marker: { enabled: false, symbol: 'circle', radius: 2, states: {hover: {enabled: true}} } } ,
				column: {pointPadding: .5,  pointWidth: 10, borderWidth: 0},
				series: {turboThreshold: 1000,animation: false,dataLabels:{enabled:true,rotation: 315,x:10,y:-20}}
				},
		title: 	{text: '' },
		tooltip:{enabled: false,crosshairs: true,shared: true},
		series:	[{data: $data1 ,type:'spline',name:'new '    ,colorIndex:"1",dataLabels:{enabled:false}}]
	});
}

function ShowTTGraph($search){
	var d = new Date();var e = new Date();
	d.setHours(0,0,0,0);e.setHours(23,59,59,0);
 	var TTData = [];
	for ( var dd=0; dd < 7; ++dd ){
		PB={};
		PB.x= Date.parse(d);PB.x2 =Date.parse(e);PB.y= dd;PB.status= 'off';PB.color= '#F6F6F6';TTData.push(PB);
	}
	$.ajax({
		url: window.location.origin+'/scripts/GetTodayTT?vin='+$search,
		dataType:'JSON',type: "GET",
		success:function(data){
			if (data.length==0){ UpdateCounter('<div class="m-1 primary">no data available</div>','TodayTT');return;}
			PB={};FL={};ABL={};EMI={};EO={};ECT={};EEF={};
 			PB.x= Date.parse(data[0].createdDateTime);PB.x2 ='';PB.y= 0;PB.status= 'off';PB.color= '#F6F6F6';
			FL.x= Date.parse(data[0].createdDateTime);FL.x2 ='';FL.y= 1;FL.status= 'off';FL.color= '#F6F6F6';
			ABL.x=Date.parse(data[0].createdDateTime);ABL.x2='';ABL.y=2;ABL.status='off';ABL.color='#F6F6F6';
			EMI.x=Date.parse(data[0].createdDateTime);EMI.x2='';EMI.y=3;EMI.status='off';EMI.color='#F6F6F6';
			EO.x= Date.parse(data[0].createdDateTime);EO.x2 ='';EO.y =4;EO.status= 'off';EO.color= '#F6F6F6';
			ECT.x=Date.parse(data[0].createdDateTime);ECT.x2='';ECT.y=5;ECT.status='off';ECT.color='#F6F6F6';
			EEF.x=Date.parse(data[0].createdDateTime);EEF.x2='';EEF.y=6;EEF.status='off';EEF.color='#F6F6F6';
			for ( var t=0; t < data.length; ++t ){
				var $tale = data[t].tellTale;var $state=data[t].state;
				if ($tale=="PARKING_BRAKE" ){
					if (PB.status!=$state){
						PB.x2=Date.parse(data[t].createdDateTime);TTData.push(PB);
						if (t<data.length){PB={};PB.x=Date.parse(data[t].createdDateTime);PB.x2='';PB.y=0;PB.status=$state;PB.color=setTTColor($state);}
					}
				}
				if ($tale=="FUEL_LEVEL" ){
					if (FL.status!=$state){
						FL.x2=Date.parse(data[t].createdDateTime);TTData.push(FL);
						if (t<data.length){	FL={};FL.x=Date.parse(data[t].createdDateTime);FL.x2='';FL.y=1;FL.status=$state;FL.color=setTTColor($state);	}
					}
				}
				if ($tale=="ENGINE_MIL_INDICATOR" ){
					if (EMI.status!=$state){
						EMI.x2=Date.parse(data[t].createdDateTime);TTData.push(EMI);
						if (t<data.length){EMI={};EMI.x=Date.parse(data[t].createdDateTime);EMI.x2='';EMI.y=3;EMI.status=$state;EMI.color=setTTColor($state);	}
					}
				}
				if ($tale=="ENGINE_EMISSION_FAILURE" ){
					if (EEF.status!=$state){
						EEF.x2=Date.parse(data[t].createdDateTime);TTData.push(EEF);
						if (t<data.length){
							EEF={};EEF.x=Date.parse(data[t].createdDateTime);EEF.x2='';EEF.y=6;EEF.status=$state;EEF.color=setTTColor($state);
						}
					}
				}
			}

			Highcharts.chart('TodayTT', {
				chart: 	{type: 'xrange', zoomType: 'x',marginLeft: 80, marginRight: 40,styledMode: true },
				title: { text: '',margin: 0},
				xAxis: 	{type: 'datetime',dateTimeLabelFormats: {hour: '%H:00', minute:'%H:%M' },title: { text: ''},tickInterval: 14400000,labels: {autoRotation: [-30, -40] }},
				yAxis: 	{title: {text: ''},categories: ['Parking Brake','Fuel Level','AdBlue Level','EngineMil Indicator','Engine Oil','Engine Collant Temperature','Engine Emission Failure' ],reversed: true },
				tooltip:{xDateFormat: '%H:%M:%S',headerFormat: '{point.x} - {point.x2}',pointFormat: '<br/>Status : <b>{point.status}</b>',crosshairs: true,shared: true},
				credits:{enabled: false},legend:{enabled: false},
				series: [{borderRadius:0,borderWidth:0,pointWidth: 15,data: TTData}]
			});
		},
		error:function(){ UpdateCounter('<div class="m-1 text-danger">no data available</div>','TodayTT');}
	});

}

function ReadDriverTrips(s){
	var $LInfo = $('#'+s);
	var $str='';
	ShowSpinnerSmall(s);
	var localDate = new Date();
	$.ajax({
		url: window.location.origin+'/scripts/GetDriverTrips',
		type:'GET',
		dataType:'json',
		success:function(data){
			$LInfo.html('');
			$.each(data, function(key, val){
				if (dateFormat(val.StartDate, "yyyy-mm-dd")!=dateFormat(val.localDate, "yyyy-mm-dd")){
					$str='<div class="col-5 TripListName no-padding primary pr-3"><b>'+dateFormat(val.StartDate, "yyyy-mm-dd")+'</b></div>';
					$LInfo.append($str);
				}
				if (val.TripActive==0){
					$str='<div class="TC-row pb-1 mb-1 ">';
					$str+='<div class="col-12 d-flex no-padding px-1 pt-2 ">';
					$str+='<div class="col-5 TripListName no-padding primary pr-3"><i class="primary far fa-flag"></i> <b>'+dateFormat(val.StartDate, "HH:MM ")+'</b></div>';
					$str+='<div class="col-4 mr-auto TripListNameS no-padding pr-3 primary"><i class="primary fas fa-truck"></i> '+val.customerVehicleName+' </div>';
					$str+='<div class="ml-auto TripListName primary no-padding "><b>'+dateFormat(val.EndDate, "HH:MM ")+'<i class="primary fas fa-flag-checkered"></b></i></div>';
					$str+='</div>';
					$str+='<div class="col-12 d-flex no-padding px-1 pt-2">';
					$str+='<div class="col-3 TripListName no-padding pr-3 primary"><i class="fas fa-route"></i> '+val.Distance+' </div>';
					if (val.FuelUsage>0){$str+='<div class="col-3 TripListName no-padding pr-3 primary"> <i class="fas fa-tint "></i> '+val.FuelUsage+' </div>';}
					else {$str+='<div class="col-3 TripListNameS no-padding pr-3">  </div>';}
					if (val.FuelUsed>0){$str+='<div class="col-3 TripListNameS no-padding primary"" > <i class="fas fa-gas-pump "></i> '+val.FuelUsed+' </div>';}
					else {$str+='<div class="col-3 TripListNameS no-padding pr-3">  </div>';}
					$str+='<div class="ml-auto text-right TripListName primary no-padding"> '+val.Duration+' <i class="far fa-clock"></i></div>';
					$str+='</div></div>';
					$LInfo.append($str);
				}
				else {
					$Duration=new Date(localDate)- new Date(val.StartDate);
					$Distance=($C.OdoMeter-val.start_odometer)/1000;
					$str='<div class="TC-row pb-1 mb-1 ">';
					$str+='<div class="col-12 d-flex no-padding px-1 pt-2">';
					$str+='<div class="col-3 TripListName no-padding primary pr-3"><i class="primary far fa-flag"></i> <b>'+dateFormat(val.StartDate, "y-m-d HH:MM ")+'</b></div>';
					$str+='<div class="col-6 mr-auto TripListNameS no-padding pr-3"> '+val.customerVehicleName+' </div>';
					$str+='<div class="ml-auto TripListName work"><b>'+dateFormat(localDate, "HH:MM ")+'</b> <i class="fas fa-flip-horizontal fa-wind fa-sm" ></i><i class="fas fa-truck-moving"></i></div>';
					$str+='</div>';
					$str+='<div class="col-12 d-flex no-padding px-1 pt-2">';
					$str+='<div class="col-4 mr-auto TripListName no-padding primary"><i class="fas fa-route"></i> '+$Distance+' km </div>';
					$str+='<div class="col-3 text-right TripListName no-padding primary"> '+dateFormat($Duration, "HH:MM ")+' <i class="far fa-clock"></i></div>';
					$str+='</div></div>';
					$LInfo.append($str);
				}
				localDate=new Date(val.StartDate);
			})
		},
		error:function(){$LInfo.append('No Trips Today');}
	});
}
function ReadDriver_TachoStatus(s){
	var $LInfo = $('#'+s);
	var $str='';
	ShowSpinnerSmall(s);
	var localDate = new Date();
	$.ajax({
		url: window.location.origin+'/scripts/GetDriverTachoStatus',
		type:'GET',
		dataType:'json',
		success:function(data){
			$LInfo.html('');
			$.each(data, function(key, val){
				$str= '	<div class="col-12">';
				$str+='		<div class="row TC-row">';
				$str+='			<div class="col-12 "><b>'+val.Lastname+', '+val.Surname+'</b> / ';
				$str+='			<b>'+val.tachoDriverIdentification+' </b> <small>(valid until '+dateFormat(val.Validity, "yyyy-mm-dd HH:MM ")+')</small></div>';
				$Str+='		</div>';
				$str+='	</div>';
				$str+='	<div class="col-12">';
				$str+='		<div class="row TC-row">';
				$str+='			<div class="col-4  primary">Today </div>';
				$str+='			<div class="ml-auto text-right primary "> <b>'+val.RemainingDriveToday+'</b></div>';
				$str+='			<div class=" col-4 progressTC" id="TC1"></div>';
				$Str+='		</div>';
				$str+='	</div>';
				$str+='	<div class="col-12">';
				$str+='		<div class="row TC-row">';
				$str+='			<div class="col-4 primary">Weekly </div>';
				$str+='			<div class="ml-auto text-right primary "> <b>'+val.RemainingDriveWeek+'</b></div>';
				$str+='			<div class=" col-4 progressTC" id="TC2"></div>';
				$Str+='		</div>';
				$str+='	</div>';
				$str+='	<div class="col-12">';
				$str+='		<div class="row TC-row">';
				$str+='			<div class="col-4 primary">Bi-Weekly </div>';
				$str+='			<div class="ml-auto text-right primary "> <b>'+val.RemainingDriveBiWeekly+'</b></div>';
				$str+='			<div class=" col-4 progressTC" id="TC3"></div>';
				$Str+='		</div>';
				$str+='	</div>';
				$str+='	<div class="col-12">';
				$str+='		<div class="row TC-row">';
				$str+='			<div class="col-4  primary">Rest Today  </div>';
				$str+='			<div class="ml-auto text-right primary "> <b>'+val.RemainingRestToday+'</b></div>';
				$str+='			<div class=" col-4 progressTC" id="TC4"></div>';
				$Str+='		</div>';
				$str+='	</div>';
				$str+='</div>';
				$LInfo.append($str);

			})
			var options={strokeWidth: 18, easing: 'easeInOut', duration: 1100, color: '#266cab', trailColor: '#ddd', trailWidth: 4, svgStyle: {width: '100%', height: '100%'} };
			var options1={strokeWidth: 18, easing: 'easeInOut', duration: 1100, color: '#8AC543', trailColor: '#ddd', trailWidth: 4, svgStyle: {width: '100%', height: '100%'} };
			var bar = new ProgressBar.Line(TC1, options);
			var bar1 = new ProgressBar.Line(TC2, options);
			var bar2 = new ProgressBar.Line(TC3, options);
			var bar3 = new ProgressBar.Line(TC4, options1);
			bar.animate(.45);  // Number from 0.0 to 1.0
			bar1.animate(.5);  // Number from 0.0 to 1.0
			bar2.animate(.74);  // Number from 0.0 to 1.0
			bar3.animate(.24);  // Number from 0.0 to 1.0
		},
		error:function(){$LInfo.append('No Driver information available');}
	});
}

function ReadNotifications(){
	var $LInfo = $('#Not_Sidebar');
	var $str='';
	ShowSpinnerSmall('Not_Sidebar');
	var localDate = new Date();
	$.ajax({
		url: window.location.origin+'/scripts/GetNotifications',
		type:'GET',
		dataType:'json',
		success:function(data){
			$LInfo.html('');
			$.each(data, function(key, val){
				$str='<div class="col-12 " title="enabled until '+val.endPublish+'">';
				$str+='<div class="card Notification ">';
				if (val.notificationHeader!=''){
					$str+='<div class="card-header '+val.notificationType+'"><h4><strong>'+val.notificationHeader+'</strong><h4></div>';
				} else {
					$str+='<div class="card-header "><h4><strong></strong><h4></div>';
				}
				$str+='<div class="card-body '+val.notificationType+'">'+val.notificationInfo+'</div>';
				$str+='</div>';
				$str+='</div>';
				$LInfo.append($str);
			})
		},
		error:function(){$LInfo.html('');$LInfo.append('No information available');}
	});
}
function DashboardAPIStatusDetail(){
	var $LInfo = $('#DashboardAPIStatus');
	var $DP0=[]; $DP1=[];$DP2=[];$DP3=[];
	var $LInfo = $('#DashboardAPIStatus');
	var $str='<div class="card shadow-sm mb-3 DashTileCards"><div class="card-header">API interface status</div><div class="card-body"><div class="col-12 p-0" id="APIStatusGraph"></div></div></div>';
	$.ajax({
		url: window.location.origin+'/scripts/GetAPIStatus',
		type:'GET',
		dataType:'json',
		success:function(data){
			UpdateCounter($str,'DashboardAPIStatus');
			$str ='<div class="row px-3">';
			$str+='	<div class="col-4 shadow-sm rounded border d-flex p-2" title="Count Active Daemons"><div class="text-left"><i class="fad fa-download fa-2x fa-fw"></i></div><div class="ml-auto larger">'+data[0].ActiveDaemon+'</div></div>';
			$str+='	<div class="col-4 shadow-sm rounded border d-flex p-2 ml-auto " title="Connections"><div class="text-left"><i class="fad fa-list fa-2x fa-fw"></i></div>  <div class="ml-auto larger">'+data.length+'</div></div>';
			$str+='	<div class="col-12 p-0 pt-2">';
			$.each(data, function(key, val){
				$str+='<div class="row my-1 pr-3 ">';
				$str+='	<div class="col-7 text-left" title="Connection"><span class="gray">'+val.Name+'</span></div>';
				$str+='<div class="d-flex ml-auto ">';
				if(val.ActiveEndPoints>0){
					$str+='	<div class="px-1" title="Active">  <span class="badge px-2 badge-success ">'+val.ActiveEndPoints+'</span></div>';}
				if(val.InActiveEndPoints>0){
					$str+='	<div class="px-1" title="InActive"><span class="badge px-2 badge-warning ">'+val.InActiveEndPoints+'</span></div>';}
				if (val.FailedEndPoints>0){
					$str+='	<div class="px-1" title="Failed">  <span class="badge px-2 badge-danger ">'+val.FailedEndPoints+'</span></div>';}
				$str+='</div></div>';
			})
			$str+='</div></div></div>';
			UpdateCounter($str,'APIStatusGraph');
		//	GraphAPIStatusDetail($DP0,$DP1,$DP2,$DP3);
		},
		error:function(){$LInfo.html('');$LInfo.append('<i>No information available</i>');}
	});
}

function DashboardAPIStatus(){
	var $LInfo = $('#DashboardAPIStatus');
	var $str='<div class="card shadow-sm mb-3 "><div class="card-header">API interface status</div><div class="card-body"><div class="col-12 " id="APIStatusGraph"></div></div></div>';
	$.ajax({
		url: window.location.origin+'/scripts/GetAPIStatus?detail=false',
		type:'GET',
		dataType:'json',
		success:function(data){
			UpdateCounter($str,'DashboardAPIStatus');
			$str ='<div class="row  ">';
			$str+='	<div class="col-4 mb-3 mr-1 btn alert-success" title="DeamonActive"><div class="text-left">Collectors</div><div class="normal text-right alert-success info-box-number">'+data[4][0]+'</div></div>';
			$str+='	<div class="col-4 mb-3 ml-auto btn  alert-secondary" title="Connections"><div class="text-left"> Endpoints</div>  <div class="normal text-right  info-box-number">'+data[3][0]+'</div></div>';
			$str+='</div>';
			$str+='<div class="row">';
			$str+='	<div class="col mr-1 btn alert-success text-left" title="Active">Active <span class="normal info-box-number">'+data[0][0]+'</span></div>';
			$str+='	<div class="col mr-1 btn alert-warning " title="InActive">InActive <span class="normal info-box-number">'+data[1][0]+'</span></div>';
			$str+='	<div class="col btn alert-danger text-right" title="Failed">Failed <span class="normal info-box-number">'+data[2][0]+'</span></div>';
			$str+='</div>';
			UpdateCounter($str,'APIStatusGraph');
		//	GraphAPIStatus(data);
		}
	});
}
function GraphAPIStatus($DataPoints){
	var chart=Highcharts.chart('APIStatusGraph', {
		chart: {plotBackgroundColor: null,plotBorderWidth: 0,plotShadow: false,marginLeft: 10, marginTop:0,marginRight: 10,styledMode: false},
		title: {text: '',	align: 'center',verticalAlign: 'middle',y: 60},
		xAxis:{visible:false,datalabels:{enabled:false}},yAxis:{datalabels:{enabled:false},minorTickLength: 0,tickLength: 0},
		plotOptions: {
			column: {dataLabels: {	enabled: true}},
			series :{animation: false, dataLabels:{enabled:false}}
		},
		credits:false,
		series: [
			{type: 'column',name: 'Active', color:'green',data: $DataPoints[0] 	},
			{type: 'column',name: 'InActive',data: $DataPoints[1],color:'orange'},
			{type: 'column',name: 'Failed',data: $DataPoints[2],color:'red'	},
		]
	});
}
function CreateReport($R,$S) 	{
	UpdateCounter('Creating report...','TripReport');
	ShowSpinnerSmall('TripReport');
	if ($S==false) 	{
//		var $startdate = new Date(document.getElementById("SelectDate").value).format("yyyy/mm/dd");
//		var $enddate = new Date(document.getElementById("SelectDateEnd").value).format("yyyy/mm/dd");
		$url = '/scripts/createreport?type=' + $R ;
	} else {
		$url='/scripts/createreport?type='+$R+'&Schedule='+$S
	}
	$.ajax({
		url: window.location.origin+$url,
		success:function(data)	{
			if ($S==true){$("#TripReport").html("Scheduler has been set to weekly as of next monday").fadeIn(500).delay(5000).fadeOut(1000);}
			else {$("#TripReport").html("Report has been created ").fadeIn(500).delay(5000).fadeOut(1000);}
		},
		error:function(){$("#TripReport").html('<span class="danger"><i class="fas fa-exclamation-circle "></i>Report NOT created</span>').fadeIn(500).delay(5000).fadeOut(1000);}
	});
}

function openNav() {
	var $LInfo = $('#mySidebar');$LInfo.html('');
	var $str='<div class="card VehicleDetails shadow-sm">';
	$str+='<div class="card-header py-2"><h3>General Notifications</h3><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a></div>';
	$str+='</div>';
	$str+='<div id="Not_Sidebar"></div>';
	$LInfo.append($str);
	document.getElementById("mySidebar").style.width = "450px";
//	document.getElementById("main").style.marginLeft = "450px";
	ReadNotifications();
}
function closeNav() {
	UpdateCounter('','mySidebar');
	document.getElementById("mySidebar").style.width = "0";
//	document.getElementById("main").style.marginLeft= "0";
}
function openVehDet() {
	$("#VehicleDetails").css("margin-right", "-1px");
	$("#VehicleDetails").css("margin-top", "-1px");
	map.invalidateSize();
	map.fitBounds(rFMS_Latest.getBounds(),{padding: [60,60]});
}
function closeVehDet() {
	$("#VehicleDetails").css("margin-right", "-405px");
	rFMS_Trip.clearLayers();
	map.fitBounds(rFMS_Latest.getBounds(),{padding: [60,60]});
//	UpdateCounter('','VehicleDetails');
	ShowLatestLayer();
}
function HideNavBarGroup() {
	document.getElementById("navbar-group-selector").class += " hide";
}
function toggleIcon(e) {
  $(e.target)
    .prev(".panel-heading")
    .find(".more-less")
    .toggleClass("fa-plus fa-minus");
}
$(".panel-group").on("hidden.bs.collapse", toggleIcon);
$(".panel-group").on("shown.bs.collapse", toggleIcon);


function ShowAdminPagesTable($data){
	table=$('#tableAdminPages').DataTable( {
		data:$data,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		 processing: false,responsive: true,
		autoWidth: false,destroy:true,order:[[6,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,scrollX: true,deferRender: true,pageResize: true,colReorder: true,
		buttons: {	buttons: [	]},
		columns: [
			{ "data": "id","title":"id","class":"dt-left","width": "3%"},
			{ "data": "page", "title":"page","visible":true },
			{ "data": "",
                    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-lock-open rest"></i>';} else {return '<i class="fas fa-lock text-danger"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-right" },
			]
	} );
	$('#tableAdminPages').off('click');
	$('#tableAdminPages').off('mousedown');
	$('#tableAdminPages').on('click', 'tbody tr', function (e) {
		e.preventDefault();
		var SelectedId = $(this).find('td:nth-child(0)').text();
		$(this).closest('tr')
		$(window).attr("location","../users/admin_page?id="+$row);
   } );
}

function ShowTomFooleryTable($data){
	table=$('#tableAdminPages').DataTable( {
		data:$data,
		language: {	search: '<i class="fad fa-fw fa-filter" aria-hidden="true"></i>',searchPlaceholder: 'filter records'	},
		 processing: false,responsive: true,
		autoWidth: false,destroy:true,order:[[6,'desc' ]],dom: 'Bfltirp',bInfo: true, scrollCollapse: true,paging: true,scrollX: true,deferRender: true,pageResize: true,colReorder: true,
		buttons: {	buttons: [	]},
		columns: [
			{ "data": "id","title":"id","class":"dt-left","width": "3%"},
			{ "data": "user", "title":"user","visible":true },
			{ "data": "page", "title":"page","visible":true },
			{ "data": "timestamp", "title":"timestamp","visible":true },
			{ "data": "ip", "title":"ip-address","visible":true },
			{ "data": "",
                    "render": function(data, type, row) {if(data == '1') {return '<i class="fas fa-lock-open rest"></i>';} else {return '<i class="fas fa-lock text-danger"></i>';}},
					"title":" ","visible":true,"width": "2%","class":"dt-right" },
			]
	} );
}

function ShowVehicleDetails(){
	var $LInfo = $('#VehicleDetails');
	$S=new URL(location.href).searchParams.get("id");
	$LInfo.html('');
	$LInfo.append('ja het werkt ');
	$.ajax({
		url: window.location.origin+'/scripts/GetVehicleDetails?id='+$S,
		dataType:'JSON',
		success: function (data) {
			if (data[0].model==null){$model='';var $ImageUrl='../images/avatars/'+ data[0].brand +'_XF_'+data[0].Year+'.png';}
			else {$model=data[0].model;var $ImageUrl='../images/avatars/'+ data[0].brand +'_'+data[0].model.substring(0,2)+'_'+data[0].Year+'.png';}
			var $Str='<div class="vehicleCard truck_background">';
			$Str+='	<div class="row px-2">';
			$Str+='		<div class="col-12 px-3 ">';
			$Str+='			<div class="col-12 p-0 StatusLine "><i class="far fa-fw fa-building fa-fw tooltipicon"></i> <span> '+data[0].name +'</b></span></div>';
			$Str+='			<div class="col-12 p-0 StatusLine " id="VLAddress"></div>';
			$Str+='			<div class="d-flex StatusLine col-12 p-0">';
			$Str+='				<div class="col-6 p-0 "><i class="fas fa-tachometer-alt fa-fw tooltipicon "></i> '+(data[0].OdoMeter/1000).toFixed(0)+'</b> km</div>';
			$Str+='				<div class="ml-auto p-0 StatusValue  "><i class="fas fa-truck-moving fa-fw tooltipicon "></i> '+ data[0].grossCombinationVehicleWeight +'</b> kg</div>';
			$Str+='			</div>';
			$Str+='			<div class="d-flex StatusLine col-12 p-0">';
			$Str+='				<div class="col-6 p-0 "><i class="fas fa-gas-pump fa-fw tooltipicon "></i> '+data[0].FuelLevel+'</b> %</div>';
			$Str+='				<div class="ml-auto p-0 StatusValue "> '+ data[0].currentSpeed +'</b> km/u</div>';
			$Str+='			</div>';
			$Str+='			<div class="d-flex StatusLine col-12 p-0">';
			$Str+='				<div class="ml-auto p-0 StatusValue "> <b>'+dateFormat(data[0].LastActivity+' UTC', "yyyy-mm-dd HH:MM ")+'</b></div>';
			$Str+='			</div>';
			$Str+='		</div>';
			$Str+='	</div>';
			$LInfo.append($Str);
			Convert2Address([data[0].last_Latitude, data[0].last_Longitude,'VLAddress']);
			ShowVehicleRD(data[0].id);
			$Str='<div class="row mt-4 ">';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>vin</label>';
			$Str+='			<div class="form-group" >'+data[0].VI+'</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>brand </label>';
			$Str+='			<div class="form-group" >'+data[0].brand+'</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>model</label>';
			$Str+='			<div class="form-group" >'+data[0].Mode+'</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>service distance</label>';
			$Str+='			<div class="form-group" >'+data[0].serviceDistance/1000+' km</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>odo meter </label>';
			$Str+='			<div class="form-group" >'+data[0].OdoMeter/1000+' km</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>fuel level </label>';
			$Str+='			<div class="form-group" >'+data[0].FuelLevel+' %</div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>ad-blue level </label>';
			$Str+='			<div class="form-group" >'+data[0].CatalystFuelLevel+' %</div>';
			$Str+='		</div>';
			$Str+='<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>total fuel used </label>';
			$Str+='			<div class="form-group" >'+data[0].TotalFuelUsed/1000+' L </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>total engine hours  </label>';
			$Str+='			<div class="form-group" >'+data[0].TotalEngineHours+' h </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>last activity  </label>';
			$Str+='			<div class="form-group" >'+data[0].LastActivity+' </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>outside temp </label>';
			$Str+='			<div class="form-group" >'+data[0].ambientAirTemperature+' </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>coolant temp </label>';
			$Str+='			<div class="form-group" >'+data[0].engineCoolantTemperature+' </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>last driver</label>';
			$Str+='			<div class="form-group" >'+data[0].driver+' </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>damage</label>';
			$Str+='			<div class="form-group" >'+data[0].DamageCount+' </div>';
			$Str+='		</div>';
			$Str+='		<div class="col-6 col-lg-4 p-1">';
			$Str+='			<label>service home dealer>';
			$Str+='			<div class="form-group" >'+data[0].Service_Homedealer+' </div>';
			$Str+='		</div>';
			$Str+='	</div>';
			$LInfo.append($Str);
		},
		error:function()		{UpdateCounter('<span class="danger"><i class="fas fa-exclamation-circle "></i>functionality failed</span>','TripReport');}
	});
}
function SelectDrivers(){
	$select = $('#drivers');
	$select.html('');
	if (document.getElementById("groups").value=='*'){
		$select.append('<option value="" selected> <i>First select a group</i></option>');
	}
	else {
		$.ajax({
			url: window.location.origin+'/scripts/GetDrivers',
			dataType:'JSON',
			success:function(data){
				$selected='selected';
				$.each(data, function(key, val){
					$select.append('<option value="' + val.id + '" class="select" '+$selected+'>'+val.driver+'</option>');
					if ($selected=='selected') {$selected='';}
				})
			}
		});
	}
}
function SessionAlert($message){
//	var root = location.protocol + '//' + location.host;
//	window.location.replace(root+"/users/logout");
}
function DeleteReport($id,$name){
	Notiflix.Confirm.show('Delete',' Do you wish to delete report  <br><b>'+$name+'</b> ?','Yes','No',	() => {
		$.ajax({url: window.location.origin+'/scripts/PostDeleteReport',type: 'POST',
			dataType: 'json',	contentType: 'application/json; charset=utf-8',
			data: JSON.stringify({id:$id,name:$name}),
			success: function (result) {
				Notiflix.Notify.success('Report <br><b>'+$name+' </b><br> has been deleted');
				$("#tableReport").DataTable().ajax.reload();
			},
		});
	},	() => {});
}
function DeleteUser($id,$name){
	Notiflix.Confirm.show('Delete',' Do you wish to delete user  <br><b>'+$name+'</b> ?','Yes','No',	() => {
		$.ajax({url: window.location.origin+'/scripts/PostDeleteUser',type: 'POST',
			dataType: 'json',	contentType: 'application/json; charset=utf-8',
			data: JSON.stringify({id:$id,name:$name}),
			success: function (result) {
				Notiflix.Notify.success('User <br><b>'+$name+' </b><br> has been deleted');
				$("#tableUsers").DataTable().ajax.reload();
			},
		});
	},	() => {});
}
function DeleteDriver($id,$name){
	Notiflix.Confirm.show('Delete',' Do you wish to delete driver  <br><b>'+$name+'</b> ?','Yes','No',	() => {
		$.ajax({url: window.location.origin+'/scripts/PostDeleteDriver',type: 'POST',
			dataType: 'json',	contentType: 'application/json; charset=utf-8',
			data: JSON.stringify({id:$id,name:$name}),
			success: function (result) {
				Notiflix.Notify.success('Driver <br><b>'+$name+' </b><br> has been deleted');
				$("#tableDrivers").DataTable().ajax.reload();
			},
		});
	},	() => {});
}
function DeleteNotification($id,$name){
	Notiflix.Confirm.show('Delete',' Do you wish to delete notification  <br><b>'+$name+'</b> ?','Yes','No',	() => {
		$.ajax({url: window.location.origin+'/scripts/PostDeleteNotification',type: 'POST',
			dataType: 'json',	contentType: 'application/json; charset=utf-8',
			data: JSON.stringify({id:$id,name:$name}),
			success: function (result) {
				Notiflix.Notify.success('Notification <br><b>'+$name+' </b><br> has been deleted');
				$("#tableNotifications").DataTable().ajax.reload();
			},
		});
	},	() => {});
}
function DeleteAPICollector($id,$name){
	Notiflix.Confirm.show('Delete',' Do you wish to delete API-collector account <br><b>'+$name+'</b> ?','Yes','No',	() => {
		$.ajax({url: window.location.origin+'/scripts/PostDeleteAPICollector',type: 'POST',
			dataType: 'json',	contentType: 'application/json; charset=utf-8',
			data: JSON.stringify({id:$id,name:$name}),
			success: function (result) {
				Notiflix.Notify.success('API-Collector account <br><b>'+$name+' </b><br> has been deleted');
				$("#tableAPI").DataTable().ajax.reload();
			},
		});
	},	() => {});
}
function CheckUserSession() {
	var userInSession = false;
	$.ajax({
		url: window.location.origin+'/scripts/CheckSession',
		type: 'POST',
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		async: false,
		success: function (result) {
			userInSession = result.userInSession;
		}
	});
	return userInSession;
}
function ChangeStyle(){
	const themeM = document.querySelector("#menustyle");
	var selM = document.getElementById("style_css");
	var selectedTextM = selM.options[selM.selectedIndex].text;
	var $urlm = window.location.origin+'/users/css/style_schemes/';
	themeM.href = $urlm+selectedTextM+".css";
}

function ChangeCSS(){
	var theme = document.querySelector("#colorstyle");
	var sel = document.getElementById("us_css");
	var selectedText = sel.options[sel.selectedIndex].text;
	var $url = window.location.origin+'/users/css/color_schemes/';
	document.getElementsByTagName("BODY")[0].style.display = "none";
	theme.href = $url+selectedText+".css";
	document.getElementsByTagName("BODY")[0].style.display = "block";
}
function GetUserGroupAccess($id){
	var $select = $('#group');
	$select.html('');
	$.ajax({
		url: window.location.origin+'/scripts/GetUserGroupAccess?id='+$id,
		dataType:'JSON',
		success:function(data) {
			$x=0;
			$.each(data, function (key, val) {
				if (val.selected == "1") { $selected = "selected";} else {$selected = "";}
				$select.append('<option class="DeActiveGroup" value="' + val.accountnumber + '" ' + $selected + '>' + val.name + '</option>');
				if ($x==0 && val.accountnumber=='0' ){
					$select.append('<option disabled role=separator><hr></option>');
				}
				$x++;
			})
		}
	});
}
function GetUserPermissions($id){
	var $select = $('#roles');
	$select.html('');
	$.ajax({
		url: window.location.origin+'/scripts/GetUserPermissions?id='+$id,
		dataType:'JSON',
		success:function(data) {
			$.each(data, function (key, val) {
				if (val.selected == "1") { $selected = "selected";} else {$selected = "";}
				$select.append('<option class="DeActiveGroup" value="' + val.id + '" ' + $selected + '>' + val.name + '</option>');
			})
		}
	});
}
function ShowProgressBar($a,$b,$c){
	var $e ='';
	var $f='drive';
	//calculate value percentage of limit
	if ($c == 'time') {
		$e = (TimeToSeconds($a)/TimeToSeconds($b))*100;
		if (TimeToSeconds($a)<90){$f='bg-warning';}
		if (TimeToSeconds($a)<45){$f='bg-danger';}
	}
	if ($c == 'integer') { $e = ($a/$b)*100;  }
	var $d = '<div class = "progress-bar '+$f+'" role="progressbar" style = "width: '+$e+'%" aria-valuenow = "'+$e+'" aria-valuemin = "0" aria-valuemax = "100"></div>'
	return $d;
}

function ShowDriverTachoStatus($id,$a){
	var $DriveTodayLimit='10:00';
	var $srdt='text-primary';
	var $srdw='text-primary';
	$.ajax({
		url: window.location.origin+'/scripts/GetDriverDashboard_driver.php?id='+$id,
		type:'GET',
		dataType:'json',
		success:function(data){
			if (data.length!=0){
				if (data.RemainingExtendedDrive<1){ $DriveTodayLimit='09:00'; }
				if (TimeToSeconds(data.RemainingDriveToday)<90){ $srdt = 'text-warning';}
				if (TimeToSeconds(data.RemainingDriveToday)<45){ $srdt = 'text-danger '+TimeToSeconds(data.RemainingDriveToday);}
				var $HInfo = $('#'+$a);$HInfo.html('');
				$str	= '	<div class="row  ">';
				$str	+='		<div class="col mb-3">';
				$str	+='		<div class="m-1" id="remaintodaycard">';
				$str	+='			<div class="text-left  '+$srdt+'"><b>'+data.RemainingDriveToday+'</b></div>';
				$str	+='         <div class = "progress">';
				$str	+= ShowProgressBar(data.RemainingDriveToday,$DriveTodayLimit,'time');
				$str	+='         </div>';
				$str	+='			<div class="text-left ">remaining today</div>';
				$str	+='		</div>';
				$str	+='		</div>';
				$str	+='		<div class="col p-0 mb-3">';
				$str	+='		    <div class="m-1" id="remainweeklycard">';
				$str	+='			    <div class="text-left '+$srdw+'"><b>'+data.RemainDriveWeekly+'</b></div>';
				$str	+='             <div class = "progress">';
				$str	+= ShowProgressBar(data.RemainDriveWeekly,'55:00','time');
				$str	+='             </div>';
				$str	+='			    <div class="text-left "> weekly</div>';

				$str	+='		    </div>';
				$str	+='		</div>';
				$str	+='		<div class="col mb-3">';
				$str	+='		    <div class="m-1" id="totalbyweeklycard">';
				if (TimeToSeconds(data.DriveBiWeekly)>5400){
					$str	+='				    <div class="text-right text-danger "><b> INFRIGMENT '+data.RemainingBiWeekly+'</b></div>';}
				else {
					$str	+='				    <div class=" text-left text-primary"><b>'+data.RemainingBiWeekly+'</b></div>';}
				$str	+='             <div class = "progress">';
				$str	+= ShowProgressBar(data.RemainingBiWeekly,'90:00','time');
				$str	+='             </div>';
				$str	+='			    <div class=" text-left "> bi-weekly</div>';
				$str	+='			</div>';
				$str	+='		</div>';
				$str	+='	</div>';
				$str	+='	<div class="row ">';
				$str	+='		<div class="col-12 mb-3">';
				$str	+='		    <div class="d-flex" id="extendedcard">';
				$str	+='		    	<div class="px-1 text-left ">extended hour</div>';
				if (data.RemainingExtendedDrive<0){
					$str	+='		    	<div class="ml-auto text-danger "><b> INFRIGMENT</b></div>';}
				else {
					if (data.RemainingExtendedDrive == 2 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fas fa-circle fa-fw "></i><i class="fas fa-circle fa-fw"></i></div>';}
					if (data.RemainingExtendedDrive == 1 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fad fa-circle text-danger fa-fw "></i><i class="fas fa-circle fa-fw"></i></div>';}
					if (data.RemainingExtendedDrive == 0 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fad fa-circle text-danger fa-fw "></i><i class="fad fa-circle text-danger fa-fw"></i></div>';}
				}
				$str	+='		    </div>';
				$str	+='		</div>';
				$str	+='	</div>';
				$str	+='	<div class="row ">';
				$str	+='		<div class="col mb-3">';
				$str	+='		    <div class="d-flex" id="extendedcard">';
				$str	+='		    	<div class="px-1 text-left">short rest</div>';
				if (data.RemainingShortRests<0){
					$str	+='		    	<div class="px-1 ml-auto text-danger "><b> INFRIGMENT</b></div>';}
				else {
					if (data.RemainingShortRests == 3 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fas fa-circle fa-fw "></i><i class="fas fa-circle fa-fw "></i><i class="fas fa-circle fa-fw"></i></div>';}
					if (data.RemainingShortRests == 2 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fad fa-circle text-danger fa-fw "></i><i class="fas fa-circle fa-fw "></i><i class="fas fa-circle fa-fw"></i></div>';}
					if (data.RemainingShortRests == 1 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fad fa-circle text-danger fa-fw "></i><i class="fad fa-circle text-danger fa-fw "></i><i class="fas fa-circle fa-fw"></i></div>';}
					if (data.RemainingShortRests == 0 ){ $str	+='			    <div class="px-1 ml-auto drivetimecicrle"><i class="fad fa-circle text-danger fa-fw "></i><i class="fad fa-circle text-danger fa-fw "></i><i class="fad fa-circle text-danger fa-fw"></i></div>';}
				}
				$str	+='		    </div>';
				$str	+='		</div>';
				$str	+='	</div>';
				$str	+='	<div class="row ">';
				$str	+='		<div class="col mb-3">';
				$str	+='		    <div class="d-flex" id="extendedcard">';
				$str	+='		    	<div class="px-1 text-left">working days</div>';
				var i=0;
				$str	+='<div class="px-1 ml-auto drivetimecicrle">';
				for (; i < 7; ) {
					if (i<data.DaysDriving){$str	+='<i class = "fas fa-circle fa-fw text-primary"></i>';}
					else 					{$str	+='<i class = "fas fa-circle fa-fw "></i>';}
					i++;
				}
				$str	+='		    </div>';
				$str	+='		</div>';
				$str	+='	</div>';
				$HInfo.append($str);
				var $LVInfo = $('#lastVehicle');$LVInfo.html('');
				$LVInfo.append(data.LastVehicle);
				ReadDriverDamageStatus("CountedDamage",data.LastVehicle);
			}
			else {
				var $HInfo = $('#'+$a);$HInfo.html('');
				$HInfo.append(
					'	<div class="d-flex py-2">'+
					'		<div class="mr-auto">Remaing Drive Today </div>'+
					'		<div class="text-right grey ml-auto">You are not a driver or <br>Your Tachgraph DriverCard is not known</b></div>'+
					'</div>');
			}
		},
		error:function(data){
		}
	});
}

function CheckNewChats(){
	var $mailbadge = document.getElementById('mail_badge');
	var $userbadge = document.getElementById('user_badge');
	var $menubadge = document.getElementById('mailmenu_badge');
	var $txt='';
	$.ajax({
		url: window.location.origin+'/scripts/GetUserChatsUnread',
		dataType:'JSON',
		success:function(data) {
			if (data[0].OnreadMessages>0) {$txt = data[0].OnreadMessages;  } else { $txt = ''; $menubadge.classList.remove('bg-warning');}
			if ( $mailbadge) { $mailbadge.innerHTML = $txt;$mailbadge.classList.add('badge_on_icon');$mailbadge.classList.add('badge-success');}
			if ( $userbadge) { $userbadge.innerHTML = $txt;$userbadge.classList.add('badge_on_icon');$userbadge.classList.add('badge-success');}
			if ( $menubadge) { $menubadge.innerHTML = $txt;}
		}
	});
}
function CopyToClipBoard(element) {
	/* Get the text field
	var copyText = document.getElementById(element);
	/* Select the text field
	const clipboardItemInput = new ClipboardItem({'image/png' : blobInput});

	navigator.clipboard.write(copyText.outerText);

	/* Alert the copied text
	Notiflix.Notify.success('Copied JSON response to clipboard....');*/
}

$('#carouselNotifications').carousel({ interval: 5000});

$('#password-eye-btn').click(function() {
	$(this).toggleClass("fa-eye fa-eye-slash");
	var x = document.getElementById("password");
	if (x.type === "password") { x.type = "text";} else {	x.type = "password";}
});

$('#ShowAPIDaemon').click(function(e) {
	e.preventDefault();
	var $url = window.location.origin+'/pages/api_daemon_status';
	window.open($url, '_blank');
});
// Enabling Report table to be refreshed after adding or deleting records
$('#reporttable-refresh').click(function() {
	$("#tableReport").DataTable().ajax.reload();
});

$('#loginbtn').click(function() {
	$('#loginbtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> authenticating...').attr('enabled', true);
});
// General setting for Notiflix
Notiflix.Notify.init(
 	{ position:'right-bottom',timeout:6000,ID:'dashboardMessages',plainText:false,distance: '2rem',clickToClose:true,cssAnimationStyle:'from-top',cssAnimationDuration:1200,
		borderRadius: 'var(--rounding)',fontFamily:'--font-family-sans-serif',useFontAwesome:true,fontAwesomeIconSize:'36px',fontAwesomeIconStyle: 'basic',
		success: { background: 'var(--primary)',fontAwesomeIconColor:'var(--primaryborder)'},
		failure: { background: 'var(--danger)' },
		warning: { background: 'var(--warning)'	},
	});
Notiflix.Confirm.init(
	{	width: '30%',messageMaxLength: 6000,plainText: false,borderRadius: 'var(--rounding)',	messageColor : 'var(--primary)',
		titleColor: 'var(--primary)',okButtonBackground : 'var(--primary)',	fontFamily:'--font-family-sans-serif'
	});
// <end>
$(document).ready(function() {
	$('#menu').show();
});
// making the navbar hoverable
$(document).ready(function(){
	$('ul.navbar-nav li.nav-item.dropdown').hover(function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(100);
	}, function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(100);
	});

});

// <end>
const scrollSmoothToBottom = (id) => {
	const element = $(`#${id}`);
	element.animate({
		scrollTop: element.prop("scrollHeight")
	}, 500);
}
const scrollToBottom = (id) => {
	const element = $(`#${id}`);
	element.animate({
		scrollTop: element.prop("scrollHeight")
	}, 0);
}

// Require jQuery
const scrollSmoothToTop = (id) => {
	$(`#${id}`).animate({
		scrollTop: 0,
	}, 500);
}

$(document).ready(function() {
	var f_menu = document.getElementById("footer_menu");
	if(deviceType()=='mobile') { f_menu.classList.remove("hide"); } else { f_menu.classList.add("hide");}
});
function checkValue(a) {
	var $base = document.getElementById(a);
	if ( $base.value != '' ) {
		$('#'+a).addClass('has-value');
	} else {
		$('#'+a).removeClass('has-value');
	}
}
$('input').each(function() { checkValue(this); })