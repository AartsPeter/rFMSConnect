<?php
//This is what happens after a user logs out. Where do you want to send them?  What do you want to do?
$user->logout();
Redirect::to('../users/login');


?>