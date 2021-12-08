<?php
include_once("includes/mysql_connect.php");
include_once("includes/shopify.php");

$shopify = new Shopify();

$parameters = $_GET;

//use Query and SELECT statement to get shop information
$query = "SELECT * FROM shops WHERE shop_url='" . $parameters['Sshop'] ."' LIMIT 1";
$result = $mysql -> query($query);

//Check if the number of the row is less than 1 : no store record -> redict to installtion
if($result->num_rows <1){
    header("Location: install.php?shop=". $_GET['shop']);
    exit();
}
// Use fetch assoc functioon to get the record
$store_date = $result->fetch_assoc();
echo print_r($store_data);

//check if shopify.php is working
$shopify->set_url($parameters['shop']);
$shopify->set_token($store_data['access_toke']);

echo $shopify->get_url();
echo '<br/>';
echo $shopify->get_token();

$products = $shopify->rest_api('/adminapi/2021-04/products.json', array(), 'GET');
echo print_r($products['body']);