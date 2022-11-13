<?php
//Navigation
	$query = $db->query("SELECT * FROM email");

	if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
    else {
    	if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
    	else {$SCName='All Groups';$SCNumber='*';}
    }

	$str1="";$str2="";$str3="";
	if ($user->data()->cust_id!='0'){
		$str2=" WHERE rcu.User_ID='".$user->data()->id."'";
		$str3=" LEFT JOIN rel_cust_user rcu on c.accountnumber=rcu.Cust_ID";}

	$results = $query->first();
	$query="
        SELECT
            c.name, c.accountnumber, COUNT(v.vin) AS CountedVehicles
        FROM
            CUSTOMERS c
            ".$str3."
            LEFT JOIN FAMReport f ON f.client=c.accountnumber
            LEFT JOIN vehicles v ON v.VIN=F.vin
        ".$str2."
        GROUP BY
            c.name";
	$GroupQ =$db->query($query);
	$Group = $GroupQ->results();
	if (count($Group)==1){$SCNumber=$Group[0]->accountnumber;}

	$AlertQ  = $db->query("SELECT * FROM notification where (domain='".$_SERVER['SERVER_NAME']."' OR domain='*') AND public='1' ORDER BY EndPublish DESC");
	$Alerts =  $AlertQ->results();


//Value of email_act used to determine whether to display the Resend Verification link
$email_act=$results->email_act;
$username=ucfirst($user->data()->username);

