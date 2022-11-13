<?php
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
$validation = new Validate();
$pagePermissions = fetchPagePermissions(4);

// To make this panel super admin only, uncomment out the lines below
// if($user->data()->id !='1'){
//   Redirect::to('account.php');
// }

//PHP Goes Here!
delete_user_online(); //Deletes sessions older than 24 hours

//Find users who have logged in in X amount of time.
$date = date("Y-m-d H:i:s");

$hour = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($date)));
$today = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($date)));
$week = date("Y-m-d H:i:s", strtotime("-1 week", strtotime($date)));
$month = date("Y-m-d H:i:s", strtotime("-1 month", strtotime($date)));

$last24=time()-86400;

$recentUsersQ = $db->query("SELECT * FROM users_online WHERE timestamp > ? ORDER BY timestamp DESC",array($last24));
$recentUsersCount = $recentUsersQ->count();
$recentUsers = $recentUsersQ->results();

$usersHourQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($hour));
$usersHour = $usersHourQ->results();
$hourCount = $usersHourQ->count();

$usersTodayQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($today));
$dayCount = $usersTodayQ->count();
$usersDay = $usersTodayQ->results();

$usersWeekQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($week));
$weekCount = $usersWeekQ->count();

$usersMonthQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($month));
$monthCount = $usersMonthQ->count();

if (CheckPermissionLevel()>1) { $Restrict=" WHERE u.account_owner='".$user->data()->id."'";} else { $Restrict ="";}
$query="SELECT u.*, CONCAT(m.lname,', ',m.fname) AS 'creator', GROUP_CONCAT(p.name) as roles FROM users u INNER JOIN users m ON m.id = u.account_owner INNER JOIN user_permission_matches upm ON u.id=upm.user_id INNER JOIN permissions p ON p.id=upm.permission_id ".$Restrict." GROUP BY u.id";
$usersQ = $db->query($query);
$user_count = $usersQ->count();

$GroupAdminQ=$db->findAll('customers');
$group_count = $GroupAdminQ->count();
$customers = $GroupAdminQ->results();

$assetsgroupQ = $db->query("SELECT * FROM rel_cust_user");
$assetsgroup_count = $assetsgroupQ->count();

$pagesQ = $db->query("SELECT * FROM pages");
$page_count = $pagesQ->count();

$levelsQ = $db->query("SELECT * FROM permissions");
$level_count = $levelsQ->count();

$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
$settings = $settingsQ->first();

$settingsWLQ = $db->query("SELECT * FROM settings_wl ");
$settingswl = $settingsWLQ->first();
$settingsAPIQ = $db->query("SELECT * FROM api_scheduler ");
$settingsapi = $settingsAPIQ->first();

$tomC = $db->query("SELECT * FROM audit")->count();

