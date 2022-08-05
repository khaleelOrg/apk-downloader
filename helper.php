<?php

define( 'WP_USE_THEMES', true );
require( $_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );	
require_once(ABSPATH . 'wp-admin/includes/image.php');

session_start();
$_SESSION["mode"] = "apk-downloader";

$url=$_POST['url'];
$url = trim($url);

$av = $_POST['android_ver'];
$av  =  trim($av);

$arch = $_POST['device_id'];
$arch  =  trim($arch);


#below is the code for splitting url into app id
   if (stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0 || stripos($url, 'www.') === 0)# if statement runs when there is complete url with https://
   {
      $split_arr =parse_url($url);
      $split_ampr = explode("&",$split_arr['query']);#split with &
      $split_id = explode("=",$split_ampr[0]);# split with id 
      #echo $split_id[1];
      $appId = $split_id[1];
   	  $_SESSION['appId'] = $appId;
   	  
        $file_pointer = "https://apkfuel.com/apk-downloader/user_content/short_apk_json/$appId.json";
        if (!does_url_exists($file_pointer)) {
            $output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/playstore.py $appId 'appId'"); 
        	// echo gettype($av) . "<br>";
        	// echo "<br> $output <br>";                                                       																															 		                                              
        } 	
   	  $mode = 'appId';
    }
        
    elseif (strpos($url, ".") !== false){    
      	#initialize $url value to $appId if there is no url i.e only appId is given by user 
    	$appId = $url;
        $file_pointer = "https://apkfuel.com/apk-downloader/user_content/short_apk_json/$appId.json";
        if (!does_url_exists($file_pointer)) {
            $output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/playstore.py $appId 'appId'"); 
        	// echo gettype($av) . "<br>";
        	// echo "<br> $output <br>";                                                       																															 		                                              
        }                                                       																															 		                                              
    	$mode = 'appId';
     }
	else {
    	$appId = $url;		           
        $file_pointer = "https://apkfuel.com/apk-downloader/user_content/short_apk_search/$appId.txt";
    	// echo "<br> $file_pointer <br>";
          if (!does_url_exists($file_pointer)) {
          	  // echo "<br> $file_pointer does not exist <br>";
              $output = shell_exec("python3 /home/apkfuel/public_html/apk-downloader/playstore.py '$appId' 'search'"); 
          	  // echo gettype($av) . "<br>";
          	  // echo "<br> $output <br>";                                                       																															 		                                              
          }
    	$mode = 'search';
    }

?>

<?php

switch($mode)
{
    case 'appId':
            #case logic here									                                     
            $get_json = file_get_contents("user_content/short_apk_json/$appId.json");
            $json_obj = json_decode($get_json, true);?>

            <strong style="font-size: 18px !important; color: #218838"><?php echo $json_obj['title'] ?></strong> </br>
            <img style="margin-bottom: 5px !important; margin-top: 2px;" src= "<?php echo $json_obj['icon'] ?>" alt="icon" width="110"/></br>
            <strong>Package Name:</strong> <?php echo $json_obj['appId'] ?></br>
            <strong>Play Store Link: </strong> <a href="<?php echo $json_obj['url'] ?>" target="_blank">[Play Store]</a></br>
            <strong>App Rating:</strong> <?php echo round($json_obj['rating'], 1); ?></br>
            <strong>Downloads: </strong>    <?php echo $json_obj['num_of_downloads']  ?> </br>   
            
            <div class="w-button-green-out" style=" margin-top: 6px !important;">
            	<a style="color: white !important;" href="https://apkfuel.com/apk-downloader/apk.php?id=<?php echo $appId;?>" target="_blank";>Download APK Now</a>
            </div></hr>

            <?php                                      				

    break;

    case 'search':
        #logic          	
        $handle = fopen( "/home/apkfuel/public_html/apk-downloader/user_content/short_apk_search/$appId.txt", "r");
        if ($handle) {
        	$count = 0;
            while (($line = fgets($handle)) !== false) {            
            	$continue_while = false;
                $line = trim($line);     
                $get_json = file_get_contents("/home/apkfuel/public_html/apk-downloader/user_content/short_apk_json$line.json");
                $json_obj = json_decode($get_json, true);          
                           		
        ?>

            <strong style="font-size: 18px !important; color: #218838"><?php echo $json_obj['title'] ?></strong> </br>
            <img style="margin-bottom: 5px !important; margin-top: 2px;" src= "<?php echo $json_obj['icon'] ?>" alt="icon" width="110"/></br>
            <strong>Package Name:</strong> <?php echo $json_obj['appId'] ?></br>
            <strong>Play Store Link: </strong> <a href="<?php echo $json_obj['url'] ?>" target="_blank">[Play Store]</a></br>
            <strong>App Rating:</strong> <?php echo round($json_obj['rating'], 1); ?></br>
            <strong>Downloads: </strong>    <?php echo $json_obj['num_of_downloads']  ?> </br>   
            
            <div class="w-button-green-out" style=" margin-top: 6px !important;">
            	<a style="color: white !important;" href="https://apkfuel.com/apk-downloader/apk.php?id=<?php echo $appId;?>" target="_blank";>Download APK Now</a>
            </div></hr></br>                                                                                

        <?php 
                
            } // end of while brace
            
        fclose($handle); 
        
        }# end of if condition 
     
    break;

    default:
    echo 'something wrong';
}




#$get_json = file_get_contents("user_content/apk_json/$appId.json");
#$json_obj = json_decode($get_json, true);
 ?>

