<?php
require("config.php");

// Variables
$authUrl = "https://account.withings.com/oauth2_user/authorize2";
$url = "https://wbsapi.withings.net/v2/oauth2 ";
$urlMeasure = "https://wbsapi.withings.net/measure";
$apiUrl = "https://wbsapi.withings.net";
$data = array(
    "action" => "getdemoaccess",
    "client_id" => "WITHINGS_ID",  
    "client_secret" => "WITHINGS_SECRET",
 	"scope_oauth2" => "user.metrics"
);
$clientId = "WITHING_ID";
$secret = "WITHINGS_SECRET";

// Autorization
$authCode = $_GET['code'];

// Access Token
if($authCode){
  
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt  ($ch,CURLOPT_RETURNTRANSFER, true); 
    curl_setopt  ($ch,   CURLOPT_POSTFIELDS, $data); 

    $response = curl_exec($ch); 
    $result = json_decode($response, true);

    curl_close($ch); 

    var_dump($response); 

  // API Measure-Getmeas

  $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $urlMeasure);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer YOUR_ACCESS_TOKEN']);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
	'action' => 'getmeas',
	'meastype' => 'meastype',
	'meastypes' => 'meastypes',
	'category' => 'category',
	'startdate' => 'startdate',
	'enddate' => 'enddate',
	'offset' => 'offset',
	'lastupdate' => 'int'
]));

$rsp = curl_exec($ch);
curl_close($ch);

var_dump($rsp);

}
?>


<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <title>Test Withings Marine</title>
    <link rel="stylesheet" type="text/css" href="design/default.css">
</head>
<body>
    <h1>Login</h1>
        <p><a href="https://account.withings.com/oauth2_user/authorize2">Se connecter à Withings</a></p>
</body>
</html>