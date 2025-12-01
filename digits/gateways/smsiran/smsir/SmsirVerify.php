<?php

namespace SMSGateway;


class SmsirVerify {
    public static function sendSMS($gateway_fields, $mobile, $message, $test_call) {
        $authorization_token = $gateway_fields['authorization_token'];
        $TemplateID = $gateway_fields['TemplateID'];
        $message = str_replace('|','"',$message);
        $message = json_decode($message,true);
        return self::process_sms($authorization_token, $TemplateID, $mobile, $message, $test_call);
    }

    public static function process_sms($authorization_token, $TemplateID, $mobile, $message, $test_call) {
        $params = array(
            "mobile" => $mobile,
            "templateId" => $TemplateID,
            "parameters" => $message
        );
        $auth = array(
            'Content-Type: application/json',
            'X-API-KEY: '.$authorization_token
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sms.ir/v1/send/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($params),
        CURLOPT_HTTPHEADER => $auth,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response,true);
        if($res['status'] == true){
            return true;
        }
        return false;
    }

}
