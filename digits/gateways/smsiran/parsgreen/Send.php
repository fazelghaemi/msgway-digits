<?php
$webServiceURL  = "http://login.parsgreen.com/Api/SendSMS.asmx?WSDL";  
$webServiceSignature = "123456-DFCA-42BB-8233-7BDA20D6D1EB";  
$webServicetoMobile   = "09---------"; 
mb_internal_encoding("utf-8");
 $textMessage="Hello World"; 
 $textMessage= mb_convert_encoding($textMessage,"UTF-8"); 
     $parameters['signature'] = $webServiceSignature;
     $parameters['toMobile' ]= $webServicetoMobile;
     $parameters['smsBody' ]=$textMessage;
     $parameters[ 'retStr'] = "";
try 
{
    $con = new SoapClient($webServiceURL);  
    $responseSTD = (array) $con ->Send($parameters); 
    echo 'OutPut Method Value.............................=>';
    echo '</br>';
    echo  $responseSTD['SendResult']; 
    echo '</br>';
}
catch (SoapFault $ex) 
{
    echo $ex->faultstring;  
}
?>
 