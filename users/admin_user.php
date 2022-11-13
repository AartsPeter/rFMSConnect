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
 // Redirect::to("../pages/users.php"); die();
}
$today = date("Y-m-d H:i:s");

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
	if(!empty($_POST)) {
		$token = $_POST['csrf'];
		if(!Token::check($token)){    die('Token doesn`t match!');   }
		//Update display name
		if ($userdetails->username != $_POST['user_name']){
			$displayname = Input::get("user_name");
			$fields=array('username'=>$displayname);
			$validation->check($_POST,array('username' => array('display' => 'Username','required' => true,'unique_update' => 'users,'.$userId,'min' => 4,'max' => 25 ) ) );
			if($validation->passed()){
				$db->update('users',$userId,$fields);
				$successes[] = "Username Updated";
			} else {
				foreach ($validation->errors() as $error) { $errors[] = $error;   }
			}
		}
		//Update first name
		if ($userdetails->fname != $_POST['fname']){
			$fname = Input::get("fname");
			$fields=array('fname'=>$fname);
			$validation->check($_POST,array('fname' => array('display' => 'First Name','required' => true,'min' => 2) ) );
			if($validation->passed()){
			  $db->update('users',$userId,$fields);
			  $successes[] = "First Name Updated";
			} else {
				foreach ($validation->errors() as $error) { $errors[] = $error;   }
			}
		}
		//Group membership
		if (isset($_POST['group'])){
			$RemoveQuery=$db->query("DELETE FROM rel_cust_user rcu WHERE  rcu.User_ID=''.$userId.");
			$groupArray = Input::get("group");
			foreach ($group as $val) {
				$db->query("UPDATE rel_cust_user SET Cust_ID='".$val."' WHERE User_ID='".$userId."'");
			}
			$successes[] = "Group updated ";
		}
		//Update last name
		if ($userdetails->lname != $_POST['lname']){
			$lname = Input::get("lname");
			$fields=array('lname'=>$lname);
			$validation->check($_POST,array('lname' => array('display' => 'Last Name','required' => true,'min' => 2) ) );
			if($validation->passed()){
				$db->update('users',$userId,$fields);
				$successes[] = "Last Name Updated";
			} else {
				foreach ($validation->errors() as $error) { $errors[] = $error;   }
			}
		}
		if ($userdetails->language != $_POST['language']){
			$language = Input::get("language");
			$fields=array('language'=>$language);
			$db->update('users',$userId,$fields);
			$successes[] = "Language updated";
		}
		if(!empty($_POST['pssword'])) {
			$validation->check($_POST,array('password' => array('display' => 'New Password','required' => true,'min' => $settings->min_pw,'max' => $settings->max_pw), 'confirm' => array('display' => 'Confirm New Password',  'required' => true, 'matches' => 'password') ) );
			if (empty($errors)) {
			  //process
			  $new_password_hash = password_hash(Input::get('pssword'),PASSWORD_BCRYPT,array('cost' => 12));
			  $user->update(array('password' => $new_password_hash,),$userId);
			  $user->update(array('last_passwordreset' => $today),$userId);
			  $successes[]='Password updated.';
			}
		}
		//Block User
		if ($userdetails->permissions != $_POST['permissions']){
			if(isset($_POST['permissions'])) {$active =1;} else {$active =0;}
			$fields=array('permissions'=>$active);
			$db->update('users',$userId,$fields);
		}
		//Update email
		if ($userdetails->email != $_POST['email']){
		  $email = Input::get("email");
		  $fields=array('email'=>$email);
		  $validation->check($_POST,array( 'email' => array(    'display' => 'Email',  'required' => true,'valid_email' => true,   'unique_update' => 'users,'.$userId, 'min' => 3, 'max' => 75 ) ) );
		if($validation->passed()){
		  $db->update('users',$userId,$fields);
		  $successes[] = "Email Updated";
		}else{
			  ?><div id="form-errors">
				<?=$validation->display_errors();?></div>
				<?php
		  }
		}
		//Remove & set permission level
		if (isset($_POST['roles'])){
			$RolesArray = Input::get("roles");
            UpdateUserRoles($RolesArray,$userdetails->id);
		}
		if (isset($_POST['group'])){
			$GroupArray = Input::get("group");
			UpdateGroupRelationShip($GroupArray,$userdetails->id);
		}
	}
    // collect data for form
	$userdetails = fetchUserDetails(NULL, NULL, $userId);
    $languagesQ = $db->findAll('language');
    $languages = $languagesQ->results();

    $userPermission = fetchUserPermissions($userId);
    $permissionData = fetchAllPermissions();

	$AuthGroupsQ    = $db->query("SELECT Cust_ID,cu.name FROM rel_cust_user LEFT JOIN customers cu ON cu.accountnumber=Cust_ID  WHERE User_ID='".$userId."' ORDER BY Cust_ID");
    $AuthGroups = $AuthGroupsQ->results();
	//$grav = get_gravatar(strtolower(trim($userdetails->email)));
	if ($userdetails->avatar==1) {
	    $useravatar = '/images/avatars/userid_'.$userdetails->id.'_'.$userdetails->username.'.png';
	} else {
	    $useravatar = '/images/avatars/avatar.png';
	}