$grav = get_gravatar(strtolower(trim($user->data()->email)));
$useravatar = '<img src="'.$grav.'" class="img-responsive img-thumbnail" alt="">';
?>
    <header class="" id="menu" style="display:none;">
		<nav class="container-fluid navbar navbar-expand-lg fixed-top <?=$menu_style?>">
			<div class="ml-3 logo-image "></div><a class="navbar-brand" title="Dashboard" href="<?=$us_url_root?>index" > <?=$settings->site_name;?></a>
			<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
				<span class="text-white"><i class="fas fa-bars"></i></span>
			</button>
			<div class="navbar-collapse collapse " id="collapsibleNavbar" style="">
				<ul class="navbar-nav ">
					<li class="nav-item">
						<a class="nav-link"  href="<?=$us_url_root?>pages/map" target=""><?=lang("MENU_MAP","");?></a>
					</li>
					<li class="nav-item dropdown ">
						<a class="nav-link " href="#" id="navbarDD_resources" role="button" area-disabled="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=lang("MENU_PLANNING","");?></a>
                        <div class="dropdown-menu" role="menu" aria-labelledby="navbarDD_resources">
							<a class="dropdown-item disabled" href="<?=$us_url_root?>planner/ETA_routing" disabled target=""><?=lang("MENU_PLAN_ETAROUTING","");?></a>
							<a class="dropdown-item disabled" href="<?=$us_url_root?>planner/ETA_Status" target=""><?=lang("MENU_PLAN_Status","");?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item disabled" href="<?=$us_url_root?>planner/drivers" target=""><?=lang("MENU_PLAN_Drivers","");?></a>
							<div class="dropdown-divider"></div>
                            <a class="dropdown-item disabled" href="<?=$us_url_root?>planner/groups"><?=lang("MENU_PLAN_Cargo","");?></a>
                            <a class="dropdown-item disabled" href="<?=$us_url_root?>planner/dealers"><?=lang("MENU_PLAN_Del_Contacts","");?></a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link "  id="navbarDD_resources" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=lang("MENU_RESOURCES","");?></a>
                        <div class="dropdown-menu" role="menu" aria-labelledby="navbarDD_resources">
                            <a class="dropdown-item" href="<?=$us_url_root?>pages/vehicles" target=""><?=lang("MENU_RES_VEHICLES","");?></a>
                            <a class="dropdown-item" href="<?=$us_url_root?>pages/trailers" target=""><?=lang("MENU_RES_TRAILERS","");?></a>
							<div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?=$us_url_root?>pages/drivers" target=""><?=lang("MENU_RES_DRIVERS","");?></a>
							<div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?=$us_url_root?>pages/groups"><?=lang("MENU_RES_GROUPS","");?></a>
                            <a class="dropdown-item" href="<?=$us_url_root?>pages/dealers"><?=lang("MENU_RES_DEALERS","");?></a>
						</div>
					</li>
					<li class="nav-item dropdown  has-megamenu">
						<a class="nav-link "  id="navbarDD_reporting" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=lang("MENU_REPORTS","");?></a>
						<div class="dropdown-menu megamenu" role="menu">
							<div class="row m-0">
								<div class="col-12 col-xl-4 p-0">
									<div class="col-megamenu trips_background">
									<div class="page-title px-2 text-primary d-none d-sm-block">trip</div>
										<ul class="list-unstyled">
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/tripsdb"><?=lang("MENU_Rep_Trips","");?></a></li>
											<li><a class="dropdown-item d-none d-sm-block" href="<?=$us_url_root?>report/tripdb"><?=lang("MENU_Rep_TripAnalysis","");?> <BR><i class="tiny"><?=lang("MENU_Rep_TripAnalysisAdd","");?></i></a></li>
											<div class="dropdown-divider"></div>
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/PDC_Report"><?=lang("MENU_Rep_PDC","");?></a></li>
                                            <li><a class="dropdown-item d-none d-sm-block" href="<?=$us_url_root?>report/AlertHistoryReport"><?=lang("MENU_Rep_AHistory","");?></a></li>
                                            <li><a class="dropdown-item d-none d-sm-block" href="<?=$us_url_root?>report/Alerts"><?=lang("MENU_Rep_AAnalysis","");?></a></li>
										</ul>
									</div>
								</div>
								<div class="col-12 col-xl-4 p-0 d-none d-sm-block">
									<div class="col-megamenu">
										<div class="page-title px-2 text-primary d-none d-sm-block"><?=lang("MENU_Rep_SM_USAGE","");?></div>
										<ul class="list-unstyled">
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/FleetUtilisation"><?=lang("MENU_Rep_FleetUtil","");?></a></li>
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/VehicleReport"><?=lang("MENU_Rep_VehicleAct","");?></a></li>
											<div class="dropdown-divider"></div>
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/DriverReport"><?=lang("MENU_Rep_DriverAct","");?></a></li>
											<li><a class="dropdown-item" href="<?=$us_url_root?>report/DriveTimeMgt"><?=lang("MENU_Rep_DriveTime","");?></a></li>
											<div class="dropdown-divider"></div>
                                            <li><a class="dropdown-item disabled" href="#"><?=lang("MENU_Rep_AdvFuel","");?></a></li>
                                            <li><a class="dropdown-item disabled" href="#"><?=lang("MENU_Rep_CC_Fuelcard","");?></a></li>
										</ul>
									</div>  
								</div>
								<div class="col-12 col-xl-4 p-0">
									<div class="col-megamenu">
                                        <div class="page-title px-2 text-primary d-none d-sm-block"><?=lang("MENU_Rep_SM_DEALER","");?></div>
                                        <ul class="list-unstyled">
                                            <li><a class="dropdown-item" href="<?=$us_url_root?>pages/maintenance"><?=lang("MENU_Rep_Maintenance","");?></a></li>
                                            <li><a class="dropdown-item disabled" href="#"><?=lang("MENU_Rep_Damages","");?></a></li>
                                            <li><a class="dropdown-item disabled" href="#"><?=lang("MENU_Rep_WS_history","");?></a></li>
                                        </ul>
                                        <div class="dropdown-divider"></div>
                                        <div class="page-title px-2 text-primary d-none d-sm-block"><?=lang("MENU_Rep_SM_MISCELLANEOUS","");?></div>
                                        <ul class="list-unstyled">
                                            <!--<li><a class="dropdown-item" href="<?=$us_url_root?>report/delayeddb"><?=lang("MENU_Rep_DelayedVehicles","");?></a></li>-->
                                            <li><a class="dropdown-item" href="<?=$us_url_root?>report/geofencingReport"><?=lang("MENU_Rep_Geofencing","");?></a></li>
                                        </ul>
                                    </div>
								</div>
							</div>
							<div class="row m-0 mb-3">
                                <div class="dropdown-divider col-12"></div>
                                <div class="col-xl-4 p-0">
                                    <div class="col-megamenu">
                                        <a class="dropdown-item" href="<?=$us_url_root?>pages/report_scheduler"><?=lang("MENU_Rep_Planner","");?><BR><i class="tiny"><?=lang("MENU_Rep_PlannerAdd","");?></i></a>
                                    </div>
                                </div>
                            </div>
 						</div>
					</li> 
					<li class="nav-item dropdown">
						<a class="nav-link "  id="navbarDD_settings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=lang("MENU_SETTINGS","");?></a>
						<div class="dropdown-menu  " aria-labelledby="navbarDD_settings">
							<a class="dropdown-item" href="<?=$us_url_root?>pages/manage_geofences"><?=lang("MENU_Tools_Geofences","");?></a>
							<?php if (checkMenu(2,$user->data()->id)){	?>
							<a class="dropdown-item" href="<?=$us_url_root?>pages/monitoring"><?=lang("MENU_Tools_Monitoring","");?></a>
							<a class="dropdown-item" href="<?=$us_url_root?>pages/api_collector"><?=lang("MENU_Tools_APIColl","");?></a>
							<?php } else {?>
							<a class="dropdown-item disabled" href="#"><?=lang("MENU_Tools_Monitoring","");?>Monitoring</a>
							<?php } ?>
							<?php if (checkMenu(3,$user->data()->id)){  //Links for permission level 3 (default Fleetadmin) ?>
							<div class="dropdown-divider"></div>
<!--							<a class="dropdown-item" href="<?=$us_url_root?>driverdashboard"><?=lang("MENU_SETTINGS","");?>DriverDashboard </a>-->
							<a class="dropdown-item" href="<?=$us_url_root?>users/admin"><?=lang("MENU_Tools_Settings","");?></a>
							<a class="dropdown-item" href="<?=$us_url_root?>pages/pdc_new"><?=lang("MENU_Tools_StartPDC","");?></a>
<!--							<a class="dropdown-item" href="<?=$us_url_root?>users/fleetadmin"><?=lang("MENU_Tools_FleetAdmin","");?></a>
							<a class="dropdown-item" href="<?=$us_url_root?>pages/DAFConnect_ShowCase" target="_blank"><?=lang("MENU_Tools_CONNECTDEMO","");?></a>-->
							<?php } // is user an FLEETMANAGER ?>	
							<div class="dropdown-divider"></div>
