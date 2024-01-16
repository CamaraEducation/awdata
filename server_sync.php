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

function get_usage_last(){     # get the last synchronised rows for usage
    $usage = 'SELECT last FROM aw_records WHERE id = 1';
    $result = mysqli_query(conn(), $usage);
    $res = $result->fetch_array()[0];
    return $res;
}

function get_app_last(){     # get the last synchronised rows for apps
    $apps  = 'SELECT last FROM aw_records WHERE id = 2';
    $result = mysqli_query(conn(), $apps);
    $res = $result->fetch_array()[0];
    return $res;
}

function get_usage_max(){	# get the max available rows to sync for usage
	$usage = 'SELECT id FROM aw_usage ORDER BY id DESC LIMIT 1';
    $result = mysqli_query(conn(), $usage);
    $res = $result->fetch_array()[0];
    return $res;
}

function get_apps_max(){	# get the max available rows to sync for apps
	$apps = 'SELECT id FROM aw_application ORDER BY id DESC LIMIT 1';
    $result = mysqli_query(conn(), $apps);
    $res = $result->fetch_array()[0];
    return $res;
}

function get_usage($last_usage, $max_usage){	# Get the usage data and convert it to json
    $sql = "select devicename, username,duration, datetimeadded, cstatus from aw_usage WHERE id >= '$last_usage' and id <= '$max_usage'";
    $result = mysqli_query(conn(), $sql);
    $usage = $result->fetch_all(MYSQLI_ASSOC);

	$json = json_encode($usage);
    return $json;
}  

function get_apps($last_app, $max_app){	# Get the app data and convert it to json
        
    $sql = "select devicename, username,duration, datetimeadded, app,title from aw_application WHERE id >= '$last_app' AND id <= '$max_app'";
    $result = mysqli_query(conn(), $sql);
    $app = $result->fetch_all(MYSQLI_ASSOC);
    $json = json_encode($app);
    return $json;
}

function get_school(){
    $school = 'SELECT school, category, ownership, region, country FROM `config` WHERE 1';
    $result = mysqli_query(conn(), $school);
    $school = $result->fetch_all(MYSQLI_ASSOC)[0];
    $json = json_encode($school);
    return $json;
}

function update_last($max_usage,$max_app){  # updates the last records inserted
    $last_usage = $max_usage;
    $last_app = $max_app;
    $last_usage_sql = "UPDATE aw_records SET last = '$last_usage' WHERE id = 1";
    $last_app_sql = "UPDATE aw_records SET last = '$last_app' WHERE id = 2";

    if (mysqli_query(conn(), $last_usage_sql)) {
	  echo "Record updated successfully for usage" . PHP_EOL;
	} else {
	  echo "Error updating record: " . mysqli_error() . PHP_EOL;
	}
	if (mysqli_query(conn(), $last_app_sql)) {
	  echo "Record updated successfully for apps" . PHP_EOL;
	} else {
	  echo "Error updating record: " . mysqli_error() . PHP_EOL;
	}
}

function init(){
	$last_usage = get_usage_last();
	$last_app = get_app_last();
	$max_usage = get_usage_max();
	$max_app = get_apps_max();

    if ($last_app == $max_app and $last_usage == $max_usage) {
        echo "No new data to sync to the cloud!" . PHP_EOL;
    }else{
    	$usage_data = get_usage($last_usage, $max_usage);
    	$app_data = get_apps($last_app, $max_app);
    	$school = get_school();
    	$data = array(
            'school' => $school,
            'usage'  => $usage_data,
            'apps'   => $app_data
        );
    	// $jsondata = json_encode($data);
        // foreach ($data as $key ) {
        //    echo "<pre>" . $key . "</pre>";
        //}
        
    	$response = upload($data);
    	if($response == 'success' or $response == 'successsuccess'){
    		update_last($max_usage,$max_app);
    	}
    }
}

function upload($data){
    // curl request to upload the data to the server
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://dashboard.camara.org/aw/v2/receive');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    return $server_output;
}

init();
?>
