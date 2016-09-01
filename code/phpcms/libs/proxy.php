<?php
if(isset($_GET['url'])){
$url = base64_decode($_GET['url']);

$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);
curl_exec($ch); 
curl_close($ch);
}
?>
