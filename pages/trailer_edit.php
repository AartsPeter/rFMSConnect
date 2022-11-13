<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 
//PHP Goes Here!
$errors = [];
$successes = [];

$SearchId = Input::get('id');
$validation = new Validate();
//Check if selected user exists
if(!IdExists($SearchId,'Trailers')){
  Redirect::to("trailers.php"); die();
}
	if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
	else {
		if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
		else {$SCName='All Groups';$SCNumber='*';}
	}	
	if ($user->data()->cust_id=='0'){
		$str2="";$str3="";} 
	else {
		$str2="and rel_cust_user.User_ID='".$user->data()->id."'";
		$str3="INNER JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=famreport.client";}
	if ($SCNumber=='*')	{
		$str1="";} 
	else {
		$str1="and famreport.client='".$SCNumber."'";}

$db = DB::getInstance();
$query="SELECT trailers.*,vehicles.customerVehicleName,
	(SELECT count(trailers.vin) FROM pdc_damage WHERE pdc_damage.vin = trailers.vin  AND pdc_damage.repairStatus=0) AS `DamageCount`
	FROM trailers 
		LEFT JOIN vehicles ON vehicles.VIN=trailers.vehicleVIN
		LEFT JOIN FAMReport ON FAMReport.vin=trailers.vehicleVIN ".$str3."
	WHERE trailers.id=".$SearchId." ".$str1." ".$str2;
