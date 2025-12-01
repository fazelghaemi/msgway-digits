<?php
class digits_farazsms {
  private $username;
  private $Password;
  private $sender;
  public function __construct($arg ,array $options = array()) {
    if (empty($arg)) {
      digitsDebug("Username and Password and Sender can't be blank");
    } else {
      $this->username = $arg['username'];
      $this->password = $arg['password'];
      $this->sender = $arg['sender'];
    }
  }
  public function send(array $sms) {

    if (!is_array($sms)) {
      digitsDebug("sms parameter must be an array");
    }

	$url = 'sms.farazsms.com/services.jspd';
	if (substr($sms['message'], 0, 11) === "patterncode") {	return $this->sendPattern($sms,$url);}
	$rcpt_nm = array($sms['to']);
	$param = array
				(
					'uname'=> $this->username,
					'pass'=>$this->password,
					'from'=>$this->sender,
					'message'=> $sms['message'],
					'to'=>json_encode($rcpt_nm),
					'op'=>'send',
				);
	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$response2 = json_decode($response);
	$res_code = $response2[0];
	$res_data = $response2[1];
	$errorCodes = array(
		'1' =>    'The message is empty!',
		'2' =>    'The user is limited!',
		'3' =>    'The Number is not belong to you!',
		'4' =>    'You have no reciver!',
		'5' =>    'You do not have enough credit!',
		'6' =>    'The message length is not proper!',
		'7' =>    'The line is not for send all',
		'98' => 'The maximum reciver is not alowed!',
		'99' => 'The operator is Off!',
		'962' =>'The username or password is incorrect!',
		'963' =>'The user is limited!',
		);
	if ($res_code == '0') {
		//digitsDebug("farazsms : SMS sent json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'bulk'=>$res_data)));
		return array('success'=>true);
	} else {
		digitsDebug("farazsms : SMS failed json query:".json_encode(array('message'=>$sms['message'],'to'=>$sms['to'],'error'=>$errorCodes[$res_code] ? $errorCodes[$res_code] : 'Unknow Error:'.$res_code.'-'.$res_data)));
		return array('success'=>false);
	}
  }
  public function sendPattern(array $sms,$url) {
	$text = $sms['message'];
	$to = array($sms['to']);
	$splited = explode(';', $text);
	$pattern_code_array = explode(':', $splited[0]);
	$pattern_code = $pattern_code_array[1];
	unset($splited[0]);
	$resArray = array();
	foreach ($splited as $parm) {
		$splited_parm = explode(':', $parm);
		$resArray[$splited_parm[0]] = $splited_parm[1];
	}
	$user = $this->username;
	$pass = $this->password;
	$fromNum = $this->sender;
	$toNum = $to;
	$input_data = $resArray;
	$url = "http://sms.farazsms.com/patterns/pattern?username=".$user."&password=".urlencode($pass)."&from=".$fromNum."&to=".json_encode($toNum)."&input_data=".urlencode(json_encode($input_data))."&pattern_code=$pattern_code";
	$handler = curl_init($url);             
	curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($handler);
	
	$response = json_decode($result);
	if (is_array($response) && $response[0] != 'sent') {
		$res_code = $response[0];
		$res_data = $response[1];
		$errorCodes = array(
			'962' => __("The username or password is incorrect!"),
			'971' => __("Patterns is not available!"),
			'970' => __("Parameters are incorrect!"),
			'972' => __("The reciver number is not correct!"),
		);
		digitsDebug('farazsms sms failed: ' . $errorCodes[$res_code]);
		return array('success'=>false);
	}
	return array('success'=>true);
  }

}