<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} 
//PHP Goes Here!
$errors = [];
$successes = [];
$validation = new Validate();

$db = DB::getInstance();
$QNV =$db->query("SELECT t1.vin FROM vehicles t1 LEFT JOIN famreport t2 ON t1.vin = t2.vin WHERE t2.vin IS NULL");
$QUC =$db->query("SELECT t1.vin,t2.`client` FROM vehicles t1 LEFT JOIN famreport t2 ON t1.vin = t2.vin LEFT JOIN customers t3 ON t2.`client`= t3.accountnumber WHERE t3.accountnumber IS NULL");
$RogueCount = $QNV->count();
$Vehicles = $QNV->results();
$VehiclesUC= $QUC->results();



//Forms posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
        die('Token doesn\'t match!');
    }else {

if ($QNV->count()>0){
    echo "\n<BR><h4>".$RogueCount." new vehicles being added to standard group :</h4>" ;
    $count=0;
    $Q1="INSERT ignore into customer (accountnumber,name) values ('_Default','1_Standard Group')";
    $db->query($Q1);
    foreach ($Vehicles as $val){
  //      $Q1="INSERT into famreport (vin,client) values ('".$val->vin."','_Default');";
        echo ".";
        $db->query($Q1);
        $count++;
    }
}
if ($QUC->count()>0){
    $count=0;
    echo "\n<BR><h4>".$QUC->count()." vehicles related to an unknown group being added to standard group </h4><BR><div class='col-12 adminpad ' id='VehicleDetails'>";
 //   $db->query("INSERT ignore into customer (accountnumber,name) values ('_Default','1_Standard Group')");
    foreach ($VehiclesUC as $val){
        $Q1="&nbsp;&nbsp;&nbsp;UPDATE famreport SET client='' WHERE VIN='".$val->vin."';";
        echo $Q1."</br>";
        $db->query($Q1);
        $count++;
    }
}
	}
}
echo "</div></div>"
?>

	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="page-title-secondary ">
					<div class="col-12">
						<div class="row">
							<div class="mr-auto page-title ">Find rogue vehicles <small>(with no group relation)</small></div>
						</div>
					</div>
				</div>
				<div class="pagina p-3 ">
					<div class="row">
						<div class="col-12 col-md-5 pt-2">
							<div class="card VehicleDetails shadow col-12 mb-3">
								<div class="card-header VehicleTile">
									<div class="row pb-3">
										<div class="col-6 info-box-number primary large"><?=$RogueCount;?> </div>
									</div>
								</div>
								<div class="card-body ">
									<form class="form-signup col-12" action="GetRoqueVehicles.php?=<?=$SearchId?>" method="POST" id="rogue-form">
										<div class="row ">
											<div class="col-12 p-1"> By agreeing to process all rogue vehicles will be added to group '<b>_Default</b>'	</div>
										</div>
										<div class="row pt-2">
											<div class="col-12 p-0 pt-3 mr-auto"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
										</div>
										<div class="row py-4">
											<input type="hidden" value="<?=Token::generate();?>" name="csrf">
											<input class="btn btn-primary" type='submit' name='ProcessRogue' value="Process vehicles" />
											<a class="btn btn-secondary mx-2 ml-auto" href="#">Cancel / return to overview</a>
										</div>										
									</form>	
								</div>
							

<?php

?>