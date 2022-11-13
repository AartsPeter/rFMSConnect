<?php
ob_start();
header('X-Frame-Options: SAMEORIGIN');
require_once $abs_us_root.$us_url_root.'users/helpers/helpers.php';
//check for a custom page
$currentPage = currentPage();
if(isset($_GET['err'])){
	$err = Input::get('err');
}

if(isset($_GET['msg'])){
	$msg = Input::get('msg');
}

if(file_exists($abs_us_root.$us_url_root.'usersc/'.$currentPage)){
	if(currentFolder()!= 'usersc'){
		$url = $us_url_root.'usersc/'.$currentPage;
		if(isset($_GET)){
			$url .= '?'; //add initial ?
			foreach ($_GET as $key=>$value){
				$url .= '&'.$key.'='.$value;
			}
		}
		Redirect::to($url);
	}
}

$db = DB::getInstance();
$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
$settings = $settingsQ->first();

// set menu style
$bodybg='';
if ($settings->style_menu==0){$menu_style='navbar-dark bg-primary';}	
if ($settings->style_menu==1){$menu_style='navbar-dark bg-dark';}
if ($settings->style_menu==2){$menu_style='navbar-light bg-light';}
if ($settings->style_css=='floating_canvas.css'){$bodybg=$menu_style;}

//dealing with logged in users
if($user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		//:: force logout then redirect to maint.page
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}
//deal with guests
if(!$user->isLoggedIn()){
	if (($settings->site_offline==1) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		//:: redirect to maint.page
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}

//notifiy master_account that the site is offline
if($user->isLoggedIn()){
	if (($settings->site_offline==1) && (in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		err("<br>Maintenance Mode Active");
	}
}

//if($settings->glogin==1 && !$user->isLoggedIn()){
//	require_once $abs_us_root.$us_url_root.'users/includes/google_oauth.php';
//}

if ($settings->force_ssl==1){
	if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
		// if request is not secure, redirect to secure url
		$url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		Redirect::to($url);
		exit;
	}
}

//if track_guest enabled AND there is a user logged in
if($settings->track_guest == 1 && $user->isLoggedIn()){
	if ($user->isLoggedIn()){
		$user_id=$user->data()->id;
	}else{
		$user_id=0;
	}
	new_user_online($user_id);
}
if($user->isLoggedIn()) {
	$thisUserID = $user->data()->id;}
else {
	$thisUserID = 0; }

$ED=date('Y/m/d',strtotime('+1 days'));
$SD=date('Y/m/d',strtotime('-3 days'));
if ($settings->reporting_adaptiveDates==true){
    if (isset($_SESSION['UStartDate'])) {
        $SD=$_SESSION['UStartDate'];$ED=$_SESSION['UEndDate'];
    }
}

$UserIsAdmin==false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="rFMS Reader; rFMS-Connect, ConnectedTrucks, connectivity, trailer, API, TPMS, vehicles, trucks, telematic, fleetowners, fleet, oem-independent, rfms 1.0, rfms 1.1, rfms 2.0 rfms 2.1,rfms 3.0,FMS3.0">

	<link rel="manifest" href="<?=$us_url_root?>manifest.json" />
	<link rel="apple-touch-icon" href="images/favicon_light.png">
	<meta name="author" content="Peter Aarts">
    <META NAME="ROBOTS" CONTENT="ALL">
	<META NAME="COPYRIGHT" CONTENT="&copy; 2022 Peter Aarts ">
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
	<META HTTP-EQUIV="CONTENT-LANGUAGE" CONTENT="en-US">
	<META HTTP-EQUIV="EXPIRES" CONTENT="31 DEC 2022 23:59:01 GMT">
	<title><?=$settings->site_name;?></title>
	<script src="https://kit.fontawesome.com/f433537204.js" async crossorigin="anonymous"></script>
	<link rel="icon" href="<?=$us_url_root?>images/favicon_dark.png" type="image/x-icon"/>
<!--	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/bootstrap4/css/bootstrap.min.css">-->
 	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/leaflet/leaflet.css" />
 	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/leaflet.draw/leaflet.draw.css" />
 	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/highcharts/css/highcharts.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>css/markercluster.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>css/markercluster.default.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>css/leaflet.awesome-rfmsmarkers.css" />
	<link rel="stylesheet/less" type="text/css" href="<?=$us_url_root?>css/rotatedMarkers.less" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>css/easy-button.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/datatables/datatables.min.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/slimselect/slimselect.css">
	<script src="<?=$us_url_root?>plugins/slimselect/slimselect.js"></script>
	<link rel="stylesheet" href="<?=$us_url_root?>plugins/notiflix/notiflix-3.2.4.min.css" />
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="<?=$us_url_root?>plugins/gridmaster/jquery.gridstrap.min.css">

<?php
	if(file_exists($abs_us_root.$us_url_root.'usersc/includes/head_tags.php')){
		require_once $abs_us_root.$us_url_root.'usersc/includes/head_tags.php';
	}

	if($user->isLoggedIn()){
		if ( $settings->allow_user_css == 1){
		    if ( $user->data()->us_css == null ){
		        echo '<link href="'.$us_url_root.'users/css/color_schemes/default_blue.css" rel="stylesheet" type="text/css" id="colorstyle">';}
		    else {
			    echo '<link href="'.$us_url_root.'users/css/color_schemes/'.$user->data()->us_css.'"  id="colorstyle">';}
		} else {
		    echo '<link href="'.$us_url_root.'users/css/color_schemes/'.$settings->us_css.'" rel="stylesheet" type="text/css" id="colorstyle">';
		}
	}
	else {
		if ($settings->us_css==null){
		    echo '<link href="'.$us_url_root.'users/css/color_schemes/_default.css" rel="stylesheet" type="text/css" id="colorstyle">';
		}
		else {
		    echo '<link href="'.$us_url_root.'users/css/color_schemes/'.$settings->us_css.'" rel="stylesheet" type="text/css" id="colorstyle">';
		}
	}
	// Set styling layout
	if ($settings->style_css==null) 	{ echo '<link href="'.$us_url_root.'users/css/style_schemes/fullscreen_menu.css" rel="stylesheet" id="menustyle">';}
	else 						{ echo '<link href="'.$us_url_root.'users/css/style_schemes/'.$settings->style_css.'" rel="stylesheet" id="menustyle">';}
	?>

</head>


<body class="<?=$bodybg?> user-select-none">
	<div id="mySidebar" class="sidebar"></div>
<?php
	if(isset($_GET['err'])){
		err("<br>".$err);
	}

	if(isset($_GET['msg'])){
		bold("<br>".$msg);
	}
?>
