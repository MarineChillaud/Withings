<?php
// Configuration
$clientId = "c05fa7d8322157565238c19b9357ab25969a0fb2b6d6b93a490b58717f853127";
$secret = "271e0a491a620b74e2e4a0481be5e5452f6f87c62a1ac992a2674c731880504d"; // hash key

// DECLARATION DES FONCTIONS

// Interroger l'API getnonce
function getNonce($clientId, $secret){
    $timestamp = time(); 
    // cf https://developer.withings.com/api-reference#operation/signaturev2-getnonce
    $signature = hash_hmac('sha256','getnonce'.','.$clientId.','.$timestamp, $secret);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/v2/signature");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
        'action' => 'getnonce',
        'client_id' => $clientId,
        'timestamp' => $timestamp,
        'signature' => $signature,
    ]));
    $rsp = curl_exec($ch);
    curl_close($ch);
    //print_r(json_decode($rsp));

    $nonce = json_decode($rsp)->body->nonce;
    return $nonce;
}

// Interroger l'API getdemoaccess pour obtenir un token
function getToken($clientId, $secret, $nonce){
    // cf https://developer.withings.com/developer-guide/v3/get-access/sign-your-requests/
    $signature = hash_hmac('sha256','getdemoaccess'.','.$clientId.','.$nonce, $secret);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/v2/oauth2");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
        'action' => 'getdemoaccess',
        'client_id' => $clientId,
        'signature' => $signature,
        'nonce' => $nonce,
        'scope_oauth2' => 'user.activity,user.metrics'
    ]));
    $rsp = curl_exec($ch);
    curl_close($ch);
    //print_r(json_decode($rsp));

    $token=json_decode($rsp)->body->access_token;
    return $token;
}

// Interroge l'API getmeas pour récupérer les données de poids
function getWeightsMeasures($token){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/measure");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$token]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
        'action' => 'getmeas',
        'meastype' => 1,
        // 'meastypes' => 'meastypes',
        'category' => 1,
        // 'startdate' => 'startdate',
        // 'enddate' => 'enddate',
        // 'offset' => 'offset',
        // 'lastupdate' => 'int'
    ]));

    $rsp = curl_exec($ch);
    curl_close($ch);
    //print_r(json_decode($rsp));

    $weights = json_decode($rsp)->body->measuregrps;
    return $weights;
}

// APPEL DES FONCTIONS - ALGORYTHME D'INTERROGATION DE L'API

$nonce = getNonce($clientId, $secret);
$token = getToken($clientId, $secret, $nonce);
$weights = getWeightsMeasures($token);

?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="utf-8">
        <title>Test Withings Marine Chillaud</title>
        <style>
            h1{text-align:center;}
            table{ border-collapse: collapse; width:50%;margin:auto;}
            td { border: 1px solid; padding:1em; text-align:right;}
        </style>
    </head>
    <body>
        <h1>Withings Weight Measures</h1>
        <table>
            <thead>
                <tr><th>date</th><th>weight</td></tr>
            </thead>
            <tbody>
            <?php 
                foreach($weights as $weight){
                    echo "<tr>
                            <td>".date("Y-m-d H:i:s", $weight->date)."</td>
                            <td>".number_format($weight->measures[0]->value/1000, 2, '.', '')." kg </td>
                        </tr>";
                };
            ?>
            </tbody>
        </table>
    </body>
</html>