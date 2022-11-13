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
//ini_set('memory_limit','-1');
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="row p-2">
					<div class="col-12">
						<div class="row ">
							<div class="mr-auto page-title primary">Import vehicle / customer relation</div>
						</div>
					</div>
				</div>
				<div class="row">	
					<?php
					if(isset($_POST["Import"])){  
						$filename=$_FILES["file"]["tmp_name"]; 
						echo $filename;						
						if($_FILES["file"]["size"] <16000000){
							$con = getdb();
							$UC=0;$IC=0;$RC=0;
							//$records=count(file($filename));
							$file = fopen($filename, "r");
							
							while (($getData = fgetcsv($file, 16000000, ";")) !== FALSE){	
								if ($getData[1]!=''){
									$sql = "INSERT INTO famreport(vin,client,OBU_Serial,enginepower,firstregistrationdate,model,numberofaxles,series,vehicleweight,obu_sw_version) values "
									$sql+="('".$getData[18]."','".$getData[1]."','".$getData[21]."','".$getData[13]."','".$getData[14]."','".$getData[15]."','".$getData[16]."','".$getData[17]."','".$getData[19]."','".$getData[22]."')";
									if(!mysqli_query($con,$sql)) { 
										$sql = "UPDATE famreport set vin='".$getData[18]."', client='".$getData[1]."',OBU_Serial='".$getData[21]."',enginepower='".$getData[13]."',firstregistrationdate='".$getData[14]."',model='".$getData[15]."',numberofaxles='".$getData[16]."',series='".$getData[17]."',vehicleweight='".$getData[19]."',obu_sw_version='".$getData[22]."' WHERE vin='".$getData[18]."'";
										if(!mysqli_query($con,$sql)) {} else { $UC++;}	
									}
									else { $IC++;}				
								}
								$RC++;							
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
							<div class="col-xl-2"><b><?=$IC?> </b>vehicles added</div>
							<div class="col-xl-2"><b><?=$UC?> </b>vehicles updated</div>
							<div class="col-xl-2"><b><?=$RC?> </b>of records in file</div>
						</div>
						<a href="../import/index.php"><button type="submit" class="btn btn-primary button-loading col-2" >return to IMPORT page</button></a>
					</div>												
				</div>			
			</div>
		</section>
	</main>		

 	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
