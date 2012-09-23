<?php
function isValidDomain($domain){
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	return preg_match($pattern, $domain);
}
function isUp($domain){
	$curl = curl_init($domain);
	$returned = 0;
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 404){
		$returned = 2;
	}else{
		$returned = $response ? 1 : 0;
	}
	curl_close($curl);
	return $returned;
}
function easteregg($domain){
	$response = '';
	switch($domain){
		case $_SERVER['SERVER_NAME']:
			$response = 'If you can see this, we are up.';
		break;
		case '127.0.0.1':
		case 'localhost':
			$response = 'Party at 127.0.0.1!';
		break;
		case 'nebkat':
			$response = 'YES, HE IS UP UP AND HIGH ON CRYSTAL WEED!';
		break;
		case 'datagutt':
			$response = 'Javascript all teh things!';
		break;
		case 'canada':
			$response = 'Hi Chad.';
		break;
	}
	return $response;
}
function getResponse($domain){
	if(empty($domain)){
		$response = 'I can feel the emptiness inside...';
	}else if(!isValidDomain($domain)){
		// Check if there is an easteregg
		$easteregg = easteregg($domain);
		if(!empty($easteregg)){
			$response = $easteregg;
		}else{
			$response = 'That is not a valid domain.';
		}
	}else{
		$response = (string) isUp($domain);
	}
	if(isset($_REQUEST['json']) && $_REQUEST['json'] == '1'){
		return "{domain: '$domain', status: '$response'}";
	}else{
		return $response;
	}
}