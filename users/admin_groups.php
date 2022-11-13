<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$errors = $successes = [];
$form_valid=TRUE;


//Forms posted
if (!empty($_POST)) {
  //Manually Add Group
  if(!empty($_POST['addGroup'])) {
    $join_date = date("Y-m-d H:i:s");
    $token = $_POST['csrf'];
    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }
    $form_valid=FALSE; // assume the worst
    $validation = new Validate();
    $validation->check($_POST,array(
      'name' => array(
      'display' => 'Name',
      'required' => true,
      'min' => 2,
      'max' => 74,
      ),
      'code' => array(
      'display' => 'Code',
      'required' => true,
      'min' => 2,
      'max' => 19,
      ),
    ));
  	if($validation->passed()) {
		$form_valid=TRUE;
      try {
        // echo "Trying to create group";
        $fields=array(
          'name' => Input::get('name'),
          'accountnumber' => Input::get('code'),
          'Country' => Input::get('country'),
          'Service_Homedealer' => Input::get('HomeDealer'),
          'isDealer' => Input::get('hdealer'),
        );
        $db->insert('customers',$fields);

        $successes[] = lang("ACCOUNT_USER_ADDED");

      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }
}

$DealerQ = $db->query("SELECT name, location FROM dealers_daf ORDER BY name");
$Dealer = $DealerQ->results();

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="mr-auto page-title" ><a href="admin.php">Admin Dashboard </a> - Group Overview</div>
                        <div class="col-12 card">
                            <div class="tableUsers py-3 ">
                                <table class="display table noWrap" id="tableGroups" style="width:100%">	</table>
                            </div>
                        </div>
                        <div class="mt-3 d-flex">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#NewGroupModal" >Create new Group</button>
                            <a href="admin.php" class="btn btn-secondary mx-1" >Return to AdminPage</a>
                            <?php echo resultBlock($errors,$successes);	?>
                            <?php if (!$form_valid && Input::exists()){echo display_errors($validation->errors());}   ?>
                        </div>
                    </div>
                    <div id="NewGroupModal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
                            <div class="modal-content">
                                <div class="modal-body" id="EditGroupDetails">
                                    <form class="form-signup" action="admin_groups.php" method="POST" id="payment-form">
                                        <div class="card ">
                                            <div class="card-title">New Group
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 padding-10"><input class="form-control" type="text" 	  name="name" 		id="name" 		placeholder="Name" value="" required autofocus></div>
                                                    <div class="col-6 padding-10"><input class="form-control" type="text" 	  name="code" 		id="code" 		placeholder="Code" value="" required ></div>
                                                    <div class="col-6 padding-10"><input class="form-control" type="text" 	  name="country"	id="country" 	placeholder="Country" value="" required></div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-12 col-xl-6">
                                                        <label>Home Dealer </label>
                                                        <select id="group" class="form-control" name="HomeDealer" value="" >
                                                            <option class="DeActiveGroup" value="0" >  </option>
                                                    <?php foreach ($Dealer as $row){ ?>
                                                            <option class="DeActiveGroup" value="<?php echo ucfirst($row->name);?>" > <?php echo ucfirst($row->name);?></option>
                                                    <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 col-xl-6">
                                                        <label>Group is Dealer </label>
                                                        <select id="perm" class="form-control" name="IsDealer" value="" >
                                                            <option class="DeActiveGroup" value="0" > </option>
                                                        <?php foreach ($Dealer as $row1){ ?>
                                                            <option class="DeActiveGroup" value="<?php echo ucfirst($row1->location);?>" > <?php echo ucfirst($row1->name);?></option>
                                                        <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 p-3  mb-3">
                                                    <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                                    <input class='btn btn-primary pull-right' type='submit' name='addGroup' value='Create Group' />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';  ?>

<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/features/pageResize/dataTables.pageResize.min.js"></script>

<script>
window.onload=function(){
	ShowGroupAdminTable();
};
</script>


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
