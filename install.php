<?php
    $_API_KEY='e3f17905b9d868b5c58ada0ccc7b0824';
    $_NGROK_URL = 'https://39d1-222-153-154-122.ngrok.io';
    $shop = $_GET['shop'];
    $scopes = 'read_products, write_products, read_orders, write_orders';
    $redirect_uri = $_NGROK_URL . '/elena/toke.php';
    $nounce = bin2hex(random_bytes(12));
    $access_mode = 'per-user';

    $oauth_url = 'https://' .$shop .'admin/oauth/authorize?client_id=' .$_API_KEY . '&scope=' . $scopes . '&redirect_uri=' . urlencode($redirect_uri) . '$state=' . $nonce . 'grant_options[]=' . $access_mode;

    //https://{shop}.myshopify.com/admin/oauth/authorize?client_id={api_key}&scope={scopes}&redirect_uri={redirect_uri}&state={nonce}&grant_options[]={access_mode}

    header("Location: " .$oauth_url);
    exit();
