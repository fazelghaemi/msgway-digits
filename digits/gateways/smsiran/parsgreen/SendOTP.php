<?php
$webServiceURL       = "http://login.Parsgreen.com/Api/SendSMS.asmx?wsdl"; 
$webServiceSignature = "123456-DFCA-42BB-8233-7BDA20D6D1EB"; 
$webServicemobile = "09---------"; 
$webServiceLang = "fa"; 
$webServiceotpType = 2; 
$webServicepatternId = 1; 

     $parameters['signature'] = $webServiceSignature;
     $parameters['mobile'] = $webServicemobile;
     $parameters['Lang'] = $webServiceLang;
     $parameters['otpType'] = $webServiceotpType;
     $parameters['patternId'] = $webServicepatternId;
     $parameters['otpCode'] = 0x0;
     
try 
{
    $con = new SoapClient($webServiceURL);  
    $responseSTD = (array) $con ->SendOtp($parameters); 
    echo 'OutPut Method Value.............................=>';
    echo '</br>';
    echo  $responseSTD['SendOtpResult'];
    echo '</br>';
    echo 'OTP Code.............................=>';
    echo '</br>';
    echo $responseSTD['otpCode'];  
    echo '</br>';

}
catch (SoapFault $ex) 
{
    echo $ex->faultstring;  
}
?>
 