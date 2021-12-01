<?php

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}

function logger($log)
{
    //store in 2 directories (root xampp) behind becos if someone uses wget request they'll be able to retrieve the log document
    if (!file_exists('logs/log.txt')) {
        file_put_contents('logs/log.txt', '');
    }
    
    $user_ip = getUserIP();

    // $ip = $_SERVER['REMOTE_ADDR']; //get client's IP addr
    date_default_timezone_set('Asia/Singapore');
    $time = date('d/m/y h:iA', time());

    $contents = file_get_contents('logs/log.txt');
    $contents .= " $user_ip \t$time\t$log\r";

    file_put_contents('logs/log.txt', $contents);
}