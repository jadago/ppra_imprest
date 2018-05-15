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
//echo "Message Sent to ".$fullname;
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

if(isset($_POST['Submit']))
{
require_once('includes/db_conn.php');


										$getitem = "SELECT * FROM imprest WHERE imprest_id > 0 AND status=5 AND stage < 4 ";
									
                                        $query = mysqli_query($con, $getitem);
                                        while ($result = mysqli_fetch_array($query)) {
                                            //get users
                                            $users = "SELECT * FROM users WHERE user_id='".$result['staff_id']."'";
                                            $query1 = mysqli_query($con,$users);
                                            $array1 = mysqli_fetch_array($query1);
                                            
                                           // get total amount
                                            $amount = "SELECT SUM(amount) AS tamount FROM imprest_item WHERE imprest_id='".$result['imprest_id']."'";
                                            $queryA = mysqli_query($con,$amount);
                                            $arrayA = mysqli_fetch_array($queryA);
                                            
                                            //get voucher details
                                            $v = "SELECT * FROM imprest_voucher WHERE imprest_id='".$result['imprest_id']."'";
                                            $queryv = mysqli_query($con,$v);
                                            $arrayv = mysqli_fetch_array($queryv);
											// sms content part
                                            $name = $array1['firstname'];
                                            $phone = $array1['mobile'];
											$amount = $arrayA['tamount'];


// You have phone numbers then send messages 
       $message = "Dear ".$name.", you are reminded to retire your imprest of Tsh.".$amount.", PPRA:";
       $username="ipsdemo";
	   $password = "ips**?ips2013";
	   $sender = "TIB PREMIER";
	   $fullname = "Justine";
	   MyFunction::send_sms($phone,$sender,$username,$password,$message,$fullname);
}


	
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
 <form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
<table width="40%" border="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" id="button" value="Send Sms" />
    </label></td>
  </tr>
</table>
</form>
</body>
</html>
