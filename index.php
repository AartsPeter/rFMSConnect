<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';


if (!securePage($_SERVER['PHP_SELF'])){die();}

	$CRQ=$db->query("SELECT COUNT(creator) as CountedRecords FROM report_planning WHERE creator='".$user->data()->id."' and status='1' and reporting_frequency!='once'");
	$CR =$CRQ->first();
	$CountedReports= $CR->CountedRecords;

    if (isset($_SESSION['UGselected']))
        {$SCNumber=$_SESSION['UGselected'];}
    else
        {$SCNumber='*';}
    $db = DB::getInstance();
    $str1="";$str2="";$str3="";
    if ($user->data()->cust_id!='0'){
        $str2="and rel_cust_user.User_ID='".$user->data()->id."'";
        $str3=" left JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=f.client";}
    if ($SCNumber!='*') { $str1="and f.client='".$SCNumber."'";}

	$CountVehQuery="SELECT count(v.VIN) as CountedVehicles FROM vehicles v LEFT JOIN FAMReport f ON v.VIN=f.vin ".$str3." WHERE v.vehicleActive=true ".$str1." ".$str2;

	$CVQ=$db->query($CountVehQuery);
	$CV =$CVQ->first();
	$CountedVehicles= $CV->CountedVehicles;
	$Today=date("l ").", ".date("j")." ".date("F")." ".date("Y");
	$userId = $user->data()->id;
	$userPermission = fetchUserPermissions($userId);
	$permissionData = fetchAllPermissions();

    $query = "
        (SELECT c.name as groupName,	n.*
         FROM notification n LEFT JOIN famreport f on f.client=n.messagegroup LEFT JOIN customers c ON c.accountnumber=f.client ".$str3."
         WHERE n.archive=false and desktop=true and public=false and n.messagegroup<>'0' AND CURDATE() BETWEEN StartPublish AND EndPublish ".$str1." ".$str2." GROUP BY ID  )
        UNION
        (SELECT '' as groupName, n.* FROM notification n WHERE n.archive=false and desktop=true and n.messagegroup='0' AND CURDATE() BETWEEN StartPublish AND EndPublish GROUP BY ID  )
        ORDER BY EndPublish ASC";
    $AlertQ  = $db->query($query);
    $Alerts =  $AlertQ->results();

    if ($user->data()->driver_id!=''){
        require 'users/views/_dashboarddriver.php';
    }
    else {
        if($CountedVehicles > $settings->DashboardVC){
            require 'users/views/_dashboardfleet.php';
        } else {
            if ($settings->DashBoardType=='0'){
                require 'users/views/_dashboardgroup.php';
            }else{
               require 'users/views/_dashboardgroupcards.php';
            }
        }
    }
?>
<script src="<?=$us_url_root?>js/index.js"></script>
<noscript>
    <strong>We`re sorry but website doesn`t work properly without JavaScript enabled. Please enable javascript in your browser to continue.</strong>
</noscript>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';  ?>
	<script src="<?=$us_url_root?>plugins/leaflet/leaflet.js"></script>
	<script src="<?=$us_url_root?>js/easy-button.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.awesome-markers.js"></script>
	<script src="<?=$us_url_root?>plugins/notiflix/notiflix-3.2.4.min.js"></script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>

