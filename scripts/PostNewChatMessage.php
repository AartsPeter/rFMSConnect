<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	error_reporting(E_ALL);
	date_default_timezone_set('Europe/Amsterdam');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //check if its an ajax request, exit if not
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            //exit script outputting json data
            $output = json_encode(
                    array(
                        'type' => 'error',
                        'text' => 'Request must come from Ajax'
            ));
            die($output);
        } else{
            // echo "Data being processed \n";
        }
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content,true);

        //Sanitize input data using PHP filter_var().

        try {
            // echo "Trying to create user";
            $fields=array(
              'msg_thread' => $data['id'],
              'msg_body' => $data['message'],
              'msg_from' => $user->data()->id,
              'msg_read' => 0 );
            //print_r($fields);
            $db->insert('messages',$fields);
            $lastId=$db->lastId();
            $query1     = "SELECT me.*, if(me.deleted=1,'this message has been deleted',me.msg_body) AS msg_body FROM  messages me WHERE me.id =".$lastId;
            $Q1         = $db->query($query1);
            $Data1      = $Q1->results();
            echo json_encode(array('type' => 'success', 'text' => ' chat row added','message' => $Data1 ));

            $fields=array("archived" => 0,"msg_subject" => $data['message'],"last_update_by" =>$user->data()->id);
            $db->update('message_threads',$data['id'],$fields);
            return true;
          } catch (Exception $e) {
            die($e->getMessage());
          }
    }

?>