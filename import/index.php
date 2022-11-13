<?php
require_once '../users/init.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
require_once 'config.php'; 
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">	
					<div class="mr-auto page-title">Import <small><small> upload  CSV-files</small></small></div>
					<div class="row">
						<div class="col-6 col-md-4 col-xl-3">
							<div class="card VehicleDetails">
								<div class="card-header">Update Vehicle/Group relation</div>
								<div class="card-body">
									<div class="import pt-3 ">upload <b> CSV-file</b> to maintain vehicle group relation (max 100.000 vehicles)</div>
									<form class="row d-flex pt-3" action="famreport.php" method="post" name="upload_excel" enctype="multipart/form-data">
										<div class="col-12 form-group ">
											<input type="file" name="file" id="famreport" class="col-12 p-0">
										</div>
										<div class="col-12 form-group ml-auto ">
											<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading col-4" data-loading-text="Loading..."> <i class="fad fa-upload fa-fw"></i> Import</button>
										</div>		
									</form>
								</div>
							</div>
						</div>
<!--						<div class="col-6 col-md-4 col-xl-3">
							<div class="card border shadow-sm">
								<div class="card-header">Update FAM-Report</div>
								<div class="card-body">
									<div class="import pt-3">upload <b>qryAllChassis CSV-file</b> to maintain vehicle customer relation (max 100.000 vehicles)</div>
									<form class="row d-flex p-0 pt-3" action="allchassis.php" method="post" name="upload_excel" enctype="multipart/form-data">
										<div class="col-12 form-group ">
											<input type="file" name="file" id="allchassis" class="input-large">	
										</div>
										<div class="col-12 form-group ml-auto ">
											<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
										</div>							
									</form>
								</div>
							</div>
						</div>-->
						<div class="col-6 col-md-4 col-xl-3">
							<div class="card">
								<div class="card-header">Update Group</div>
								<div class="card-body">
									<div class="import pt-3">upload Group definition CSV-file (max 100.000 entries)</div>
									<form class="row d-flex pt-3" action="customers.php" method="post" name="upload_excel" enctype="multipart/form-data">
										<div class="col-12 form-group ">
											<input type="file" name="file" id="customers" class="col-12 p-0 ">	
										</div>
										<div class="col-12 form-group ml-auto ">
											<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
										</div>
										<div id="importprogress"></div>
									</form>
								</div>
							</div>
						</div>
						<div class="col-6 col-md-4 col-xl-3">
							<div class="card">
								<div class="card-header">Update Drivers </div>
								<div class="card-body">
									<div class="import pt-3">upload Drivers CSV-file  (max 100.000 drivers)</div>							
									<form class="row d-flex pt-3" action="drivers.php" method="post" name="upload_excel" enctype="multipart/form-data">
										<div class="col-12 form-group ">
											<input type="file" name="file" id="drivers" class="col-12 p-0 ">	
										</div>
										<div class="col-12 form-group ml-auto ">
											<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload  fa-fw"></i> Import</button>
										</div>
										<div id="importprogress"></div>									
									</form>
								</div>
							</div>
						</div>
						<div class="col-6 col-md-4 col-xl-3">
							<div class="card ">
								<div class="card-header">Update Historical rFMS API-data </div>
								<div class="card-body">
									<div class="import pt-3">upload rFMS-API JSON-file  (max 12 Mb)</div>							
									<form class="row d-flex pt-3" action="drivers.php" method="post" name="upload_excel" enctype="multipart/form-data">
										<div class="col-12 form-group ">
											<input type="file" name="file" id="drivers" class="col-12 p-0">	
										</div>
										<div class="col-12 form-group ml-auto ">
											<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading col-4" data-loading-text="Loading..."><i class="fad fa-upload fa-fw"></i> Import</button>
										</div>
										<div id="importprogress"></div>									
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row pt-3">
						<div class="col-12 col-md-6 mr-auto">
							<div class="card alert-secondary">
								<div class="card-header text-secondary">HELP</div>
								<div class="card-body">
									<div class="import">This page will help you update some critical mass data based on CSV-file.
										<ul>
											<li>Always use '<span class="info-box-number danger">;</span>' as column seperator</li>
											<li> Processing of large files can take a few minutes, please only use incremental files</li>
										</ul><br>
										<span class="card-title">Examples of the files:</span>
										<ul>
											<li><a href="*">FAM-report (vehicle-group relation) <i class="fad fa-file-download"></i></a></li>
											<li><a href="*">Group CSV-file <i class="fad fa-file-download"></i></a></li>
											<li><a href="*">Driver CSV-file <i class="fad fa-file-download"></i></a></li>
										</ul>										
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

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
 