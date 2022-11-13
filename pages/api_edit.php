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
if(!IdExists($SearchId,'api_collector')){
  Redirect::to("api_collector.php"); die();
}
$db = DB::getInstance();
		$query =$db->query("
			SELECT c.*,c.username AS APIUser, c.password AS APIPass, a.name as ApiType
			FROM api_collector c
				LEFT JOIN api_type a on a.id=c.typeId
			WHERE c.id=".$SearchId);
$apidetails =$query->first();

//Forms posted
if(!empty($_POST)) {

    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else
    {
        if ($apidetails->active != $_POST['API_Active']){
            $Active= isset($_POST['API_Active']) ? '1' : '0';
            $fields=array('active'=>$Active);
            $db->update('api_collector',$SearchId,$fields); $successes[] = "API Activation status Updated";
        }
		if ($apidetails->name != $_POST['name']){
			$displayname = Input::get("name");
			$fields=array('name'=>$displayname);
			$validation->check($_POST,array('name' => array('display' => 'name','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('api_collector',$SearchId,$fields); $successes[] = "Name Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($apidetails->type != $_POST['type']){
			$displayname = Input::get("type");
			$fields=array('type'=>$displayname);
		}
		if ($apidetails->apiCustomerName != $_POST['apiCustomerName']){
			$displayname = Input::get("apiCustomerName");
			$fields=array('apiCustomerName'=>$displayname);
			$validation->check($_POST,array('apiCustomerName' => array('display' => 'apiCustomerName','required' => true,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('api_collector',$SearchId,$fields); $successes[] = "apiCustomerName Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($apidetails->vendor != $_POST['vendor']){
			$displayname = Input::get("vendor");
			$fields=array('vendor'=>$displayname);
			$validation->check($_POST,array('vendor' => array('display' => 'vendor address','required' => false,'min' => 2,'max' => 50	)));
			if($validation->passed()){ 
				$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($apidetails->contactName != $_POST['contactName']){
			$displayname = Input::get("contactName");
			$fields=array('contactName'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor contact Updated";
		}
		if ($apidetails->contactemail != $_POST['contactemail']){
			$displayname = Input::get("contactemail");
			$fields=array('contactemail'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor contact email Updated";
		}
		if ($apidetails->contactPhone != $_POST['contactPhone']){
			$displayname = Input::get("contactPhone");
			$fields=array('contactPhone'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor contact phone Updated";
		}
		if ($apidetails->BaseUrl != $_POST['BaseUrl']){
			$displayname = Input::get("BaseUrl");
			$fields=array('BaseUrl'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor BaseUrl Updated";
		}
		if ($apidetails->auth_type != $_POST['auth_type']){
			$displayname = Input::get("auth_type");
			$fields=array('auth_type'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor authentication Updated";
		}
		if ($apidetails->APIUser != $_POST['apiuser']){
			$displayname = Input::get("apiuser");
			$fields=array('username'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor authentication username Updated";
		}
		if ($apidetails->APIPass != $_POST['apipass']){
			$displayname = Input::get("apipass");
			$fields=array('password'=>$displayname);
			$db->update('api_collector',$SearchId,$fields); $successes[] = "vendor authentication password Updated";
		}
		$db = DB::getInstance();
		$query =$db->query("SELECT c.*,c.username AS APIUser, c.password AS APIPass, a.name as ApiType	FROM api_collector c LEFT JOIN api_type a on a.id=c.typeId	WHERE c.id=".$SearchId);
		$apidetails =$query->first();
	}
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class=" inner-pagina">
				    <div class="page-title pb-2">Tools - API-Collector - Details</div>
					<div class="row">
						<div class="col-12 col-md-6 col-xl-5">
							<div class="card shadow">
								<div class="card-header ">
									<div class="row">
										<div class="col-12 col-lg-12 pb-3">
											<div class="info-box-number primary" ><?php echo ucfirst($apidetails->name);?> </div> 
										</div>												
										<div class="col-6 ">
											<div class=" " ><i class="far fa-fw fa-building" aria-hidden="true"></i> <?php echo ucfirst($apidetails->vendor);?></div>
										</div>										
									</div>
								</div>
								<div class="card-body ">
									<form class="form-signup col-12" action="" method="POST" id="editvehicle-form">
										<div class="row ">
											<div class="col-6  p-1">
												<label>Name</label>
												<input class="form-control" type="text" name="name" id="name" placeholder="" value="<?php echo ucfirst($apidetails->name);?>" required autofocus>
											</div>
											<div class="col-3  p-1">
												<label>Interface type</label>
												<input class="form-control" type="text" name="type" id="type" placeholder="" value="<?php echo ucfirst($apidetails->ApiType);?>" >
											</div>
											<div class="p-1 ml-auto text-right">
											    <label>Interface Active</label>
											    <div class="custom-control custom-switch custom-switch-sm">
                                                     <input type="checkbox" id="API_Active" name="API_Active" class="custom-control-input" <?php echo ($apidetails->active==1 ? 'checked' : '');?>>
                                                     <label class="custom-control-label" for="API_Active"></label>
                                                </div>
											</div>
											<div class="col-6  p-1 ">
												<label>Vendor</label>
												<input class="form-control" type="text" name="vendor" id="vendor" placeholder="" value="<?php echo ucfirst($apidetails->vendor);?>" >
											</div>
											<div class="col-6  p-1">
												<label>API Customername</label>
												<input class="form-control" type="text" name="apiCustomerName" id="apiCustomerName" placeholder="" value="<?php echo ucfirst($apidetails->apiCustomerName);?>" >
											</div>
											<div class="col-4 p-1">
												<label>Contact Name  </label>
												<input class="form-control" type="text" name="contactName" id="contactName" placeholder="" value="<?php echo ucfirst($apidetails->contactName);?>" >
											</div>										
											<div class="col-4  p-1">
												<label>Contact email </label>
												<input class="form-control" type="text" name="contactemail" id="contactemail" placeholder="" value="<?php echo ucfirst($apidetails->contactemail);?>" >
											</div>										
											<div class="col-4  p-1">
												<label>Contact Phone  </label>
												<input class="form-control " type="text" name="contactPhone" id="contactPhone" placeholder="" value="<?php echo ucfirst($apidetails->contactPhone);?>" >
											</div>		
											<div class="col-8 p-1">
												<label>BaseUrl  </label>
												<input class="form-control " type="text" name="BaseUrl" id="BaseUrl" placeholder="" value="<?php echo ucfirst($apidetails->BaseUrl);?>" >
											</div>		
											<div class="col-4 p-1">
												<label>Authentication  </label>
												<input class="form-control " type="text" name="auth_type" id="auth_type" placeholder="" value="<?php echo ucfirst($apidetails->auth_type);?>" >
											</div>										
											<div class="col-6  p-1">
												<label>Username/Client</label>
												<input class="form-control" type="text" name="apiuser" id="apiuser" placeholder="" value="<?php echo ucfirst($apidetails->APIUser);?>" >
											</div>	
											<div class="col-6 p-1">
												<label>Password/ClientId </label>
												<input class="form-control" type="password" name="apipass" id="apipass" placeholder="" value="<?php echo ucfirst($apidetails->APIPass);?>" >
											</div>	
										</div>
										<div class="row pt-2">
											<div class="col-12 p-0 pt-3 mr-auto"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
										</div>
										<div class="row py-4">
											<input type="hidden" value="<?=Token::generate();?>" name="csrf">
											<input class="btn btn-primary" type='submit' name='changeapi' value="Update / Save " />
											<a class="btn btn-secondary mx-2" href="api_collector.php">cancel / return</a>
										</div>										
									</form>	
								</div>
							</div>
						</div>
						<div class="col-12 col-md-7 ml-auto">
							<div class="row">
								<div class="col-12" id="#apidetails">
									<div class="card border accordion " id="vaccordion" role="tablist">
										<div class="_card">
											<div class="card-header" id="headingOne">
												<a data-toggle="collapse" data-target="#collapseOne" data-parent="#vaccordion" aria-expanded="true" aria-controls="collapseOne">
													<div class="subtitle">scheduler</div>
												</a>
											</div>
											<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#vaccordion">
												<div class="card-body" >
													<table class="display table noWrap" id="APIScheduleTable"></table>
												</div>
											</div>
										</div>
										<div class="_card">
											<div class="card-header" id="headingFour">
												<a class="" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" data-parent="#vaccordion" aria-controls="collapseFour">
												  <div class="subtitle">interface monitoring</div>
												</a>
											</div>
											<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#vaccordion">
                                                <div class="col-12">
                                                    <input type='text' class="hide form-control input-group text-left" value="<?php echo date('Y-m-d H:i',strtotime('now'))?>" id='SelectDate' />
                                                    <select id="SelectHours" class="hide col-12 form-control" name="SelectedG" onchange="LoadMonitorData();" title="uren voor geslecteerde einddatum" >
                                                        <option value="1"  >1 hour</option>
                                                        <option value="2"  >2 hours</option>
                                                        <option value="4"  >4 hours</option>
                                                        <option value="6"  >6 hours</option>
                                                        <option value="12">12 hours</option>
                                                        <option value="24" selected >24 hours</option>
                                                        <option value="48">48 hours</option>
                                                        <option value="168">1 week</option>
                                                        <option value="336">2 weeks</option>
                                                        <option value="672">4 weeks</option>
                                                    </select>
                                                    <div class="nav-tabs-custom"  id="MonitorBuilder"></div>
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
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
<script src="<?=$us_url_root?>plugins/moment/moment.min.js"></script>
<script src="<?=$us_url_root?>js/monitoring2.js"></script>
	<script>
		window.onload=function(){
			ShowAPIScheduler(<?php echo '"'.ucfirst($apidetails->id).'"';?>);
			ShowMonitor(<?php echo '"'.ucfirst($apidetails->id).'"';?>);
		}
	</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
