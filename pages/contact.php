<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
if (!securePage($_SERVER['PHP_SELF'])){alert("No Access granted, reverting to dashboard");die();} 
// if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }
if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
else {
	if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
	else {$SCName='All Groups';$SCNumber='*';}
}

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="mr-auto page-title ">Get in touch <small> <small> Tell us what you think or share your experience</small></small></div>
                        <div class="col-12 p-0">
                            <div class="row">
                                <div class="col-lg-8  col-12">
                                    <div class="feedback-form">
                                        <div class="common-form-style card-body">
                                            <form class="account-setting" method="post" id="contact-form" novalidate="novalidate">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                                            <label for="fname">Name</label>
                                                            <div class="form-control-ro" id="fname" name="fname"><?php echo ucfirst($user->data()->fname)." ".ucfirst($user->data()->lname);?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                                            <label for="email">Email Address</label>
                                                            <div class="form-control-ro" id="fname" name="fname"><?php echo ucfirst($user->data()->email);?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                                    <label for="sub">Subject</label>
                                                    <input type="text" id="sub" class="form-control" name="sub"><span class="pmd-textfield-focused"></span>
                                                </div>
                                                <div class="form-group pmd-textfield pmd-textfield-floating-label">
                                                    <label for="message">Message</label>
                                                    <textarea id="message" class="form-control" rows="5" name="message"></textarea><span class="pmd-textfield-focused"></span>
                                                </div>
                                                <button class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="submit">Send message</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12 mt-2">
                                    <div class="card-body ">
                                        <h2 class="card-title mb-4 h1 ">Contact info</h2>
                                        <address>
                                        <b>Office Address</b><br>
                                        rFMS-Connect <br>
                                        streetaddress <br>
                                        city & zipcode
                                        </address>
                                        <address>
                                        <b>Office number</b><br>
                                        +31 officenumber
                                        </address>
                                        <address>
                                        <b>Email us</b><br>
                                        <a href="mailto:sales@rfmsreader.nl" class="grey">Sales @ rFMS Connect.nl</a>

                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>	
		</section>	
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script>
	window.onload=function(){
//		ShowDriverTable();
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>