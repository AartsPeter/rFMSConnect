<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}

//dealing with if the user is logged in
if($user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}

$emailQ = $db->query("SELECT * FROM email");
$emailR = $emailQ->first();
// dump($emailR);user_settings
// dump($emailR);
// dump($emailR->email_act);

$errors=[];
$successes=[];
$userId = $user->data()->id;
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$validation = new Validate();
$userdetails=$user->data();
//Temporary Success Message
$holdover = Input::get('success');
if($holdover == 'true'){
    bold("Account Updated");
}
//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
        die('Token doesn\'t match!');
    }else {
        //Update display name
        if ($userdetails->username != $_POST['username']){
            $displayname = Input::get("username");
            $fields=array(
                'username'=>$displayname,
                'un_changed' => 1,
            );
            $validation->check($_POST,array(
                'username' => array(
                    'display' => 'Username',
                    'required' => true,
                    'unique_update' => 'users,'.$userId,
                    'min' => 1,
                    'max' => 25
                )
            ));
            if($validation->passed()){
                if(($settings->change_un == 2) && ($user->data()->un_changed == 1)){
            //        Redirect::to('index.php?err=Username+has+already+been+changed+once.');
                }
                $db->update('users',$userId,$fields);
                $successes[]="Username updated.";
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $displayname=$userdetails->username;
        }
		//Update colorscheme setting
        if ($userdetails->ucss != $_POST['ucss']){
            $ucss= Input::get("ucss");
			$fields=array('ucss'=>$ucss);
            $db->update('users',$userId,$fields);
            $successes[]='Personal Color Scheme updated.';
			//Redirect::to('user_settings.php');
        } else{
            $ucss=$userdetails->ucss;
        }
        //Update first name
        if ($userdetails->fname != $_POST['fname']){
            $fname = Input::get("fname");
            $fields=array('fname'=>$fname);
            $validation->check($_POST,array(
                'fname' => array(
                    'display' => 'First Name',
                    'required' => true,
                    'min' => 1,
                    'max' => 25
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='First name updated.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $fname=$userdetails->fname;
        }
        //Update last name
        if ($userdetails->lname != $_POST['lname']){
            $lname = Input::get("lname");
            $fields=array('lname'=>$lname);
            $validation->check($_POST,array(
                'lname' => array(
                    'display' => 'Last Name',
                    'required' => true,
                    'min' => 1,
                    'max' => 25
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='Last name updated.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $lname=$userdetails->lname;
        }
        //Update email
        if ($userdetails->email != $_POST['email']){
            $email = Input::get("email");
            $fields=array('email'=>$email);
            $validation->check($_POST,array(
                'email' => array(
                    'display' => 'Email',
                    'required' => true,
                    'valid_email' => true,
                    'unique_update' => 'users,'.$userId,
                    'min' => 3,
                    'max' => 75
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                if($emailR->email_act==1){
                    $db->update('users',$userId,['email_verified'=>0]);
                }
                $successes[]='Email updated.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $email=$userdetails->email;
        }
        if(!empty($_POST['password'])) {
            $validation->check($_POST,array(
                'old' => array(
                    'display' => 'Old Password',
                    'required' => true,
                ),
                'password' => array(
                    'display' => 'New Password',
                    'required' => true,
                    'min' => $settings->min_pw,
                'max' => $settings->max_pw,
                ),
                'confirm' => array(
                    'display' => 'Confirm New Password',
                    'required' => true,
                    'matches' => 'password',
                ),
            ));
            foreach ($validation->errors() as $error) {
                $errors[] = $error;
            }
            if (!password_verify(Input::get('old'),$user->data()->password)) {
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
                $errors[]='There is a problem with your password.';
            }
            if (empty($errors)) {
                //process
                $new_password_hash = password_hash(Input::get('password'),PASSWORD_BCRYPT,array('cost' => 12));
                $user->update(array('password' => $new_password_hash,),$user->data()->id);
                $successes[]='Password updated.';
            }
        }
    }
}else{
    $displayname=$userdetails->username;
    $fname=$userdetails->fname;
    $lname=$userdetails->lname;
    $email=$userdetails->email;
	$ucss=$userdetails->ucss;
}
?>
			<div class="modal-dialog modal-lg">
				<div class="modal-content bg-white">
					<div class="modal-header">
						<div class="page-title">Update your user settings</div>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>					
					<div class="modal-body pr-0">
						<div class="row">
							<div class="col-6 col-md-10">	
								<?php if (count($errors)>0){?>		<span class="alert alert-danger"><?=display_errors($errors);?></span><?php }?>
								<?php if (count($successes)>0) {?>	<span class="alert alert-success"><?=display_successes($successes);?></span><?php }?>
								<form name='updateAccount'  action='user_settings.php' method='post'>
									<div class="row">
										<div class="form-group col-md-6 col-12">
											<label>Username</label>
											<?php if (($settings->change_un == 0) || (($settings->change_un == 2) && ($user->data()->un_changed == 1)) ) {
												echo "<input  class='form-control' type='text' name='username' value='$displayname' readonly/>";
											}else{
												echo "<input  class='form-control' type='text' name='username' value='$displayname'>";
											}
											?>
										</div>
										<div class="form-group col-md-6 col-12">
											<label class="" for="ucss">Personal Color Scheme </label>
											<select class="form-control" name="ucss" id="ucss" >
												<option class="ActiveGroup" selected="selected"><?=$ucss?></option>
												<option value="" >Default (no personal)</option>
												<?php
												$dir=$us_url_root.'users/css/color_schemes';
												if (is_dir($dir)) {
													if ($dh = opendir($dir)) {
														while (($file = readdir($dh)) !== false) {
															if (substr($file, -3, 3)=='css'){
																echo "<option value=".$file.">".substr($file,0, -4)."";
															}
														}
														closedir($dh);
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6 col-12">
											<label>First Name</label>
											<input  class='form-control' type='text' name='fname' value='<?=$fname?>' />
										</div>
										<div class="form-group col-md-6 col-12">
											<label>Last Name</label>
											<input  class='form-control' type='text' name='lname' value='<?=$lname?>' />
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6 col-12">
											<label>Email</label>
											<input class='form-control' type='text' name='email' value='<?=$email?>' />
										</div>
									</div>
									<div class="row">										
										<div class="form-group col-md-6 col-12">
											<label>Old Password (required to change password)</label>
											<input class='form-control' type='password' name='old' />
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6 col-12">
											<label>New Password (<?=$settings->min_pw?> char min, <?=$settings->max_pw?> max.)</label>
											<input class='form-control' type='password' name='password' />
										</div>
										<div class="form-group col-md-6 col-12">
											<label>Confirm Password</label>
											<input class='form-control' type='password' name='confirm' />
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6 col-12">
											<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
											<p><input class='btn btn-primary' type='submit' value='Update' class='submit' />
											<a class="btn btn-secondary pl-2" href="../index.php">Cancel</a></p>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>	
				</div>
			</div>

<SCRIPT>new SlimSelect({
  select: '#ucss'
});
</SCRIPT>
