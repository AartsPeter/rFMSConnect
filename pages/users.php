<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$errors = $successes = [];
$form_valid=TRUE;

$permOp     = CheckPermissionLevel();
$permOpsQ   = $db->query("SELECT id,name FROM permissions WHERE id>".$permOp);
$permOps    = $permOpsQ->results();
// dnd($permOps);

//Forms posted
if (!empty($_POST)) {
     //Manually Add User
    if(!empty($_POST['addUser'])) {
        $token = $_POST['csrf'];
        if(!Token::check($token)){
          die('Token doesn\'t match!');
        }
        $form_valid=FALSE; // assume the worst
        $validation = new Validate();
        $validation->check($_POST,array(
            'username'=> array('display' => 'Username','required' => true,'min' => $settings->min_un,'max' => $settings->max_un, 'unique' => 'users' ),
            'fname' =>    array('display' => 'First Name', 'required' => true, 'min' => 2,'max' => 35 ),
            'lname' =>    array('display' => 'Last Name', 'required' => true, 'min' => 2, 'max' => 35 ),
            'email' =>    array('display' => 'Email','required' => true,'valid_email' => true, 'unique' => 'users'),
            'pssword' =>  array('display' => 'pssword','required' => true,'min' => $settings->min_pw,'max' => $settings->max_pw ),
            'confirm' =>  array( 'display' => 'Confirm Password', 'required' => true, 'matches' => 'pssword' ),
        ));
        if($validation->passed()) {
            $form_valid=TRUE;
            try {
                // echo "Trying to create user";
                $fields=array(
                  'username'        => Input::get('username'),
                  'fname'           => Input::get('fname'),
                  'lname'           => Input::get('lname'),
                  'email'           => Input::get('email'),
                  'password'         => password_hash(Input::get('pssword'), PASSWORD_BCRYPT, array('cost' => 15)),
                  'permissions'     => 1,
                  'account_owner'   => $user->data()->id,
                  'join_date'       => date("Y-m-d H:i:s"),
                  'email_verified'  => 1,
                  'active'          => 1,
                  'vericode'        => 111111 );

                if($db->insert('users',$fields)){
                    $theNewId=$db->lastId();
                    print_r($theNewId);
                    $successes[] = lang("ACCOUNT_USER_ADDED");

                    //Remove & set Group Relationships
                    if (isset($_POST['group'])){
                        $GroupArray = Input::get("group");
                        UpdateGroupRelationShip($GroupArray,$theNewId);
                    }
                    // bold($theNewId);
                    $perm = Input::get('perm');
                    $addNewPermission = array('user_id' => $theNewId, 'permission_id' => $perm);
                    $db->insert('user_permission_matches',$addNewPermission);
    //                if($perm != 1){
    //                    $addNewPermission2 = array('user_id' => $theNewId, 'permission_id' => 1);
    //                    $db->insert('user_permission_matches',$addNewPermission2);
    //                }
                    $successes[] = lang("ACCOUNT_USER_PERM_ADDED");
                } else
                { $errors[] = lang("ACCOUNT_USER_FAILED") ; }
            }
            catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }
}

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
				    <div class="inner-pagina">
					    <div class="page-title" ><a href="../users/admin.php">Admin Dashboard</a> » Manage Users </div>
	                    <div class="col-12 card card-body border-0 "> <table class="display table responsive noWrap" id="tableUsers">	</table> </div>
                        <div class="row col-12 pt-3">
                            <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#NewUserModal" >Create new User</button> <a href="../users/admin.php">
                            <button class="btn btn-secondary" >Return to AdminPage</button></a>
                        </div>
                        <div class="d-flex pt-3">
                            <div class="card-body w-25 p-0">
                                <?php echo resultBlock($errors,$successes);	?>
                            </div>
                        </div>
                    </div>
                </div>
				<div id="NewUserModal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg modal-dialog-centered " role="document">
						<div class="modal-content">
							<div class="modal-body p-0" id="EditUserDetails">
								<form class="form-signup" action="users.php" method="POST" id="payment-form">
									<div class="p-0">
										<div class="card-header">New User
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-6 pt-3">
												    <label>Username</label>
												    <input class="form-control" type="text" 	  name="username" id="username" placeholder="select an username" value="" required autofocus>
												 </div>
												<div class="col-6 pt-3">
												    <label>email address</label>
												    <input class="form-control" type="text" 	  name="email" 	  id="email" 	placeholder=" your e-mail address" value="" required >
												    </div>
                                            </div>
											<div class="row">
												<div class="col-6 pt-3">
												    <label>surname</label>
												    <input class="form-control" type="text" 	  name="fname" 	  id="fname" 	placeholder="first name" value="" required>
												</div>
												<div class="col-6 pt-3">
												    <label>lastname</label>
												    <input class="form-control" type="text" 	  name="lname" 	  id="lname" 	placeholder="last name" value="" required>
												</div>
                                            </div>
											<div class="row">
												<div class="col-6 pt-3">
												    <label>password</label>
												    <input class="form-control" type="password" name="pssword" id="pssword" placeholder="type your secret password" required aria-describedby="passwordhelp">
												</div>
											</div>
											<div class="row">
												<div class="col-6 pt-3">
												    <label>confirm password</label>
												    <input class="form-control" type="password" name="confirm"  id="confirm"  placeholder="re-type your secret password" required >
												</div>
											</div>
											<div class="row pt-4">
												<div class="form-group col-12 col-xl-6 ">
													<label>User Role </label>
													<div class="card-body p-0">
													    <select name="perm" class="col p-0" id="perm" multiple >
    														<?php
    														$x=0;
    														foreach ($permOps as $permOp){
                                                                if ($x==0) {  echo "<option class='DeActiveGroup' value='$permOp->id' selected>$permOp->name</option>";}
    														    else {  echo "<option class='DeActiveGroup' value='$permOp->id'>$permOp->name</option>";}
    														    $x++;
    														}
    														?>
    													</select>
    											    </div>
												</div>
												<div class="form-group col-12 col-xl-6">
                                                    <label>Access to vehicle groups </label>
                                                    <div class="card-body p-0">
                                                        <select id="group" class="col p-0" name="group[]" multiple></select>
                                                    </div>
												</div>
											</div>
										</div>
										<div class="col-12 pb-3">
											<input type="hidden" value="<?=Token::generate();?>" name="csrf">
											<input class='btn btn-primary pull-right' type='submit' name='addUser' value='Create User' />
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';  ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>
    window.onload=function(){
	    ShowUserTable();

    };
    new SlimSelect({ select: '#group'});
    new SlimSelect({ select: '#perm' });
    GetUserGroupAccess('<?=$user->data()->id?>');
    HideNavBarGroup();
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
