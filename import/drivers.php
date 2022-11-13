<?php
require_once '../users/init.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
require_once 'config.php'; 

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="row p-2">
					<div class="col-12">
						<div class="row ">
							<div class="mr-auto page-title primary">Import Drivers</div>
						</div>
					</div>
				</div>
				<div class="row">	
					<?php
					 if(isset($_POST["Import"])){  
						$filename=$_FILES["file"]["tmp_name"];    
						if($_FILES["file"]["size"] > 0){
							$con = getdb();
							$UC=0;$IC=0;
							$file = fopen($filename, "r");
							while (($getData = fgetcsv($file, 0, ';')) !== FALSE){	
								$sql = "INSERT INTO driver (Surname,Lastname,eMail,tachoDriverIdentification) values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[7]."')";
								if(!mysqli_query($con,$sql)) { 
									$sql = "UPDATE driver set surname='".$getData[0]."', lastname='".$getData[1]."',eMail='".$getData[2]."' WHERE tachoDriverIdentification='".$getData[7]."'";
									if(!mysqli_query($con,$sql)) {} else {  $UC++;}	
								}
								else { $IC++;}				
							}
							fclose($file);  
						}
					}  ?>  
					<div class="col-12">
						<div class="row pb-4">
							<div class="col-xl-2"><b><?=$IC?> </b>drivers added</div>
							<div class="col-xl-2"><b><?=$UC?> </b>drivers updated</div>
						</div>
						<a href="../import/index.php"><button type="submit" class="btn btn-primary button-loading col-2" >return to IMPORT page</button></a>
					</div>												
				</div>			
			</div>
		</section>
	</main>		

 	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
