<?php
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$validation = new Validate();
//PHP Goes Here!
$errors = [];
$successes = [];
$userId = Input::get('id');
//Check if selected user exists
if(!userIdExists($userId)){
  Redirect::to("../pages/users.php"); die();
}
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    }else {			
		if (!empty($_POST['delete'])){
			$db = DB::getInstance();
			$i = 0;
			$query1 = $db->query("DELETE FROM users WHERE id ='".$userId."'");
			$query2 = $db->query("DELETE FROM user_permission_matches WHERE user_id ='".$userId."'");
			$query3 = $db->query("DELETE FROM profiles WHERE user_id ='".$userId."'");
		}
		Redirect::to("../pages/users.php"); die();
	} 
}
$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

$grav = get_gravatar(strtolower(trim($userdetails->email)));
$useravatar = '<img src="'.$grav.'" class="img-responsive img-thumbnail" alt="">';
//
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="page-title-secondary ">
					<div class="col-12">
						<div class="row">
							<div class="mr-auto page-title ">User settings</div>
						</div>
					</div>
				</div>
				<div class="pagina pt-3">
					<div class="col-12">
						<div class="row">
							<div class="col-2 col-md-2">
								<?php echo $useravatar;?></br>
								<small>
								<label>User ID: </label> <?=$userdetails->id?><br/>
								<label>Joined: </label> <?=$userdetails->join_date?><br/>
								<label>Last seen: </label> <?=$userdetails->last_login?><br/>
								<label>Logins: </label> <?=$userdetails->logins?><br/>
								<label>Group</label> <?=$userdetails->cust_id?><br/>
								</small>
							</div><!--/col-2-->
							<div class="col-10">
								
									<div class="row">							
										<div class="card col-12 col-md-6">
											<div class="card-title"><h3>User Information</h3></div>
											<div class="card-body ">
												<div class="row px-2">
													<div class="form-group col-6 p-1">
														<label>Username:</label>
														<input  class='form-control' type='text' name='username' value='<?=$userdetails->username?>' />
													</div>
												</div>
												<div class="row px-2">
													<div class="form-group col-6 p-1">
														<label>First Name:</label>
														<input  class='form-control' type='text' name='fname' value='<?=$userdetails->fname?>' />
													</div>
													<div class="form-group col-6 p-1">														
														<label>Last Name:</label>
														<input  class='form-control ' type='text' name='lname' value='<?=$userdetails->lname?>' />
													</div>
												</div>	
												<div class="row px-2">
													<div class="form-group col-6 p-1">
														<label>Email:</label>
														<input class='form-control' type='text' name='email' value='<?=$userdetails->email?>' />
													</div>
												</div>
												<div class="row px-2">
													<div class="form-group col-6 p-1">
														<label>New Password (<?=$settings->min_pw?> char min, <?=$settings->max_pw?> max.)</label>
														<input class='form-control' type='password' name='password' />
													</div>
													<div class="form-group col-6 p-1">
														<label>Confirm Password</label>
														<input class='form-control' type='password' name='confirm' />
													</div>
												</div>
											</div>
										</div>
										<div class="card col-12 col-md-6">
											<div class="card-title"><h3>Permissions</h3></div>
											<div class="card-body p-0">
												<div class="row ">
													<div class="col-6 p-0">
														<div class="card-title">Remove These Permission(s):</div>
														<div class="card-body border">
														<?php
														//NEW List of permission levels user is apart of

														$perm_ids = [];
														foreach($userPermission as $perm){
															$perm_ids[] = $perm->permission_id;
														}

														foreach ($permissionData as $v1){
														if(in_array($v1->id,$perm_ids)){ ?>
														<div class='col-12'>
														  <input type='checkbox'  name='removePermission[]' id='removePermission[]' value='<?=$v1->id;?>' /> <?=$v1->name;?>
														</div>
														<?php
														}
														}
														?>
														</div>
													</div>
													<div class="col-6 p-0">
														<div class="card-title">Add These Permission(s):</div>
														<div class="card-body border">
														<?php
														foreach ($permissionData as $v1){
														if(!in_array($v1->id,$perm_ids)){ ?>
														<div class='col-12'>
														  <input type='checkbox' name='addPermission[]' id='addPermission[]' value='<?=$v1->id;?>' /> <?=$v1->name;?>
														</div>
															<?php
														}
														}
														?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-6 p-0">
														<div class="card-title">Miscellaneous </div>
														<div class="card-body border">
															<div class="row">
																<label class="col-6"> Block?:</label>
																<select name="active" class="form-control col-6">
																	<option <?php if ($userdetails->permissions==1){echo "selected='selected'";} ?> value="1">No</option>
																	<option <?php if ($userdetails->permissions==0){echo "selected='selected'";} ?>value="0">Yes</option>
																</select>
															</div>
														</div>
													</div>
													<div class="col-12 p-0">
														<div class="card-title">Group </div>
														<div class="card-body border">
															<select id="group" class="form-control" name="cust_id" value="<?=ucfirst($row->accountnumber)?>" >
														<?php if (sizeof($Group)>1){ ?>
																<option class="DeActiveGroup" value="0">All groups</option>
																<option class="DeActiveGroup" value='-' disabled ><b>- - - - - - - - - - - - - - - - - - - - -</option>
														<?php 	}?>
														<?php foreach ($Group as $row){
																	if ($row->accountnumber==ucfirst($userdetails->cust_id )){ ?>
																<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" selected > <?php echo ucfirst($row->name);?></option>	
														<?php } else {?>
																<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" > <?php echo ucfirst($row->name);?></option>	
															<?php }?> 
														<?php }?> 
															</select>
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
				<div class="modal fade" id="DeleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
								<form class="form" name='adminUser' action='admin_user_delete.php?id=<?=$userId?>' method='post'>	
									<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
									<input class="btn btn-primary submit" type="submit" name="delete" value='Delete' />
									<a class="btn btn-secondary mx-2" href="../pages/users.php">Cancel / return to overview</a>									
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
        $('#DeleteUserModal').modal('show');
    });
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
