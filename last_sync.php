<?php

header("Content-Type:application/json");
$data = file_get_contents('php://input');
$datares = json_decode($data, true);
$devicename = $datares['devicename'];
$status = $datares['status'];
$username = $datares['username'];
// $awserverdb = new SQLite3('/var/www/html/awdata/awserverdb.db');

function conn(){

    $database = 'portal';
    $username = 'ccnms_user';
    $password = 'Camara2004!';
    $hostname = 'localhost';

    // connect to database
    $conn = new mysqli($hostname, $username, $password, $database);
    return $conn;
}
if ($status=='afk' and $username == 'camara') {
    $sql = "select datetimeadded FROM aw_usage WHERE devicename = '$devicename' and username = 'camara' ORDER BY id DESC LIMIT 1";
}elseif($status=='app' and $username == 'camara'){
    $sql = "select datetimeadded FROM aw_application WHERE devicename = '$devicename' and username = 'camara' ORDER BY id DESC LIMIT 1";
}elseif($status=='afk' and $username == 'camaraadmin'){
    $sql = "select datetimeadded FROM admin_computer WHERE devicename = '$devicename' and username = 'camaraadmin' ORDER BY id DESC LIMIT 1";
}elseif($status=='app' and $username == 'camaraadmin'){
    $sql = "select datetimeadded FROM admin_application WHERE devicename = '$devicename' and username = 'camaraadmin' ORDER BY id DESC LIMIT 1";
}

// $result = $awserverdb->query($sql);
$result = mysqli_query(conn(), $sql);
$res = $result->fetch_array()[0];
echo $res . PHP_EOL;

