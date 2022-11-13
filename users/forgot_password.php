<?php 
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation_login.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 

$error_message = null;
$errors = array();
$email_sent=false;

$token = Input::get('csrf');
if(Input::exists()){
    if(!Token::check($token)){
        die('Token doesn\'t match!');
    }
}
if (Input::get('forgotten_password')) {
    $email = Input::get('email');
    $fuser = new User($email);
    //validate the form
    $validate = new Validate();
    $validation = $validate->check($_POST,array('email' => array('display' => 'Email','valid_email' => true,'required' => true,),));
    if($validation->passed()){
        if($fuser->exists()){
            //send the email
            $options = array(
              'fname' => $fuser->data()->fname,
              'email' => rawurlencode($email),
              'vericode' => $fuser->data()->vericode,
            );
            $subject = 'Password Reset';
            $encoded_email=rawurlencode($email);
            $body =  email_body('_email_template_forgot_password.php',$options);
            $email_sent=email($email,$subject,$body);
            if(!$email_sent){
                $errors[] = 'Email NOT sent due to error. Please contact site administrator.';
            }
        }
		else {$email_sent=TRUE;  }
    }
	else{
        //display the errors
//        $errors = $validation->errors();
		$email_sent=TRUE;  // not showing to reset requestor wether the email address exist or not. => security issue
    }
}
?>
<?php
if ($user->isLoggedIn()) {
Redirect::to('account.php');
}
?>
	<section class="section section-login d-flex ">
			<div class="logintext"><?=$settings->site_name;?></div>
			<div class="container " >
				<div class="pagina-login col-12" >
					<div class="row">
						<div class="col-12 col-md-8 loginbox d-flex p-0 login-bg">
							<div class="d-flex justify-content-center flex-column col-12 p-0" >
								<div class="d-flex title p-2 mt-auto"><?=$settings->site_name;?></div>	
								<div class="d-flex subtitle trans-light p-2"><?=$settings->site_description;?></div>	
							</div>
						</div>
						<div class="col-12 col-md-4 loginbox-info" id="login_switcher" >
							<div class="col-12 login-body">	
							<?php
							if($email_sent){
								require 'views/_forgot_password_sent.php';
							}else{
								require 'views/_forgot_password.php';
							}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
