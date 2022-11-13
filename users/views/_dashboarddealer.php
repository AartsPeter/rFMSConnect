<?php
$AlertQ  = $db->query("SELECT * FROM notification WHERE domain='*' AND desktop='1' AND CURDATE() BETWEEN StartPublish AND EndPublish ORDER BY EndPublish ASC");
$Alerts =  $AlertQ->results();
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container center">
				<div class="pagina ">
					<div class="row">	

						<div class="flex col-12 ">		
							<div class="row">	
								<div class="col-12 col-xl-7 ">
									<div class="col-12 ">
										<div class="row">
											<div class="card  col-12 mb-2">
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="subtitle pb-3">Hi <?php echo ucfirst($user->data()->fname);?>, </div>
														</div>
														<div class="col-12 col-md-4 ml-auto">
															<div class="text-right info-box-number work ml-auto"><?php echo $Today;?> </div>
														</div>	
													</div>
													<div class="row">
														<?php if($settings->messaging == 1){ ?>
															<div class="col-3 d-none d-xl-block ">
																<a class="" href="<?=$us_url_root?>users/messages">		
																	<div class="mini-card ">
																		<!--<div class="card-header">Messages</div>-->
																		<div class="card-body mail pl-3">You have <b>
																		<?php if ($msgC<>0){ echo $msgC ?></b> new messages <?php } 
																		else { ?>no</b> new messages<?php } ?>
																		</div>
																	</div>
																</a>
															</div>
														<?php } ?>	
															<div class="col-3 d-none d-xl-block ">
																<div class="mini-card ">
																	<!--<div class="card-header">Reports</div>-->
																	<a href="<?=$us_url_root?>pages/report_scheduler">
																		<?php if ($CR->CountedRecords==1) { ?><div class="card-body report pl-3"> There is  <B><?php echo $CR->CountedRecords; ?></b> report scheduled </div> <?php }
																		else {?><div class="card-body report pl-3"> There are <B><?php echo $CR->CountedRecords; ?></b> reports scheduled </div> <?php } ?>
																	</a>
																</div>
															</div>
														<?php if(count($Alerts)>= 1){ ?>
															<div class="col-6 d-none d-xl-block">
																<div class="mini-card ">
																	<!--<div class="card-header">Notifications</div>-->
																	<div class="card-body notes pl-3">
																		<ul>
																		<?php foreach ($Alerts as $val){ ?>
																			<li class="mb-2 mx-0 <?=$val->Notification_Type; ?>"><b><?=$val->Notification_header; ?></b> <BR><?=$val->Notification_info; ?></li>
																		<?php }?>
																		</ul>
																	</div>
																</div>
															</div>
														<?php }?>				
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<div class="card VehicleDetails shadow mb-3">
											<div class="card-header VehicleTile d-flex"><div class="col-6 p-0">Fleet status </div> <div class="ml-auto" id="VehiclesStats"></div></div>
											<div class="card-body flow-yaxis"><table class="display nowrap" id="DashboardVehicles"></table>	</div>
										</div>
									</div>			
								</div>
								<div class="col-12 col-xl-5">
									<div class="col-12">
									<a title="Vehicles driving today " href="<?=$us_url_root?>report/tripsdb">
									<div class="card VehicleDetails shadow mb-3">
										<div class="card-header VehicleTile">Fleet Usage Statistics 
									<?php if ($settings->daysStatistics>1){
											echo "<small> of last ".$settings->daysStatistics." days </small>";
										  }
										  else { 
											echo "<small> of today </small>";
										  }	?>
										</div>
										<div class="card-body "><span class="info-box-number text-primary" id="CountedTrips"></span></div>
									</div>
									</a>
								</div>
								<div class="col-md-12 d-none d-xl-block">
									<a href="<?=$us_url_root?>pages/map">
									<div class="DashTileMap card  shadow">
										<div id="map" class="card DashTileMap p-0 shadow" ></div>
									</div>	
									</a>
								</div>
							</div>
						</div>
<!--- -->
					</div>
				</div>
			</section>
		</div>  
	</main>				

	<script>
		window.onload=function(){
			InitializeMAP();
			SaveSelectedGroup();
//			ShowVehicleStats();
			ShowDashboard('1');
			setInterval(ShowDashboard,120000);
//			setInterval(ShowVehicleStats,120000);
//			ReadTodayDelay();
//			ReadBackLogValues();
//			ReadBackLog('2','DAF-rFMS2',new Date());
//		}
	</script>