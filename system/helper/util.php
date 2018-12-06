<?php

function format_money($money,$decimals=2){
	if(!is_numeric($money)){
		return $money;	
	}
	return number_format($money,$decimals,'.','');
}


function generateRandomDigitString($length = 6) {
	$characters = '0123456789';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

function http_get($url,$data=array(),$timeout = 10){
    $params=http_build_query($data);
    $ch = curl_init() or die(curl_error());
    if(empty($data)){
        curl_setopt($ch, CURLOPT_URL,$url);
    }else{
        curl_setopt($ch, CURLOPT_URL,$url.'?'.$params);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $result =curl_exec($ch);// or die(curl_error());
    $error = curl_error($ch);
    curl_close($ch);

    return $result;
}

function http_post($url,$data=array()){
    $ch = curl_init() or die(curl_error());
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result =curl_exec($ch);// or die(curl_error());
    $error = curl_error($ch);
    curl_close($ch);

    return $result;
}

function http_post_raw($url,$data){

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type:application/json; encoding=utf-8',
            'content' => $data
        )
    );

    $context  = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function http_request_async($url, $params, $type='GET')
{
    $post_params = array();
    foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);
//    echo print_r($parts, TRUE);
    $fp = fsockopen($parts['host'],
        (isset($parts['scheme']) && $parts['scheme'] == 'https')? 443 : 80,
        $errno, $errstr, 30);

    $out = "$type ".$parts['path'] . (isset($parts['query']) ? '?'.$parts['query'] : '') ." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    // Data goes in the request body for a POST request
    if ('POST' == $type && isset($post_string)) $out.= $post_string;
    fwrite($fp, $out);
    fclose($fp);
}

function get_client_ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        //echo $key." ";
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if ($ip) {
                    return $ip;
                }
            }
        }
    }
    return "Unknown";
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

//String
function str_start_with($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function str_end_with($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function extract_data($source,&$dest){
	if(empty($source) || !is_array($source) || !is_array($dest)){
		return;	
	}
	
	foreach($source as $key=>$value){
		$dest[$key] = $value;	
	}
}

?>