
<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} 
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
				<div class="mr-auto page-title ">DAF Connect webservices </div>	
				<div class="row pb-2">
					<div class="col-12 col-lg-3 p-0" id="CVPLoginInfo">
						<div class="form-group col-12 pb-1">
							<input  class="form-control" type="text" name="username" id="CVPusername" placeholder="Username/Email" required autofocus>
						</div>
						 <div class="form-group col-12 pb-1">
							<input type="password" class="form-control" name="password" id="CVPpassword"  placeholder="Password" required autocomplete="off">
						</div>
					</div>
					<div class="col-12 col-lg-4" id="CVPCustomerName">
						<div class="d-flex form-group col-6">
							<button class="submit btn btn-primary " type="submit" onclick="PrepareLogin()"><i class="fas fa-sign-in-alt"></i> Login </button>
						</div>
						<div class="col-12" id="CVPLoginProgress"></div>
					</div>
				</div>
				<div class="row border-top hor-scroll">
					<div class="col-xl-3 col-12 ">
						<div class="card CVPpad">
							<div class="card-title"><h4 class="">DAF Connect Customers</h4>	</div>
							<div class="p-2">
								<div class="">
									<table id="CVPCustomerTable" class="dataTable row-border" width="100%"></table>
								</div>
								<div id="CVPLoadCustomerTable"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-12">
						<div class="card CVPpad">
							<div class="card-title d-flex"><div class="">Active Subscription</div></div>
							<div class="card-body">
								<div><table id="Subscriptiondata" class="dataTable row-border" width="100%"></table></div>	
								<div id="CVPLoadSubscriptionsTable"></div>
								<div id="CVPLoadSubscriptionsMessage"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-5 col-12">
						<div class="card CVPpad">
							<div class="card-title"><h4 class="">User Account Overview </h4></div>
							<div class="card-body">
								<div>
									<table id="UserAccountsdata" class="dataTable row-border" width="100%"></table>
								</div>	
								<div id="CVPLoadUserAccountsTable"></div>
							</div>
						</div>
					</div>
				</div>					
			</div>
		<section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script src="<?=$us_url_root?>js/cvp_ws.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>
window.onload=function(){
//	ShowCVPUserLogin();
	ShowCustomerTable();
	ShowCVPSubscriptionsTable();
	ShowCVPCustomerAccountsTable();
};
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
