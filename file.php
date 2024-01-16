<?php
$server_time = $_SERVER['REQUEST_TIME'];
if (isset($_FILES['file1']) && isset($_FILES['file2'])) {
    // File 1
    $file1 = $_FILES['file1'];
    $file1_name = $file1['name'];
    $file1_tmp_name = $file1['tmp_name'];

    // File 2
    $file2 = $_FILES['file2'];
    $file2_name = $file2['name'];
    $file2_tmp_name = $file2['tmp_name'];


    // move the files to the desired location
    move_uploaded_file($file1_tmp_name, 'data/files/'.$server_time."-$file1_name");
    move_uploaded_file($file2_tmp_name, 'data/files/'.$server_time."-$file2_name");
    // move_uploaded_file($file3_tmp_name, 'data/files/'.$server_time."-$file3_name");

    $data = [
        "usage" => "data/files/$server_time-$file1_name",
        "apps"  => "data/files/$server_time-$file2_name",
    ];

    
     function conn(){

        $database = 'portal';
        $username = 'ccnms_user';
        $password = 'Camara2004!';
        $hostname = 'localhost';

        // connect to database
        $conn = new mysqli($hostname, $username, $password, $database);
        return $conn;
    }

    function insert_data($data){
        # data strucutre: all three files have same structure - DeviceName, Name, StartLocalTime, EndLocalTime, Duration
        # sample data: [{"DeviceName":"CAMARAC-N03VP2M","Name":"Active","StartLocalTime":"2023-06-10 06:49:32","EndLocalTime":"2023-06-10 06:53:31","Duration":3.22}, ... ]

        # database structure: all three tables have samestructure - DeviceName, Name, StartLocalTime, EndLocalTime, Duration
        # tables: manic_usage, manic_apps, manic_docs

        $usage_file = json_decode(file_get_contents($data['usage']));
        $apps_file = json_decode(file_get_contents($data['apps']));


        if($usage_file != ""){
            foreach($usage_file as $row){
                $sql = "INSERT INTO aw_usage(devicename, username, duration, datetimeadded, cstatus) VALUES ('$row->devicename', '$row->username', '$row->duration', '$row->date_time', '$row->afk_status')";
                
                if(!mysqli_query(conn(), $sql)) file_put_contents('usage.log', $sql);
            }
        }

        if($apps_file != ""){
            foreach($apps_file as $row){
                $sql = "INSERT INTO aw_application (devicename, username, duration, datetimeadded, app, title) VALUES ('$row->devicename', '$row->username', '$row->duration', '$row->date_time', '$row->app', '$row->title')";
                
                if(!mysqli_query(conn(), $sql)) file_put_contents('app.log', $sql);
            }
        }     

    }

    insert_data($data);

    echo 'ok';
}

?>