<div class="row">
	<div class="col-12">
		<h1>Reset your password.</h1>
		<ol align="left">
			<li>Enter your email address and click Reset</li>
			<li>Check your email and click the link that is sent to you.</li>
			<li>Follow the on screen instructions</li>
		</ol>
		<span class="notice-danger"><?=display_errors($errors);?></span>
		<form action="forgot_password.php" method="post" class="form ">
			<div class="form-group col-md-12">
				<input type="text" name="email" placeholder="Email Address" class="form-control" autofocus>
			</div>
			<input type="hidden" name="csrf" value="<?=Token::generate();?>">
			<p><input type="submit" name="forgotten_password" value="Reset" class="btn btn-primary">
			   <a href="login.php" class="btn btn-primary">Return to Login</a></p>
		</form>
	</div>
</div>