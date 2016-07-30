<?php
/*
Author : Shafraz Rahim
*/

ini_set('error_log', 'sms-app-error-demo.log');

include 'lib/SMSSender.php';
include 'lib/SMSReceiver.php';


date_default_timezone_set("Asia/Colombo");


$serverurl= 'http://api.dialog.lk:8080/sms/send';
$password= "0b1c00b72ed373f909b6fb0119b744cc";
$applicationId = "APP_026259";

   	 

try{

	/*************************************************************/

	// Initializing SMS Receiver class object

	$receiver = new SMSReceiver(file_get_contents('php://input'));

	$content =$receiver->getMessage();

	$content=preg_replace('/\s{2,}/',' ', $content); 

	$address = $receiver->getAddress();


	/*************************************************************/
	


	// Initializing Sender class object 
	
	$sender = new SMSSender( $serverurl, $applicationId, $password);
	
	

	list($key, $message) = explode(" ",$content);

							
	//getting a star wars character randomely. 	


	$urloftranslate = "https://www.googleapis.com/language/translate/v2?key=AIzaSyAqYNyOrwJ7vDS0M4gf29WVFbA5-SuGIgQ&source=en&target=si&q=".$message;



	$json = file_get_contents($urloftranslate);  
             
	$exp = json_decode($json,true);
	
	$transtext = $exp['data']['translations']['translatedText'];	
	
	
	//SMS-ing translated text to the user 

	$sender->sendMessage($message.' Translated to : '.$transtext, $address);
					 
															


}catch(SMSServiceException $e){


//$logger->WriteLog($e->getErrorCode().' '.$e->getErrorMessage());

}

?>