if(!empty($_POST['settings'])){

	if($settings->recaptcha != $_POST['recaptcha']) {
		$recaptcha = Input::get('recaptcha');
		$fields=array('recaptcha'=>$recaptcha);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->daysStatistics != $_POST['daysStatistics']) {
		$daysStatistics = Input::get('daysStatistics');
		$fields=array('daysStatistics'=>$daysStatistics);
		$db->update('settings',$settings->id,$fields); 
	}
	if($settings->DashboardVC != $_POST['DashboardVC']) {
		$DashboardVC = Input::get('DashboardVC');
		$fields=array('DashboardVC'=>$DashboardVC);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->DashBoardType != $_POST['DashBoardType']) {
		$DashBoardType = Input::get('DashBoardType');
		$fields=array('DashBoardType'=>$DashBoardType);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->FooterText != $_POST['FooterText']) {
		$FooterText = Input::get('FooterText');
		$fields=array('FooterText'=>$FooterText);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->FooterActive != $_POST['FooterActive']) {
		if(isset($_POST['FooterActive'])) {$FooterActive =1;} else {$FooterActive =0;}
		$fields=array('FooterActive'=>$FooterActive);
		$db->update('settings',$settings->id,$fields);
	}	
	if($settings->messaging != $_POST['messaging']) {
		if(isset($_POST['messaging'])) {$messaging =1;} else {$messaging =0;}
		$fields=array('messaging'=>$messaging);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->echouser != $_POST['echouser']) {
		$echouser = Input::get('echouser');
		$fields=array('echouser'=>$echouser);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->FooterText != $_POST['FooterText']) {
		$FooterText = Input::get('FooterText');
		$fields=array('FooterText'=>$FooterText);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->wys != $_POST['wys']) {
		if(isset($_POST['wys'])) {$wys =1;} else {$wys =0;}
		$fields=array('wys'=>$wys);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->sioc != $_POST['sioc']) {
		if(isset($_POST['sioc'])) {$sioc =1;} else {$sioc =0;}
		$fields=array('sioc'=>$sioc);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->site_name != $_POST['site_name']) {
		$site_name = Input::get('site_name');
		$fields=array('site_name'=>$site_name);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->site_description != $_POST['site_description']) {
		$site_description = Input::get('site_description');
		$fields=array('site_description'=>$site_description);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->login_type != $_POST['login_type']) {
		$login_type = Input::get('login_type');
		$fields=array('login_type'=>$login_type);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->force_ssl != $_POST['force_ssl']) {
        if(isset($_POST['force_ssl'])) {$force_ssl =1;} else {$force_ssl =0;}
		$fields=array('force_ssl'=>$force_ssl);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->force_pr != $_POST['force_pr']) {
		if(isset($_POST['force_pr'])) {$force_pr =1;} else {$force_pr =0;}
		$fields=array('force_pr'=>$force_pr);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->site_offline != $_POST['site_offline']) {
		if(isset($_POST['site_offline'])) {$site_offline =1;} else {$site_offline =0;}
		$fields=array('site_offline'=>$site_offline);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->track_guest != $_POST['track_guest']) {
		if(isset($_POST['track_guest'])) {$track_guest =1;} else {$track_guest =0;}
		$fields=array('track_guest'=>$track_guest);
		$db->update('settings',$settings->id,$fields);
	}
	Redirect::to('admin.php');
}

if(!empty($_POST['css'])){
	if($settings->css_sample != $_POST['css_sample']) {
		if(isset($_POST['css_sample'])) {$css_sample =1;} else {$css_sample =0;}
		$fields=array('css_sample'=>$css_sample);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->allow_user_css != $_POST['allow_user_css']) {
		if(isset($_POST['allow_user_css'])) {$allow_user_css = 1;} else {$allow_user_css = 0;}
		$fields=array('allow_user_css'=>$allow_user_css);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->us_css != $_POST['us_css']) {
		$us_css = Input::get('us_css');
		$fields=array('us_css'=>$us_css);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->style_css != $_POST['style_css']) {
		$style_css = Input::get('style_css');
		$fields=array('style_css'=>$style_css);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->style_menu != $_POST['style_menu']) {
		$style_menu = Input::get('style_menu');
		$fields=array('style_menu'=>$style_menu);
		$db->update('settings',$settings->id,$fields);
	}
	Redirect::to('admin.php');
}

if(!empty($_POST['reportsettings'])){
    if ( $settings->reporting_enabled != $_POST['reporting_enabled']) {
        if(isset($_POST['reporting_enabled'])) {$reporting_enabled = 1;} else {$reporting_enabled = 0;}
        $fields=array('reporting_enabled'=>$reporting_enabled);
        $db->update('settings',$settings->id,$fields);
    }
    if ( $settings->reporting_adaptiveDates != $_POST['reporting_adaptiveDates']) {
        if(isset($_POST['reporting_adaptiveDates'])) {$reporting_adaptiveDates = 1;} else {$reporting_adaptiveDates = 0;}
        $fields=array('reporting_adaptiveDates'=>$reporting_adaptiveDates);
        $db->update('settings',$settings->id,$fields);
    }
	if ( $settings->report_description != $_POST['report_description']) {
		$rd = Input::get('report_description');
		$fields=array('report_description'=>$rd);
		$db->update('report_description',$settings->id,$fields);
	}
	if ( $settings->report_logo != $_POST['report_logo']) {
		$rl = Input::get('report_logo');
		$fields=array('report_logo'=>$rl);
		$db->update('report_logo',$settings->id,$fields);
	}
	Redirect::to('admin.php#list-item-8');
}
if(!empty($_POST['pdcsettings'])){
    if ( $settings->reporting_enabled != $_POST['pdc_enabled']) {
        if(isset($_POST['pdc_enabled'])) {$pdc_enabled = 1;} else {$pdc_enabled = 0;}
        $fields=array('pdc_enabled'=>$pdc_enabled);
        $db->update('settings',$settings->id,$fields);
    }
    if ( $settings->pdc_reporting != $_POST['pdc_reporting']) {
        if(isset($_POST['pdc_reporting'])) {$pdc_reporting = 1;} else {$pdc_reporting = 0;}
        $fields=array('pdc_reporting'=>$pdc_reporting);
        $db->update('settings',$settings->id,$fields);
    }
    if ( $settings->pdc_autoprocess != $_POST['pdc_autoprocess']) {
        if(isset($_POST['pdc_autoprocess'])) {$pdc_autoprocess = 1;} else {$pdc_autoprocess = 0;}
        $fields=array('pdc_autoprocess'=>$pdc_autoprocess);
        $db->update('settings',$settings->id,$fields);
    }
	Redirect::to('admin.php#list-item-9');
}

