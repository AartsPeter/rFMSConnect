<?php
if (!securePage($_SERVER['PHP_SELF'])){die();} 
require_once 'config.php'; 

 if(isset($_POST["Import"])){  
    $filename=$_FILES["file"]["tmp_name"];    
    if($_FILES["file"]["size"] > 0){
		$con = getdb();
		$counter=0;
        $file = fopen($filename, "r");
		
		if(!mysqli_query($con,"TRUNCATE TABLE famreport")) { echo "\n\r failed to empty table";} else { echo "\n\r Table has been cleared";}
		echo "\n\r<div class='row'><div class='col-6'>";
        while (($getData = fgetcsv($file, 100000, ",")) !== FALSE){			
            $sql = "INSERT IGNORE INTO famreport(vin,client,OBU_Serial,enginepower,firstregistrationdate,model,numberofaxles,series,vehicleweight,obu_sw_version) 
                   values ('".$getData[18]."','".$getData[1]."','".$getData[21]."','".$getData[13]."','".$getData[14]."','".$getData[15]."','".$getData[16]."','".$getData[17]."','".$getData[19]."','".$getData[22]."')";
			if(!mysqli_query($con,$sql)) { echo "x";} else { $counter++;}
			
		}
        fclose($file);  
		echo "\n\r".$counter." records added";
		echo '</div></div >';
    }
}   


 ?>