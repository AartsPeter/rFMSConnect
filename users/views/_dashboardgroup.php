
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid center">
				<div class="pagina  ">
					<div class="inner-pagina">
                        <div class="d-flex">
                            <div class="col-md-8 col-12 d-none d-md-block">
                                <div class="row ">
                                   <div class="col-6">
                                        <div class="page-title"><?=lang("DASH_Welcome","");?> <?php echo ucfirst($user->data()->fname);?>, </div>
                                    </div>
                                    <div class="col-6 text-right ml-auto ">
                                        <div class="text-right ml-auto text-date"><b><i class="fad fa-calendar-day"></i> <?php echo $Today;?></b> </div>
                                    </div>
                                <?php if(count($Alerts)>= 1){ ?>
                                    <div class="col-6 p-0 hide">
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
                                                    <div class="card admincard mb-3  shadow-sm notes alert-<?=strtolower($val->notificationType)?>">
                                                    <?php if ($val->groupName!=''){ ?>
                                                        <div class="card-header p-2 "><?=$val->groupName?> </div>
                                                    <?php }?>
                                                        <div class="card-body ">
                                                            <div class="card-title p-0 "><?=html_entity_decode($val->notificationHeader) ?></div>
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
                                <?php if($settings->messaging==1 ){ ?>
                                    <?php if($msgC>-1){?>
                                    <div class="col-md-3 col-12 mt-auto hide">
                                        <a class="" href="<?=$us_url_root?>users/messages">
                                        <div class="card  mb-3 p-0 mail">
                                            <!--<div class="card-header">Messages</div>-->
                                            <div class="p-3 pb-5 mini-card">You have <b>
                                            <?php if ($msgC!=0){ echo $msgC ?></b> new messages <?php }
                                            else { ?>no</b> new messages<?php } ?>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <?php   }?>
                                <?php   }?>
                                <?php if($CR->CountedRecords>0){ ?>
                                    <div class="col-md-3 col-12 mt-auto hide">
                                        <a href="<?=$us_url_root?>pages/report_scheduler">
                                            <div class="card  mb-3 p-0 report">
                                                <div class=" p-3"><b>Scheduled Reports </b><div class=" info-box-number"><B><?=$CR->CountedRecords?></b></div></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>

                                </div>
                            </div>
                            <div class="col-12 col-xl-4 mt-auto">
                                <a title="Fleet Status" href="<?=$us_url_root?>report/TripsDb">
                                <div class="card mb-3 ">
                                    <div class="card-header hide"><?=lang("DASH_UsageStats","");?>
                                    <?php if ($settings->daysStatistics>1){
                                        echo "<small>".lang("DASH_CountOf","")." ".$settings->daysStatistics." ".lang("DASH_Days","")."</small>";
                                    }
                                    else {
                                        echo "<small> ".lang("DASH_Today","")." </small>";
                                    }	?>
                                    </div>
                                    <div class="card-body ">
                                        <div class="info-box-number " id="CountedTrips"><div class="ph-item"><div class="ph-col-12"><div class="ph-row"><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div><div class="ph-col-10 big empty"></div><div class="ph-col-2 "></div><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div></div></div></div> </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="col-12 col-xl-8 ">
                                <div class="card DashTileCards shadow-sm">
                                    <div class="p-3">
                                        <div class="row m-0">
                                            <div class="col-12 col-md-4 hide"><?=lang("DASH_FleetStatus","");?></div>
                                            <div class="mr-auto d-flex">
                                                <a href="<?=$us_url_root?>report/alerthistoryreport" class="text-primary" title="red dashboard lights">                      <div class="mr-1" id="RedTellTales"> </div></a>
                                                <a href="<?=$us_url_root?>pages/vehicles" class="text-primary" title="severe damages, vehicle(s) not to be used"> <div class="mr-1" id="SevereDamages"></div></a>
                                                <a class="text-primary" title="# overspeeding drivers">  <div class="mr-1" id="SpeedingDrivers"></div></a>
                                                <a href="<?=$us_url_root?>pages/drivers" class="text-primary" title="# exceeding driving time">                  <div class="mr-1" id="DriversEXT">     </div></a>
                                            </div>
                                            <div class="px-1 ml-auto"><span id="VehiclesStats" title="active vehicles today"></span></div>
                                        </div>
                                     </div>
                                    <div class="card-body flow-yaxis">
                                        <table class="display table stripe nowrap" id="DashboardVehicles"> </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-4 mb-3 d-none d-xl-block">
                                <a href="<?=$us_url_root?>pages/map">
                                <div class="card shadow-sm mb-3 DashTileMap">
                                    <div id="map"></div>
                                </div>
                                </a>
                            </div>
                        </div>
					</div>
				</div>
			</section>
		</div>  
	</main>

	<script>
		window.onload=function(){
			InitializeMAP();
			SaveSelectedGroup();
            CheckNewChats();
            setInterval(function(){ CheckNewChats();},10000);
			setInterval(LoadDashboard(),120000);
		}
	</script>