if(!empty($_POST['social'])){
	if($settings->change_un != $_POST['change_un']) {
		$change_un = Input::get('change_un');
		$fields=array('change_un'=>$change_un);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->req_cap != $_POST['req_cap']) {
		if(isset($_POST['req_cap'])) {$req_cap =1;} else {$req_cap =0;}
		$fields=array('req_cap'=>$req_cap);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->req_num != $_POST['req_num']) {
		if(isset($_POST['req_num'])) {$req_num =1;} else {$req_num =0;}
		$fields=array('req_num'=>$req_num);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->frocepwr != $_POST['frocepwr']) {
		if(isset($_POST['frocepwr'])) {$frocepwr =1;} else {$frocepwr =0;}
		$fields=array('frocepwr'=>$frocepwr);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->emailpwa != $_POST['emailpwa']) {
		if(isset($_POST['emailpwa'])) {$emailpwa =1;} else {$emailpwa =0;}
		$fields=array('req_num'=>$emailpwa);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->min_pw != $_POST['min_pw']) {
		$min_pw = Input::get('min_pw');
		$fields=array('min_pw'=>$min_pw);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->max_pw != $_POST['max_pw']) {
		$max_pw = Input::get('max_pw');
		$fields=array('max_pw'=>$max_pw);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->min_un != $_POST['min_un']) {
		$min_un = Input::get('min_un');
		$fields=array('min_un'=>$min_un);
		$db->update('settings',$settings->id,$fields);
	}
	if($settings->max_un != $_POST['max_un']) {
		$max_un = Input::get('max_un');
		$fields=array('max_un'=>$max_un);
		$db->update('settings',$settings->id,$fields);
	}

	Redirect::to('admin.php');
}
if (CheckPermissionLevel()>2){
    $disabled="alert alert-secondary p-0 border-0";}
else {$disabled="";}


?>

