<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
require_once 'config.php'; 
ini_set('upload_max_filesize', '16M');
ini_set('post_max_size', '16M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);
ini_set('memory_limit','-1');
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="row p-2">
					<div class="col-12">
						<div class="row ">
							<div class="mr-auto page-title primary">Import customers</div>
						</div>
					</div>
				</div>
				<div class="row">	
					<?php
					if(isset($_POST["Import"])){  
						$filename=$_FILES["file"]["tmp_name"];    
						echo $filename."</br>";
						if($_FILES["file"]["size"] <10000000){
							$con = getdb();
							$UC=0;$IC=0;
							$file = fopen($filename, "r");
							while (($getData = fgetcsv($file, 10000000, ";")) !== FALSE){		
								$sql = "INSERT INTO customers (accountnumber,name,Country,Service_Homedealer) 
										values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."')";
								if(!mysqli_query($con,$sql)) { 
									$sql = "UPDATE customers set accountnumber='".$getData[0]."', name='".$getData[1]."',Country='".$getData[2]."',Service_Homedealer='".$getData[3]."' WHERE accountnumber='".$getData[0]."'";
									if(!mysqli_query($con,$sql)) {} else { $UC++;}	
								}
								else { $IC++;}				
							}
							fclose($file);  
						}
						else {echo "file too big";	}
					} 
					else {
						echo "No file selected to import";
					}
					?>  
					<div class="col-12">
						<div class="row pb-4">
							<div class="col-xl-2"><b><?=$IC?> </b>customers added</div>
							<div class="col-xl-2"><b><?=$UC?> </b>customers updated</div>
						</div>
						<a href="../import/index.php"><button type="submit" class="btn btn-primary button-loading col-2" >return to IMPORT page</button></a>
					</div>												
				</div>			
			</div>
		</section>
	</main>		

 	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