<!--							<a class="dropdown-item" data-toggle="modal" data-target="#TermsAndConditions" href="#" onclick="ShowTermsTxt();"><?=lang("MENU_Tools_Terms","");?></a>-->
							<a class="dropdown-item" data-toggle="modal" data-target="#modal-about" href="#"><?=lang("MENU_Tools_About","");?></a>
						</div>
					</li>    
				</ul>
				<ul class="navbar-nav ml-auto group-selector">
					<li class="nav-item <?php	if (sizeof($Group)==1){ ?>hide<?php }?> " id="navbar-group-selector">
						<select id="groups" class="col-12 p-0 " size="20" name="SGDashboard" value="" onchange="SaveSelectedGroup();window.location.reload(); ">
						<?php
						if (sizeof($Group)>1){
							if ($SCNumber=='*'){ echo '<option class="DeActiveGroup" value="*" selected><b>All groups</option>'; }
							else               { echo '<option class="DeActiveGroup" value="*"><b>All groups</option>'; }
						}
						foreach ($Group as $row){
							if ($row->accountnumber==$SCNumber) { echo '<option class="DeActiveGroup" value="'.ucfirst($row->accountnumber).'" selected > '.ucfirst($row->name).'</option>'; }
							else                                { echo '<option class="DeActiveGroup" value="'.ucfirst($row->accountnumber).'" > '.ucfirst($row->name).'</option>'; }
						}
						?>
						 </select>
					</li>
				</ul>

				<ul class="navbar-nav mr-4">
				    <li class="nav-item dropdown">
                		<a class="nav-link" href="#" id="navbarDD_resources" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fad fa-user fa-fw larger"></i>
				    	    <?php echo $username;?>
