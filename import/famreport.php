<?php
require_once '../users/init.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
require_once $abs_us_root.$us_url_root.'import/config.php';

ini_set('upload_max_filesize', '16M');
ini_set('post_max_size', '16M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);
ini_set('memory_limit','-1');
$db = DB::getInstance();
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="col-12 page-title ">Admin - Import - Vehicle/Group (FAM-Report) </div>
                        <div class="col-12">
					<?php
					if(isset($_POST["Import"])){
						$filename=$_FILES["file"]["tmp_name"];    
    					echo $filename."</br>";
						if($_FILES["file"]["size"] <10000000){
							$con = getdb();
							$UC=0;$IC=0;
//                            $sql='LOAD DATA LOCAL INFILE `uploads/'.$_FILES["file"]["tmp_name"].' IGNORE INTO TABLE `famreport` FIELDS TERMINATED BY `;` IGNORE 1 LINES (vin,client,OBU_Serial,obu_sw_version)';
//                            echo "\n".$sql;
//                            $db->query($sql);
							$file = fopen($filename, "r");
							while (($getData = fgetcsv($file, 10000000, ",")) !== FALSE){
								$sql = "INSERT INTO famreport(vin,client,OBU_Serial,obu_sw_version)
										values ('".$getData[1]."','".$getData[0]."','".$getData[2]."','".$getData[12]."')";
								if(!mysqli_query($con,$sql)) {
									$sql = "UPDATE famreport set client='".$getData[0]."', vin='".$getData[1]."',OBU_Serial='".$getData[2]."',obu_sw_version='".$getData[12]."' WHERE vin='".$getData[1]."'";
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
						$filename=$_FILES["file"]["tmp_name"];
                        echo $filename." filename</br>";
					}
					?>  
					<div class="col-12">
						<!--<div class="row pb-4">
							<div class="col-xl-2"><b><?=$IC?> </b>customers added</div>
							<div class="col-xl-2"><b><?=$UC?> </b>customers updated</div>-->
						</div>
						<a href="../import/index.php"><button type="submit" class="btn btn-primary button-loading col-2" >return to IMPORT page</button></a>
					</div>												
				</div>			
			</div>
		</section>
	</main>		

 	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
