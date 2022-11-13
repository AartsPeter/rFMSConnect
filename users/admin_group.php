<?php
require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

//PHP Goes Here!
$errors = [];
$successes = [];
$groupId = Input::get('id');
    $validation = new Validate();
//Check if selected user exists
if(!groupIdExists($groupId)){
  Redirect::to("admin_groups.php"); die();
}
$db = DB::getInstance();
$query =$db->query("SELECT * FROM customers WHERE id=".$groupId);
$groupdetails =$query->first();

$DealerQ = $db->query("SELECT name, location FROM dealers_daf ORDER BY name");
$Dealer = $DealerQ->results();
$CountriesQ = $db->query("SELECT * FROM countries ORDER BY name");
$Countries = $CountriesQ->results();

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else 
	{
		$form_valid=FALSE; // assume the worst

		$validation->check($_POST,array(
		  'name' => array(
		  'display' => 'Name',
		  'required' => true,
		  'min' => 2,
		  'max' => 74,
		  ),
		));
		if($validation->passed()) {
			$form_valid=TRUE;
			try {
			// echo "Trying to create group";
			$fields=array(
			  'name' => Input::get('name'),
			  'accountnumber' => Input::get('accountnumber'),
			  'Country' => Input::get('Country'),
			  'Service_Homedealer' => Input::get('Service_Homedealer'),
			  'isDealer' => Input::get('Isdealer'),
			);
			$db->update('customers',$groupId,$fields);
			$successes[] = lang("GROUP_UPDATED");

			} catch (Exception $e) {
			die($e->getMessage());
			}
		}	
		$db = DB::getInstance();
		$query =$db->query("SELECT * FROM customers WHERE id='".$groupId."'");
		$groupdetails =$query->first();
	}
}



?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina inner-pagina">
				    <div class="col-12 page-title ">Admin - Group settings - Group detail</div>
					<div class="col-12">
                        <form class="form" name='adminUser' action="" method="post">
                            <div class="row">
                                <div class="card col-12 col-md-8 col-lg-6">
                                    <div class="card-body ">
                                        <div class="row px-2">
                                            <div class="form-group col-8 p-1">
                                                <label>Group Name:</label>
                                                <input  class='form-control' type='text' name='name' value='<?=$groupdetails->name?>' />
                                            </div>
                                            <div class="form-group col-2 ml-auto p-1">
                                                <label>Code:</label>
                                                <input  class='form-control' type='text' name='accountnumber' value='<?=$groupdetails->accountnumber?>' />
                                            </div>
                                        </div>
                                        <div class="row px-2">
                                            <div class="form-group col-6 p-1">
                                                <label>Country:</label>
                                                <select id="land" class="form-control p-0" name="Country" value="" >
                                                    <option class="DeActiveGroup" value=" " >select a country </option>
                                            <?php foreach ($Countries as $row){
                                                    if ($row->name==$groupdetails->Country){ ?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row->name);?>" selected > <?php echo ucfirst($row->name);?></option>
                                            <?php } else {?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row->name);?>" > <?php echo ucfirst($row->name);?></option>
                                            <?php }?>
                                            <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row px-2">
                                            <div class="form-group col-6 p-1">
                                                <label>Home Dealer:</label>
                                                <select id="group" class="form-control p-0" name="Service_Homedealer" value="" >
                                                    <option class="ActiveGroup" value="0">select a Dealer</option>
                                            <?php foreach ($Dealer as $row){
                                                    if ($row->name==$groupdetails->Service_Homedealer){ ?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row->name);?>" selected > <?php echo ucfirst($row->name);?></option>
                                            <?php } else {?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row->name);?>" > <?php echo ucfirst($row->name);?></option>
                                            <?php }?>
                                            <?php }?>
                                                </select>
                                            </div>
                                            <div class="form-group col-6 p-1">
                                                <label>Is Dealer:</label>
                                                <select id="dealer" class="form-control p-0" name="IsDealer" value="" >
                                                    <option class="DeActiveGroup" value="0" >select a Dealer</option>
                                                <?php foreach ($Dealer as $row1){
                                                    if ($row1->location==$groupdetails->IsDealer){ ?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row1->location);?>" selected > <?php echo ucfirst($row->name);?></option>
                                            <?php } else {?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($row1->location);?>" > <?php echo ucfirst($row1->name);?></option>
                                                <?php }?>
                                            <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="">
                                    <div class="">
                                        <input type="hidden" name="csrf" value="<?=Token::generate();?>" />
                                        <input class="btn btn-primary " type="submit" value="Update" class="submit" />
                                        <input class="btn btn-secondary"  onclick="history.back();" value="cancel / return" />

                                    </div>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="">
                                    <?=resultBlock($errors,$successes);?>
                                    <?=$validation->display_errors();?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script>
new SlimSelect({
  select: '#land'
});
new SlimSelect({
  select: '#dealer'
});
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
