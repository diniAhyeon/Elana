<?php
include_once("includes/mysql_connect.php");

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