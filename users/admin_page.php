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
//PHP Goes Here!
$pageId = Input::get('id');
$errors = [];
$successes = [];

//Check if selected pages exist
if(!pageIdExists($pageId)){
  Redirect::to("admin_pages.php"); die();
}

$pageDetails = fetchPageDetails($pageId); //Fetch information specific to page


//Forms posted
if(Input::exists()){
	$token = Input::get('csrf');
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
	$update = 0;

	if(!empty($_POST['private'])){
		$private = Input::get('private');
	}

	//Toggle private page setting
	if (isset($private) AND $private == 'Yes'){
		if ($pageDetails->private == 0){
			if (updatePrivate($pageId, 1)){
				$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("private"));
			}else{
				$errors[] = lang("SQL_ERROR");
			}
		}
	}elseif ($pageDetails->private == 1){
		if (updatePrivate($pageId, 0)){
			$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("public"));
		}else{
			$errors[] = lang("SQL_ERROR");
		}
	}

	//Remove permission level(s) access to page
	if(!empty($_POST['removePermission'])){
		$remove = $_POST['removePermission'];
		if ($deletion_count = removePage($pageId, $remove)){
			$successes[] = lang("PAGE_ACCESS_REMOVED", array($deletion_count));
		}else{
			$errors[] = lang("SQL_ERROR");
		}
	}

	//Add permission level(s) access to page
	if(!empty($_POST['addPermission'])){
		$add = $_POST['addPermission'];
		$addition_count = 0;
		foreach($add as $perm_id){
			if(addPage($pageId, $perm_id)){
				$addition_count++;
			}
		}
		if ($addition_count > 0 ){
			$successes[] = lang("PAGE_ACCESS_ADDED", array($addition_count));
		}
	}
	$pageDetails = fetchPageDetails($pageId);
}
$pagePermissions = fetchPagePermissions($pageId);
$permissionData = fetchAllPermissions();
?>

	<main role="main"  >
		<section class="section ">
			<div class="container-fluid">
				<div class="pagina inner-pagina">
				    <div class="col-12 mr-auto page-title ">Page Permissions <small>for (<b><?=$pageDetails->page;?></b>)</small></div>
					<div class="col-10 pt-3">
						<form name='adminPage' action='' method='post'>
							<div class="row">	
								<input type='hidden' name='process' value='1'>
								<div class="col-12 col-md-2">
									<div class="card shadow-sm">
										<div class="card-header"><strong>Public or Private?</strong></div>
										<div class="card-body">
											<div class="form-group">
												<?php $checked = ($pageDetails->private == 1)? ' checked' : ''; ?>
											<!--	<input type='checkbox' class="text-primary" name='private' id='private' value='Yes' <?=$checked;?> ><span class="primary"> Private</span></label>-->
												<div class="custom-control custom-switch custom-switch-sm">
													<input type='checkbox' class="text-primary custom-control-input" id='private' name='private'  value='Yes'<?=$checked;?>>
													<label class="custom-control-label secondary" for="private"> Private </label>
												</div>
                                            </div>
										</div>
									</div>
								</div>							
								<div class="col-12 col-md-3">
									<div class="card shadow-sm">
										<div class="card-header"><strong>Current Access</strong></div>
										<div class="card-body">
											<div class="form-group">
											<?php
											$perm_ids = [];
											foreach($pagePermissions as $perm){
												$perm_ids[] = $perm->permission_id;
											}
											foreach ($permissionData as $v1){
												if(in_array($v1->id,$perm_ids)){ ?>
												<label><input type='checkbox' name='removePermission[]' id='removePermission[]' value='<?=$v1->id;?>'> <span class="primary"><?=$v1->name;?></span></label><br/>
												<?php }} ?>
											</div>
										</div>
									</div>
								</div>						
								<div class="col-12 col-md-3">
									<div class="card shadow-sm">
										<div class="card-header d-flex">
										    <div class="col-12 p-0"><strong>Optional Access Level</strong></div>
										</div>
										<div class="card-body">
											<div class="form-group">
											<?php
											//Display list of permission levels without access
											foreach ($permissionData as $v1){
											if(!in_array($v1->id,$perm_ids)){ ?>
											    <div class="col-12">
												    <label>
                                                        <input type='checkbox' name='addPermission[]' id='addPermission[]' value='<?=$v1->id;?>'>
                                                        <span class="primary"><?=$v1->name;?><span>
												    </label>
												</div>
											<?php }} ?>
											</div>
                                            <div class="ml-auto"><a href="admin_permissions.php" class="btn btn-secondary " >Create new level </a></div>
										</div>
									</div>
								</div>			
							</div>	
							<div class="row pt-5">
								<div class="col-12 col-md-4">
									<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
									<input class='btn btn-primary' type='submit' value='Update' class='submit' />
									<a href="<?=$us_url_root?>users/admin_pages.php" class="btn btn-secondary" >return to Page Admin</a>
								</div>
							</div>
							<div class="row pt-2">
								<div class="col-6">
									<?php resultBlock($errors,$successes); ?>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>	
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
