<?php
$db = DB::getInstance();
$query = $db->query("SELECT * FROM email");
$results = $query->first();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <p>Hello <?=$fname;?>,</p>
    <p>You are receiving this email because your password was reset. If this was not you, Please contact the administrator.</p>
    <p>Sincerely,</p>
    <p><br>rFMS-Reader.nl</p>
  </body>
</html>
