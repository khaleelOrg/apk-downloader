<?php

define( 'WP_USE_THEMES', true );
require( $_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );	
require_once(ABSPATH . 'wp-admin/includes/image.php');

// $appId = $_GET['appId'];
// $scrape_str = $_GET['scrape_str'];

if(isset($_GET['appId'])){
	$appId = $_GET['appId'];
	$scrape_str = $_GET['scrape_str'];
}
else {
 $apId = 'com.facebook.katana';
}

if(isset($_POST['appId'])){	
	$appId = $_POST['appId'];
	$scrape_str = $_POST['scrape_str'];
}
else
{
	$scrape_str = "facebook";
}

if(isset($_POST['source'])){	
	$source = $_POST['source'];
}
else {
	$source = 'general';
}
$output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/scrape_dl.py $appId $scrape_str $source");

// echo $appId;
// echo "<br>";
// echo $scrape_str;

// $arch  = $_POST['arch'];
// $av    = $_POST['av'];

// $appId_type = gettype($appId) . ": $appId ";
// $arch_type = gettype($arch) . ": $arch ";
// $av_type = gettype($av) . ": $av ";

// $file_pointer_apk = "https://apkfuel.com/apk-downloader/user_content/apk_data/$appId.apk";
// $file_pointer_json = "https://apkfuel.com/apk-downloader/user_content/apk_json/$appId.json";

// if(does_url_exists($file_pointer_json)){
// 		$get_json = file_get_contents("https://apkfuel.com/apk-downloader/user_content/apk_json/$appId.json");
// 		$json_obj = json_decode($get_json, true);
// 		// include 'publish.php';
// }

// if(!does_url_exists($file_pointer_apk)){
		// $output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/download.py $appId");	
		// $output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/scrape_dl.py $appId $scrape_str $source");	
// }

// echo $output;

// print_r($output);

// $file = fopen("background_log.txt","w");

// $data = $output . PHP_EOL . $appId_type . PHP_EOL . $arch_type . PHP_EOL . $av_type ;

// fwrite($file, $data );
// fclose($file);

if(isset($output)){
	echo $output;

	// $file = 'dl_links/' . $appId . '.txt';
	// file_put_contents($file, $output);

	// print_r($output);
	// $array = array('message'=>'success', "response"=>$output);
	// echo json_encode($array);
    return json_encode(array('message'=>'success', "response"=>$output));
}
else{
	print_r($output);
    return json_encode(array('message'=>'fail'));
}

?>
