<?php 
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();}
$validation = new Validate();
//PHP Goes Here!

$errors = [];
$successes = [];

//Forms posted
if(!empty($_POST))
{
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }

  //Delete permission levels
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
  }

  //Create new permission level
  if(!empty($_POST['name'])) {
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
          $db->insert('permissions',$fields);
          echo "Permission Updated";

  }else{

    }
  }
}


$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels
$count = 0;
// dump($permissionData);
// echo $permissionData[0]->name;
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
					<div class="page-title pb-2">Admin - Manage Permissions</div>
					<div class="row">
						<div class="col-12">
							<div id="form-errors">
							<?php $errors = [];$successes = [];	echo resultBlock($errors,$successes);?>
							</div>
							<form name='adminPermissions' action='<?=$_SERVER['PHP_SELF']?>' method='post'>
								<div class="row">										
									<div class="col-4">
										<div class="card  shadow-sm">
											<div class="card-header ">Create a new permission group</div>
											<div class="card-body">
												<label>new Permission Name:</label>
												<input type='text' class="form-control" name='name' />
											</div>
										</div>
									</div>	
									<div class="col-4">		
										<div class="card shadow-sm">
											<div class="card-header">Current permission groups</div>
											<div class="card-body">
												<span>Delete   -   Permission Name</span>
												<?php
												//List each permission level
												foreach ($permissionData as $v1) {
												  ?>
												<div class="col-12">
													<label>
													    <?php if ($permissionData[$count]->system==0){ ?>
														<input type='checkbox' name='delete[<?=$permissionData[$count]->id?>]' id='delete[<?=$permissionData[$count]->id?>]' value='<?=$permissionData[$count]->id?>'>
                                                        <?php } else { ?>
                                                        <input type='checkbox' name='delete[<?=$permissionData[$count]->id?>]' id='delete[<?=$permissionData[$count]->id?>]'  value='<?=$permissionData[$count]->id?>' disabled>
                                                        <?php }?>
														<span><a href='admin_permission.php?id=<?=$permissionData[$count]->id?>'> - <?=$permissionData[$count]->name?> </a></span>
													</label>
												</div>
												  <?php
												  $count++;
												}
												?>

											</div>
										</div>
									</div>
									<div class="col-12">
										<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
										<input class='btn btn-primary' type='submit' name='Submit' value='Add/Update/Delete' />
										<a href="admin.php" class="btn btn-secondary" >return to Page Admin</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="js/search.js" charset="utf-8"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
