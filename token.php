<?php

include_once("/includes/mysql_connect.php");

$api_key='e3f17905b9d868b5c58ada0ccc7b0824';
$secret_key = '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';
//getting URL
$parameters = $_GET;
//getting shop url
$shop_url = $parameters['shop'];
// get hmac from the url parameter
$hmac = $parameter['hmac']; 
// removing hmac using array_diff_key
$parameters = array_diff_key($parameters, array('hmac' => '')); 
ksort($parameters);

/*
Creating new hmac using hash_hmac function
compare hmac we got from shopify and create new hmac using hash hmac and see if they have the same value
if they are the same, it means that hmac we got from our URL is real
*/

//creating hash code in sha256 format, that shopify is using
$new_hmac = hash_hmac('sha256', http_build_query($parameters), $secret_key);

//check if hmac and new_hmac is same
if(hash_equals($hmac, $new_hmac)){
    //echo 'This is coming from Shopify';
    
    //create access token
    $access_token_endpoint = 'https://' . $shop_url . '/admin/oauth/access_toke';
    $var = array(
        "client_id" => $api_key,
        "client_secret" => $secret_key,
        "code" => $parameters['code']
    );
    
    /*
     curl  is what we are going to use to send our data to the following endpoint : /admin/oauth/access_toke
     We are going to send  api key, secret key, and code to this endpoint
     we can find that code in the query variable of our token 
    */
    
    //create curl
    $ch = url_init();
    //set up the configuration
    curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($var));
    curl_setopt($ch, CURLOPTT_POSTFIELDS, http_build_query($var));
    
    $response = url_exec($ch);
    //close curl
    curl_close($ch);

    $response = json_decode($response, true);
    echo print_r($response);

    $query = "INSERT INTO shops (shop_url, access_token, install_date) VALUES ('" . $shop_url ."','" . $response['access_token'] ."',NOW()) ON DUPLICATE KEY UPDATE access_token='" . $response['access_token'] ."'";
    
    if($mysql -> query($query)){
        header("Locaion: https://" . $shop_url . '/admin/apps');
        exit();
    }
}else{
    echo 'This is NOT coming from Shopify, probably someone is hacking';
}