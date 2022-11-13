<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>
<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$validation = new Validate();
//PHP Goes Here!
$permissionId = $_GET['id'];

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
Redirect::to("admin_permissions.php"); die();
}

//Fetch information specific to permission level
$permissionDetails = fetchPermissionDetails($permissionId);
//Forms posted
if(!empty($_POST)){
  $token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}

  //Delete selected permission level
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
      Redirect::to('admin_permissions.php');
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }
  else
  {
    //Update permission level name
    if($permissionDetails['name'] != $_POST['name']) {
      $permission = Input::get('name');
      $fields=array('name'=>$permission);
//NEW Validations
    $validation->check($_POST,array(
      'name' => array(
        'display' => 'Permission Name',
        'required' => true,
        'unique' => 'permissions',
        'min' => 1,
        'max' => 25
      )
    ));
    if($validation->passed()){
      $db->update('permissions',$permissionId,$fields);

    }else{
        }
      }

    //Remove access to pages
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($permissionId, $remove)) {
        $successes[] = lang("PERMISSION_REMOVE_USERS", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($permissionId, $add)) {
        $successes[] = lang("PERMISSION_ADD_USERS", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Remove access to pages
    if(!empty($_POST['removePage'])){
      $remove = $_POST['removePage'];
      if ($deletion_count = removePage($remove, $permissionId)) {
        $successes[] = lang("PERMISSION_REMOVE_PAGES", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPage'])){
      $add = $_POST['addPage'];
      if ($addition_count = addPage($add, $permissionId)) {
        $successes[] = lang("PERMISSION_ADD_PAGES", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    $permissionDetails = fetchPermissionDetails($permissionId);
  }
}

//Retrieve list of accessible pages
$pagePermissions = fetchPermissionPages($permissionId);




  //Retrieve list of users with membership
$permissionUsers = fetchPermissionUsers($permissionId);
// dump($permissionUsers);

//Fetch all users
$userData = fetchAllUsers();


//Fetch all pages
$pageData = fetchAllPages();

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
					<div class="mr-auto page-title" ><a href="../users/admin.php">Admin Dashboard</a> / Permissions  <small>Configure Permission for role : <b><?=$permissionDetails['name']?></b></small></div>
					<div class="col-12 pt-3">
						<div id="form-errors">
							<form name='adminPermission' action='<?=$_SERVER['PHP_SELF']?>?id=<?=$permissionId?>' method='post'>
								<div class="row ">
									<div class="col-6">
										<div class="card admincard col-6">
											<div class="card-header">Permission Information</div>
											<div class="card-body">
												<div class="row">
													<div class="col-6">
														<label class="">Name </label>
														<input type='text' name='name' class="form-control" value='<?=$permissionDetails['name']?>' />
													</div>
													<div class="col-6">
														<label  class="">Delete:</label>
														<input type='checkbox' name='delete[<?=$permissionDetails['id']?>]' id='delete[<?=$permissionDetails['id']?>]' value='<?=$permissionDetails['id']?>'><span></span>
													</div>
												</div>
											</div>
										</div>
										<div class="card admincard col-12">
											<div class="card-header">Permission Membership</div>
											<div class="card-body">	
												<div class="row pt-2">
													<div class="col-6"><b>Remove Members:</b>
														<div class="admincard-body border">	
														<?php		//Display list of permission levels with access
														$perm_users = [];foreach($permissionUsers as $perm){ $perm_users[] = $perm->user_id;}
														foreach ($userData as $v1){
															if(in_array($v1->id,$perm_users)){ ?>
															<div class="col-12"><input type='checkbox' name='removePermission[]' id='removePermission[]' value='<?=$v1->id;?>'><span> </span><?=$v1->username;?></div><?php
															}
														}	?>
														</div>
													</div>
													<div class="col-6"><b>Add Members:</b>
														<div class="admincard-body border">	
														<?php 		//List users without permission level
														$perm_losers = [];	foreach($permissionUsers as $perm){ $perm_losers[] = $perm->user_id;}
														foreach ($userData as $v1){
															if(!in_array($v1->id,$perm_losers)){ ?>
															<div class="col-12"><input type='checkbox' name='addPermission[]' id='addPermission[]' value='<?=$v1->id?>'> <span></span><?=$v1->username;?></div><?php
														}}?>
														</div>
													</div>
												</div>
											</div>
										</div>
							<?=$validation->display_errors();?>
							<?php
							$errors = [];
							$successes = [];
							echo resultBlock($errors,$successes);
							?>
									</div>
									
									<div class="col-6">
										<div class="card admincard shadow">
											<div class="card-header">Permission Access</div>
											<div class="card-body ">	
												<div class="row pt-2">
													<div class="col-6 "><b>Remove Access From This Level</b>
														<div class="admincard-body border ">
														<?php		//Display list of pages with this access level
														$page_ids = [];	foreach($pagePermissions as $pp){ $page_ids[] = $pp->page_id;}
														foreach ($pageData as $v1){
														  if(in_array($v1->id,$page_ids)){ ?>
															<div class="col-12"><label><input type='checkbox' name='removePage[]' id='removePage[]' value='<?=$v1->id;?>'><span> <?=$v1->page;?></span></label></div>
														<?php }}  ?>  
														</div>
													</div>
													<div class="col-6 "><b>Add Access To This Level</b>
														<div class="admincard-body border">
													<?php 	//Display list of pages with this access level
													foreach ($pageData as $v1){
														if(!in_array($v1->id,$page_ids) && $v1->private == 1){ ?>
														<div class="col-12"><label><input type='checkbox' name='addPage[]' id='addPage[]' value='<?=$v1->id;?>'><span> <?=$v1->page;?></span></label></div>
												  <?php }
													}  ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
								<input class='btn btn-primary' type='submit' value='Update Permission' class='submit' />
								<a href="../users/admin_permissions.php"><button type="submit" class="btn btn-secondary" >return to Page Admin</button></a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>



      </div>
    </div>
	</div>
	</div>

    <!-- /.row -->
    <!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
