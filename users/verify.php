<?php 
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if($user->isLoggedIn()){
	$user->logout();
	Redirect::to($us_url_root.'users/verify.php');
}

$verify_success=FALSE;

$errors = array();
if(Input::exists('get')){

	$email = Input::get('email');
	$vericode = Input::get('vericode');

	$validate = new Validate();
	$validation = $validate->check($_GET,array(
	'email' => array(
	  'display' => 'Email',
	  'valid_email' => true,
	  'required' => true,
	),
	));
	if($validation->passed()){ //if email is valid, do this
		//get the user info based on the email
		$verify = new User(Input::get('email'));

		if ($verify->exists() && $verify->data()->vericode == $vericode){ //check if this email account exists in the DB
			$verify->update(array('email_verified' => 1),$verify->data()->id);
			$verify_success=TRUE;
		}
	}else{
		$errors = $validation->errors();
	}
}

?>

	<main role="main"  >
		<section class="section section-full">
			<div class="container">
				<div class="row ">
					<div class="col-12 pt-2">
						<div class="row">
<?php
if ($verify_success){
	require 'views/_verify_success.php';
}else{
	require 'views/_verify_error.php';
}
?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>