	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid center">
				<div class="pagina">
				    <div class="inner-pagina">
    					<div class="row ">
                            <div class="col-12 col-xl-8">
                                <div class="row">
                                    <div class="col-12 col-md-8" >
                                        <div class="page-title"><?=lang("DASH_Welcome","");?> <?php echo ucfirst($user->data()->fname);?>, </div>
                                    </div>
                                    <div class="col text-right ml-auto ">
                                        <div class="text-right ml-auto text-date"><b><i class="fad fa-calendar-day"></i> <?php echo $Today;?></b> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xl-8 col-12 d-none d-xl-block">
                                <div class="row">
                                <?php if(count($Alerts)>= 1){ ?>
                                    <div class="col-md-6  p-0">
                                        <div id="carouselNotifications" class="carousel slide carousel-fade" data-ride="carousel">
                                            <div class="carousel-inner d-flex col-12">
                                        <?php $first=true;
                                        foreach ($Alerts as $val){
                                            if ($val->startPublish<Date("Y-m-d")&& $val->endPublish>Date("Y-m-d")){
                                            $endDate=new DateTime($val->endPublish);
                                            $result = $endDate->format('Y-m-d');
                                            if ($first==true){?>
                                                <div class="carousel-item active"  data-holder-rendered="true">
                                            <?php $first=false;} else {?>
                                                <div class="carousel-item"  data-holder-rendered="true">
                                            <?php }?>
                                                    <div class="card  mb-3 shadow-sm notes alert-<?=strtolower($val->notificationType)?>">
                                                    <?php if ($val->groupName!=''){ ?>
                                                        <div class="card-title p-3 "><i class="fad fa-car-bus fa-fw"></i> <?=$val->groupName?> </div>
                                                    <?php }?>
                                                        <div class="card-body ">
                                                            <div class="card-title p-0"><?=html_entity_decode($val->notificationHeader) ?></div>
                                                            <?=html_entity_decode($val->notificationInfo) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php	}
                                            }?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                                <?php if($CR->CountedRecords>0){ ?>
                                    <div class="col-md-3 col-6 mt-auto">
                                        <a href="<?=$us_url_root?>pages/report_scheduler">
                                            <div class="card  shadow-sm mb-3 p-0 report mini-card ">
                                                <div class="p-3"><?=lang("DASH_Reports","");?><div class=" info-box-number"><B><?=$CR->CountedRecords?></b></div></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-8 p-0">
							    <div class="row m-0">
                                    <div class="col-12 col-xl-4 mb-3">
                                        <a title="Fleet Status" href="<?=$us_url_root?>report/FleetUtilisation">
                                        <div class="card shadow-sm DashTileCards ">
                                            <div class="card-title"><?=lang("DASH_FleetStatus","");?></div>
                                        <?php if ($settings->sioc>0){ ?>
                                            <img class="card-img-top" src="<?=$us_url_root?>css/images/fleet.png" alt="Card image cap" >
                                        <?php }	?>
                                            <div class="card-body">
                                                <div class="info-box-number" id="VehiclesStats"></div>
                                                <div class="row mt-2 col">
                                                    <a href="<?=$us_url_root?>report/alerthistoryreport" class="text-primary" title="red dashboard lights"><div class="mr-1 col-1 p-0 " id="RedTellTales"></div></a>
                                                    <a href="<?=$us_url_root?>pages/vehicles" class="text-primary" title="severe damages, vehicle(s) not to be used"><div class="mr-1 col-1" id="SevereDamages"></div></a>
                                                </div>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-xl-4 d-none d-xl-block mb-3">
                                        <a  title="Driver Status" href="<?=$us_url_root?>report/DriveTimeMgt">
                                        <div class="card shadow-sm mb-3 DashTileCards">
                                            <div class="card-title "><?=lang("DASH_DriverStatus","");?></div>
                                            <?php if ($settings->sioc>0){ ?>
                                                <img class="card-img-top" src="<?=$us_url_root?>css/images/driver.png" alt="Card image cap" >
                                            <?php }	?>
                                            <div class="card-body  ">
                                                <div class="info-box-number" id="CountedDrivers"></div>
                                                <div class="row mt-2 col">
                                                    <a class="text-primary" title="# overspeeding drivers">  <div class="mr-1" id="SpeedingDrivers"></div></a>
                                                    <a class="text-primary" title="# exceeding driving time"><div class="mr-1" id="DriversEXT">     </div></a>
                                                </div>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-xl-4 mb-3">
                                        <a title="Fleet Status" href="<?=$us_url_root?>report/TripsDb">
                                        <div class="card shadow-sm DashTileCards">
                                            <div class="card-title"><?=lang("DASH_UsageStats","");?>
                                            <?php if ($settings->daysStatistics>1){
                                                echo "<small>".lang("DASH_CountOf","")." <B>".$settings->daysStatistics."</b> ".lang("DASH_Days","")."</small>";
                                            }
                                            else {
                                                echo "<small> ".lang("DASH_Today","")."</small>";
                                            }	?>
                                            </div>
                                            <?php if ($settings->sioc>0){ ?>
                                                <img class="card-img-top" src="<?=$us_url_root?>css/images/stats.png" alt="Card image cap" >
                                            <?php }	?>
                                            <div class="card-body ">
                                                <span class="info-box-number" id="CountedTrips"></span>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-12 mb-3">
                                        <div class="card shadow-sm DashTileCards">
                                            <div class="card-title "><?=lang("DASH_FleetHealth","");?></div>
                                            <?php if ($settings->sioc>0){ ?>
                                                <img class="card-img-top" src="<?=$us_url_root?>css/images/Diagnostics.jpg" alt="Card image cap" >
                                            <?php }	?>
                                            <div class="card-body  ">
                                                <div class="col-12 info-box-number text-primary p-0" id="alertmessage">
                                                    <div class="row text-primary">
                                                        <div class="p-2"><a href="<?=$us_url_root?>report/geofencingReport" title="Geofence activated" class=" p-1" id="CountedGE"> </a></div>
                                                        <div class="p-2"><a href="<?=$us_url_root?>pages/maintenance" title="Due maintenance, Service distance < 15.000km" id="CountedMDue" class="p-1"> </a></div>
                                                        <div class="p-2"><a href="<?=$us_url_root?>pages/maintenance" title="Overdue Maintenance, Service distance < 1000km and not 0" id="CountedMOverDue" class="p-1"> </a> </div>
                                                        <div class="p-2"><a href="#" title="Fuel Incidents" 		id="CountedFuelIncidents" class=" p-1 text-secondary">	<i class="fad fa-gas-pump fa-fw gray "></i> 0 </a> </div>
                                                        <div class="p-2"><a href="#" title="Low Battery " 		id="CountedBatIncidents"  class=" p-1 text-secondary">	<i class="fad fa-car-battery fa-fw gray"></i> 4 </a> </div>
                                                        <div class="p-2"><a href="#" title="Low Oil level" 		id="CountedOilLevWarnings" class="p-1 text-secondary"> <i class="fad fa-oil-can fa-fw gray"></i> 1 </a> </div>
                                                        <div class="p-2"><a href="#" title="Tire Pressure issue"  id="CountedTire"		  class=" p-1 text-secondary">	<i class="fad fa-tire-pressure-warning fa-fw gray"></i> 0</a> </div>
                                                        <div class="p-2"><a href="#" title="Engine issue"         id="CountedEngine"		  class=" p-1 text-secondary">	<i class="fad fa-engine-warning fa-fw gray"></i> 0</a> </div>
                                                        <div class="p-2"><a href="#" title="Delayed Arrival" 	    id="CountedNoETA" 		  class=" p-1 text-secondary">	<i class="fad fa-route fa-fw gray"></i> 2</a> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-none hide d-xl-block ml-auto mb-3" id="DashboardAPIStatus"></div>
                                </div>
                            </div>
						    <div class="col-12 col-xl-4">
							    <div class="row">
								    <div class="col-12 d-none d-xl-block">
									    <a href="<?=$us_url_root?>pages/map">
                                        <div class="card shadow-sm mb-3 DashTileMap"> <div id="map" class="shadow-rfms p-0"></div> </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
		                </div>
					</div>
				</div>
			</div>
        </section>
	</main>
    <script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
    <script>
    window.onload=function(){
        InitializeMAP();
        SaveSelectedGroup();
        LoadDashboard('1');
        DashboardAPIStatusDetail();
        CheckNewChats();
		ReadTripCounter('CountedTrips');
        setInterval(function(){ CheckNewChats();},10000);
        setInterval(function(){ LoadDashboard();},120000);
        setInterval(function(){ DashboardAPIStatusDetail();},180000);
		setInterval(function(){ ReadTripCounter('CountedTrips');},120000);
		ReadRedTellTales();
        ShowVehicleStats();
    }
    </script>