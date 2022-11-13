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
                        <div class="col-12 d-flex">
                            <div class="page-title mr-auto">Resources » Drivers</div>
                            <div class="form-group  ml-auto">
                                <div class="dropdown ">
                                    <button type="button" class="btn btn-secondary  shadow-sm col-12" data-toggle="dropdown"> <i class='fal fa-envelope'></i> - report  </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" onclick="CreateReport('Drivers',false);" href="#">Now</a>
                                        <a class="dropdown-item" onclick="CreateReport('Drivers',true);"  href="#">Schedule</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12  card card-body border-0 py-3">
                            <table class="display table noWrap" id="tableDrivers">	</table>
                        </div>
                    </div>
				</div>
			</div>	
		</section>	
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>
	window.onload=function(){
		ShowDriverTable();
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>