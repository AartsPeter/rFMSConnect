<?php

require_once 'init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();}
if($settings->messaging != 1){
  Redirect::to('account.php?err=Messaging+is+disabled');
}
//PHP Goes Here!
$validation = new Validate();
$userID = $user->data()->id;
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$id = Input::get('id');

if(!empty($_POST['send_message'])){

  $token = $_POST['csrf'];
  if(!Token::check($token)){
    die('Token doesn\'t match!');
  }
  $date = date("Y-m-d H:i:s");

  $thread = array(
    'msg_from'    => $user->data()->id,
    'msg_to'      => $id,
    'msg_subject' => Input::get('msg_subject'),
    'last_update' => $date,
    'last_update_by' => $user->data()->id,
  );
  $db->insert('message_threads',$thread);
  $newThread = $db->lastId();


  $fields = array(
    'msg_from'    => $user->data()->id,
    'msg_to'      => $id,
    'msg_body'    => Input::get('msg_body'),
    'msg_thread'  => $newThread,
    'sent_on'     => $date,
  );

  $db->insert('messages',$fields);

  Redirect::to('create_message.php?msg=Message+sent!');
}

if(!empty($_POST['search'])){
  $query = Input::get('input');
  $query = htmlspecialchars($query);
  $usersQ = $db->query("SELECT * FROM users WHERE username LIKE '%$query%' OR fname LIKE '%$query%' OR lname LIKE '%$query%' LIMIT 200");
  $count = $usersQ->count();

  if($count > 0){
    $users = $usersQ->results();
  }
}


?>

	<main role="main">
		<section class="section section-full ">
			<div class="container">
				<div class="row pt-2">
						<div class="col-6"> <h3>Your messages</h3></div>
					<div class="col-2"> <a class="btn btn-primary" href="create_message.php">Compose New Message</a></div>
				</div>
      <div class="row">

        <!-- if no id was set -->
        <?php if(!empty($id)){ ?>
          <div class="col-xs-12 col-md-10">
            <h3>Create message</h3>
            <form name="create_message" action="create_message.php?id=<?=$id?>" method="post">

              <label>Subject:</label>
              <div align="center">
                <input required size='100' class='form-control' type='text' name='msg_subject' value='' />
                <textarea rows="20" cols="80"  id="mytextarea" name="msg_body" ></textarea></div>
                <input required type="hidden" name="csrf" value="<?=Token::generate();?>" >
              </p>
              <p>
                <input class="btn btn-primary" type="submit" name="send_message" value="Send Message">


              </p>

            </form>
            <?php }else{ ?>
              <form class="" name="search" action="create_message.php" method="post">
                <label for="system-search">Find someone to message!</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="input" id="system-search" value="" placeholder="Search for a user">
                  <input class="btn btn-primary" type="submit" name="search" value="Search">
                </div>
              </form>
              <?php if(isset($count) && $count >0){ ?>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($users as $u){  ?>
                      <tr>
                        <td><a href="create_message.php?id=<?=$u->id?>"><?=$u->username?></a></td>
                        <td><a href="create_message.php?id=<?=$u->id?>"><?=$u->fname?></a></td>
                        <td><a href="create_message.php?id=<?=$u->id?>"><?=$u->lname?></a></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

                  <?php
                }
              }
              ?>

            </div>
          </div>
        </div>


      </div> <!-- /container -->

    </div> <!-- /#page-wrapper -->
    <!-- If you disable this script below you will get a standard textarea with NO WYSIWYG editor. That simple -->
    <?php if ($settings->wys == 1){  ?>
      <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
      <script>
      tinymce.init({
        selector: '#mytextarea'
      });
      </script>
      <?php } ?>

      <!-- footers -->
      <?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

      <!-- Place any per-page javascript here -->

      <?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
