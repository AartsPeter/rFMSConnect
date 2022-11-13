
	<main role="main">
		<section class="section section-mobile">
			<div class="container-fluid center">
			    <div class="pagina">
                    <div class="pdc-card-pagina  m-auto" id="pdc_main">
                        <div class="col-12">
                            <div class="row">
								<div class="col-6">
									<div class="larger bold pb-2"><?=lang("DASH_Welcome","");?> <?=$user->data()->fname?> </div>
								</div>
<!--								<div class="col-6 text-right ml-auto py-4">
                                    <div class="text-right ml-auto text-date "><b><i class="fad fa-calendar-day"></i> <?php echo $Today;?></b> </div>
								</div>-->
                            </div>
                            <div class="row pt-1">
                                <div class="col-12 ">
                                    <div class="card shadow-sm mb-3">
                                        <div class="card-header">
                                            <i class="fad fa-digital-tachograph fa-fw"></i> Driving activities
                                            <span class="ml-auto small"> (<?=$user->data()->driver_id?>) </span>
                                        </div>
                                        <div class="card-body" id="ShowTachoStatus"></div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="card shadow-sm mb-3">
                                        <div class="card-header"><i class="fal fa-clipboard-list-check fa-fw"></i> Pre-Departure Check</div>
                                        <div class="card-body " id="CountedDamage">	</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</section>
		</div>  
	</main>		
	<script src="<?=$us_url_root?>js/driver.js"></script>
	<script>
        window.onload=function(){
        CheckNewChats();
        ShowDriverTachoStatus("<?=$user->data()->driver_id?>","ShowTachoStatus");
        setInterval(function(){ CheckNewChats();},10000);
        }
    </script>