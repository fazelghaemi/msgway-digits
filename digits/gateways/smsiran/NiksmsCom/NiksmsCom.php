<?php
class digits_NikSMS {
  private $username;
  private $Password;
  private $sender;
  public function __construct($arg ,array $options = array()) {
	  
    if (empty($arg)) {
      digitsDebug("Username and Password and Sender can't be blank");
	  wp_die("Username and Password and Sender can't be blank"); 
    } else {
      $this->username = $arg['username'];
      $this->password = $arg['password'];
      $this->sender = $arg['sender'];
    }
  }
  public function send(array $sms) {
    if (!is_array($sms)) {
      digitsDebug("sms parameter must be an array");
	  return array('success'=>false);
    }

	$url = "niksms.com/fa/publicapi/groupsms?username=".$this->username."&password=".$this->password."&message=".urlencode($sms['message'])."&numbers=".$sms['to']."&senderNumber=".$this->sender."&sendOn=&sendType=1";

	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$result = json_decode($response,true);

	if (is_array($result) && $result['Status'] == 1 ) {
		//digitsDebug("NikSMS : SMS sent json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'result'=>$status)));
		return array('success'=>true);
	} else {
		digitsDebug("NikSMS : SMS failed json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'error'=>$result['WarningMessage'] ? $result['WarningMessage'] : 'Unknow Error.')));
		return array('success'=>false);
	}
  }
}