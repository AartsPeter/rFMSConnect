<?php 
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$errors = $successes = [];
$form_valid=TRUE;
$permOpsQ = $db->query("SELECT * FROM permissions");
$permOps = $permOpsQ->results();
// dnd($permOps);

//Forms posted
if (!empty($_POST)) {
  //Delete User Checkboxes
  if (!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)){ $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count)); }
    else {$errors[] = lang("SQL_ERROR"); }
  }
  //Manually Add User
  if(!empty($_POST['addUser'])) {
    $join_date = date("Y-m-d H:i:s");
 //   $username = Input::get('username');
 // 	$fname = Input::get('fname');
 // 	$lname = Input::get('lname');
 //		$email = Input::get('email');
    $token = $_POST['csrf'];

    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }

    $form_valid=FALSE; // assume the worst
    $validation = new Validate();
    $validation->check($_POST,array(
      'username' => array(
      'display' => 'Username',
      'required' => true,
      'min' => $settings->min_un,
      'max' => $settings->max_un,
      'unique' => 'users',
      ),
      'fname' => array(
      'display' => 'First Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
      'lname' => array(
      'display' => 'Last Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
      'email' => array(
      'display' => 'Email',
      'required' => true,
      'valid_email' => true,
      'unique' => 'users',
      ),
      'password' => array(
      'display' => 'Password',
      'required' => true,
      'min' => $settings->min_pw,
      'max' => $settings->max_pw,
      ),
      'confirm' => array(
      'display' => 'Confirm Password',
      'required' => true,
      'matches' => 'password',
      ),
    ));
  	if($validation->passed()) {
		$form_valid=TRUE;
      try {
        // echo "Trying to create user";
        $fields=array(
          'username' => Input::get('username'),
          'fname' => Input::get('fname'),
          'lname' => Input::get('lname'),
          'email' => Input::get('email'),
          'password' =>
          password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
          'permissions' => 1,
          'account_owner' => 1,
          'stripe_cust_id' => '',
          'join_date' => $join_date,
          'company' => Input::get('company'),
          'email_verified' => 1,
          'active' => 1,
          'vericode' => 111111,
        );
        $db->insert('users',$fields);
        $theNewId=$db->lastId();
        // bold($theNewId);
        $perm = Input::get('perm');
        $addNewPermission = array('user_id' => $theNewId, 'permission_id' => $perm);
        $db->insert('user_permission_matches',$addNewPermission);
        $db->insert('profiles',['user_id'=>$theNewId, 'bio'=>'This is your bio']);

        if($perm != 1){
          $addNewPermission2 = array('user_id' => $theNewId, 'permission_id' => 1);
          $db->insert('user_permission_matches',$addNewPermission2);
        }

        $successes[] = lang("ACCOUNT_USER_ADDED");

      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }
}
$userData = fetchAllUsers(); //Fetch information for all users
?>

	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="page-title-secondary ">
					<div class="col-12">
						<div class="row">
							<div class="mr-auto page-title "><a href="admin.php">Admin Dashboard</a> - <small>Manage Users</small></div>
						</div>
					</div>
				</div>
				<div class="pagina pt-3">	
				    <div class="col-12 col-md-4">
						<?php echo resultBlock($errors,$successes);	?>
					   <?php if (!$form_valid && Input::exists()){echo display_errors($validation->errors());}   ?>						
					</div>	
					<div class="col-md-12">
						<div class="card-title">All users</div>
						<div class="card-body">
							<form name="adminUsers" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
								<table id="tableAdminUsers" class="table display responsive pageResize noWrap">
									<thead class='pb-2'>
										<tr>
										<th ></th>
										<th >Username</th>
										<th >Email</th>
										<th >First Name</th>
										<th >Last Name</th>
										<th >Customer</th>	
										<th >Stylesheet</th>
										<th ># logins</th>
										<th >Last Sign In</th>
										</tr>
									</thead>
									<tbody>
									<?php
									//Cycle through users
									foreach ($userData as $v1) {	?>
										<tr>
										<td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
										<td><a href='admin_user.php?id=<?=$v1->id?>'><?=$v1->username?></a></td>
										<td ><?=$v1->email?></td>
										<td><?=$v1->fname?></td>
										<td><?=$v1->lname?></td>
										<td><?=$v1->cust_id?></td>
										<td><?=$v1->ucss?></td>
										<td><?=$v1->logins?></td>
										<td><?=$v1->last_login?></td>
										</tr>
											<?php } ?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-2"><input class="btn btn-danger" type="submit" name="Submit" value="Delete selected" /></div>
									<div class="col-2 ml-auto"><button class="btn btn-secondary" data-toggle="modal" data-target="#modal-newuser" >Add User </button></div>
								</div>
							</form>	
						</div>
					</div>					
				</div>
			</div>
		</section>
	
		<div class="modal fade modal-center" id="modal-newuser" tabindex="-1" role="dialog" aria-labelledby="modal-newuser" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="modal-newuser"><b>New User</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>					
					<div class="modal-body">
						<form class="form-signup" action="PostNewUser.php" method="POST" id="payment-form">
							<div class="card ">
								<div class="card-header">Manually Add a New</div>
								<div class="card-body">
									<div class="row">
										<div class="col-6 padding-10"><input class="form-control" type="text" 	  name="username" id="username" placeholder="Username" value="" required autofocus></div>
										<div class="col-6 padding-10"><input class="form-control" type="text" 	  name="email" 	  id="email" 	placeholder="Email Address" value="" required ></div>
										<div class="col-6 padding-10"><input class="form-control" type="text" 	  name="fname" 	  id="fname" 	placeholder="First Name" value="" required></div>
										<div class="col-6 padding-10"><input class="form-control" type="text" 	  name="lname" 	  id="lname" 	placeholder="Last Name" value="" required>	</div>
										<div class="col-6 padding-10"><input class="form-control" type="password" name="password" id="password" placeholder="Password" required aria-describedby="passwordhelp"></div>
										<div class="col-6 padding-10"><input class="form-control" type="password" name="confirm"  id="confirm"  placeholder="Confirm Password" required >	</div>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label>User Role</label>
									<select name="perm" class="form-group">
										<?php foreach ($permOps as $permOp){echo "<option value='$permOp->id'>$permOp->name</option>";}?>
									</select>
								</div>
								<div class="card-footer">
									<input type="hidden" value="<?=Token::generate();?>" name="csrf">
									<input class='btn btn-primary pull-right' type='submit' name='addUser' value='Manually Add User' />
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
							</div>
						</form>
					</div>	
				</div>
			</div>
		</div>
	</main>	

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>users/js/search.js" charset="utf-8"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
