<?php
class digits_parsgreen_com {
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
      $this->signature = $arg['signature'];
    }
  }
  public function send(array $sms) {
    if (!is_array($sms)) {
      digitsDebug("sms parameter must be an array");
	  return array('success'=>false);
    }
	
$url = "login.parsgreen.com/UrlService/sendSMS.ashx?from=".$this->sender."&to=".$sms['to']."&text=".urlencode($sms['message'])."&signature=".$this->signature;

	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$result = json_decode($response,true);

	if ($response != '-1' ) {
		//digitsDebug("parsgreen.com : SMS sent json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'result'=>$status)));
		return array('success'=>true);
	} else {
		digitsDebug("parsgreen.com : SMS failed json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'error'=>$result['WarningMessage'] ? $result['WarningMessage'] : 'Unknow Error.')));
		return array('success'=>false);
	}
  }
}