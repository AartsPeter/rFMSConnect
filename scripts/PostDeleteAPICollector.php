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
        }
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content,true);
        //Sanitize input data using PHP filter_var().
        $db->deleteById('api_collector',$data['id']);
        echo json_encode(array('type' => 'succes', 'text' => ' API_Collector account deleted'));
    }

?>