<main role="main">
    <section class="section section-full ">
        <div class="container-fluid ">
            <div class="pagina p-0 no-flow">
                <div class="inner-adminpagina">
                <div class="d-flex">
                    <nav class="">
                        <div class="leftmenu-sticky  p-3 rounded-0 h-100">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="#list-item-1" title="Dashboard">
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-home fa-fw "></span>
                                            <span class="d-none d-xl-block mx-2"><?=lang("ADMDASHMENU_Dashboard","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-3" title="Site Settings">
                                <?php } else {?>
                                    <a class="nav-link text-secondary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center ">
                                            <span class="fad fa-sitemap fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"><?=lang("ADMDASHMENU_Site","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-4" title="Style Settings">
                                <?php } else {?>
                                    <a class="nav-link text-secondary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-swatchbook fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"><?=lang("ADMDASHMENU_Style","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-5" title="UserAccount settings">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-user-shield fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_Users","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#list-item-6" onclick="ShowAdminNotifications();" title="<?=lang("ADMDASHMENU_Notifications","");?>">
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-comments fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_Notifications","");?></span>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                              <span class="d-none d-xl-block ml-1"><?=lang("ADMDASHMENU_Features","");?></span>
                            </h6>
                            <ul class="nav flex-column">
                                <li class="nav-item d-none d-sm-block">
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-7" onclick="ShowAPITable();" title="<?=lang("ADMDASHMENU_API","");?>">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-layer-group fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_API","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(3,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-8" onclick="ShowReportTypes();" title="Report Settings">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>

                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-analytics fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_Report","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(3,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-9" onclick="ShowPDCTemplates();" title="Pre-Departure Check">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-clipboard-list fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_PDC","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <?php if (checkMenu(3,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-10" onclick="ShowAPITable();" title="Fleet Management">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>
                                        <div class="d-flex w-100 justify-content-start align-items-center">
                                            <span class="fad fa-truck fa-fw"></span>
                                            <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_FM","");?></span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item d-none d-sm-block">
                                <?php if (checkMenu(3,$user->data()->id)){ ?>
                                    <a class="nav-link" href="#list-item-11" title="Import Data">
                                <?php } else {?>
                                    <a class="nav-link text-primary hide" >
                                <?php } ?>
                                    <div class="d-flex w-100 justify-content-start align-items-center">
                                        <span class="fad fa-upload fa-fw"></span>
                                        <span class="d-none d-xl-block mx-2"> <?=lang("ADMDASHMENU_Import","");?></span>
                                    </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col ml-auto p-0" >
                        <div class="AdminList ">
                            <div class="adminpad col-12" id="list-item-1" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom " >
                                    <div class="page-title">Dashboard</div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 col-xl">
                                        <a class="" href="../pages/users.php" disabled>
                                        <div class="card mb-3 admincard shadow-sm">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-xl info-box-number"><i class="fad fa-user-shield fa-2x fa-fw text-primary"></i> <b><?=$user_count?></b></div>
                                                    <div class="col-12 col-xl  bold text-primary text-right"> <?=lang("ADMDASH_USERS","");?></div>
                                                </div>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl">
                                    <?php if (checkMenu(2,$user->data()->id)){ ?>
                                        <a class="" href="admin_groups.php" >
                                      <?php }?>
                                        <div class="card mb-3 admincard shadow-sm<?=$disabled?>">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-xl info-box-number"><i class="fad fa-users fa-2x fa-fw text-primary"></i> <b><?=$group_count?></b></div>
                                                    <div class="col-12 col-xl  bold text-primary text-right"><?=lang("ADMDASH_ASSETSGROUPS","");?></div>
                                                </div>
                                            </div>
                                        </div>
                                        </a>

                                    </div>
                                    <div class="col-12 col-lg-4 col-xl d-none d-sm-block">
                                    <?php if (checkMenu(2,$user->data()->id)){ ?>
                                        <a class="" href="#" disabled>
                                        <?php }?>
                                        <div class="card mb-3   <?=$disabled?>">
                                            <div class="card-body grey ">
                                                <div class="row">
                                                    <div class="col-12 col-xl text-secondary info-box-number"><i class="fad fa-truck fa-2x fa-fw text-secondary"></i> <b><?=$assetsgroup_count?></b></div>
                                                    <div class="col-12 col-xl  bold text-secondary text-right"><?=lang("ADMDASH_GROUPRELATION","");?></div>
                                                </div>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl">
                                    <?php if (checkMenu(2,$user->data()->id)){ ?>
                                        <a class="" href="admin_permissions.php">
                                    <?php }?>
                                        <div class="card mb-3 admincard shadow-sm <?=$disabled?>">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-xl info-box-number"><i class="fad fa-unlock-alt fa-2x fa-fw text-primary"></i> <b><?=$level_count?></b></div>
                                                    <div class="col-12 col-xl  bold text-primary text-right"><?=lang("ADMDASH_PERMISSIONS","");?></div>
                                                </div>
                                            </div>
                                        </div>
                                        </a>

                                    </div>
                                    <div class="col-12 col-lg-4 col-xl">
                                    <?php if (checkMenu(2,$user->data()->id)){ ?>
                                        <a class="" href="admin_pages.php">
                                    <?php }?>
                                        <div class="card mb-3 admincard shadow-sm <?=$disabled?>">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-xl info-box-number"><i class="fad fa-file-alt fa-2x fa-fw text-primary"></i> <b><?=$page_count?></b></div>
                                                    <div class="col-12 col-xl-4  bold text-primary text-right"><?=lang("ADMDASH_PAGES","");?></div>
                                               </div>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl">
                                    <?php if (checkMenu(1,$user->data()->id)){ ?>
                                        <a class=" " href="email_settings.php">
											<div class="card mb-3 admincard shadow-sm <?=$disabled?> ">
									<?php } else {?>
											<div class="card mb-3<?=$disabled?> ">
									<?php }?>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-xl"><i class="fad fa-mail-bulk fa-fw fa-3x text-primary"></i> </div>
                                                    <div class="col-12 col-xl  bold text-primary text-right"><?=lang("ADMDASH_EMAIL","");?></div>
                                                </div>
                                            </div>
                                        </div>
                                        </a>

                                    </div>
                                </div>

                                <div class="col-12 align-items-top mb-3 pt-3 border-bottom" id="list-item-2" >
                                    <div class="page-title">Statistics</div>
                                </div>
                                <div class="row ">
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <div class="col-12 col-md-6 col-xl-4 ">
                                        <div class="card mb-3">
                                            <div class="card-title">Logged In Users<small> (past 24 hours)</small></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 ">
                                                        <div class="">
                                                        <table class="display datatable table noWrap">
                                                        <?php if($settings->track_guest == 1){ ?>
                                                        <thead><tr><th>Username</th><th>IP</th><th>Last Activity</th></tr></thead>
                                                        <tbody>
                                                        <?php foreach($recentUsers as $v1){
                                                            $user_id=$v1->user_id;
                                                            $username=name_from_id($v1->user_id);
                                                            $timestamp=date("Y-m-d H:i:s",$v1->timestamp);
                                                            $ip=$v1->ip;
                                                            if ($user_id==0){
                                                                $username="guest";
                                                            }
                                                            if ($user_id==0){?>
                                                                <tr><td><?=$username?></td><td><?=$ip?></td><td><?=$timestamp?></td></tr>
                                                            <?php }else{ ?>
                                                                <tr><td><a href="admin_user.php?id=<?=$user_id?>"><?=$username?></a></td><td><?=$ip?></td><td><?=$timestamp?></td></tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </tbody>
                                                        <?php }else{echo 'Guest tracking off. Turn "Track Guests" on below for advanced tracking statistics.';} ?>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <div class="col-12 col-md-6 col-xl-4 ">
                                        <div class="card  mb-3">
                                            <div class="card-title   ">All Users</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3 "><span class="info-box-number primary"><?=$hourCount?></span> <small>this hour</small></div>
                                                    <div class="col-3 "><span class="info-box-number primary"><?=$dayCount?></span> <small>this day</small></div>
                                                    <div class="col-3 "><span class="info-box-number primary"><?=$weekCount?></span> <small>this week</small></div>
                                                    <div class="col-3 "><span class="info-box-number primary"><?=$monthCount?></span> <small>this month</small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-4 ">
                                        <div class="card  mb-3 ">
                                            <div class="card-title   ">All Visitors</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 ">
                                                        <?php  if($settings->track_guest == 1){ ?>
                                                        <?="In the last 30 minutes, the unique visitor count was <b><span class='info-box-number primary'>".count_users()."</span></b><br>";?>
                                                        <?php }else{ ?>
                                                        Guest tracking off. Turn "Track Guests" on below for advanced tracking statistics.
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php if (checkMenu(2,$user->data()->id)){ ?>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="card  mb-3 ">
                                            <div class="card-title   ">Security Events <small><a href="tomfoolery.php"> (View Logs)</a></small></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3 info-box-number primary"><?=$tomC?></div>
                                                    <div class="col-9">security events triggered</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-3" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">Site Setting</div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <form class="row" action="admin.php#list-item-3" name="settings" method="post">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="form-group col-12 col-md-6 col-xl-4">
                                                        <label class="primary" for="site_name">Site Name</label>
                                                        <input type="text" class="form-control" name="site_name" id="site_name" value="<?=$settings->site_name?>">
                                                    </div>
                                                    <div class="form-group col-6 col-xl-4 d-none d-sm-block">
                                                        <label class="primary" for="site_name">Domain</label>
                                                        <div class="form-control-ro"><b><?=$settings->domain?></b></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-12">
                                                <div class="row">
                                                    <div class="form-group col-12 col-xl-12">
                                                        <label class="primary " for="site_description">Site Description</label>
                                                        <input type="text" class="form-control" name="site_description" id="site_description" value="<?=$settings->site_description?>">
                                                    </div>
                                                    <div class="form-group col-12 col-xl-12">
                                                        <label class="primary " for="FooterText">Footer Text</label>
                                                        <input type="text" class="form-control" name="FooterText" id="FooterText" value="<?=$settings->FooterText?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-12 col-xl-4">
                                                        <label class="primary " for="recaptcha">Recaptcha</label>
                                                        <select id="recaptcha" class="form-control col-12" name="recaptcha">
                                                            <option value="1" <?php if($settings->recaptcha==1) echo 'selected="selected"'; ?> >Enabled</option>
                                                            <option value="0" <?php if($settings->recaptcha==0) echo 'selected="selected"'; ?> >Disabled</option>
                                                            <option value="2" <?php if($settings->recaptcha==2) echo 'selected="selected"'; ?> >For Join Only</option>
                                                        </select>
                                                    </div>
                                                    <!-- echouser Option -->
                                                    <div class="form-group col-12 col-xl-4">
                                                        <label class="primary" for="echouser">echouser Function</label>
                                                        <select id="echouser" class="form-control" name="echouser">
                                                            <option value="0" <?php if($settings->echouser==0) echo 'selected="selected"'; ?> >FName LName</option>
                                                            <option value="1" <?php if($settings->echouser==1) echo 'selected="selected"'; ?> >Username</option>
                                                            <option value="2" <?php if($settings->echouser==2) echo 'selected="selected"'; ?> >Username (FName LName)</option>
                                                            <option value="3" <?php if($settings->echouser==3) echo 'selected="selected"'; ?> >Username (FName)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-12 col-xl-4">
                                                        <label class="primary" for="daysStatistics">Days of Statistics</label>
                                                        <input type="text" class="form-control" name="daysStatistics" id="daysStatistics" value="<?=$settings->daysStatistics?>">
                                                    </div>
                                                    <div class="form-group col-12 col-xl-4">
                                                        <label class="primary" for="DashboardVC">Simple Dashboard max vehicles</label>
                                                        <input type="text" class="form-control" name="DashboardVC" id="DashboardVC" value="<?=$settings->DashboardVC?>">
                                                    </div>
                                                    <div class="form-group col-12 col-xl-4">
                                                        <label class="primary" for="DashBoardType">Type Simple Dashboard</label>
                                                        <select id="DashBoardType" class="form-control" name="DashBoardType">
                                                            <option value="0" <?php if($settings->DashBoardType==0) echo 'selected="selected"'; ?> >Table</option>
                                                            <option value="1" <?php if($settings->DashBoardType==1) echo 'selected="selected"'; ?> >Cards</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xl-3 col-12 d-none d-sm-block">
                                                <div class="card card-body  shadow-sm">
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='site_offline' name='site_offline' <?php echo ($settings->site_offline==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="site_offline">Site Offline</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='messaging' name='messaging' <?php echo ($settings->messaging==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="messaging">Messaging</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='wys' name='wys' <?php echo ($settings->wys==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="wys">WYSIWYG Editor</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='sioc' name='sioc' <?php echo ($settings->sioc==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="sioc">Show image on card</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='force_ssl' name='force_ssl' <?php echo ($settings->force_ssl==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="force_ssl">Force HTTPS Connections</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='force_pr' name='force_pr' <?php echo ($settings->force_pr==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="force_pr">Force Password Reset (disabled)</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='track_guest' name='track_guest' <?php echo ($settings->track_guest==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="track_guest">Track Guests</label>
                                                            <small id="passwordHelpBlock" class="form-text text-info">If your site gets a lot of traffic and starts to stumble, this is the first thing to turn off.</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-switch custom-switch-sm">
                                                            <input type='checkbox' class="custom-control-input" id='FooterActive' name='FooterActive' <?php echo ($settings->FooterActive==1 ? 'checked' : '');?>   >
                                                            <label class="custom-control-label" for="FooterActive">Enable Footer</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 pt-3">
                                                <div class="row ">
                                                    <div class="col-12 col-md-2 text-right mb-3 ">
                                                        <input class='col-12 btn btn-primary' type='submit' name="settings" value='Save Site Settings' />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="adminpad col-12 " id="list-item-4" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">Styling & layout setting</div>
                                </div>
                                <div class="row ">
                                    <div class="col-12">
                                        <form class="col-12" action="admin.php#list-item-4" name="css" method="post">
                                            <div class="row m-0 mb-4">
                                                <div class="custom-control custom-switch custom-switch-sm   col-md-4 col-lg-3 col-xl-2">
                                                    <input type='checkbox' class="custom-control-input" id='css_sample' name='css_sample' <?php echo ($settings->css_sample==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="css_sample">Show style example</label>
                                                </div>
                                                <div class="custom-control custom-switch custom-switch-sm  col-md-4 col-lg-3 col-xl-2 ">
                                                    <input type='checkbox' class="custom-control-input" id='allow_user_css' name='allow_user_css' <?php echo ($settings->allow_user_css==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="allow_user_css">Allow User to overrule color scheme</label>
                                                </div>
                                            </div>
                                            <div class="row m-0">
                                                <div class="form-group col-12 col-md-4 col-lg-3 col-xl-2 pl-0">
                                                    <label class="text-primary" for="us_css">color Scheme</label>
                                                    <select class="form-control" name="us_css" id="us_css" onchange="ChangeCSS();" >
                                                        <?php
                                                        $dir='../users/css/color_schemes';
							if ($settings->us_css==null) 	{ echo '<option class="alert-danger" value="" selected>no colorscheme selected</option>';}
                                                        if (is_dir($dir)) {
                                                            if ($dh = opendir($dir)) {
                                                                while (($file = readdir($dh)) !== false) {
                                                                    if (substr($file, -3, 3)=='css'){
                                                                        if ($file==$settings->us_css)
                                                                            {echo "<option value=".$settings->us_css." selected > ".substr($settings->us_css,0, -4)."</option>";}
                                                                        else
                                                                            {echo "<option value=".$file.">".substr($file,0, -4)."";}
                                                                    }
                                                                }
                                                                closedir($dh);
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-md-4 col-lg-3 col-xl-2 pl-0">
                                                    <label class="text-primary" for="style_css">styling </label>
                                                    <select class="form-control" name="style_css" id="style_css" onchange="ChangeStyle();">
                                                        <?php
                                                        $dir='../users/css/style_schemes';
							                            if ($settings->style_css==null) 	{ echo '<option class="alert-danger"  value="" selected>no style selected</option>';}
                                                        if (is_dir($dir)) {
                                                            if ($dh = opendir($dir)) {
                                                                while (($file = readdir($dh)) !== false) {
                                                                    if (substr($file, -3, 3)=='css'){
                                                                        if ($file==$settings->style_css)
                                                                            {echo "<option value=".$settings->style_css." selected >".substr($settings->style_css,0, -4)."</option>";}
                                                                        else
                                                                            {echo "<option value=".$file.">".substr($file,0, -4)."";}
                                                                    }
                                                                }
                                                                closedir($dh);
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-md-4 col-lg-3 col-xl-2 pl-0">
                                                    <label class="text-primary" for="style_menu">menu style </label>
                                                    <select class="form-control" name="style_menu" id="style_menu" >
                                                        <option value="0" <?php if ( $settings->style_menu == 0 ) { echo 'selected="selected"';} ?> >primary</option>
                                                        <option value="1" <?php if ( $settings->style_menu == 1 ) { echo 'selected="selected"';} ?> >dark</option>
                                                        <option value="2" <?php if ( $settings->style_menu == 2 ) { echo 'selected="selected"';} ?> >light</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-12 ml-auto text-right pt-3 pl-0">
                                                    <input class='btn btn-primary col-12' type='submit' name="css" value='Save Style Settings'/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php if($settings->css_sample==1){require '../users/views/_admin_css_styling.php';}?>
                            </div>
                            <div class="adminpad col-12" id="list-item-5" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">User Account settings</div>
                                </div>
                                <div class="row">
                                    <form class="col-12" action="admin.php#list-item-5" name="social" method="post">
                                        <div class="row ">
                                            <div class="col-12 col-xl-3">
                                                <label class="pt-2" for="min_un">Minimum Username Length</label>
                                                <input type="text" class="form-control" name="min_un" id="min_un" value="<?=$settings->min_un?>">
                                            </div>
                                            <div class="col-12 col-xl-3">
                                                <label class="pt-2" for="max_un">Maximum Username Length</label>
                                                <input type="text" class="form-control" name="max_un" id="max_un" value="<?=$settings->max_un?>">
                                            </div>
                                            <div class="col-12 col-xl-3">
                                                <label class="pt-2" for="change_un">Allow users to change their Usernames</label>
                                                <select id="change_un" class="form-control " name="change_un">
                                                    <option value="0" <?php if($settings->change_un==0) echo 'selected="selected"'; ?> >Disabled</option>
                                                    <option value="1" <?php if($settings->change_un==1) echo 'selected="selected"'; ?> >Enabled</option>
                                                    <option value="2" <?php if($settings->change_un==2) echo 'selected="selected"'; ?> >Only once</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div  class="row mb-3">
                                            <div class="col-12 col-xl-3">
                                                <label class="pt-2" for="min_pw">Minimum Password Length</label>
                                                <input type="text" class="form-control" name="min_pw" id="min_pw" value="<?=$settings->min_pw?>">
                                            </div>
                                            <div class=" col-12 col-xl-3">
                                                <label class="pt-2" for="max_pw">Maximum Password Length</label>
                                                <input type="text" class="form-control" name="max_pw" id="max_pw" value="<?=$settings->max_pw?>">
                                            </div>
                                            <div class="col-12 col-xl-3">
                                                <label class="pt-2" for="max_pwa">Maximum password age</label>
                                                <input type="text" class="form-control" name="max_pwa" id="max_pwa" value="<?=$settings->max_pwa?>"></label>
                                            </div>
                                        </div>
                                        <div  class="row mb-3">
                                            <div class=" col-12 col-xl-3">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='req_num' name='req_num' <?php echo ($settings->req_num==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="req_num">recommend a Number in the Password?</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-3">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='req_cap' name='req_cap' <?php echo ($settings->req_cap==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="req_cap">Recommend a Capital Letter in the Password?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="row mb-3 d-flex align-items-end">
                                            <div class="col-12 col-xl-3 ">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='frocepwr' name='frocepwr' <?php echo ($settings->frocepwr==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="frocepwr">Force password reset when expired</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-3">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='emailpwa' name='emailpwa' <?php echo ($settings->emailpwa==1 ? 'checked' : '');?>   >
                                                    <label class="custom-control-label" for="emailpwa">Sent E-mail when password expired</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12 col-md-2  text-right ">
                                                <input class='col-12 btn btn-primary'  type='submit' name="social" value='Save User Account Settings'/>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-6" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">Notifications</div>
                                    <div class="pt-3 ml-auto "><button class='btn btn-primary' disabled>Add Notification</button></div>
                                </div>
                                <div class="tableAPI card p-3" >
                                    <table class="display table responsive noWrap" id="tableNotifications"></table>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-7" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">API settings</div>
                                    <div class="pt-3 ml-auto "><button href="" class='btn btn-primary' disabled >Add API-Collector</button></div>
                                </div>
                                <div class="card-body p-0">
                                <?php require 'views/_admin_API_settings.php'; ?>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-8" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">Report settings</div>
                                </div>
                                <div class="card-body p-0">
                                <?php require 'views/_admin_Report_settings.php'; ?>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-9" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">PreDeparture Check settings</div>
                                </div>
                                <div class="card-body p-0">
                                <?php require 'views/_admin_PDC_settings.php'; ?>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-10" >
                                <div class="d-flex align-items-top mb-3 pt-3 border-bottom" >
                                    <div class="page-title">Fleet Management settings</div>
                                </div>
                                <div class="card-body p-0">
                                <?php require 'views/_admin_FM_settings.php'; ?>
                                </div>
                            </div>
                            <div class="adminpad col-12" id="list-item-11" >
                                <div class="d-flex align-items-top pt-3 mb-3 border-bottom" >
                                    <div class="page-title">Import data</div>
                                </div>
                                <div class="card-body p-0">
                                <?php require 'views/_admin_importCSV.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';  ?>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	window.onload=function(){
		ShowAPIScheduler();
        HideNavBarGroup();
	};
	new SlimSelect({ select: '#us_css',     showSearch: false,});
	new SlimSelect({ select: '#style_css',  showSearch: false,});
	new SlimSelect({ select: '#style_menu', showSearch: false,});
	new SlimSelect({ select: '#recaptcha',  showSearch: false,});
	new SlimSelect({ select: '#echouser',   showSearch: false,});
	new SlimSelect({ select: '#DashBoardType',  showSearch: false,});
	new SlimSelect({ select: '#change_un',  showSearch: false,});

</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
