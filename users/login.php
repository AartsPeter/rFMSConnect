<?php 
	require_once 'init.php'; 
	require_once $abs_us_root.$us_url_root.'users/includes/header.php';
	require_once $abs_us_root.$us_url_root.'users/helpers/language.php';

$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
$settings = $settingsQ->first();
$error_message = '';
if (@$_REQUEST['err']) $error_message = $_REQUEST['err']; // allow redirects to display a message
$reCaptchaValid=FALSE;
 
if (Input::exists()) {
    $token = Input::get('csrf');
//	echo "<h2>".$token."</h2>\n\r";
	if(!Token::check($token)){
        die("Token doesn\'t match!");
    }
    //Check to see if recaptcha is enabled
    if($settings->recaptcha == 1){
        require_once 'includes/recaptcha.config.php';
        //reCAPTCHA 2.0 check
        $response = null;
        // check secret key
        $reCaptcha = new ReCaptcha($privatekey);
        // if submitted check response
        if ($_POST["g-recaptcha-response"]) {
            $response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],$_POST["g-recaptcha-response"]);
        }
        if ($response != null && $response->success) {
            $reCaptchaValid=TRUE;
        }else{
            $reCaptchaValid=FALSE;
            $error_message .= 'Please check the reCaptcha.';
        }
    }else{
        $reCaptchaValid=TRUE;
    }
 
    if($reCaptchaValid || $settings->recaptcha == 0){ //if recaptcha valid or recaptcha disabled
 
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('display' => 'Username','required' => true),
            'password' => array('display' => 'Password', 'required' => true)));
 
        if ($validation->passed()) {
            //Log user in
 
            $remember = (Input::get('remember') === 'on') ? true : false;
            $user = new User();
            $login = $user->loginEmail(Input::get('username'), trim(Input::get('password')), $remember);
            if ($login) {
              //  usleep(400);
				if (!empty($user->data()->custom1)){Redirect::to('..'.$user->data()->custom1);} 
				else { Redirect::to('../index');}
//                # if user was attempting to get to a page before login, go there
//               $dest = sanitizedDest('dest');
//                if (!empty($dest)) {
//                    Redirect::to('../index.php');	//replace with $dest to use redirect to start url before login
//                } elseif (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php')) {
//                   
//                    # if site has custom login script, use it
//                    # Note that the custom_login_script.php normally contains a Redirect::to() call
//                    require_once $abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php';
//                } else {
//                    if (($dest = Config::get('homepage')) ||
//                            ($dest = 'account.php')) {
//                        #echo "DEBUG: dest=$dest<br />\n";
//                        #die;
//                        Redirect::to($dest);
//                   }
//              }
            } else {
                $error_message .= 'Authentication FAILED. Please check your username and/or password and try again <i class="fad fa-exclamation"></i>';
            }
        } else{
            $error_message .= '<ul>';
            foreach ($validation->errors() as $error) {
                $error_message .= '<li>' . $error . '</li>';
            }
            $error_message .= '</ul>';
        }
    }
}
if (empty($dest = sanitizedDest('dest'))) {
  $dest = '';
}
$CurrentDT = Date("Y-m-d");
    $query = "
        SELECT '' as groupName, n.*
        FROM notification n
        WHERE n.archive=false and Public=true and n.messagegroup='0' AND CURDATE() BETWEEN StartPublish AND EndPublish
        GROUP BY ID
        ORDER BY EndPublish ASC";
    $AlertQ  = $db->query($query);
    $Alerts =  $AlertQ->results();
