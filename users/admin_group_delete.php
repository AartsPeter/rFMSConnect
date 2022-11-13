<?php
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$validation = new Validate();
//PHP Goes Here!
$errors = [];
$successes = [];
$groupId = Input::get('id');
//Check if selected user exists
if(!groupIdExists($groupId)){
  Redirect::to("admin_groups.php"); die();
}
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    }else {			
		if (!empty($_POST['delete'])){
			$db = DB::getInstance();
			$query1 = $db->query("DELETE FROM customers WHERE id ='".$groupId."'");
		}
		Redirect::to("admin_groups.php"); die();
	} 
}
$db = DB::getInstance();
$query =$db->query("SELECT * FROM customers WHERE id='".$groupId."'");
$groupdetails =$query->first();

$DealerQ = $db->query("SELECT name, location FROM dealers_daf ORDER BY name");
$Dealer = $DealerQ->results();
$CountriesQ = $db->query("SELECT * FROM countries ORDER BY name");
$Countries = $CountriesQ->results();
//
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="page-title-secondary ">
					<div class="col-12">
						<div class="row">
							<div class="mr-auto page-title ">Group details</div>
						</div>
					</div>
				</div>
				<div class="pagina pt-3 ">
					<div class="col-12">
						<div class="row">
							<div class="col-10">	
									<div class="row">							
										<div class="card col-12 col-lg-8">
											<div class="page-title">Group Information</div>
											<div class="card-body ">
												<div class="row px-2">
													<div class="form-group col-8 p-1">
														<label>Group Name:</label>
														<input  class='form-control' type='text' name='name' value='<?=$groupdetails->name?>' />
													</div>
													<div class="form-group col-2 ml-auto p-1">
														<label>Code:</label>
														<input  class='form-control' type='text' name='accountnumber' value='<?=$groupdetails->accountnumber?>' />
													</div>
												</div>
												<div class="row px-2">		
													<div class="form-group col-6 p-1">														
														<label>Country:</label>
												<?php foreach ($Countries as $row){ 
														if ($row->name==$groupdetails->Country){ ?>
														<div class="form-control"> <?php echo ucfirst($row->name);?></div>	
													<?php }?> 
												<?php }?> 
													</div>
												</div>
												<div class="row px-2">
													<div class="form-group col-6 p-1">
														<label>Home Dealer:</label>
													<?php foreach ($Dealer as $row){ 
															if ($row->name==$groupdetails->Service_Homedealer){ ?>
														<div class="form-control"> <?php echo ucfirst($row->name);?></div>	
													<?php }?> 
													<?php }?> 
														</select>
													</div>
													<div class="form-group col-6 p-1">
														<label>Is Dealer:</label>
												<?php foreach ($Dealer as $row1){ 
													if ($row1->location==$groupdetails->IsDealer){ ?>
														<div class="form-control"> <?php echo ucfirst($row1->name);?></div>	
													<?php } ?>
												<?php }?> 
													</div>
												</div>												
											</div>
										</div>
									</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="DeleteGroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Delete User</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<form class="form" name='adminUser' action='admin_group_delete.php?id=<?=$groupId?>' method='post'>	
									<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
									<input class="btn btn-primary submit" type="submit" name="delete" value='Delete' />
									<a class="btn btn-secondary mx-2" href="admin_groups.php">Cancel / return to overview</a>									
								</form>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#DeleteGroupModal').modal('show');
    });
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
