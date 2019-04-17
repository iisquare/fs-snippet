<?php
error_reporting(0);
ini_set('display_errors', 0);
!isset($_SERVER['HTTP_APIURL']) && die('The Request Header named "ApiUrl" must be a Url!');
$url = $_SERVER['HTTP_APIURL'];
$apiKey = 'e0526cc51709d84f7528c530385bbd34';
isset($_SERVER['HTTP_APIKEY']) && $apiKey = $_SERVER['HTTP_APIKEY'];
$header = array(
	'apikey:'.$apiKey
);

$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
if(!empty($_GET)) {
	if(false === strpos($url, '?')) {
		$url .= '?';
	} else {
		$url .= '&';
	}
	$url .= http_build_query($_GET);
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if('post' == $requestMethod) {
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
}
$res = curl_exec($ch);
curl_close($ch);
echo $res;