?>
		<section class="section section-login  ">
		<!--	<div class="logintext center"><?=$settings->site_name;?></div>-->
			<div class="container-fluid " >  <!--removed { id="slider"}-->
				<div class="pagina-login login-bg shadow border-primary">
					<div class="d-flex subtitle trans-light p-3 w-100"><?=$settings->site_description;?></div>
					<div class="row h-100">
						<div class=" col-md-8 loginbox d-flex">
							<div class="d-flex justify-content-center flex-column col-12 p-0" >
								<div class="d-flex title p-2 m-auto"><?=$settings->site_name;?></div>
							</div>
						</div>
						<div class="col-10 col-md-3 mx-auto m-3"  id="login_switcher">
						    <div class="row loginbox-info" >
						    	<div class="col loginPhrase" id="loginPhrase">
						    	 <!--   <div class="px-3"><?=$settings->loginPhrase;?></div>-->
						    	</div>
						    	<div class="col-12 login-body my-auto">
                                    <form name="login" class="form-signin " action="" method="post" _lpchecked="1">
                                        <input type="hidden" name="dest" value="<?= $dest ?>" />
                                        <div class="field form-group col-12 mt-3 mb-5">
                                            <input type="text" class="form-control" onchange="checkValue('username');" autocomplete="off" tabindex=1 name="username" onkeyup="UpdateCounter('','loginwarning');"  id="username" required>
                                            <label for="username"><?=lang("LOGIN_LBL_USERNAME","");?></label>
                                        </div>
                                         <div class="field form-group col-12 mb-5">
                                            <input type="password" class="form-control" onchange="checkValue('password');" name="password" tabindex=2 onkeyup="UpdateCounter('','loginwarning');" id="password"  required>
                                            <label for="password"><?=lang("LOGIN_LBL_PSSWORD","");?></label>
                                            <span class="fad fa-fw fa-eye field-icon toggle-password gray" toggle="#password-field" id="password-eye-btn"></span>

                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary col" id="loginbtn" type="submit" ><i class="fad fa-sign-in"></i> <?=lang("SIGNIN_BUTTONTEXT","");?></button>
                                        </div>
                                <?php	if($settings->recaptcha == 1){	?>
                                        <div class="form-group">
                                            <label><?=lang("LOGIN_LBL_Recaptcha","");?></label>
                                            <div class="g-recaptcha" data-sitekey="<?=$publickey; ?>"></div>
                                        </div>
                                <?php 	} ?>
                                        <input type="hidden" name="csrf" value="<?=Token::generate(); ?>">
                                <?php 	if(!!$error_message){?>
                                        <div class="form-group col-12 mt-4">
                                            <div class="text-danger col-12 py-1" id="loginwarning"><b><?=$error_message;?></b></div>
                                        </div>
                                <?php 	} ?>
                                        <div class="d-flex mt-2"><a class="text-primary m-auto" href='forgot_password.php'><?=lang("LOGIN_FLD_FORGOTTEN","");?> <i class="fad fa-question fa-fw text-primary"></i></a>	</div>
                                    </form>
                                </div>
						<?php if (count($Alerts) >0){?>
							<div class="col-12 login-message">
								<div id="carouselNotifications " class="carousel slide carousel-fade px-3" data-ride="carousel">
									<div class="carousel-inner d-flex shadow-rfms">
								<?php
								$first=true;
								foreach ($Alerts as $val){
									if ($val->startPublish<Date("Y-m-d")&& $val->endPublish>Date("Y-m-d")){
									$endDate=new DateTime($val->endPublish);
									$result = $endDate->format('Y-m-d');
									if ($first==true){?>
										<div class="carousel-item active">
									<?php $first=false;} else {?>
										<div class="carousel-item">
									<?php }?>
											<div class="w-100 h-100 card p-2 alert-<?=$val->notificationType?> ">
											<?php if ($val->notificationHeader!=''){ ?>
												<div class="p-0 <?=$val->notificationType?>"><strong><?=html_entity_decode($val->notificationHeader) ?></strong></div>
											<?php }?>
												<div class="p-0 <?=$val->notificationType?>"><?=html_entity_decode($val->notificationInfo) ?></div>
											</div>
										</div>
									<?php	}
									}?>
									</div>
								    <?php if (count($Alerts) >1){?>
									<ul class="carousel-indicators mt-3">
									<?php $counter=0;
									foreach ($Alerts as $val){
									if ($counter==0) {?>
										<li data-target="#carouselNotifications" data-slide-to="<?=$counter?>" class="active"></li>
									<?php } else {?>
										<li data-target="#carouselNotifications" data-slide-to="<?=$counter?>" ></li>
									<?php }
										$counter++;
									  }?>
									</ul>
									<?php }?>
								</div>
								</div>
							</div>
						<?php }?>
						</div>
					</div>
				</div>
			</div>
		</section>
    <script src="https://kit.fontawesome.com/f433537204.js" crossorigin="anonymous"></script>
    <script src="<?=$us_url_root?>plugins/jQuery/jquery-latest.min.js"></script>
    <script src="<?=$us_url_root?>plugins/bootstrap4/js/bootstrap.bundle.min.js"></script>
    <script src="<?=$us_url_root?>js/rfms.js"></script>
    <script src="<?=$us_url_root?>js/login.js"></script>
    <script>
        $('#carouselNotifications').carousel({ interval: 2000});
        typeWriter('loginPhrase');
    </script>
<?php   if($settings->recaptcha == 1){ ?>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php }

require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>