$queryQ =$db->query($query);
$trailerdetails =$queryQ->first();
if ($trailerdetails->id!=$SearchId){ Redirect::to("trailers.php"); die();}

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else 
	{
		if ($trailerdetails->trailerName != $_POST['trailerName']){
			$displayname = Input::get("trailerName");
			$fields=array('trailerName'=>$displayname);
			$validation->check($_POST,array(
				'trailerName' => array(
					'display' => 'trailerName',
					'required' => true,
					'min' => 1,
					'max' => 50
					)
			));
			if($validation->passed()){
				$db->update('trailers',$SearchId,$fields);
				$successes[] = "Trailername Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($trailerdetails->LicensePlate != $_POST['LicensePlate']){
			$displayname = Input::get("LicensePlate");
			$fields=array('LicensePlate'=>$displayname);
			$validation->check($_POST,array(
				'LicensePlate' => array(
					'display' => 'LicensePlate',
					'required' => true,
					'min' => 0,
					'max' =>12
					)
			));
			if($validation->passed()){
				$db->update('trailers',$SearchId,$fields);
				$successes[] = "LicensePlate Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($trailerdetails->totalFuelTankVolume != $_POST['totalFuelTankVolume']){
			$displayname = Input::get("totalFuelTankVolume");
			$fields=array('totalFuelTankVolume'=>$displayname);
			$validation->check($_POST,array(
				'totalFuelTankVolume' => array(
					'display' => 'totalFuelTankVolume',
					'required' => true,
					'min' => 0,
					'max' => 5
					)
			));
			if($validation->passed()){
				$db->update('trailers',$SearchId,$fields);
				$successes[] = "totalFuelTankVolume Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($trailerdetails->noOfAxles != $_POST['noOfAxles']){
			$displayname = Input::get("noOfAxles");
			$fields=array('noOfAxles'=>$displayname);
			$validation->check($_POST,array(
				'noOfAxles' => array(
					'display' => 'noOfAxles',
					'required' => true,
					'min' => 1,
					'max' => 8
					)
			));
			if($validation->passed()){
				$db->update('trailers',$SearchId,$fields);
				$successes[] = "noOfAxles Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		$db = DB::getInstance();
		$query="SELECT trailers.*,vehicles.customerVehicleName,
			(SELECT count(trailers.vin) FROM pdc_damage WHERE pdc_damage.vin = trailers.vin  AND pdc_damage.repairStatus=0) AS `DamageCount`
			FROM trailers 
				LEFT JOIN vehicles ON vehicles.VIN=trailers.vehicleVIN
				LEFT JOIN FAMReport ON FAMReport.vin=trailers.vehicleVIN ".$str3."
			WHERE trailers.id=".$SearchId." ".$str1." ".$str2;
		$queryQ =$db->query($query);
		$trailerdetails =$queryQ->first();
	}
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class=" inner-pagina">
				    <div class="row">
					    <div class="col-12 mr-auto page-title">Trailer details</div>
						<div class="col-12 col-md-5">
							<div class="card shadow ">
								<div class="card-header ">
									<div class="row pb-3">
										<div class="col-6 info-box-number primary large" ><?php echo ucfirst($trailerdetails->trailerName);?> </div>
										<?php 
											if($trailerdetails->copplingStatus=='1') {echo '<div class="col-2 ml-auto info-box-number  text-right"><i class="fas fa-link primary"></i></div>';}
											else { echo '<div class="col-2 ml-auto info-box-number text-right"><i class="fas fa-unlink gray"></i></div>';}?> 
									</div>
									<div class="row px-3">
							        <?php 	if($trailerdetails->copplingStatus=='1') {?>
										<label class="col-12 p-0 small">connected vehicle</label>
										<div class="" ><?php echo ucfirst($trailerdetails->customerVehicleName);?> </div>
        							<?php }?>
									</div>
									<div class="row pt-3">
										<div class="col-6">
											<div class="small "><i class="fas fa-clock"></i> <?php echo ucfirst($trailerdetails->LastActivity);?></div>
										</div>										
										<div class="col-6">
											<div class="small text-right" id="VLAddress" ></div>
										</div>												
									</div>
								</div>
								<div class="card-body ">
									<form class="form-signup " action="" method="POST" id="editvehicle-form">
										<div class="row p-0 ">
											<div class="col-6">
												<label>trailer name</label>
												<input class="form-control" type="text" name="trailerName" id="trailerName" placeholder="" value="<?php echo ucfirst($trailerdetails->trailerName);?>" required autofocus>
											</div>
											<div class="col-6">
												<label>license plate</label>
												<input class="form-control" type="text" name="LicensePlate" id="LicensePlate" placeholder="" value="<?php echo ucfirst($trailerdetails->LicensePlate);?>" >
											</div>
											<div class="col-6">
												<label>axles configuration </label>
												<input class="form-control" type="text" name="noOfAxles" id="noOfAxles" placeholder="" value="<?php echo ucfirst($trailerdetails->noOfAxles);?>" >
											</div>														
										</div>
										<div class="row">
											<div class="col-12 pt-3 mr-auto"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
										</div>
									</div>
									<div class="card-footer p-3">
											<input type="hidden" value="<?=Token::generate();?>" name="csrf">
										<?php if (checkMenu(2,$user->data()->id)){  //Links for permission level 2 (default admin) ?>
											<input class="btn btn-primary mx-2" type='submit' name='changevehicle' value="Update / Save" />
										<?php }?>															
											<a class="btn btn-secondary mx-2" href="trailers.php">cancel / return</a>
										</div>
									</form>	
								</div>
							</div>
    						<div class="col-12 col-md-7 ml-auto ">
								<div class="col-12 p-0 trailerdetails" id="#trailerdetails">
                                    <div class="nav-tabs-custom "  id="PDCTabs">
                                        <ul class="nav nav-tabs pl-3" >
                                            <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" onclick=""><i class="fad fa-trailer"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB2" data-toggle="tab" onclick="ShowVehicleMOTStatus(<?=$SearchId?>);ShowVehicleMaintenanceHistory(<?=$SearchId?>);" title="maintenance"><i class="fad fa-wrench"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" onclick="ShowTrailerRD(<?=$SearchId?>);" title="registered damages"><i class="fad fa-tools"></i><span class="badge badge-danger"><?=$trailerdetails->DamageCount?></span></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB4" data-toggle="tab"  onclick="" title="tyre pressure monitoring"><i class="fad fa-tire-pressure-warning"></i></a></li>
                                        </ul>
                                        <div class="tab-content card border">
                                            <div class="tab-pane active" id="PDCTAB1" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="card-title m-0 mb-3">trailer details</div>
													<div class="row ">
														<div class="col-6 col-lg-3">
															<label>vin </label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->vin);?></div>
														</div>
														<div class="col-6 col-lg-3">
															<label>brand </label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->brand);?></div>
														</div>
														<div class="col-6 col-lg-3">
															<label>model </label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->model);?></div>
														</div>
														<div class="col-6 col-lg-3">
															<label>service distance </label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->serviceDistance/1000);?> km</div>
														</div>
														<div class="col-6 col-lg-3">
															<label>copplingStatus</label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->copplingStatus);?> </div>
														</div>
														<div class="col-6 col-lg-3">
															<label>ambient air temperature</label>
															<div class="form-control-ro" ><?php echo ucfirst($trailerdetails->ambientAirTemperature);?> </div>
														</div>
													</div>
												</div>
                                            </div>
                                            <div class="tab-pane active" id="PDCTAB2" role="tabpanel">
                                                <div class="card-body">
                                                    <div class="card-title m-0 mb-3">maintenance</div>
													<div class="row">
														<div class="col-6 col-lg-6">
															<label>last MOT</label>
															<div class="form-control-ro"> -</div>
														</div>
														<div class="col-6 col-lg-6">
															<label>Expiration MOT</label>
															<div class="form-control-ro"> 2023-12-10</div>
														</div>
													</div>
													<div class="row">
														<div class="col-6 col-lg-6">
															<label>planned maintenance</label>
															<div class="form-control-ro"> n/a</div>
														</div>
														<div class="col-6 col-lg-6">
															<label>maintenance history</label>
															<div class="form-control-ro"> n/a</div>
														</div>
													</div>
												</div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB3" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">registered damages</div>
                                                    <table class="display table" id="tableTrailerDamages"></table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB4" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">tyre pressure monitoring</div>
                                                    <table class="display table" id="tableTrailerTPMS"></table>
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
<script>
window.onload=function(){
	Convert2Address([<?php echo '"'.ucfirst($trailerdetails->last_Latitude).'"';?>, <?php echo '"'.ucfirst($trailerdetails->last_Longitude).'"';?>,'VLAddress']);
//	ShowTrailerRD(<?php echo '"'.ucfirst($trailerdetails->id).'"';?>,false);
//	ShowTrailerActivity(<?php echo '"'.ucfirst($trailerdetails->id).'"';?>);
}
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
