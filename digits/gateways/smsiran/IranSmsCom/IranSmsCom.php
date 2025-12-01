<?php
class digits_IranSMS {
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


	$url = "sms.iransms.com/post/sendsms.ashx?from=".$this->sender."&to=".$sms['to']."&text=".urlencode($sms['message'])."&password=".$this->password."&username=".$this->username;

	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);

	$status = explode('-',$response);



	if (is_array($status) && $status[0] == 1 ) {
		//digitsDebug("smsonline : SMS sent json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'result'=>$status)));
		return array('success'=>true);
	} else {
		digitsDebug("IranSMS : SMS failed json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'error'=>$status ? $status : 'Unknow Error.')));
		return array('success'=>false);
	}
  }
}