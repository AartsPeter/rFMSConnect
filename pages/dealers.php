<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="col-12 page-title ">Resources » Dealers </div>
                        <div class="col-12  card card-body border-0 py-3">
                            <div class="tableGroups p-0">
                                <table class="display table responsive noWrap" id="tableDealer"> </table>
                            </div>
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
	ShowDealerTable();
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
