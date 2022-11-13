<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 
$errors = [];
$successes = [];

$SearchId = Input::get('id');
$validation = new Validate();
//Check if selected user exists
if(!IdExists($SearchId,'dealer')){
  Redirect::to("dealers.php"); die();
}
$db = DB::getInstance();
$query="SELECT * FROM dealers_daf WHERE id='".$_GET["id"]."' ";
$DealerQ = $db->query($query);
$dealerdetails = $DealerQ->first();

$dealerdetails->Address	=json_decode(strip_tags($dealerdetails->Address));
$dealerdetails->Communication	=json_decode($dealerdetails->Communication);
$dealerdetails->Person=json_decode($dealerdetails->Person);
$dealerdetails->CompanyBusiness=json_decode($dealerdetails->CompanyBusiness);
$dealerdetails->Openinghour=json_decode($dealerdetails->Openinghour);	
//print_r($dealerdetails);
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
				    <div class="col-12 row">
				        <div class="col-12 page-title">Resources - Dealers - Dealer details</div>
						<div class="col-12 col-xl-5 pt-2">
							<div class="card shadow">
								<div class="card-header  ">
									<div class="row pb-3">
										<div class="col-9 info-box-number primary  large " ><?php echo ucfirst($dealerdetails->name);?> </div>
										<div class="col-3 info-box-number text-right ml-auto " ><?php echo ucfirst($dealerdetails->Category);?> </div>
									</div>
									<div class="row ">
										<div class="d-flex col-12">
											<?php if ($dealerdetails->its_24hours==1){?>	<div class=" m-1 p-1 border shadow-sm">ITS 24/7</div> <?php }?>		
											<?php if ($dealerdetails->its_trailer==1){?>	<div class=" m-1 p-1 border shadow-sm">ITS Trailer</div> <?php }?>		
											<?php if ($dealerdetails->its_bus==1){?>		<div class=" m-1 p-1 border shadow-sm">ITS Bus</div> <?php }?>													
											<?php if ($dealerdetails->TRP==1){?>		<div class=" m-1 p-1 border shadow-sm">TRP</div> <?php }?>													
										</div>																						
									</div>
								</div>
								<div class="card-body ">
									<div class="card-title m-0 ">dealer details</div>
									<div class="form-signup">
										<div class="row col-12 ">
											<div class="col-6  p-1">
												<label>Name</label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->name);?></div>
											</div>
											<div class="col-6  p-1">
												<label>website</label>
												<div class="form-control-ro"><a href="http://<?php echo ucfirst($dealerdetails->website);?>" target="blank"><?php echo ucfirst($dealerdetails->website);?></a></div>
											</div>
											<div class="col-8  p-1">
												<label>eMail</label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->Communication[0]->cmm_email1);?></div>
											</div>
											<div class="col-6  p-1">
												<label>Office phone  </label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->Communication[0]->cmm_phone);?></div>
											</div>										
											<div class="col-6  p-1">
												<label>Office fax </label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->Communication[0]->cmm_fax);?></div>										
											</div>										
											<div class="col-6  p-1">
												<label>Address  </label>
												<div class="form-control-ro "><?php echo ucfirst($dealerdetails->Address[0]->adr_address_1);?></div >
											</div>
											<div class="col-6  p-1">
												<label>Address  2</label>
												<div class="form-control-ro "><?php echo ucfirst($dealerdetails->Address[0]->adr_address_2);?></div>
											</div>		
											
											<div class="col-6  p-1">
												<label>City  </label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->Address[0]->adr_city);?></div>																	
											</div>	
											<div class="col-3 p-1">
												<label>Zipcode  </label>
												<div class="form-control-ro"><?php echo ucfirst($dealerdetails->Address[0]->adr_postalcode);?></div>		
											</div>
										</div>
									</div>
								</div>
									<div class="card-footer p-3">
										<a class="btn btn-secondary mx-2" href="dealers.php">return</a>
									</div>
							</div>
						</div>
						<div class="col-12 col-md-5 mr-auto  ">
                            <div class="row">
                                <div class="col-12" id="#VehicleDetails">
                                    <div class="nav-tabs-custom "  id="PDCTabs">
                                        <ul class="nav nav-tabs pl-3" >
                                            <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" onclick="GetDriveTime(<?=ucfirst($driverdetails->id)?>);" title="services"><i class="fad fa-users-cog"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB2" data-toggle="tab" onclick="ShowDriverActivity(<?=$SearchId?>);" title="trip activity"><i class="fad fa-route" aria-hidden="true"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" onclick="ShowDriverRD(<?=$SearchId?>,false);" title="registered damages"><i class="fad fa-tools" aria-hidden="true"></i> <span class="badge badge-danger"><?=$driverdetails->DamageCount?></span></a></li>
                                        </ul>
                                        <div class="tab-content card">
                                            <div class="tab-pane active" id="PDCTAB1" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="card-title m-0 mb-3">services</div>
                                                    <div id="DealerServices"></div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB2" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="card-title m-0 mb-3">personel</div>
                                                    <div id="DealerPersonel"></div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB3" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="card-title m-0 mb-3">registered damages</div>
                                                    <div id="DealerOpening"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script async src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
		<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts-more.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/xrange.src.js"></script>
	<script src="<?=$us_url_root?>plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>
	<script>
		window.onload=function(){
			ShowDealerInfo(<?php echo '"'.ucfirst($dealerdetails->id).'"';?>,1);		
		}
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
