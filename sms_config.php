<?php
Class MyFunction{
static function send_sms($phone,$sender,$username,$password,$message,$fullname){
	if ($phone !='') {
   if ($message !='') {
	   $company=$sender;
       $url = 'http://api.infobip.com/api/v3/sendsms/plain?user='.$username.'&password='.$password.'&sender='.$company.'&SMSText='.$message.'
&GSM='.$phone.'&type=longSMS';
$xmlinfo = simplexml_load_file($url);

$status = $xmlinfo->result->status;
if($status == 0){
$error ="SMS has been sent to ".$fullname;
}else{
//echo "Error: Message not sent to ".$fullname."! API Code: ".$status." Contact API provider:";
}

      //echo $x;
   }
   else {
      //echo "ERROR : Message not sent to ".$fullname."!-- Text parameter is missing!\r\n";
   }
}
else {
  // echo "ERROR : Message not sent to ".$fullname."! -- Phone parameter is missing!\r\n";
}

//echo "</br>";
	}
}

?>