<!--				    	    <span class="smallcircle"><?php echo $useravatar;?></span>-->
                            <span class="badge" id="user_badge">&nbsp&nbsp</span>
                        </a>
						<!--//<span class="badge badge-light nameBadge border"><?php echo $username[0];?></span>-->
						<div class="dropdown-menu dropdown-menu-right pt-0" aria-labelledby="navbarDD_user">
							<div class="card-header media flex col-12">
								<div class="smallcircle"><?php echo $useravatar;?></div>
								<div class="media-item"><h4><?php echo $username;?></h4><?php echo ucfirst($user->data()->email);?></div>
							</div>
						<?php if($settings->messaging == 1){ ?>
							<a class="dropdown-item" href="<?=$us_url_root?>users/messages"><?=lang("MENU_PERSON_MESSAGES","");?> <span class="badge" id="mailmenu_badge">&nbsp&nbsp</span></a>
						<?php } ?>							
<!--							<a class="dropdown-item" href="#" onclick="openNav()"> Notifications</a>-->
							<a class="dropdown-item" href="<?=$us_url_root?>pages/report_scheduler"><?=lang("MENU_PERSON_MYREPORT","");?></a>
							<a class="dropdown-item" href="<?=$us_url_root?>users/user_settings"><?=lang("MENU_PERSON_MYSETTINGS","");?></a>
<!--							<div class="dropdown-item"><button type="button" class="btn btn-demo" data-toggle="modal" data-target="#ModalRight">Right Sidebar Modal</button></div>-->
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?=$us_url_root?>users/logout"><b><?=lang("MENU_PERSON_LOGOUT","");?></b></a>
						</div>
					</div>
				</ul>
			</div>	
		</nav>
    </header>
		 <div class="modal fade modal-center" id="modal-about" tabindex="-1" role="dialog" aria-labelledby="modalabout" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content ">
					<div class="modal-header">
						<h3 class="modal-title"><b><?=$settings->site_name;?></b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>					
					<div class="modal-body">
						<div class="row">
							<div class="col-3 p-0">
								<div class=""><img src="<?=$us_url_root?>images/favicon_light.png" class="aboutlogo" ></div>
							</div>
							<div class="col-9">
								<label for="license">Licensed to</label>
								<div class="" id="license"><b><?=$settings->licensed;?></b></div>
								<label for="license">version</label>
								<div class="" id="license"><b><?=$settings->version;?></b></div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 pt-2">
								<p>rFMS Connect is a simple and robust truck fleetmanagement solution. By collecting vehicle data via a REST-API based on the rFMS-standard. <br/>The rFMS standard is created to exchange FMS-vehicle data between european OEM truck manufacturers and Telematics Solution Providers</p>
								<p>This application is based the rFMS-data from any european OEM truck  and has multiple modules and can be easily extended with new features.</p>							
							</div>
						</div>	
					</div>	
					<div class="modal-footer ">
						 <div class=" mr-auto text-right">Copyright &copy;  Peter Aarts 2021 &nbsp </div>
        				<script type='text/javascript' src="<?=$us_url_root?>js/Ko-Fi_Widget_2.js"></script><script type='text/javascript'>kofiwidget2.init('Buy me a coffee', '#29abe0', 'Q5Q81QQCG');kofiwidget2.draw();</script>

					</div>							
				</div>
			</div>
		</div>
        <div class="modal right fade" id="ModalRight" tabindex="-1" role="dialog" aria-labelledby="ModalRightHeader">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="card-title p-0" id="ModalRightHeader">Right Sidebar</h4>
                    </div>
                    <div class="modal-body" id="ModalRightBody">my right modal content body <BR><BR><b>Lorem ipsum</b> dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<BR><BR><i> "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"</i></div>
					<div class="modal-footer"></div>
                </div>
            </div>
        </div>

<?php require_once $abs_us_root.$us_url_root.'usersc/includes/navigation.php';?>
