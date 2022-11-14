<?php 
require_once '../users/init.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 

$db = DB::getInstance();
$settingsQ = $db->query("Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."'");
$settings = $settingsQ->first();
$errors = $successes = [];
$form_valid=TRUE;
$permOpsQ = $db->query("SELECT * FROM permissions");
$permOps = $permOpsQ->results();
// dnd($permOps);

//Forms posted
if (!empty($_POST)) {
  //Delete User Checkboxes
  if (!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)){ $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count)); }
    else {$errors[] = lang("SQL_ERROR"); }
  }
  //Manually Add User
  if(!empty($_POST['addUser'])) {
	  echo "Step1";
    $join_date = date("Y-m-d H:i:s");
    $username = Input::get('username');
  	$fname = Input::get('fname');
  	$lname = Input::get('lname');
 	$email = Input::get('email');
    $token = $_POST['csrf'];

    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }
	echo "Step2";
    $form_valid=FALSE; // assume the worst
    $validation = new Validate();
    $validation->check($_POST,array(
      'username' => array(
      'display' => 'Username',
      'required' => true,
      'min' => $settings->min_un,
      'max' => $settings->max_un,
      'unique' => 'users',
      ),
      'fname' => array(
      'display' => 'First Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
      'lname' => array(
      'display' => 'Last Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
      'email' => array(
      'display' => 'Email',
      'required' => true,
      'valid_email' => true,
      'unique' => 'users',
      ),
      'password' => array(
      'display' => 'Password',
      'required' => true,
      'min' => $settings->min_pw,
      'max' => $settings->max_pw,
      ),
      'confirm' => array(
      'display' => 'Confirm Password',
      'required' => true,
      'matches' => 'password',
      ),
    ));
  	if($validation->passed()) {
		$form_valid=TRUE;
      try {
        // echo "Trying to create user";
        $fields=array(
          'username' => Input::get('username'),
          'fname' => Input::get('fname'),
          'lname' => Input::get('lname'),
          'email' => Input::get('email'),
          'password' =>
          password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
          'permissions' => 1,
          'account_owner' => 1,
          'stripe_cust_id' => '',
          'join_date' => $join_date,
          'company' => Input::get('company'),
          'email_verified' => 1,
          'active' => 1,
          'vericode' => 111111,
        );
        $db->insert('users',$fields);
        $theNewId=$db->lastId();
        // bold($theNewId);
        $perm = Input::get('perm');
        $addNewPermission = array('user_id' => $theNewId, 'permission_id' => $perm);
        $db->insert('user_permission_matches',$addNewPermission);
        $db->insert('profiles',['user_id'=>$theNewId, 'bio'=>'This is your bio']);

        if($perm != 1){
          $addNewPermission2 = array('user_id' => $theNewId, 'permission_id' => 1);
          $db->insert('user_permission_matches',$addNewPermission2);
        }

        $successes[] = lang("ACCOUNT_USER_ADDED");
		return true;
      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }

}
