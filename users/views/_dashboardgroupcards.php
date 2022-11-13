
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid center">
				<div class="pagina  ">
					<div class="inner-pagina d-flex">
						<div class="col-12 col-xl-7 ">
                            <div class="col-12 mb-3  ">
                                <div class="row">
                                   <div class="col-6">
                                        <div class="page-title"><?=lang("DASH_Welcome","");?> <?php echo ucfirst($user->data()->fname);?>, </div>
                                    </div>
                                    <div class="col-6 text-right ml-auto py-4">
                                        <div class="text-right ml-auto text-date larger"><b><i class="fad fa-calendar-day"></i> <?php echo $Today;?></b> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex col-12 p-0">
                            <?php if($settings->messaging >0 ){ ?>
                                <?php if($msgC>-1){?>
                                <div class="col">
                                    <a class="" href="<?=$us_url_root?>users/messages.php">
                                    <div class="card border shadow-sm mb-3 p-0 mail">
                                        <div class="card-body p-2 pb-5">You have <b>
                                        <?php if ($msgC<>0){ echo $msgC ?></b> new messages <?php }
                                        else { ?>no</b> new messages<?php } ?>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <?php   }?>
                            <?php   }?>
                            <?php if($CR->CountedRecords>0){ ?>
                                <div class="col">
                                    <a href="<?=$us_url_root?>pages/report_scheduler.php">
                                    <div class="card border shadow-sm mb-3 p-0 report">
                                        <div class="card-body p-2 pb-5">
                                                <?php if ($CR->CountedRecords==1) { ?> There is  <B><?php echo $CR->CountedRecords; ?></b> report scheduled for you<?php }
                                                else {?> Reports scheduled for you : <B><?php echo $CR->CountedRecords; ?></b> <?php } ?>

                                        </div>
                                    </div>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(count($Alerts)>= 1){ ?>
                                <div class="col-6 p-0 d-none d-xl-block ">
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
                                                <div class="card mb-3 border shadow-sm notes alert-<?=strtolower($val->notificationType)?>">
                                                <?php if ($val->groupName!=''){ ?>
                                                    <div class="card-header p-2 "><?=$val->groupName?> </div>
                                                <?php }?>
                                                    <div class="card-body p-2">
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
                            </div>
                            <div class="col-12">
                                <div class="DashTileCards pr-2">
                                    <div class="row" id="DashCards"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-5 p-0 mb-3">
                            <div class="col-12">
                                <a title="Fleet Status" href="<?=$us_url_root?>report/TripsDb.php">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header"><?=lang("DASH_UsageStats","");?>
                                    <?php if ($settings->daysStatistics>1){
                                        echo "<small>".lang("DASH_CountOf","")." ".$settings->daysStatistics." ".lang("DASH_Days","")."</small>";
                                    }
                                    else {
                                        echo "<small> ".lang("DASH_Today","")." </small>";
                                    }	?>
                                    </div>
                                    <div class="card-body "><span class="info-box-number " id="CountedTrips">
                                        <div class="ph-item"><div class="ph-col-12"><div class="ph-row"><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div><div class="ph-col-10 big empty"></div><div class="ph-col-2 "></div><div class="ph-col-2 big"></div><div class="ph-col-8 empty"></div><div class="ph-col-2"></div></div></div></div>
                                    </span></div>
                                </div>
                                </a>
                            </div>
                            <!--d-none d-xl-block-->
                            <div class="col-md-12 ">
                                <a href="<?=$us_url_root?>pages/map.php">
                                <div class="card  shadow-rfms mb-3 DashTileMap">
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
			LoadDashboard('1','cards');
			setInterval(LoadDashboard('','cards'),120000);
            CheckNewChats();
            setInterval(function(){ CheckNewChats();},10000);
		}
	</script>