//
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="page-title ">Change userdetails</div>
                        <div class="row">
                            <div class="col-xl-3 col-md-4 col-12 mb-3">
                                <div class="card flex-grow-1 shadow-sm ">
                                    <div class="col-md-12 col card-header">
                                        <div class="row">
                                            <div class="avatar avatar-xxl m-3">
                                                <img class="avatar-img shadow" src="<?=$useravatar?>" alt="user photo">
                                                <div class="avatar-button">
                                                    <a class="text-primary" onclick="ChangeAvatar();"><i class="fad fa-3x fa-camera-retro"></i>Change Picture</a>
                                                </div>
                                            </div>
                                            <div class="col p-3">
                                                <div class="card-title m-0 border-0 larger"><?php echo ucfirst($userdetails->username);?></div>
                                                <div class="card-text small"><?php echo ucfirst($userdetails->email);?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="col-md-12 col-7 card-body d-none d-sm-block " >
                                        <div class="col-12 p-0">
                                            <span class="text-cap text-primary">groups</span>
                                            <div class="col-12">
                                                <div class="row">
                                                <?php foreach ($AuthGroups as $row){
                                                    echo '<div class="rounded servicebadge m-1 p-1" > '.$row->name.'</div>';
                                                }?>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="col-12 p-0" >
                                            <span class="text-cap text-primary">Roles</span>
                                            <div class="col-12 ">
                                                <div class="row">
                                                <?php
                                                    $perm_ids = [];
                                                    foreach($userPermission as $perm){	$perm_ids[] = $perm->permission_id; }
                                                        foreach ($permissionData as $v1){
                                                        if(in_array($v1->id,$perm_ids)){ ?>
                                                        <div class="rounded servicebadge m-1 p-1"> <?=$v1->name;?> </div>
                                                <?php   }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="align-self-end gray">
                                            <div  class="col-12 p-0" >
                                                <span class="text-cap text-primary">Statistics</span>
                                                <div class="small">
                                                    <div class="d-flex " ><div><i class="fas fa-lock"></i> last login           </div><div class="ml-auto text-right"><b> <?=$userdetails->last_login?></b></div></div>
                                                    <div class="d-flex " ><div><i class="fas fa-clock"></i> last password reset </div><div class="ml-auto text-right"><b><?=$userdetails->last_passwordreset?></b></div></div>
                                                    <div class="d-flex " ><div><i class="fas fa-hashtag"></i> counted of logins </div><div class="ml-auto text-right"><b><?=$userdetails->logins?></b></div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-8 col-12 ">
                                <div class="card shadow-sm">
                                    <div class="card-header">Info</div>
                                    <div class="card-body">
                                        <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-12 col-lg-7 ">
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>Username:</label>
                                                        <input  class='form-control' type='text' name='user_name' value='<?=$userdetails->username?>' />
                                                    </div>
                                                    <div class="custom-control custom-switch custom-switch-sm ml-auto pt-3">
                                                        <input type='checkbox' class="custom-control-input" id='permissions' name='permissions' <?php echo ($userdetails->permissions=1 ? 'checked' : '');?>   >
                                                        <label class="custom-control-label" for="permissions">Active user</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>First Name:</label>
                                                        <input  class='form-control' type='text' name='fname' value='<?=$userdetails->fname?>' />
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label>Last Name:</label>
                                                        <input  class='form-control ' type='text' name='lname' value='<?=$userdetails->lname?>' />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>Email:</label>
                                                        <input class='form-control' type='text' name='email' value='<?=$userdetails->email?>' />
                                                    </div>
                                                    <div class="form-group col-4 ml-auto">
                                                        <label>Language</label>
                                                        <select id="language" class="col form-control" name="language" >
                                                        <?php													    foreach($languages as $lang){
                                                            if($lang->id == $userdetails->language) {
                                                                echo '<option class="DeActiveGroup" value="'.$lang->id.'" selected>'.$lang->name.'</option>';
                                                            } else {
                                                                echo '<option class="DeActiveGroup" value="'.$lang->id.'">'.$lang->name.'</option>';
                                                            }
                                                        } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>New Password (<?=$settings->min_pw?> char min, <?=$settings->max_pw?> max.)</label>
                                                        <input class='form-control' type='pssword' name='pssword' />
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label>Confirm Password</label>
                                                        <input class='form-control' type='password' name='confirm' />
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-12 col-lg-5">
                                                <div class="form-group">
                                                    <label>Permissions / Roles </label>
                                                    <div class="card-body p-1">
                                                        <select id="roles" class="col p-0" name="roles[]" multiple></select>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <label>Access to vehicle groups </label>
                                                    <div class="card-body p-1">
                                                        <select id="group" class="col p-0" name="group[]" multiple></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 card-bottom">
                                                <div class="row ">
                                                    <div class="card-body">
                                                        <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                                        <input class="btn btn-primary " type="submit" value="Update" class="submit" />
                                                        <a class="btn btn-secondary mx-2" href="../pages/users.php">Cancel / return to overview</a>
                                                    </div>
                                                </div>
                                                <div class="row pt-2">
                                                    <div class="col-12">
                                                        <?=resultBlock($errors,$successes);?>
                                                        <?=$validation->display_errors();?>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>
    <script src="<?=$us_url_root?>plugins/bs4_multiselect/js/BsMultiSelect.min.js"></script>

    <script>
        new SlimSelect({ select: '#group'});
        new SlimSelect({ select: '#roles'});
        new SlimSelect({ select: '#language'});
        GetUserPermissions('<?=$userId?>');
        GetUserGroupAccess('<?=$userId?>');
         HideNavBarGroup();

    </script>


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
