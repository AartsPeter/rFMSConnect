<?php
?>
<?php 
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 
if($user->data()->id != 1){
  Redirect::to('account.php');
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina inner-pagina">
				    <div class="row">
					    <div class="col-12 page-title"><a href="admin.php">Admin Dashboard</a> - <small>Test your email settings</small></div>
						<div class="col-8 ">
							<div class="col-12 "> It's a good idea to test to make sure you can actually receive system emails before forcing your users to verify theirs. <br><br>
								  <strong>DEVELOPER NOTE 1:</strong>
									 If you are having difficulty with your email configuration, go to  users/helpers/helpers.php (around line 114) and set $mail->SMTPDebug  to a non-zero value. This is a development-platform-ONLY setting - be sure to set it back to zero (or leave it unset) on any live platform - otherwise you would open significant security holes.<br><br>
								 <strong>DEVELOPER NOTE 2:</strong>
										Gmail is significantly easier to use for sending mail than your average SMTP mailer. However, if you are using SMTP and your mail is not sending, you can try uncommenting out line 115 of users/helpers/helpers.php. <br><br>
									 <br>			
									  <?php
											if (!empty($_POST)){
											  $to = $_POST['test_acct'];
											  $subject = 'Testing Your Email Settings!';
											  $body = 'This is the body of your test email';
											  $mail_result=email($to,$subject,$body);

												if($mail_result){
													echo '<div class="alert alert-success" role="alert">Mail sent successfully</div><br/>';
												}else{
													echo '<div class="alert alert-danger" role="alert">Mail ERROR</div><br/>';
												}
											}
										  ?>

								<form class="" name="test_email" action="email_test.php" method="post">
									<div class="d-flex align-items-end">
									<div class="">
										<label>Send test to (Ideally different than your from address):
										<input required size='50' class='form-control' type='text' name='test_acct' value='' /></label>
									</div>
									<div class="ml-auto ">
										<label>
										<input class='btn btn-primary' type='submit' value='Send A Test Email' class='submit' /> </label>
									</div>
									</div>
								</form>
								<div class="row col-12 py-3">
									<a href="admin.php"><button class="btn btn-secondary" >Return to AdminPage</button></a>
								</div>
							</div>
						  </div>
						</div>
					</div>
				</div>
			</section>
		</main>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
