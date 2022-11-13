<?php 

require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){ Redirect::to('../index.php');} 

$errors = [];
$successes = [];

//Get line from z_us_root.php that starts with $path
$file = fopen($abs_us_root.$us_url_root."z_us_root.php","r");
while(!feof($file)){
	$currentLine=str_replace(" ", "", fgets($file));
	if (substr($currentLine,0,5)=='$path'){
		//echo $currentLine;
		//if here, then it found the line starting with $path so break to preserve $currentLine value
		break;
	}
}
fclose($file);

//sample text: $path=('/','/users/','/usersc/');
//Get array of paths, with quotes removed
$lineLength=strlen($currentLine);
$pathString=str_replace("'","",substr($currentLine,7,$lineLength-11));
$paths=explode(',',$pathString);

$pages=[];

//Get list of php files for each $path
foreach ($paths as $path){
	$rows=getPathPhpFiles($abs_us_root,$us_url_root,$path);
	foreach ((array)$rows as $row){
		$pages[]=$row;
	}
}

$dbpages = fetchAllPages(); //Retrieve list of pages in pages table

$count = 0;
$dbcount = count($dbpages);
$creations = array();
$deletions = array();

foreach ($pages as $page) {
	$page_exists = false;
	foreach ($dbpages as $k => $dbpage) {
		if ($dbpage->page === $page) {
			unset($dbpages[$k]);
			$page_exists = true;
			break;
		}
	}
	if (!$page_exists) {
		$creations[] = $page;
	}
}

// /*
//  * Remaining DB pages (not found) are to be deleted.
//  * This function turns the remaining objects in the $dbpages
//  * array into the $deletions array using the 'id' key.
//  */
$deletions = array_column(array_map(function ($o) {return (array)$o;}, $dbpages), 'id');

$deletes = '';
for($i = 0; $i < count($deletions);$i++) {
	$deletes .= $deletions[$i] . ',';
}
$deletes = rtrim($deletes,',');
//Enter new pages in DB if found
if (count($creations) > 0) {
	createPages($creations);
}
// //Delete pages from DB if not found
if (count($deletions) > 0) {
	deletePages($deletes);
}

//Update $dbpages
$dbpages = fetchAllPages();

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
					<div class="inner-pagina">
                        <div class="d-flex mr-auto page-title" >Admin Dashboard - Manage Page Access </div>
                        <div class="col-12 card">
                            <div class="tableGroups py-3">
                                <table class="display responsive pageResize noWrap" id="tablePagePermissions"></table>
                            </div>
                        </div>
                        <div class="d-flex pt-3">
                            <a href="admin.php" class="btn btn-secondary" >Return to AdminPage</a>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script>
		window.onload=function(){
			ShowPagesPermissionsTable();
		};
	</script>
	<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>