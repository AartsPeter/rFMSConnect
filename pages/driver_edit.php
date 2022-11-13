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
if(!driverIdExists($SearchId,'driver')){
  Redirect::to("drivers.php"); die();
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
$query =$db->query("
	SELECT driver.*,
	(SELECT count(pdc_damage.driver) FROM pdc_damage WHERE pdc_damage.driver=driver.tachoDriverIdentification AND pdc_damage.repairStatus=0) AS `DamageCount`
	FROM driver
	INNER JOIN FAMReport ON FAMReport.vin=driver.LastVehicle
	INNER JOIN customers ON customers.accountnumber=FAMReport.client ".$str3." 
	WHERE driver.id=".$SearchId." ".$str1." ".$str2);
$driverdetails =$query->first();
if ($driverdetails->id!=$SearchId){ Redirect::to("drivers.php"); die();}
//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else 
	{
		if ($driverdetails->Surname != $_POST['Surname']){
			$displayname = Input::get("Surname");
			$fields=array('Surname'=>$displayname);
			$validation->check($_POST,array('Surname' => array('display' => 'Surname','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Surname Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Lastname != $_POST['Lastname']){
			$displayname = Input::get("Lastname");
			$fields=array('Lastname'=>$displayname);
			$validation->check($_POST,array('Lastname' => array('display' => 'Lastname','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Lastname Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->eMail != $_POST['eMail']){
			$displayname = Input::get("eMail");
			$fields=array('eMail'=>$displayname);
			$validation->check($_POST,array('eMail' => array('display' => 'eMail address','required' => false,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "eMail Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Phone_Home != $_POST['Phone_Home']){
			$displayname = Input::get("Phone_Home");
			$fields=array('Phone_Home'=>$displayname);
			$validation->check($_POST,array('Phone_Home' => array('display' => 'Phone_Home','required' => false,'min' => 10	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Phone_Home Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Phone_Mobile != $_POST['Phone_Mobile']){
			$displayname = Input::get("Phone_Mobile");
			$fields=array('Phone_Mobile'=>$displayname);
			$validation->check($_POST,array('Phone_Mobile' => array('display' => 'Phone_Mobile','required' => false,'min' => 10	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Phone_Mobile Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Address != $_POST['Address']){
			$displayname = Input::get("Address");
			$fields=array('Address'=>$displayname);
			$validation->check($_POST,array('Address' => array('display' => 'Address','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Address Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Housenumber != $_POST['Housenumber']){
			$displayname = Input::get("Housenumber");
			$fields=array('Housenumber'=>$displayname);
			$validation->check($_POST,array('Housenumber' => array('display' => 'Housenumber','required' => true,'min' => 1,'max' => 10	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Housenumber Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->City != $_POST['City']){
			$displayname = Input::get("City");
			$fields=array('City'=>$displayname);
			$validation->check($_POST,array('City' => array('display' => 'City','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "City Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Zipcode != $_POST['Zipcode']){
			$displayname = Input::get("Zipcode");
			$fields=array('Zipcode'=>$displayname);
			$validation->check($_POST,array('Zipcode' => array('display' => 'Zipcode','required' => true,'min' => 2,'max' => 9	)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Zipcode Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->Validity != $_POST['Validity']){
			$displayname = Input::get("Validity");
			$fields=array('Validity'=>$displayname);
			$validation->check($_POST,array('Validity' => array('display' => 'Validity','required' => true)));
			if($validation->passed()){ 
				$db->update('driver',$SearchId,$fields); $successes[] = "Validity Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->driverLicenseId != $_POST['driverLicenseId']){
			$displayname = Input::get("driverLicenseId");
			$fields=array('driverLicenseId'=>$displayname);
			$validation->check($_POST,array('driverLicenseId' => array('display' => 'driverLicenseId','required' => true)));
			if($validation->passed()){
				$db->update('driver',$SearchId,$fields); $successes[] = "DriverLicense Id Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($driverdetails->driverLicenseValidity != $_POST['driverLicenseValidity']){
			$displayname = Input::get("driverLicenseValidity");
			$fields=array('driverLicenseValidity'=>$displayname);
			$validation->check($_POST,array('driverLicenseValidity' => array('display' => 'driverLicenseValidity','required' => true)));
			if($validation->passed()){
				$db->update('driver',$SearchId,$fields); $successes[] = "DriverLicense validity Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		$db = DB::getInstance();
		$query =$db->query("
			SELECT driver.*,
			(SELECT count(pdc_damage.driver) FROM pdc_damage WHERE pdc_damage.driver=driver.tachoDriverIdentification AND pdc_damage.repairStatus=0) AS `DamageCount`
			FROM driver
			INNER JOIN FAMReport ON FAMReport.vin=driver.LastVehicle
			INNER JOIN customers ON customers.accountnumber=FAMReport.client  
			WHERE driver.id=".$SearchId);
		$driverdetails =$query->first();
	}
}
if ($driverdetails->Lastname!=''){
	$driverTitle=ucfirst($driverdetails->Lastname).", ".ucfirst($driverdetails->Surname);
} else {$driverTitle='...';}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
				    <div class="inner-pagina">
				    <div class="row">
				        <div class="col-12 page-title mr-auto">Resources - Driver - Details</div>
						<div class="col-12 col-xl-5">
							<div class="card shadow">
								<div class="card-header ">
									<div class="col-12">
										<div class="row py-1">
											<div class="info-box-number primary large" ><i class="fad  fa-id-card gray"></i> <?php echo $driverTitle ;?> </div>
											<div class="small gray ml-auto" ><i class="fas fa-clock"></i> <?php echo ucfirst($driverdetails->LastDriverActivity);?></div>

										</div>
									</div>
								</div>
								<div class="card-body ">
									<form class="form-signup" action="" method="POST" id="editvehicle-form">
										<div class="row p-0 pt-4">
											<div class="col-3 ">
												<label for="Surname">name</label>
												<input class="form-control has-value" type="text" name="Surname" id="Surname" onchange="checkValue('Surname');" placeholder="" value="<?=$driverdetails->Surname?>" required autofocus>
											</div>
											<div class="col-3 ">
											    <label for="Lastname">lastname</label>
												<input class="form-control has-value" type="text" name="Lastname" id="Lastname" onchange="checkValue('Lastname');" value="<?=$driverdetails->Lastname?>" >
											</div>
											<div class="col-6">
												<label for="eMail">lastname</label>
												<input class="form-control has-value" type="text" onchange="checkValue('eMail');" name="eMail" id="eMail" placeholder="" value="<?php echo ucfirst($driverdetails->eMail);?>" >
											</div>
										</div>
										<div class="row p-0 pt-4">
											<div class="col-6">
												<label  for="Phone_Home">Phone home</label>
												<input class="form-control" type="text" onchange="checkValue('Phone_Home');" name="Phone_Home" id="Phone_Home" placeholder="" value="<?php echo ucfirst($driverdetails->Phone_Home);?>" >
											</div>										
											<div class="col-6">
												<label>Phone Mobile </label>
												<input class="form-control" type="text" name="Phone_Mobile" id="Phone_Mobile" placeholder="" value="<?php echo ucfirst($driverdetails->Phone_Mobile);?>" >
											</div>										
											<div class="col-9">
												<label>Address  </label>
												<input class="form-control " type="text" name="Address" id="Address" placeholder="" value="<?php echo ucfirst($driverdetails->Address);?>" >
											</div>		
											<div class="col-3">
												<label>Housenumber  </label>
												<input class="form-control " type="text" name="Housenumber" id="Housenumber" placeholder="" value="<?php echo ucfirst($driverdetails->Housenumber);?>" >
											</div>
										</div>
										<div class="row p-0">
											<div class="col-3">
												<label>Zipcode  </label>
												<input class="form-control" type="text" name="Zipcode" id="Zipcode" placeholder="" value="<?php echo ucfirst($driverdetails->Zipcode);?>" >
											</div>
											<div class="col-9">
												<label>City  </label>
												<input class="form-control" type="text" name="City" id="City" placeholder="" value="<?php echo ucfirst($driverdetails->City);?>" >
											</div>
										</div>
                                        <div class="row p-0 pt-4">
                                            <div class="col-6 bg-default">
                                                <label>tachoDriverIdentification</label>
                                                <div class="form-control-ro primary"><?php echo ucfirst($driverdetails->tachoDriverIdentification);?> - <?php echo ucfirst($driverdetails->CCIndex);?> -  <?php echo ucfirst($driverdetails->CReplIndex);?> - <?php echo ucfirst($driverdetails->CRenewIndex);?></div>
                                            </div>
                                            <div class="col-6 bg-default">
                                                <label>Validity</label>
                                                <div class='input-group date ' id='datetimepicker1'>
                                                    <input type='text' class="form-control input-group text-left" value="<?php echo ucfirst($driverdetails->Validity);?>" name="Validity" placeholder="Validity" id='Validity' required />
                                                    <span class="input-group-addon btn ">
                                                        <span class="fad fa-calendar-alt"></span>
                                                    </span>
                                                </div>
                                            </div>
										</div>
										<div class="row p-0 pt-4">
                                            <div class="col-6 bg-default">
												<label>Driver License  </label>
												<input class="form-control" type="text" name="driverLicenseId" id="driverLicenseId" placeholder="" value="<?php echo ucfirst($driverdetails->driverLicenseId);?>" >
                                            </div>
                                            <div class="col-6 bg-default ">
                                                <label>License validity</label>
                                                <div class='input-group date ' id='datetimepicker1'>
                                                    <input type='text' class="form-control input-group text-left" value="<?php echo ucfirst($driverdetails->driverLicenseValidity);?>" name="driverLicenseValidity" placeholder="driverLicenseValidity" id='driverLicenseValidity' required />
                                                    <span class="input-group-addon btn ">
                                                        <span class="fad fa-calendar-alt"></span>
                                                    </span>
                                                </div>
                                            </div>
										</div>
										<div class="row ">
											<div class="col-12 pt-3 mr-auto"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
										</div>
                                    </div>
									<div class="card-footer d-flex">
                                        <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                        <?php if (checkMenu(2,$user->data()->id)){  //Links for permission level 2 (default admin) ?>
                                        <input class="btn btn-primary" type='submit' name='changedriver' value="Update / Save " />
                                        <?php }?>
                                        <a class="btn btn-secondary mx-2" href="drivers.php">cancel / return</a>
                                        <a class="btn btn-info mx-2 ml-auto disabled" aria-disabled="true" href="driverhistory.php">history report</a>
									</div>
								</form>
							</div>
						</div>
						<div class="col-12 col-md-7 ml-auto  ">
							<div class="row">
								<div class="col-12" id="#VehicleDetails">
								    <div class="nav-tabs-custom "  id="PDCTabs">
                                        <ul class="nav nav-tabs pl-3" >
                                            <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" onclick="GetDriveTime(<?=ucfirst($driverdetails->id)?>);" title="driving time"><i class="fad fa-user-clock" aria-hidden="true"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" onclick="ShowDriverActivity(<?=$SearchId?>);" title="trip activity"><i class="fad fa-route" aria-hidden="true"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB4" data-toggle="tab" onclick="ShowDriverRD(<?=$SearchId?>,false);" title="registered damages"><i class="fad fa-tools" aria-hidden="true"></i> <span class="badge badge-danger"><?=$driverdetails->DamageCount?></span></a></li>
                                        </ul>
                                        <div class="tab-content card">
                                            <div class="tab-pane active" id="PDCTAB1" role="tabpanel">
												<div class="card-body">
												    <div class="card-title m-0 mb-3">driving time</div>
													<table class="display table noWrap" id="DriveTimeTable"></table>
												</div>
                                            </div>                                          
                                            <div class="tab-pane" id="PDCTAB3" role="tabpanel">
												<div class="card-body">
												    <div class="card-title m-0 mb-3">trip activity <small>( & PDC)</small></div>
                                                    <table class="display table noWrap" id="tableDriverActivity"></table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB4" role="tabpanel">
												<div class="card-body">
												    <div class="card-title m-0 mb-3">registered damages</div>
                                                    <table class="display table noWrap" id="tableDriverDamages"></table>
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
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script>
	window.onload=function(){
		GetDriveTime(<?=ucfirst($driverdetails->id)?>);
	}
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
