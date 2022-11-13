<?php
require_once 'init.php'; 
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
$Q="SELECT c.name,c.accountnumber FROM rel_cust_user RCU LEFT JOIN customers c on c.accountnumber=rcu.Cust_ID where rcu.User_ID='".$userId."'";
$AuthGroupsQ = $db->query($Q);
$AuthGroups = $AuthGroupsQ->results();
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
        header('HTTP/1.0 403 Unauthorized');die('Token does not match!');
    }else {
        //Update display name
        if ($userdetails->username != $_POST['username']){
            $displayname = Input::get("username");
            $fields=array('username'=>$displayname, 'un_changed' => 1 );
            $validation->check($_POST,array('username' => array('display' => 'Username','required' => true,'unique_update' => 'users,'.$userId,'min' => 1,'max' => 25 ) ) );
            if($validation->passed()){
                if(($settings->change_un == 2) && ($user->data()->un_changed == 1)){
                    Redirect::to('user_settings.php?err=Username+has+already+been+changed+once.');
                }
                $db->update('users',$userId,$fields);
                $successes[]="Username updated.";
            } else {
                foreach ($validation->errors() as $error) {$errors[] = $error;}
            }
        } else {
            $displayname=$userdetails->username;
        }
		//Update colorscheme setting
        if ($userdetails->us_css != $_POST['us_css']){
            $us_css= Input::get("us_css");
			$fields=array('us_css'=>$us_css);
            $db->update('users',$userId,$fields);
            $successes[]='Personal Color Scheme updated.';
			Redirect::to('user_settings.php');
        } else{
            $us_css=$userdetails->us_css;
        }
        //Update first name
        if ($userdetails->fname != $_POST['fname']){
            $fname = Input::get("fname");
            $fields=array('fname'=>$fname);
            $validation->check($_POST,array('fname' => array('display' => 'First Name','required' => true, 'min' => 1,'max' => 25) ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='First name updated.';
            } else {
                foreach ($validation->errors() as $error) { $errors[] = $error; }
            }
        } else {
            $fname=$userdetails->fname;
        }
        //Update last name
        if ($userdetails->lname != $_POST['lname']){
            $lname = Input::get("lname");
            $fields=array('lname'=>$lname);
            $validation->check($_POST,array('lname' => array('display' => 'Last Name','required' => true,'min' => 1,'max' => 25 ) ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='Last name updated.';
            } else {
                foreach ($validation->errors() as $error) { $errors[] = $error; }
            }
        }else{
            $lname=$userdetails->lname;
        }
        //Update email
        if ($userdetails->email != $_POST['email']){
            $email = Input::get("email");
            $fields=array('email'=>$email);
            $validation->check($_POST,array('email' => array('display' => 'Email','required' => true,'valid_email' => true,'unique_update' => 'users,'.$userId, 'min' => 3, 'max' => 75 ) ) );
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                if($emailR->email_act==1){  $db->update('users',$userId,['email_verified'=>0]); }
                $successes[]='Email updated.';
            } else {
                foreach ($validation->errors() as $error) { $errors[] = $error;   }
            }
        }else{
            $email=$userdetails->email;
        }
        if(!empty($_POST['password'])) {
            $validation->check($_POST,array(
                'old'       => array('display' => 'Old Password','required' => true),
                'password'  => array('display' => 'New Password','required' => true,'min' => $settings->min_pw,'max' => $settings->max_pw ),
                'confirm'   => array('display' => 'Confirm New Password','required' => true,'matches' => 'password'),
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
                $user->update(array('last_passwordreset' => $today),$user->data()->id);
                $successes[]='Password updated.';
            }
        }
    }
}else{
	$displayname=$userdetails->username;
	$fname=$userdetails->fname;
	$lname=$userdetails->lname;
	$email=$userdetails->email;
	$driver_id=$userdetails->driver_id;
	$us_css=$userdetails->us_css;
}
if ($userdetails->last_passwordreset < date('Y-m-d', strtotime(' - '.$settings->max_pwa.' days'))){
    $errors[]='Your password has expired, please reset your password';$resetpsww='is-invalid';
} else {$resetpsww='';}
if ($user->data()->avatar==1) {
    $useravatar = '/images/avatars/avatar_'.$user->data()->id.'.png';
} else {
    $useravatar = '/images/avatars/avatar.png';
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="row">
                            <div class="col-xl-3 col-md-4 col-12 mb-3">
                                <div class="card flex-grow-1 shadow-sm ">
                                    <div class="col-md-12 col card-header ">
                                        <div class="row">
                                            <div class="avatar avatar-xxl m-2">
                                                <img class="avatar-img " src="<?=$useravatar?>" alt="user photo">
                                                <div class="avatar-button ">
                                                    <a class="text" onclick="ChangeAvatar();"><i class="fad fa-3x fa-camera-retro"></i>Change Picture</a>
                                                </div>
                                            </div>
                                            <div class="col p-3">
                                                <div class="card-title h4"><?php echo ucfirst($userdetails->username);?></div>
                                                <div class="card-text small"><?php echo ucfirst($userdetails->email);?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="text-cap text-secondary">groups</span>
                                        <div  class="col-12 mb-3" >
                                            <div class="row">
                                            <?php if ($userdetails->cust_id=='0'){ ?>
                                                <div class="card servicebadge">All groups (Admin)</div>
                                            <?php } ?>
                                            <?php foreach ($AuthGroups as $row){?>
                                                <div class="card servicebadge" > <?=$row->name?></div>
                                            <?php }?>
                                            </div>
                                        </div>
                                        <span class="text-cap text-secondary">Roles</span>
                                        <div  class="col-12 mb-3" >
                                            <div class="row">
                                            <?php
                                                $perm_ids = [];
                                                foreach($userPermission as $perm){	$perm_ids[] = $perm->permission_id; }
                                                foreach ($permissionData as $v1){
                                                    if(in_array($v1->id,$perm_ids)){ ?>
                                                    <div class="card servicebadge"> <?=$v1->name;?> </div>
                                            <?php   }
                                                } ?>
                                            </div>
                                        </div>
                                        <span class="text-cap text-secondary">Statistics</span>
                                        <div class="col-12 align-self-end gray">
                                            <div class="row small" ><div><i class="fas fa-lock"></i> last login           </div><div class="ml-auto text-right"><b> <?=$userdetails->last_login?></b></div></div>
                                            <div class="row small" ><div><i class="fas fa-clock"></i> last password reset </div><div class="ml-auto text-right"><b><?=$userdetails->last_passwordreset?></b></div></div>
                                            <div class="row small" ><div><i class="fas fa-hashtag"></i> counted of logins </div><div class="ml-auto text-right"><b><?=$userdetails->logins?></b></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-7">
                                <div class="card shadow-sm ">
                                    <div class="card-header">Info</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 ">
                                                <form name='updateAccount'  action='' method='POST'>
                                                    <div class="row">
                                                        <?php if (($settings->change_un == 0) || (($settings->change_un == 2) && ($user->data()->un_changed == 1)) ) {
                                                        }else{
                                                            echo "<div class='form-group col-12 col-xl-4 '><label>Username</label><input  class='form-control' type='text' name='username' value='$displayname'></div>";
                                                        }
                                                        ?>

                                                        <div class="form-group col-6  col-xl-4 ">
                                                            <label>First Name</label>
                                                            <input  class='form-control' type='text' name='fname' value='<?=$fname?>' />
                                                        </div>
                                                        <div class="form-group col-6 col-xl-4 ">
                                                            <label>Last Name</label>
                                                            <input  class='form-control' type='text' name='lname' value='<?=$lname?>' />
                                                        </div>
                                                        <div class="form-group col-10 col-xl-8">
                                                            <label>Email</label>
                                                            <input class='form-control' type='text' name='email' value='<?=$email?>' />
                                                        </div>
                                                        <div class="form-group col-10 col-xl-4">
                                                            <label>Tachograph id</label>
                                                            <span class='form-control' type='text' name='driver_id' ><?=$driver_id?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-12 col-xl-4 ">
                                                            <label>Old Password <br>(required to change password)</label>
                                                            <input class='form-control' type='password' name='old' />
                                                        </div>
                                                        <div class="form-group col-6 col-xl-4">
                                                            <label>New Password <br>(<?=$settings->min_pw?> char min, <?=$settings->max_pw?> max.)</label>
                                                            <input class='form-control <?=$resetpsww?>' type='password' name='password' />
                                                        </div>
                                                        <div class="form-group col-6 col-xl-4">
                                                            <label>Confirm Password <br> (retype)</label>
                                                            <input class='form-control <?=$resetpsww?>' type='password' name='confirm' />
                                                        </div>
                                                    </div>
                                                    <div class="row pt-3 ">
                                                        <?php if ($settings->allow_user_css==true){ ?>
                                                        <div class="form-group col-6 col-xl-4 ml-auto">
                                                            <label class="" for="us_css">Personal Color Scheme </label>
                                                            <select class="form-control" name="us_css" id="us_css" onchange="ChangeStyle();"  >

                                                                <option class="ActiveGroup" selected="selected"><?=$ucss?></option>
                                                                <option value="" >Default (no personal)</option>
                                                                <?php
                                                                $dir='css/color_schemes';
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
                                                        <?php } ?>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="form-group col-md-6 col-12">
                                                            <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                                            <p><input class='btn btn-primary' type='submit' value='Update' class='submit' />

                                                        </div>
                                                        <div class="form-group col-md-6 col-12"><?php resultBlock($errors,$successes);?></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div> 
			</div> 
		</section> 
	</main> 
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';  ?>
<script>
	window.onload=function(){
        HideNavBarGroup();
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>
