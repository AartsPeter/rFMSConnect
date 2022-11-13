<?php
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();}

if(!empty($_POST['clear'])){
	$db->query("TRUNCATE TABLE audit");
	Redirect::to("tomfoolery.php?err=All+events+have+been+deleted");
}

?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina inner-pagina">
					<div class="page-title pb-2 col-4">Security Log </div>                   
					<div class="row">
						<div class="col-12">
							<div class="col-12 card " >
								<table class="display table noWrap " id="AuditTable"></table>
							</div>
						</div>
						<div class="col-12 mt-3">
							<a href="admin.php" class="btn btn-secondary" >return to Admin page</a>
						</div>
<!--						<form class="col-12" action="tomfoolery.php" method="post">
							<div class=" py-2">
								<input class='btn btn-large btn-primary' type='submit' name="clear" value='Clear All Logs'/>
								
							</div>
						</form>-->
					</div>
				</div>
			</div>
		</section>
	</main>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script>
       	window.onload=function(){  ShowAdminAuditTable()};
    </script>


	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
