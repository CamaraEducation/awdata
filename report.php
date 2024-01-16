<?php 
function conn(){

    $database = 'portal';
    $username = 'ccnms_user';
    $password = 'Camara2004!';
    $hostname = 'localhost';

    // connect to database
    $conn = new mysqli($hostname, $username, $password, $database);
    return $conn;
}
ob_end_clean();
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=all_data.csv');  
$output = fopen("php://output", "w");  

fputcsv($output, array( 'devicename', 'duration', 'date_time', 'app', 'title'));  
$query = "SELECT devicename, duration, datetimeadded,app,title FROM `aw_application` UNION SELECT devicename,duration,datetimeadded,cstatus,0 from aw_usage";

$result = mysqli_query(conn(), $query);
while($row = $result->fetch_assoc())  
{  
   fputcsv($output, $row);  
}  
fclose($output);
die();
?>