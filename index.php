<?php
require("config.php");

// Autorisation


// Access Token

$ch = curl_init();

curl_setopt ($ch, CURLOPT_URL, "https://wbsapi.withings.net/v2/oauth2");
curl_setopt  ($ch,CURLOPT_RETURNTRANSFER, true); 
curl_setopt  ($ch,   CURLOPT_POSTFIELDS, http_build_query([  
	 'action' => 'getdemoaccess'  , 
	 'client_id' => 'c05fa7d8322157565238c19b9357ab25969a0fb2b6d6b93a490b58717f853127',  
	 'scope_oauth2' => 'user.metrics'
 ])); 

  $response = curl_exec($ch); 
  curl_close($ch); 

  var_dump($response); 

  // API Measure-Getmeas

  
?>


<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <title>Test Withings Marine</title>
    <link rel="stylesheet" type="text/css" href="design/default.css">
</head>
<body>
    <h1>Se connecter</h1>
        <p></p>
</body>
</html>