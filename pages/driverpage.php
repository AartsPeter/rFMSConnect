<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();}


//dealing with if the user is logged in
if($user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}
$userId = $user->data()->id;
$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();
$emailQ = $db->query("SELECT * FROM email");
$emailR = $emailQ->first();
// dump($emailR);
// dump($emailR->email_act);
$errors=[];
$successes=[];

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
                    Redirect::to('driverpage.php?err=Username+has+already+been+changed+once.');
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
			Redirect::to('user_settings.php');
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
	$driver_id=$userdetails->driver_id;
	$fname=$userdetails->fname;
	$lname=$userdetails->lname;
	$email=$userdetails->email;
	$ucss=$userdetails->ucss;
}
$useravatar = '<img src="'.$grav.'" class="img-responsive img-thumbnail" alt="">';
if ($userdetails->last_passwordreset < date('Y-m-d', strtotime(' - '.$settings->max_pwa.' days'))){
    $errors[]='Your password has expired, please reset your password';
}
?>
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-primary">
	    <div class=" logo-image "></div><a class="navbar-brand m-auto" title="Dashboard" href="<?=$us_url_root?>index.php" > <?=$settings->site_name;?></a>
    </nav>
	<main role="main">
		<section class="section section-mobile ">
			<div class="container-fluid">
				<div class="pagina">
					<div class="inner-pagina m--2">
                        <div class="card h-100">
                            <div class="card-header   ">
                                <div class="row">
                                    <div class="col-2 smallcircle"><?php echo $useravatar;?></div>
                                    <div class="col-10 ml-auto row">
                                        <div class="col-12 page-title" ><?=$displayname?><br> </b></div>
                                        <div class="col-12  " ><?=$email?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2 ">
                                <div class="col-12 ">
                                    <form name='updateAccount'  action='driverpage.php' method='post'>
                                        <div class="row">
                                            <div class="form-group col-6  col-xl-4 ">
                                                <label>First Name</label>
                                                <input  class='form-control' type='text' name='fname' value='<?=$fname?>' />
                                            </div>
                                            <div class="form-group col-6 col-xl-4 ">
                                                <label>Last Name</label>
                                                <input  class='form-control' type='text' name='lname' value='<?=$lname?>' />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12 col-xl-4 ">
                                                <label>Old Password (required to change password)</label>
                                                <input class='form-control' type='password' name='old' />
                                            </div>
                                            <div class="form-group col-6 col-xl-4">
                                                <label>New Password <br>(<?=$settings->min_pw?> char min, <?=$settings->max_pw?> max.)</label>
                                                <input class='form-control text-success' type='password' name='password' />
                                            </div>
                                            <div class="form-group col-6 col-xl-4">
                                                <label>Confirm Password <br> (retype)</label>
                                                <input class='form-control text-success' type='password' name='confirm' />
                                            </div>
                                        </div>
                                         <?php resultBlock($errors,$successes);?>
                                        <div class="row col-12 pt-4">
                                            <div class="form-group">
                                                <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                                <p><input class='btn btn-primary' type='submit' value='Update' class='submit' />
                                            </div>
                                            <div class="form-group ml-auto ">
                                                <a class="btn btn-secondary" href="<?=$us_url_root?>users/logout.php"><b> Logout</b></a>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
