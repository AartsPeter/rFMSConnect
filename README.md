# rFMSConnect
Welcome,

This repository contains the front-end application of the POC called '**rFMSConnect**'.
The POC environment was created during my work at DAF Connect as part of creating a playground with DAF Connect data based on the rFMS-standard. The rFMS-Standard was created on initiatieve of the ACEA to ensure a standard for exchanging OEM vehicle data between OEM's and customers with their services.

The POC environment consists of two main parts, the rFMS-API-ETL and the rFMSConnect.

The application is developed in PHP and Javascript based on a version 4.3 of UserSpice(C) and uses a few basic JS-libraries like Leaflet, DataTables, HighCharts, Bootstrap, Notiflix, jQuery, SLimSelect, FontAwesome, CKEditor.

```php
$GLOBALS['config'] = array(
'mysql'      => array(
'host'         => 'localhost',
'username'     => '--fill-in your useraccount to connect to database--',
'password'     => '--fill-in your password of the useraccount to connect to database--',
'db'           => '--fill-in the database tou want to connect --',
),
'remember'        => array(
  'cookie_name'   => 'pmqesoxiw318334575498',
  'cookie_expiry' => 900  // in seconds, One week, feel free to make it longer
),
'session' => array(
  'session_name' => 'user',
  'token_name' => 'token'
)
);
``
