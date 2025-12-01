<?php
class digits_payamresan {
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
	$url = "www.payam-resan.com/APISend.aspx?Username=".$this->username."&Password=".$this->password."&From=".$this->sender."&To=".$sms['to']."&Text=".urlencode($sms['message']);

	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	if ($response == 1 ) {
		//digitsDebug("payamresan : SMS sent json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'result'=>$status)));
		return array('success'=>true);
	} else {
		digitsDebug("payamresan : SMS failed json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'error'=>$status ? $status : 'Unknow Error.')));
		return array('success'=>false);
	}
  }
}