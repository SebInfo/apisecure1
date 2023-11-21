<?php
$url = 'http://127.0.0.1:8888/apiRestsecuriser1/testHTTPBasic.php';
$username = 'utilisateur';
$password = 'azerty';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

$response = curl_exec($ch);
curl_close($ch);

echo $response;