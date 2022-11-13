<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 
//PHP Goes Here!
$errors = [];
$successes = [];

$SearchId = Input::get('id');
$validation = new Validate();
//Check if selected user exists
if(!IdExists($SearchId,'notifications')){
  Redirect::to("notifications.php"); die();
}
$db = DB::getInstance();

$str1="";$str2="";$str3="";
if ($user->data()->cust_id!='0'){
    $str2 = " and rel_cust_user.User_ID='".$user->data()->id."'";
    $str3 = " left JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=f.client";}

$query = "SELECT c.name, c.accountnumber
    FROM vehicles v
        LEFT JOIN FAMReport F ON v.VIN=F.vin
        LEFT JOIN CUSTOMERS c on c.accountnumber=f.client ".$str3."
    WHERE v.vehicleActive=true  ".$str1." ".$str2."
    GROUP BY c.name";

$GQ =$db->query($query);
$MessageGroup = $GQ->results();

$query =$db->query("
	SELECT * FROM notification WHERE id=".$SearchId);
$notedetails =$query->first();
$NQ =$db->query($query);
$notedetails =$NQ->first();

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else
	{
		if ($notedetails->name != $_POST['name']){
			$displayname = Input::get("name");
			$fields=array('name'=>$displayname);
			$validation->check($_POST,array('name' => array('display' => 'name','required' => true,'min' => 2,'max' => 64	)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "Name Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->description != $_POST['description']){
			$displayname = Input::get("description");
			$fields=array('description'=>$displayname);
			$validation->check($_POST,array('description' => array('display' => 'description','required' => true,'min' => 2,'max' => 120	)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "description Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->group != $_POST['messagegroup']){
			$displayname = Input::get("messagegroup");
			$fields=array('messagegroup'=>$displayname);
			$validation->check($_POST,array('messagegroup' => array('display' => 'messagegroup')));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "group Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->notificationHeader != $_POST['notificationHeader']){
			$displayname = Input::get("notificationHeader");
			$fields=array('notificationHeader'=>$displayname);
			$validation->check($_POST,array('notificationHeader' => array('display' => 'notificationHeader','required' => false	)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "notificationHeader Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->notificationInfo != $_POST['notificationInfo']){
			$displayname = Input::get("notificationInfo");
			$fields=array('notificationInfo'=>$displayname);
			$validation->check($_POST,array('notificationInfo' => array('display' => 'notificationInfo','required' => true)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "notificationInfo Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->notificationType != $_POST['notificationType']){
			$displayname = strtolower(Input::get("notificationType"));
			$fields=array('notificationType'=>$displayname);
			$validation->check($_POST,array('notificationType' => array('display' => 'notificationType'	)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "notificationType Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->public != $_POST['public']){
			if(isset($_POST['public'])) {$public =1;} else {$public =0;}
			$fields=array('public'=>$public);
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "Show at Login Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->public != $_POST['public']){
			if(isset($_POST['desktop'])) {$desktop =1;} else {$desktop =0;}
			$fields=array('desktop'=>$desktop);
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "Show on Desktop Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->archive != $_POST['archive']){
			if(isset($_POST['archive'])) {$archive =1;} else {$archive =0;}
			$fields=array('archive'=>$archive);
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "Note will be archived";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->startPublish != $_POST['startPublish']){
			$displayname = Input::get("startPublish");
			$fields=array('startPublish'=>$displayname);
			$validation->check($_POST,array('startPublish' => array('display' => 'startPublish','required' => true	)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "Publish startdate updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($notedetails->endPublish != $_POST['endPublish']){
			$displayname = Input::get("endPublish");
			$fields=array('endPublish'=>$displayname);
			$validation->check($_POST,array('endPublish' => array('display' => 'endPublish','required' => true)));
			if($validation->passed()){
				$db->update('notification',$SearchId,$fields); $successes[] = "endPublish Updated";
			}
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		$db = DB::getInstance();
		$query =$db->query("
			SELECT * FROM notification WHERE id=".$SearchId);
		$notedetails =$query->first();
	}
}
?>

	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina ">
                        <div class="page-title col-12">Admin - Notification - Note</div>
                        <div class="col-12 col-md-10 col-xl-9 card ">
                                <div class="card-body ">
                                    <form class="form-signup " action="" method="POST" id="editvehicle-form">
                                        <div class="row pt-2">
                                            <div class="col-12 p-0 bg-default">
                                                <div class='row'>
                                                    <div class='col-9 p-0'>
                                                        <div class="row col-12 pr-0">
                                                            <div class="col-6 pb-2">
                                                                <label>name</label>
                                                                <input class="form-control" type="text" name="name" id="name" placeholder="" value="<?php echo ucfirst($notedetails->name);?>" required autofocus>
                                                            </div>
                                                            <div class="col-6 pr-0 pb-2">
                                                               <label>description</label>
                                                               <input class="form-control" type="text" name="description" id="description" placeholder="" value="<?php echo ucfirst($notedetails->description);?>" >
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <label>header </label>
                                                            <textarea class="form-control cke_editable cke_editable_inline cke_contents_ltr cke_show_borders" type="text-area" name="notificationHeader" id="notificationHeader" placeholder="" ><?php echo ucfirst($notedetails->notificationHeader);?></textarea>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <label>content body text</label>
                                                            <textarea class="form-control cke_editable cke_editable_inline cke_contents_ltr cke_show_borders" type="text-area" name="notificationInfo" id="notificationInfo" placeholder="" ><?php echo ucfirst($notedetails->notificationInfo);?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class='col-3 p-0 ml-auto'>
                                                        <div class="col-12 pb-2">
                                                            <label>group</label>
                                                            <select class="form-control"  name="messagegroup" id="messagegroup" >
                                                            <?php
                                                            if (sizeof($MessageGroup)>1){
                                                                if ($notedetails->group==0){ echo '<option class="text-primary" value="0" selected><b>All groups</option>'; }
                                                                else               { echo '<option class="DeActiveGroup" value="0"><b>All groups</option>'; }
                                                            }
                                                            foreach ($MessageGroup as $row){
                                                                if ($row->accountnumber==$notedetails->messagegroup)
                                                                    { echo '<option class="text-primary" value="'.ucfirst($row->accountnumber).'" selected > '.ucfirst($row->name).'</option>'; }
                                                                else
                                                                    { echo '<option class="DeActiveGroup" value="'.ucfirst($row->accountnumber).'" > '.ucfirst($row->name).'</option>'; }
                                                            }
                                                            ?>
                                                             </select>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <label>type</label>
                                                            <input class="form-control " type="text" name="notificationType" id="notificationType" placeholder="" value="<?php echo ucfirst($notedetails->notificationType);?>" >
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <label>start date publish </label>
                                                            <div class='input-group date ' id='datetimepicker1'>
                                                                <input type='text' class="form-control input-group text-left" name="startPublish" id="startPublish" placeholder="" value="<?php echo ucfirst($notedetails->startPublish);?>" >
                                                                <span class="input-group-addon btn btn-primary">
                                                                    <span class="far fa-calendar-alt"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <label>end date publish </label>
                                                            <div class='input-group date ' id='datetimepicker1'>
                                                                <input type='text' class="form-control input-group text-left" name="endPublish" id="endPublish" placeholder="" value="<?php echo ucfirst($notedetails->endPublish);?>">
                                                                <span class="input-group-addon btn btn-primary">
                                                                    <span class="far fa-calendar-alt"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pt-2 pb-2">
                                                            <div class="custom-control custom-switch custom-switch-sm">
                                                                <input type='checkbox' class="custom-control-input" id='public' name='public' <?php echo ($notedetails->public==1 ? 'checked' : '');?>   >
                                                                <label class="custom-control-label" for="public">show at login</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <div class="custom-control custom-switch custom-switch-sm">
                                                                <input type='checkbox' class="custom-control-input" id='desktop' name='desktop' <?php echo ($notedetails->desktop==1 ? 'checked' : '');?>   >
                                                                <label class="custom-control-label" for="desktop">show at dashboard</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pb-2">
                                                            <div class="custom-control custom-switch custom-switch-sm">
                                                                <input type='checkbox' class="custom-control-input" id='archive' name='archive' <?php echo ($notedetails->archive==1 ? 'checked' : '');?>   >
                                                                <label class="custom-control-label" for="archive">archive</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-12 p-0 col-md-4">
                                                <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                                <input class="btn btn-primary" type='submit' name='changenote' value="Update / Save" />
                                                <a class="btn btn-secondary mx-2" href="notifications.php">cancel / return</a>
                                            </div>
                                            <div class="col-12 col-md-5"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
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


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/features/pageResize/dataTables.pageResize.min.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>
<script>
//    new SlimSelect({select: '#messagegroup',showSearch: false});
    ClassicEditor
        .create( document.querySelector( '#notificationInfo' ) )
        .then( editor => {console.log( editor ); } )
        .catch( error => {console.error( error );} );
    ClassicEditor
        .create( document.querySelector( '#notificationHeader' ) )
        .then( editor => {console.log( editor ); } )
        .catch( error => {console.error( error );} );
    new SlimSelect({ select: '#messagegroup'});
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
