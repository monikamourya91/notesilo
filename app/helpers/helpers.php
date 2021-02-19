<?php


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return strtoupper($randomString);
}

function verifyAutoresponderCredentials($autoresponder) {
  switch($autoresponder['type']){
      
      case "mailchimp":
        if(verifyMailChimpCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){
          return true;
        } else {
          return false;
        }   
      break;

      case "getresponse":
        $headers = array(
          'Content-Type: application/json',
          'X-Auth-Token: api-key '.$autoresponder['field_one_value']
        );

        $result = json_decode(getCompaignId($headers, $autoresponder['field_two_value']), true);
        if(isset($result["httpStatus"]) && ($result["httpStatus"] == 401)){
          //invalid api key
          return false;
        }elseif(sizeof($result) > 0){
          return true;
        }else{
          //list id wrong
          return false;
        }
      break;

      case "mailerlite":
        if(verifyMailerLiteCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){ 
          return true;
        } else {
          return false;
        }   
      break;
      
      case "convertkit":
        if(verifyConvertKitCredentials($autoresponder)){
          $data = [
            'api_secret' => $autoresponder["field_one_value"],
            'label' => "Last Name"
          ];
          if(createLastNameInConvertKit($data)){
              return true;    
          } else {
              return false; 
          }
        }else{
         return false;
        }
      break;

      case "activecampaign":
        if(verifyActiveCampaignCredentials($autoresponder)){
          return true;
        }else{
          return false;
        }
      break;  
    
      case "klaviyo":
        if(verifyKlaviyoCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){
            return true;
        }else{
          return false;
        }
      break;
      
      case "sendinblue":
        if(verifySendInBlueCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){
            return true;
        }else{
          return false;
        }
      break;

      case "constantcontact":
        if(verifyConstantContactCredentials($autoresponder['field_two_value'],$autoresponder['field_three_value'],$autoresponder['field_one_value'])){
            return true;
        }else{
          return false;
        }
      break;
      
      case "drip":
        if(verifyDripCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){
            return true;
        }else{
          return false;
        }
      break;

      case "kartra":
        if(verifyKartraCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'], $autoresponder['field_three_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "sendgrid":
        if(verifySendGridCredentials($autoresponder['field_one_value'], $autoresponder['field_two_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "sendfox":
        if(verifySendFoxCredentials($autoresponder['field_one_value'], $autoresponder['field_two_value'])){
            return true;
        }else{
            return false;
        }
      break;

      case "hubspot":
        if(verifyHubSpotCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){  
          return true;
        } else {
          return false;
        }   
      break;
      /*
      case "sendy":
        if(verifySendyCredentials($autoresponder)){
          return true;
        }else{
          return false;
        }
      break;

      case "acellemail":
        if(verifyAcellEmailCredentials($autoresponder['field_two_value'],$autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "markethero":
        if(verifyMarketHeroCredentials($autoresponder)){
          return true;
        }else{
          return false;
        }
      break;

      case "moosend":
        if(verifyMoosendAppCredentials($autoresponder['field_two_value'],$autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;  

      case "getgist":
        if(verifyGetGistCredentials($autoresponder['field_two_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "hotprospector":
      if(verifyHotProspectorCredentials($autoresponder)){
        return true;
      }else{
        return false;
      }
      break;

      case "sendlane":
        if(verifySendLaneCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'], $autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "mailingboss":
        if(verifyMailingBossCredentials($autoresponder['field_two_value'], $autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "benchmark":
        if(verifyBenchMarkCredentials($autoresponder['field_two_value'], $autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "mautic":
        if(verifyMauticCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'], $autoresponder['field_one_value'], $autoresponder['field_four'])){
          return true;
        }else{
          return false;
        }
      break;

      case "simplero":
        if(verifySimpleroCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'],$autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "ontraport":
        if(verifyOntraportCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'],$autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "influencersoft":
        if(verifyInfluencerSoftCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'], $autoresponder['field_one_value'])){
          return true;
        }else{
          return false;
        }
      break;

      case "automizy":
        if(verifyAutomizyCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'])){ 
          return true;
        } else {
          return false;
        }   
      break;

      case "gohighlevel":
        if(verifyGoHighLevelCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'])){  
          return true;
        } else {
          return false;
        }   
      break;

      case "socialfox":
        if(verifyGoHighLevelCredentials($autoresponder['field_three_value'],$autoresponder['field_two_value'])){  
          return true;
        } else {
          return false;
        }   
      break;

      case "zenler":
        if(verifyZenlerCredentials($autoresponder['field_one_value'],$autoresponder['field_two_value'])){ 
          return true;
        } else {
          return false;
        }   
      break;*/
  }   
}


function verifyZenlerCredentials($accountName, $key){


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.newzenler.com/api/v1/users",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
    "X-API-Key: ".$key,
    "X-Account-Name: ".$accountName,
    "Content-Type: application/json",
    "Accept: application/json"
    ),
  ));

  $response = curl_exec($curl);



   $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     curl_close($curl);
  if($httpCode ==  200 ){
    return true;
  } else {
    return false;
  }


  
  
}

function verifyAutomizyCredentials($apiKey, $listId){
   $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://gateway.automizy.com/v2/smart-lists/".$listId,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$apiKey
      ),
    ));
    
    $response = curl_exec($curl);
    
// print_r($response);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     curl_close($curl);
  if($httpCode ==  200 ){
    return true;
  } else {
    return false;
  }
}

function verifyMailerLiteCredentials($apiKey, $groupId){
    $url = 'https://api.mailerlite.com/api/v2/groups/'.$groupId;
  
    $ch = curl_init($url);
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-MailerLite-ApiKey: $apiKey","Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  if($httpCode ==  200 ){
    return $result;
  } else {
    return false;
  }
}

function verifyMailChimpCredentials($apiKey, $listId) {
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId;
    
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode ==  200 ){
        return $result;
    } else {
        return false;
    }    
}

function getCompaignId($headers, $compaign_name) {
    
    $url = 'https://api.getresponse.com/v3/campaigns?query[name]='.$compaign_name;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    return curl_exec($ch);
}

function verifySendyCredentials($autoresponder) {
  $data = [
      'api_key' => $autoresponder['field_two_value'],     
      'list_id' => $autoresponder['field_one_value']          
    ];
  
  $url = $autoresponder['field_three_value'] . '/api/subscribers/active-subscriber-count.php';
    $json = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  if($result == "Invalid API key" || $result == "List does not exist" || $httpCode != 200){
    return false;
  } else {
    return true;
  }
}

function verifyConvertKitCredentials($autoresponder) {
    $url = "https://api.convertkit.com/v3/tags?api_key=".$autoresponder['field_two_value'];
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // echo "<pre>";
    // print_r($result);
  if($httpCode ==  200 ){
    $result = json_decode($result);
    foreach($result->tags as $tag){
      if($tag->name == $autoresponder['field_three_value']){
        // echo 3;
        return $result;
      }
    }
    // echo 1;
    return false;
  } else {
    // echo 2;
    return false;
  }
}

function createLastNameInConvertKit($data) {    

    $json = json_encode($data);
    $url = 'https://api.convertkit.com/v3/custom_fields';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode ==  401 ){
    return false;
  } else {
    return true;
  }
}

function verifyActiveCampaignCredentials($autoresponder) {
  $url = $autoresponder['field_one_value'].'/api/3/lists/'.$autoresponder['field_three_value'];
  
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  if($httpCode ==  200 ){
    return $result;
  } else {
    return false;
  }
}

function verifyKlaviyoCredentials($apiKey, $listId) {
  $url = 'https://a.klaviyo.com/api/v2/list/'.$listId;

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["api-key: $apiKey","Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  if($httpCode == 200){
    return true;
  } else {
    return false;
  }
}

function verifySendInBlueCredentials($key, $listId)
{

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists/".$listId,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "api-key: ".$key." "
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

 if($httpCode == 200){
    return $response;
 }else{
  return false;
 }

}

function verifyAcellEmailCredentials($key, $listId)
{
$curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://marketing.emailtimer.net/api/v1/lists/".$listId."?api_token=".$key,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

curl_close($curl);
 if($contentType == 'application/json'){
   $response = json_decode($response);
    if(isset($response->error) || isset($response->message)){
      return false;
    }else{
    return true;
  }
 }else{
  return false;
 }

}

function verifyConstantContactCredentials($key, $listId,$token)
{
$curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.constantcontact.com/v2/lists/".$listId."?api_key=".$key,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "Authorization: Bearer ".$token
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
 if($httpCode == 200){
  return true; 
 }else{
  return false;
 }

}

function verifyMarketHeroCredentials($autoresponder) {
  $url = "https://api.markethero.io/v1/api/tags?apiKey=".$autoresponder['field_two_value'];
  $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
  if($httpCode ==  200 ){
    $result = json_decode($result);
    foreach($result->tags as $tag){
      if($tag == $autoresponder['field_one_value']){
        return $result;
      }
    }
    return false;
  } else {
    return false;
  }
}

function verifyMoosendAppCredentials($key, $listId)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.moosend.com/v3/lists/".$listId."/details.Json?apikey=".$key."&WithStatistics=true",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "accept: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $response = json_decode($response);

    if($response->Error == ''){
      return true;
    }else{
      return false;
    }
}

function verifyGetGistCredentials($key){
  
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.getgist.com/tags",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer ".$key
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

    curl_close($curl);

     if($httpCode == 200){
      return true;
     }else{
      return false;
     }
}

function verifyHotProspectorCredentials($autoresponder){

  $api_uId = $autoresponder['field_three_value'];   
  $api_key = $autoresponder['field_two_value']; 
  //$parameters = array('api_uId'=>"12543",'api_key'=>"oaGYQlbbif7jSRwh",'Method'=>'FetchAllGroups');
  $parameters = array('api_uId'=>$api_uId,'api_key'=>$api_key,'Method'=>'LoginUser');
  $params_str=json_encode($parameters);
  $ch = curl_init();
  $url = 'https://hotprospector.com/glu/custom_api';
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_POST,count($parameters));
  curl_setopt($ch,CURLOPT_POSTFIELDS, array("Data"=>$params_str));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  $response = json_decode($result, true);

  curl_close($ch);

  if ( isset($response[0]['response']) and $response[0]['response'] == 'true'){
    return true;
  }else{
    return false;
  }
}

function verifyDripCredentials($account_id, $api_token){
  $token = $api_token.': ';
  $account_id = $account_id;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.getdrip.com/v2/".$account_id."/tags",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_USERPWD => $token.': ',
    CURLOPT_HTTPHEADER => array(
      "User-Agent: GroupLeads (groupleads.net)",
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);

  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  if($httpCode == 200){
    return true;
  }else{
    return false;
  }
}

function verifySendLaneCredentials($hash, $key, $listId){

  $data = array('api' => $key,
    'hash' => $hash,
    'list_id' => $listId);

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sendlane.com/api/v1/lists",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data
 
  ));

  $response = curl_exec($curl);

  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
  $respnseData = json_decode($response, true);
  
  if (is_array($respnseData) && isset($respnseData[0]['list_id'])) {
    return true;
  }else{
    return false;
  }
}

function verifyMailingBossCredentials($token, $listId){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://member.mailingboss.com/integration/index.php/lists/".$token,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
  $respnseData = json_decode($response, true);

  if (is_array($respnseData) && isset($respnseData['data']) && count(($respnseData['data'])) > 0 ) {
    $flag = false;
    foreach ( $respnseData['data'] as  $value) {
        if($value['list_uid']==$listId) {
          $flag = true;
        }
     }
    return $flag;
  }else{
    return false;
  }
}

function verifyKartraCredentials($apiKey, $apiPassword, $listId){
  $contantAppId = 'QkOzUoiSYRDM'; // $appId; 
 
  $data =   array(
                'app_id' => $contantAppId,
                'api_key' => $apiKey,
                'api_password' => $apiPassword,
                 'actions' => [
                                '0' => [
                                    'cmd' => 'retrieve_account_lists'
                                ]
                            ]
            );
     $ch = curl_init();
    // CONNECT TO API, VERIFY MY API KEY AND PASSWORD AND GET THE LEAD DATA
    curl_setopt($ch, CURLOPT_URL,"https://app.kartra.com/api");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query($data)
    );

    // REQUEST CONFIRMATION MESSAGE FROM API…
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    $server_json = json_decode($server_output,true);
 

    if ($server_json['status'] == 'Error') {
      return false;
    }else{
      $flag = false; 
      foreach ($server_json['account_lists'] as  $value) {
          if ($listId == $value) {
            $flag = true;
          }
      }
      return $flag;
    }
}

function verifySendGridCredentials($apiKey, $listId){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/".$listId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$apiKey,
      "Content-Type: application/json"
    ),
 
  ));

  $response = curl_exec($curl);

  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
  if($httpCode == 200){
    return true;
  }else{
    return false;
  }
}

function verifyBenchMarkCredentials($apiKey, $listId){
//echo $listId;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://clientapi.benchmarkemail.com/Contact/".$listId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_HTTPHEADER => array(
      "AuthToken:".$apiKey,
      "Content-Type: application/json"
    ),
 
  ));

  $responseData = json_decode(curl_exec($curl),true);

  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  //print_r($responseData);
  curl_close($curl);

  if($httpCode == 200 and isset($responseData['Response']['Status']) and $responseData['Response']['Status'] == 1){

    return true;
  }else{
    return false;
  }
}

function verifySendFoxCredentials($apiToken, $listId){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendfox.com/lists",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ".$apiToken
  ),
));

  $responseData = json_decode(curl_exec($curl),true);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
//print_r($responseData);
  $listFound = false;
  if($httpCode == 200 and isset($responseData['data']) and count($responseData['data']) > 0 ){
    foreach ($responseData['data'] as $key => $value) {
          if($value['id'] == $listId){
            $listFound = true;
          }
    }
    return $listFound ;
  }else{
    return false;
  }
}

function verifyMauticCredentials($username, $password, $apiUrl, $camId)
{
  # code...
// session_start();

// ApiAuth->newAuth() will accept an array of Auth settings
$settings = array(
    'userName'   => $username,             // Create a new user       
    'password'   => $password              // Make it a secure password
);

// Initiate the auth object specifying to use BasicAuth
$initAuth = new ApiAuth();
$auth = $initAuth->newAuth($settings, 'BasicAuth');

// Create an api context by passing in the desired context (Contacts, Forms, Pages, etc), the $auth object from above
// and the base URL to the Mautic server (i.e. http://my-mautic-server.com/api/)
$apiUrl = $apiUrl;
$api = new MauticApi();

$contactApi = $api->newApi('campaigns', $auth, $apiUrl);

$campaign = $contactApi->get($camId);
//print_r($campaign);
 if (isset($campaign['campaign']['id']) and $campaign['campaign']['id'] > 0) {
    
  return true;
 }else{
 
  return false;
 }
}

function verifyHubSpotCredentials($apiKey, $listid){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.hubapi.com/contacts/v1/lists/$listid/?hapikey=$apiKey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
  if($httpCode ==  200 ){
    return true;
  } else {
    
    return false;
  }
}

function verifySimpleroCredentials($apiKey, $userAgent, $listId){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://simplero.com/api/v1/lists.json",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "User-Agent: ".$userAgent." (support@groupleads.net)",
      "Authorization: Basic ".base64_encode($apiKey)
    ),
  ));

    $responseData = json_decode(curl_exec($curl),true);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $listFound = false;
    if($httpCode == 200 and  count($responseData) > 0 ){
      foreach ($responseData as $key => $value) {
            if($value['id'] == $listId){
              $listFound = true;
            }
      }
      return $listFound ;
    }else{
      return false;
    }
}

function verifyOntraportCredentials($apiKey, $appId, $tagId){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.ontraport.com/1/object?objectID=14&id=".$tagId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Api-Key: ".$apiKey,
      "Api-Appid: ".$appId
    ),
  ));

    $responseData = json_decode(curl_exec($curl),true);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $listFound = false;
    if($httpCode == 200 &&  isset($responseData['data']['tag_id']) && $responseData['data']['tag_id'] ==$tagId){
   
      return true;
    }else{
      return false;
    }
}
function SendInfluencersoftOne($url, $data)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // outputting the response to the variable
 
  $res = curl_exec($ch);
 
  curl_close($ch);
  return $res;
}
function CheckHashIn($resp, $user_rs) {
  $secret = $user_rs['user_rps_key'];
  $code = $resp->error_code;
  $text = $resp->error_text;
  $hash = md5("$code::$text::$secret");
  if($hash == $resp->hash)
    return true; // the hash is correct
  else
    return false; // the hash is not correct. 
}
function verifyInfluencerSoftCredentials($userName, $apiKey, $listId){

  $user_rs['user_id'] = $userName;
  
  $user_rs['user_rps_key'] = $apiKey;

  $user_id = $user_rs['user_id'];
  $secret = $user_rs['user_rps_key'];
  $params = null;
  $params = "$params::$user_id::$secret";

  $send_data['hash'] =md5($params);
   
  // Calling the GetAllGroups on the client's mail function and decoding the received data
  $resp = json_decode(SendInfluencersoftOne("http://".$userName.".influencersoft.com/api/GetAllGroups", $send_data));
  
  // Checking the service response
  if(!CheckHashIn($resp, $user_rs)){
    return false;
  }else if($resp->error_code == 0){
      $listFound = false;
      if(isset($resp->result)){
        foreach ($resp->result as $key => $value) {

              if($value->rass_name == $listId){
                $listFound = true;
              }
        }
        return $listFound;
      }else{
        return false;
      }


  }else{
    return false;
  }
}

function verifyGoHighLevelCredentials($apiKey, $listId){
   $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.gohighlevel.com/zapier/users",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization:".$apiKey
      ),
    ));
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     curl_close($curl);
  if($httpCode ==  200 ){
    return true;
  } else {
    return false;
  }
}

function getGroupsOfHotProspectorAutoresponder($autoresponder){

  $api_uId = $autoresponder['field_three_value'];   
  $api_key = $autoresponder['field_two_value']; 
  
  $parameters = array('api_uId'=>$api_uId,'api_key'=>$api_key,'Method'=>'FetchAllGroups');
  $params_str=json_encode($parameters);
  $ch = curl_init();
  $url = 'https://hotprospector.com/glu/custom_api';
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_POST,count($parameters));
  curl_setopt($ch,CURLOPT_POSTFIELDS, array("Data"=>$params_str));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  $response = json_decode($result, true);
  // echo '<pre>';
  // print_r($response);
  //print_r($response[0]['response']);
  curl_close($ch);

  $hotGroups = [];

  if ( isset($response[0]['response']) and $response[0]['response'] == 'true' and count($response[0]['group']) ){
    $groups = $response[0]['group'];
    
    foreach ($groups as $key => $value) {
        $temp = [];
        $temp['hodProsGroupId']=  $value['GroupId'];
        $temp['hodProsGroupTitle'] = $value['GroupTitle'];
        array_push($hotGroups, $temp);
    }
    return $hotGroups;
  }else{
    return $hotGroups;
  }
}

function getTagsOfDripAutoresponder($account_id, $api_token){

  $token = $api_token.': ';
  $account_id = $account_id;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.getdrip.com/v2/".$account_id."/tags",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_USERPWD => $token.': ',
    CURLOPT_HTTPHEADER => array(
      "User-Agent: GroupLeads (groupleads.net)",
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl); 
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   curl_close($curl);
  $response = json_decode($response, true);
  if (array_key_exists("tags",$response)) {
    return $response['tags']; 
  }else{
    return [];
  }
}

function addSubscriber($subscribers, $autoresponder_credentials) {
  $response = true;  
  switch($autoresponder_credentials['responder_key']){
    case "mailchimp":
      foreach($subscribers as $subscriber){
        $data = [
          'email' => strtolower($subscriber["email"]),
          'firstname' => $subscriber["name"],
          'lastname' => "",         
        ];
        syncMailchimp($data, $autoresponder_credentials);
        // 200 = OK
        // 400 = Already ADDED or Invalid Resource
      }
      $response = true;
    break;

    case "getresponse":
      $headers = array(
        'Content-Type: application/json',
        'X-Auth-Token: api-key '.$autoresponder_credentials['field_one_value']
      );

      $result = json_decode(getCompaignId($headers, $autoresponder_credentials['field_two_value']), true);
      if(isset($result["httpStatus"]) && ($result["httpStatus"] == 401)){
        $response = false;
      }elseif(sizeof($result) > 0){
        $compaign_id = $result[0]["campaignId"];        
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'name' => $subscriber["name"],
            'dayOfCycle' => 0,
            "campaign" => array(
              "campaignId" => $compaign_id
            )
          ];
          syncGetResponse($headers, $data);
          // 202 = OK
          // 400 = Email blacklisted
          // 409 = Already ADDED
        } 
        $response = true;       
      }else{
        $response = false;
      }
    break;

    case "mailerlite":    
      foreach($subscribers as $subscriber){
        $subscriberLocation = '';
        if (isset($subscriber["location"])) {
          $subscriberLocation = $subscriber["location"];
        }
        $data = [
          'email' => strtolower($subscriber["email"]),
          'firstname' => $subscriber["name"],
          'lastname' => "",
          "location" => $subscriberLocation         
        ];
        syncMailerLite($data, $autoresponder_credentials); 
        // 200 = OK
        // 400 = Already ADDED or Invalid Resource
      }
      $response = true;
    break;

    case "convertkit":
      if($tagId = getConvertKitTagId($autoresponder_credentials)){
        foreach($subscribers as $subscriber){
          $data = [
            'api_key' => $autoresponder_credentials["field_two_value"],
            'email' => strtolower($subscriber["email"]),
            'first_name' => $subscriber["name"]
            ,"fields" => array("last_name" => "")
          ];
          syncConvertKit($data,$tagId); 
          // 200 = OK
          // 400 = Already ADDED or Invalid Resource
        }
        $response = true;
      } else {
        $response = false;
      }       
    break;

    case "activecampaign":
      foreach($subscribers as $subscriber){
      $data = [
            'contact'=>[
            'email' => strtolower($subscriber["email"]),
            'firstName' => $subscriber["name"],
            'lastName' => ""
          ]
        ];
        if($contactId = addActiveCampaignContact($data, $autoresponder_credentials)){
            // if($autoresponder_credentials['tagid']){
            //     addTagToContact($contactId,$autoresponder_credentials);
            // }
            // temporarily commented
          syncActiveCampaign($contactId, $autoresponder_credentials); 
          if(isset($_POST['questionObj'])){
              $customFieldIdsArray = getCustomFieldId( $_POST['questionObj'], $autoresponder_credentials);
              addCustomFieldToContact($subscriber, $customFieldIdsArray, $autoresponder_credentials, $contactId);
          }
        }       
      }
      $response = true;
    break;

    case "klaviyo":   
      $data = array();
      foreach($subscribers as $subscriber){
        $data[] = [
            'email' => strtolower($subscriber["email"]),
            //'name' => $subscriber["first_name"]. " " .$subscriber["last_name"]
            "first_name" => $subscriber["name"],
            "last_name" => ""
        ];          
      }
      $response = syncKlaviyo($data, $autoresponder_credentials);
    break;

    case "sendinblue":   
      $data = array();
      foreach($subscribers as $subscriber){
        $data = [
          'email' => strtolower($subscriber["email"]),
          'firstname' => $subscriber["name"],
          'lastname' => "",     
        ];
        $response = syncSendInBlue($data, $autoresponder_credentials);
      }
    break;

    case "constantcontact":
      //we are saving token in app_path field
        $data = array();
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'firstname' => $subscriber["name"],
            'lastname' => "",     
          ];
          $response = syncConstantContact($data, $autoresponder_credentials);
        }  
    break;

    case "drip":
       $response = syncDripEmail($subscribers, $autoresponder_credentials);
    break;

    case "kartra":       
      foreach($subscribers as $subscriber){
        $data = [
          'email' => strtolower($subscriber["email"]),
          'first_name' => $subscriber["name"],
          'last_name' => ""   
        ];
        $response = syncKartraEmail($data, $autoresponder_credentials);
      }
    break;

    case "sendgrid":
        $data = array();
        foreach($subscribers as $subscriber){
          $data[] = [
            'first_name' => $subscriber["name"],
            'last_name' => "",
            'email' => strtolower($subscriber["email"])     
          ];          
        }
        $response = syncSendGridEmail($data, $autoresponder_credentials);
    break;

    case "sendfox":
      foreach($subscribers as $subscriber){
        $data = [
          'email' => strtolower($subscriber["email"]),
          'first_name' => $subscriber["name"],
          'last_name' => ""   
        ];
        $response = syncSendFoxEmail($data, $autoresponder_credentials);
      }
    break;

    case "hubspot":
      foreach($subscribers as $subscriber){
        $data = [
          'email' => strtolower($subscriber["email"]),
          'firstname' => $subscriber["name"],
          'lastname' => "",         
        ];
        syncHubSpot($data, $autoresponder_credentials); 
      }
      $response = true;
    break;

    /*
    case "sendy":
      foreach($subscribers as $subscriber){
        $data = [
          'name' => $subscriber["first_name"]." ".$subscriber["last_name"],
          'email' => strtolower($subscriber["email"]),
          'list' => $autoresponder_credentials['field_one_value'],
          'api_key' => $autoresponder_credentials['field_two_value']
        ];
        syncSendy($data, $autoresponder_credentials); 
        // 200 = OK
        // 400 = Already ADDED or Invalid Resource
      }
      $response = true;
    break;  
        
    case "acellemail":
      if(verifyAcellEmailCredentials($autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){   
        $data = array();
        
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'firstname' => $subscriber["first_name"],
            'lastname' => $subscriber["last_name"],     
          ];
          $response = syncAcellEmail($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }  
    break;
      
    case "markethero":
      foreach($subscribers as $subscriber){
        $data = [
          'apiKey' => $autoresponder_credentials["field_two_value"],
          'email' => strtolower($subscriber["email"]),
          'firstName' => $subscriber["first_name"],
          'lastName' => $subscriber["last_name"], 
          'tags' => [
                        $autoresponder_credentials["field_one_value"]
                    ]
        ];
        syncMarketHero($data); 
        // 200 = OK
        // 400 = Already ADDED or Invalid Resource
      }
      $response = true;
    break;
      
    case "moosend":
      if(verifyMoosendAppCredentials($autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){
        $data = array();
        foreach($subscribers as $subscriber){
          $data[] = [
            'Email' => strtolower($subscriber["email"]),
            'Name' => $subscriber["first_name"]. " " .$subscriber["last_name"],     
          ];          
        }
        $response = syncMoosendApp($data, $autoresponder_credentials);
      } else {    
        $response = false;
      }
    break;
      
    case "getgist":
      if(verifyGetGistCredentials($autoresponder_credentials['field_two_value'])){
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'name' => $subscriber["first_name"]. " " .$subscriber["last_name"]    
          ];
          $response = syncGetGistEmail($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }
    break;
      
    case "hotprospector":
      $hotProspectorCredentials =[];
      $hotProspectorCredentials['field_three_value'] = $autoresponder_credentials['field_three_value'];
      $hotProspectorCredentials['field_two_value'] = $autoresponder_credentials['field_two_value'];
      if(verifyHotProspectorCredentials($hotProspectorCredentials)){
       
        $data = array();
        foreach($subscribers as $subscriber){
          $data[] = [
            'first_name' => $subscriber["first_name"],
            'last_name' => $subscriber["last_name"],
            'email' => strtolower($subscriber["email"])     
          ];          
        }
        $response = syncHotProspectorAutoresponder($data, $autoresponder_credentials);
      } else {    
        $response = false;
      }
    break;

    case "sendlane":
      if(verifySendLaneCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){             
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'first_name' => $subscriber["first_name"],
            'last_name' => $subscriber["last_name"]   
          ];
          $response = syncSendLaneEmail($data, $autoresponder_credentials);
        }
        
      } else {    
        $response = false;
      }
    break;
        
    case "mailingboss":
      if(verifyMailingBossCredentials($autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){             
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'first_name' => $subscriber["first_name"],
                      'last_name' => $subscriber["last_name"]   
          ];
          $response = syncMailingBossEmail($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }
    break;
         
    case "benchmark":
      if(verifyBenchMarkCredentials($autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){             
           foreach($subscribers as $subscriber){
              $data = [
                'email' => strtolower($subscriber["email"]),
                'first_name' => $subscriber["first_name"],
                'last_name' => $subscriber["last_name"]   
              ];
              $response = syncBenchMarkEmail($data, $autoresponder_credentials);
            }
        
      } else {    
        $response = false;
      }
    break;

    case "mautic":
      if(verifyMauticCredentials($autoresponder_credentials['field_three_value'], $autoresponder_credentials['field_two_value'], $autoresponder_credentials['field_one_value'] , $autoresponder_credentials['field_four'])){             
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'first_name' => $subscriber["first_name"],
            'last_name' => $subscriber["last_name"]   
          ];
          $response = syncMauticEmail($data, $autoresponder_credentials);
        }

      } else {    
        $response = false;
      }
    break;

    case "simplero":
        //we are saving token in app_path field
      if(verifySimpleroCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){    
        $data = array();
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'firstname' => $subscriber["first_name"],
            'lastname' => $subscriber["last_name"],     
          ];
          $response = syncSimplero($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }
    break;
      
    case "ontraport":
        //we are saving token in app_path field
      if(verifyOntraportCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){   
        $data = array();
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'firstname' => $subscriber["first_name"],
            'lastname' => $subscriber["last_name"],     
          ];
          $response = syncOntraport($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }
    break;

    case "influencersoft":
        //we are saving token in app_path field
      if(verifyInfluencersoftCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'],$autoresponder_credentials['field_one_value'])){    
        $data = array();
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            'name' => $subscriber["first_name"]
          ];
          $response = syncInfluencerSoft($data, $autoresponder_credentials);
        }
      } else {    
        $response = false;
      }
    break;
    
    case "automizy":
      if(verifyAutomizyCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'])){      
        $automizyTagName = $autoresponder_credentials['field_one_value'];
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            "tags" => [$automizyTagName],
            'customFields'=>['firstname' => $subscriber["first_name"],
                            'lastname' => $subscriber["last_name"]]         
          ];
          syncAutomizy($data, $autoresponder_credentials); 
          // 200 = OK
          // 400 = Already ADDED or Invalid Resource
        }
        $response = true;
      } else {    
        $response = false;
      }
    break;
    
    case "gohighlevel":
      if(verifyGoHighLevelCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'])){     
        $goHighLevelTagName = $autoresponder_credentials['field_two_value'];
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            "lead" => 1,
            "tags" => $goHighLevelTagName,
            "first_name" => $subscriber["first_name"],
            "last_name" => $subscriber["last_name"],
            "name" => $subscriber["first_name"].' '.$subscriber["last_name"]
          ];
          syncGohighLevel($data, $autoresponder_credentials); 
          // 200 = OK
          // 400 = Already ADDED or Invalid Resource
        }
        $response = true;
      } else {    
        $response = false;
      }
    break;
    
    case "socialfox":
      if(verifyGoHighLevelCredentials($autoresponder_credentials['field_three_value'],$autoresponder_credentials['field_two_value'])){     
        $goHighLevelTagName = $autoresponder_credentials['field_two_value'];
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            "lead" => 1,
            "tags" => $goHighLevelTagName,
            "first_name" => $subscriber["first_name"],
            "last_name" => $subscriber["last_name"],
            "name" => $subscriber["first_name"].' '.$subscriber["last_name"]
          ];
          syncGohighLevel($data, $autoresponder_credentials); 
          // 200 = OK
          // 400 = Already ADDED or Invalid Resource
        }
        $response = true;
      } else {    
        $response = false;
      }
    break;

    case "zenler":
      if(verifyZenlerCredentials($autoresponder_credentials['field_one_value'],$autoresponder_credentials['field_two_value'])){     
        $goHighLevelTagName = $autoresponder_credentials['field_two_value'];
        foreach($subscribers as $subscriber){
          $data = [
            'email' => strtolower($subscriber["email"]),
            "roles" => [8],
            "first_name" => $subscriber["first_name"],
            "last_name" => $subscriber["last_name"]
          ];
          syncZenler($data, $autoresponder_credentials['field_one_value'], $autoresponder_credentials['field_two_value']); 
          // 200 = OK
          // 400 = Already ADDED or Invalid Resource
        }
        $response = true;
      } else {    
        $response = false;
      }
    break;*/
  }  
  return $response;
}


function syncZenler($data, $accountName, $key)
{
  
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.newzenler.com/api/v1/users",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => array(
        "X-API-Key: ".$key,
        "X-Account-Name: ".$accountName,
        "Content-Type: application/json",
        "Accept: application/json"
      ),
    ));

    $response = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if($httpCode ==  200 ){
      return true;
    } else {
      return false;
    }


}

function syncAutomizy($data, $autoresponder_credentials) {
  $apiKey = $autoresponder_credentials['field_three_value']; 
  $listId = $autoresponder_credentials['field_two_value'];  
 
    
    
  $url = "https://gateway.automizy.com/v2/smart-lists/".$listId."/contacts";  
  $json = json_encode($data);
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL =>$url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>$json,
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$apiKey,
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if($httpCode == 201){
        echo "Successfully Subscribed";
        die();
        return true;
    }else{
        return false;
    }
}

function syncMailerLite($data, $autoresponder_credentials) {
  $apiKey  = $autoresponder_credentials['field_one_value']; 
  $groupId = $autoresponder_credentials['field_two_value']; 
  
  $url = 'https://api.mailerlite.com/api/v2/groups/'.$groupId.'/subscribers'; 
  $json = json_encode([
    'email' => $data['email'],
    'name' => $data['firstname'].' '.$data['lastname'],
    'fields' => [   
                  "city" => $data['location']
                ]
  ]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-MailerLite-ApiKey: $apiKey","Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);    
  return $httpCode;
}

function syncMailchimp($data, $autoresponder_credentials) { 
  $apiKey = $autoresponder_credentials['field_one_value']; 
  $listId = $autoresponder_credentials['field_two_value'];
  $tagName = $autoresponder_credentials['field_three_value'];
  
  $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
  $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members';

    $json = null;
    
    $inputData = [
    'email_address' => $data['email'],
    'status'        => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
    'merge_fields'  => [
      'FNAME'     => $data['firstname'],
      'LNAME'     => $data['lastname']
    ]
  ];

    if($tagName != ""){
        $inputData['tags'] = [$tagName];
    }

  $json = json_encode($inputData);
  
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                
  
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);    
  return $httpCode;
}

function syncGetResponse($headers, $data) {

    $url = 'https://api.getresponse.com/v3/contacts';
    $json = json_encode($data);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}

function syncSendy($data, $autoresponder_credentials) {
    $json = json_encode($data);
    //print_r($json);
    $url = $autoresponder_credentials['field_three_value'] . '/subscribe';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);
   // print_r($result);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}

function getConvertKitTagId($autoresponder) {
    $url = "https://api.convertkit.com/v3/tags?api_key=".$autoresponder['field_two_value'];
  $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode ==  200 ){
    $result = json_decode($result);
    foreach($result->tags as $tag){
      if($tag->name == $autoresponder['field_three_value']){
        return $tag->id;
      }
    }
    return false;
  } else {
    return false;
  }
}

function syncConvertKit($data,$tagId) {   

    $json = json_encode($data);
    $url = 'https://api.convertkit.com/v3/tags/'.$tagId.'/subscribe';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}

function addActiveCampaignContact($data,$autoresponder){
  $data = json_encode($data);
  $url = $autoresponder['field_one_value'].'/api/3/contacts/sync';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  if($httpCode == 201){
    return json_decode($result)->contact->id;
  } elseif($httpCode == 422){
    return getAcContactId($data, $autoresponder);
  } else {
    return false;
  }
}

function getAcContactId($data,$autoresponder){
  $data = json_decode($data);
  $url = $autoresponder['field_one_value'].'/api/3/contacts?email='.$data->contact->email;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);              
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  if($httpCode == 200){
      $resultArray = json_decode($result);
      if(COUNT($resultArray->contacts) > 0 ){
          return $resultArray->contacts[0]->id;
      }
    return false;
  } else {
    return false;
  }
}

function addTagToContact($contactId,$autoresponder){
    

   $activeCampaignTagIds = json_decode($autoresponder['tagid']);

  if (is_array($activeCampaignTagIds)) {

    foreach ($activeCampaignTagIds as $oneTagObject) {
     
      $data = json_encode(array('contactTag' => array('contact'=>$contactId, 'tag'=>$oneTagObject->tagid) ));
      $url = $autoresponder['field_one_value'].'/api/3/contactTags';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
    }
  }else{
   
      $data = json_encode(array('contactTag' => array('contact'=>$contactId, 'tag'=>$autoresponder['tagid']) ));
      $url = $autoresponder['field_one_value'].'/api/3/contactTags';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if($httpCode == 201){
        return true;
      } else {
        return false;
      }
  }
}

function syncActiveCampaign($contactId,$autoresponder){
  $url = $autoresponder['field_one_value'].'/api/3/contactLists';
  $data = json_encode([
    'contactList'=>[
        'list' => $autoresponder['field_three_value'],
        'contact' => $contactId,
        'status' => 1
      ]
    ]
  );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Api-Token: '.$autoresponder['field_two_value']]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $result = curl_exec($ch);
  curl_close($ch);
  if($httpCode == 201){
    return true;
  } else {
    return false;
  }
}

function getCustomFieldId($allQuestionArray,$autoresponder_credentials){

    $ques_one = $allQuestionArray['questionOne'];
    $ques_two = $allQuestionArray['questionTwo'];
    $ques_three = $allQuestionArray['questionThree'];
    
    $idsArray =  getCustomFieldIdAll($allQuestionArray,$autoresponder_credentials);
 
    if($ques_one !='' and !$idsArray['ques_one_id']){
       $idsArray['ques_one_id'] = createCustomFeildAndRelation($ques_one,$autoresponder_credentials);
    }
    
    if($ques_one !='' and !$idsArray['ques_two_id']){
        $idsArray['ques_two_id'] =  createCustomFeildAndRelation($ques_two,$autoresponder_credentials);
    }
    
    if($ques_one !='' and !$idsArray['ques_three_id']){
       $idsArray['ques_three_id'] = createCustomFeildAndRelation($ques_three,$autoresponder_credentials);
    }
    
  return $idsArray;
}

function  createCustomFeildAndRelation($customFieldName,$autoresponder_credentials){
    
  
    $newCustomFieldId = false;
    $listId = $autoresponder_credentials['field_three_value'];
    $postData = array('field'=>['type' =>'text', 
                                "title" => $customFieldName, 
                                "descript" => null, 
                                "isrequired" => false,  
                                "perstag" =>null, 
                                "defval" => null, 
                                "visible"=> true,   
                                "ordernum"=> 0]
                                );
    $json = json_encode($postData);  
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL =>  $autoresponder_credentials['field_one_value']."/api/3/fields/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>$json,
      CURLOPT_HTTPHEADER =>  array(
        "Api-Token: ".$autoresponder_credentials['field_two_value'],
        'Content-Type: application/json'
      ),
    ));
    
    $responseData = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
     $response = json_decode($responseData, true); 
    
         if($httpCode ==201){
            $newCustomFieldId = $response['field']['id'];
            
            $postDataTwo = array("fieldRel"=> [ "field"=> $newCustomFieldId, "relid"=> $listId]);
                ////////// create relation with list //////////////
               $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL =>  $autoresponder_credentials['field_one_value']."/api/3/fieldRels",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS =>json_encode($postDataTwo),
                  CURLOPT_HTTPHEADER =>  array(
                    "Api-Token: ".$autoresponder_credentials['field_two_value'],
                    'Content-Type: application/json'
                  ),
                ));
                
                $responseData = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                 $response = json_decode($responseData, true); 
         
                 if($httpCode == 201){
                     return $newCustomFieldId;
                 }else{
                     return false;
                 }
     }
}

function getCustomFieldIdAll($allQuestionArray,$autoresponder){
    
    $ques_one = $allQuestionArray['questionOne'];
    $ques_two = $allQuestionArray['questionTwo'];
    $ques_three = $allQuestionArray['questionThree'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL =>  $autoresponder['field_one_value'].'/api/3/fields',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Api-Token: ".$autoresponder['field_two_value'],
        'Content-Type: application/json'
      ),
    ));
    
   
    $responseData = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
   // print_r($httpCode);
  //  echo $response;
    $response = json_decode($responseData, true); 
    
   // print_r($response);
    if($httpCode == 200 and isset($response['fields'])){
        
        //fields
       
            $ques_one_id = false;
            $ques_two_id = false;
            $ques_three_id = false;
        
      
        foreach ($response['fields'] as $key => $value) {
                    //  print_r($value);
              if($value['title'] == $ques_one){
                 $ques_one_id = $value['id'];
              }
              
              if($value['title'] == $ques_two){
                 $ques_two_id = $value['id'];
              }
              
              if($value['title'] == $ques_three){
                 $ques_three_id = $value['id'];
              }
        }
        
        $tempArray= [];
        
        $tempArray = ['ques_one_id'=>$ques_one_id,'ques_two_id'=>$ques_two_id,'ques_three_id'=>$ques_three_id];
        return $tempArray;
    


  }else{
    return false;
  }
}

function addCustomFieldToContact($subscriber, $customFieldIdsArray, $autoresponder_credentials, $contactId){

 
    foreach($customFieldIdsArray as $key => $value){
        $postData = []; 
        if($subscriber['ans_one'] !='' && $key == 'ques_one_id' && $customFieldIdsArray['ques_one_id']){
            
              $postData = array('fieldValue'=>[
                                "contact" => $contactId, 
                                 "field"=> $customFieldIdsArray['ques_one_id'],
                                 "value"=>$subscriber['ans_one']
                                 ]
                                );
                                
                setValueToField($postData, $autoresponder_credentials);
        }
        
        if($subscriber['ans_two'] !='' && $key == 'ques_two_id' && $customFieldIdsArray['ques_two_id']){
            
              $postData = array('fieldValue'=>[
                                "contact" => $contactId, 
                                 "field"=> $customFieldIdsArray['ques_two_id'],
                                 "value"=> $subscriber['ans_two']
                                 ]
                                );
             setValueToField($postData, $autoresponder_credentials);
        }
        
        if($subscriber['ans_three'] !='' && $key == 'ques_three_id' && $customFieldIdsArray['ques_three_id']){
              $postData = array('fieldValue'=>[
                                "contact" => $contactId, 
                                 "field"=> $customFieldIdsArray['ques_three_id'],
                                 "value"=>$subscriber['ans_three']
                                 ]
                                );
             setValueToField($postData, $autoresponder_credentials);
            
            
        }
    }
}

function syncKlaviyo($data, $autoresponder_credentials) {
  $apiKey = $autoresponder_credentials['field_one_value']; 
  $listId = $autoresponder_credentials['field_two_value'];  
  
  $url = 'https://a.klaviyo.com/api/v2/list/'.$listId.'/members';

  $json = json_encode([
      "profiles" => $data
    ]);
  
    $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["api-key: $apiKey","Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);    
  if($httpCode == 200){
    return true;
  } else {
    return false;
  }
}

function syncSendInBlue($data, $autoresponder_credentials) { 

  $apiKey = $autoresponder_credentials['field_one_value']; 
  $listId = (int) $autoresponder_credentials['field_two_value'];
 
   $json = json_encode([
    'email' => $data['email'],
    'listIds' => array($listId),
    'updateEnabled' => true, 
    'attributes' => [
      'FIRSTNAME' => $data['firstname'],
      'LASTNAME' => $data['lastname']
    ]
  ]);
  
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>  $json,
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "api-key: ".$apiKey."",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$err = curl_error($curl);

curl_close($curl); 
return $httpCode;
}

function syncAcellEmail($data, $autoresponder_credentials) { 
  $apiKey = $autoresponder_credentials['field_two_value']; 
  $listId = $autoresponder_credentials['field_one_value'];
 
   $json = json_encode([
    'EMAIL' => $data['email'],
      'FIRST_NAME' =>   $data['firstname'],
      'LAST_NAME'     => $data['lastname']
  ]);

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://marketing.emailtimer.net/api/v1/lists/".$listId."/subscribers/store?api_token=".$apiKey,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>  $json,
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "content-type: application/json"
    ),
  ));
}

function syncConstantContact($data, $autoresponder_credentials) {
    $apiKey = $autoresponder_credentials['field_two_value']; 
    $listId = $autoresponder_credentials['field_three_value'];
    $token  = $autoresponder_credentials['field_one_value'];
    
    $json = json_encode([
                "lists" => [array('id'=>$listId)],
                'email_addresses' => [array('email_address'=>$data['email'])],
                'first_name' =>   $data['firstname'],
                'last_name'     => $data['lastname']
            ]);
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_OWNER&api_key=".$apiKey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$token
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    
    $response = json_decode($response);
    //print_r($response);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($httpCode == 200){
      return true;
    }else{
      return false;
    }
}

function syncMarketHero($data) {    

    $json = json_encode($data);

    $url = 'https://api.markethero.io/v1/api/tag-lead';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}

function syncMoosendApp($data, $autoresponder_credentials) {
  $apiKey = $autoresponder_credentials['field_two_value']; 
  $listId = $autoresponder_credentials['field_one_value'];  
  
  $url = 'https://api.moosend.com/v3/subscribers/'.$listId.'/subscribe_many.json?apikey='.$apiKey;

  $json = json_encode([
      "Subscribers" => $data
    ]);
  
 
    $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                
  $result = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);    
  if($httpCode == 200){
    return true;
  } else {
    return false;
  }
}

function syncGetGistEmail($data, $autoresponder_credentials) { 
  $apiKey = $autoresponder_credentials['field_two_value']; 
  $tagName = [$autoresponder_credentials['field_one_value']];
 
  $data['tags'] = $tagName;
  $json = json_encode($data);
  
 // echo $json;

  $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.getgist.com/leads",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>  $json,
  CURLOPT_HTTPHEADER => array(
   "Content-Type: application/json",
    "Authorization: Bearer ".$apiKey
    ),
  ));
  $response = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $err = curl_error($curl);
  $responseData =  json_decode($response, true);
  if (isset($responseData['errors'])) {
    return false;
  }else{
    return true;
  }
}

function syncHotProspectorAutoresponder($data, $autoresponder_credentials){

  $groupId = $autoresponder_credentials['field_one_value'];
  $api_uId = $autoresponder_credentials['field_three_value'];
  $api_key = $autoresponder_credentials['field_two_value'];
  $parameters = array(
        'api_uId'=>$api_uId,
        'api_key'=>$api_key,
        'groupId'=>$groupId, 
        'leads_array'=>$data,
        'Method'=>'AddMultipleLeads');


  $params_str=json_encode($parameters);
  $ch = curl_init();
  $url = 'https://hotprospector.com/glu/custom_api';
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_POST,count($params_str));
  curl_setopt($ch,CURLOPT_POSTFIELDS, array("Data"=>$params_str));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  $response = json_decode($result, true);
  //print_r($response);
  //print_r($response[0]['response']);
  curl_close($ch);

  if ( isset($response[0]['response']) and $response[0]['response'] == 'true'){
    return true;
  }else{
    return false;
  }
}

function syncDripEmail($subscribers, $autoresponder_credentials) { 
 
  $account_id = $autoresponder_credentials['field_one_value']; 
 
  $tagName = [$autoresponder_credentials['field_three_value']];
 
  $api_token = $autoresponder_credentials['field_two_value'];
 
  $token = $api_token.': ';
 
  $data = array();
  foreach($subscribers as $subscriber){
    $data[] = [
       'email' => strtolower($subscriber["email"]),
      'first_name' => $subscriber["name"],
      'last_name' => "",
      'tags'=> $tagName
    ];         
  }

  $postData = json_encode(  array('batches'=>array(array('subscribers'=>$data))));


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.getdrip.com/v2/".$account_id."/subscribers/batches",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS =>  $postData,
    CURLOPT_USERPWD => $token.': ',
    CURLOPT_HTTPHEADER => array(
      "User-Agent: GroupLeads (groupleads.net)",
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl); 
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 
  curl_close($curl);
  
  if($httpCode ==  201 ){
    return true;
  } else {
    return false;
  }
}

function syncSendLaneEmail($subscriber, $autoresponder_credentials) { 
  $hash = $autoresponder_credentials['field_three_value']; 
  $key = $autoresponder_credentials['field_two_value'];
  $listId = $autoresponder_credentials['field_one_value'];
 
  $data =  array('api' => $key,
                'hash' => $hash,
                'email' => $subscriber['email'],
                'list_id' => $listId,
                'first_name' => $subscriber['first_name'],
                'last_name' => $subscriber['last_name']
              );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sendlane.com/api/v1/list-subscriber-add",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>  $data
  ));

  $response = curl_exec($curl); 
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
  
  if(isset($response->success)){
    return true;
  } else {
    return false;
  }
}

function syncMailingBossEmail($subscriber, $autoresponder_credentials) { 
  $token = $autoresponder_credentials['field_two_value'];
  $listId = $autoresponder_credentials['field_one_value'];

  $data = array('email' => $subscriber['email'],'list_uid' => $listId,'status' => 'confirmed','fname' => $subscriber['first_name'],'lname' => $subscriber['last_name']);

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://member.mailingboss.com/integration/index.php/lists/subscribers/create/".$token,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => $data
  ));

  $response = curl_exec($curl); 
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
//print_r($response);
  $respnseData = json_decode($response, true);
  if(isset($respnseData['status'])){
    return true;
  } else {
    return false;
  }
}

function syncKartraEmail($subscriber, $autoresponder_credentials) { 
  $apiPassword = $autoresponder_credentials['field_two_value'];
  $listName = $autoresponder_credentials['field_three_value'];
  $apiKey = $autoresponder_credentials['field_one_value'];
  $contantAppId = 'QkOzUoiSYRDM'; // $autoresponder_credentials['field_four']; //'xgIjMlXrpQOz'; 
// echo $contantAppId;
  $data =   array(
                'app_id' => $contantAppId,
                'api_key' => $apiKey,
                'api_password' => $apiPassword,
                'lead' => array(
                                'email' => $subscriber['email'],
                                'first_name' => $subscriber['first_name'],
                                'last_name' => $subscriber['last_name']        
                                  ),
                'actions' => [
                                '0' => [
                                 'cmd' => 'create_lead'
                               ],
                                '1' => [
                                    'cmd' => 'subscribe_lead_to_list',
                                    'list_name' => $listName
                                ]
                            ]
            );
     $ch = curl_init();
    // CONNECT TO API, VERIFY MY API KEY AND PASSWORD AND GET THE LEAD DATA
    curl_setopt($ch, CURLOPT_URL,"https://app.kartra.com/api");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query($data)
    );

    // REQUEST CONFIRMATION MESSAGE FROM API…
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    $server_json = json_decode($server_output,true);
    if ($server_json['status'] == 'Success') {
      return true;
    }else{
      return false;
    }
}  

function syncSendGridEmail($subscriber, $autoresponder_credentials) { 
  $apiKey = $autoresponder_credentials['field_one_value'];
  $listId = array($autoresponder_credentials['field_two_value']);

  $json = json_encode([
          "list_ids" => $listId,
          "contacts" => $subscriber
          ]
  ); 

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS =>$json,
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$apiKey,
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);

 $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);
  if($httpCode = 202){
    return true;
  }else{
    return false;
  }
} 

function syncBenchMarkEmail($subscriber, $autoresponder_credentials) { 
  $apiKey = $autoresponder_credentials['field_two_value'];
  $listId = $autoresponder_credentials['field_one_value'];

  $json = json_encode([
              'Data' => [
                      'Email'=> $subscriber['email'],
                        "FirstName"=>$subscriber['first_name'],
                        "LastName"=>$subscriber['last_name'] ,
                        "EmailPerm"=>"1"
                      ]
          ]
  ); 

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://clientapi.benchmarkemail.com/Contact/".$listId."/ContactDetails",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>$json,
    CURLOPT_HTTPHEADER => array(
      "AuthToken:".$apiKey,
      "Content-Type: application/json"
    ),
  ));

   $responseData = json_decode(curl_exec($curl),true);

  $err = curl_error($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);

  if($httpCode == 200 and isset($responseData['Response']['Status']) and $responseData['Response']['Status'] == 1){
    return true;
  }else{
    return false;
  }
}

function syncSendFoxEmail($subscriber, $autoresponder_credentials) { 
  $apiToken = $autoresponder_credentials['field_one_value'];
  $listId = array($autoresponder_credentials['field_two_value']);

  $json = json_encode([
                        'email'=> $subscriber['email'],
                        "first_name"=>$subscriber['first_name'],
                        "last_name"=>$subscriber['last_name'],
                        "lists" => $listId
                      ]); 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendfox.com/contacts",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>$json,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ".$apiToken,
    "Content-Type: application/json"
  ),
));

$responseData = json_decode(curl_exec($curl), true);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);


  if($httpCode == 200 and isset($responseData['id']) and $responseData['id'] > 0){
    return true;
  }else{
    return false;
  }
}

function syncMauticEmail($subscriber, $autoresponder_credentials) { 
  $password = $autoresponder_credentials['field_two_value'];
  $apiUrl = $autoresponder_credentials['field_one_value'];
  $username = $autoresponder_credentials['field_three_value'];
  $camId = $autoresponder_credentials['field_four']; //'xgIjMlXrpQOz'; 

$settings = array(
    'userName'   => $username,             // Create a new user       
    'password'   => $password              // Make it a secure password
);


$initAuth = new ApiAuth();
$auth = $initAuth->newAuth($settings, 'BasicAuth');

$apiUrl = $apiUrl;
$api = new MauticApi();
$contactApi = $api->newApi('campaigns', $auth, $apiUrl);

$campaign = $contactApi->get($camId);

 if (isset($campaign['campaign']['id']) and $campaign['campaign']['id'] > 0) {
    

    $contactApi = $api->newApi('contacts', $auth, $apiUrl);

    $data = array(
        'firstname' => $subscriber['first_name'],
        'lastname'  => $subscriber['last_name'],
        'email'     => $subscriber['email'],
        'ipAddress' => '127.0.0.1',
        'overwriteWithBlank' => true,
    );

  $contact = $contactApi->create($data);
//  print_r($contact);
    $contactApiAdded = $api->newApi('campaigns', $auth, $apiUrl);

// print_r($contactApiAdded);
    $response = $contactApiAdded->addContact($camId, $contact['contact']['id']);
  
  if (isset($response['success'])) {
        return true;
    }else{
      return false;
    }
 
 }else{
 
  return false;
 }
}

function syncHubSpot($data, $autoresponder_credentials) {
  $apiKey = $autoresponder_credentials['field_one_value'];
  $listid = $autoresponder_credentials['field_two_value'];

  $postData  = json_encode(array('properties' => 
            [
                    array('property' => 'email', 'value' => $data['email'] ),
                    array('property' => 'firstname', 'value' => $data['firstname'] ),
                    array('property' => 'lastname', 'value' => $data['lastname'] )
                    ]
           ));

   $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL =>  "https://api.hubapi.com/contacts/v1/contact/?hapikey=$apiKey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>$postData,
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

$responseData = json_decode($response,true);

  if(isset($responseData['vid'])){
      $contactId = $responseData['vid'];
    $curl = curl_init();
      $postDataTwo = json_encode(array('vids' => [$contactId] ));
   
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.hubapi.com/contacts/v1/lists/$listid/add?hapikey=$apiKey",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>$postDataTwo,
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return true;
  }
}

function syncSimplero($data, $autoresponder_credentials) {
  
    $userAgent = $autoresponder_credentials['field_two_value']; 
    $listId = $autoresponder_credentials['field_one_value'];
    $apiKey = $autoresponder_credentials['field_three_value'];
   
    $json = json_encode([
                'first_name' =>   $data['firstname'],
                'last_name' => $data['lastname'],
                'email' => $data['email']
            ]);
          
    $userAgentString =  "User-Agent: ".$userAgent." (support@groupleads.net)";
  
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://simplero.com/api/v1/lists/".$listId."/subscribe.json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  $json,
        CURLOPT_HTTPHEADER => array(
            $userAgentString,
             "Authorization: Basic ".base64_encode($apiKey),
             "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    
    $response = json_decode($response);
  
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($httpCode == 201){
      return true;
    }else{
      return false;
    }
}

function syncOntraport($data, $autoresponder_credentials) {
  
    $appId = $autoresponder_credentials['field_two_value']; 
    $tagId = $autoresponder_credentials['field_one_value'];
    $apiKey = $autoresponder_credentials['field_three_value'];
   
    $postData = [
                'firstname' =>   $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'contact_cat' => $tagId
            ];
          
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.ontraport.com/1/Contacts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  $postData,
        CURLOPT_HTTPHEADER => array(
            "Api-Key: ".$apiKey,
            "Api-Appid: ".$appId
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    
    $response = json_decode($response);
  
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($httpCode == 200){
      return true;
    }else{
      return false;
    }
}

function syncInfluencerSoft($data, $autoresponder_credentials){
  
  $apiKey   = $autoresponder_credentials['field_two_value']; 
  $listId   = $autoresponder_credentials['field_one_value'];
  $userName = $autoresponder_credentials['field_three_value'];
//echo $userName;
  $user_rs['user_id'] = $userName;
  
  $user_rs['user_rps_key'] = $apiKey;

   $send_data = array(
   'rid[0]' => $listId, // <span style="font-weight: 400;" data-mce-style="font-weight: 400;">the group the subscriber will go to.</span>
   'lead_name' => $data['name'],
   'lead_email' => $data['email']
   );
   // Forming the hash to the transmitted data.
   $send_data['hash'] = GetHashTwo($send_data, $user_rs);
   // Calling the AddLeadToGroup function in the API and decoding the received data.
   $resp = json_decode(SendInfluencersoftTwo("http://".$userName.".influencersoft.com/api/AddLeadToGroup", $send_data));
   // Checking the service response.
   if(!CheckHashTwo($resp, $user_rs)){
   return false;
   }
   if($resp->error_code == 0){
   return true;
  }
   else{
    return false;
   }
}
 // Forming the transferred to the API data hash.
function GetHashTwo($params, $user_rs) {
   $params = http_build_query($params);
   $user_id = $user_rs['user_id'];
   $secret = $user_rs['user_rps_key'];
   $params = "$params::$user_id::$secret";
   return md5($params);
}

function SendInfluencersoftTwo($url, $data){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // outputting the response to the variable. 
   $res = curl_exec($ch);
   curl_close($ch);
   return $res;
 }

// Checking the received response hash.
 function CheckHashTwo($resp, $user_rs) {
     $secret = $user_rs['user_rps_key'];
     $code = $resp->error_code;
     $text = $resp->error_text;
     $hash = md5("$code::$text::$secret");
     if($hash == $resp->hash)
     return true; // the hash is correct
     else
     return false; // the hash is not correct
 }

function syncGoHighLevel($data, $autoresponder_credentials) {
  $apiKey = $autoresponder_credentials['field_three_value']; 
  $url = "https://api.gohighlevel.com/zapier/contact/add_update"; 
  $json = json_encode($data);
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL =>$url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>$json,
      CURLOPT_HTTPHEADER => array(
        "Authorization:".$apiKey,
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);
   
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  if($httpCode == 200){
      return true;
  }else{
      return false;
  }
}

function setValueToField($postData, $autoresponder_credentials){
 
      $json = json_encode($postData);  
            
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
              CURLOPT_URL =>  $autoresponder_credentials['field_one_value']."/api/3/fieldValues",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>$json,
              CURLOPT_HTTPHEADER =>  array(
                "Api-Token: ".$autoresponder_credentials['field_two_value'],
                'Content-Type: application/json'
              ),
            ));
            
            $responseData = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
             $response = json_decode($responseData, true); 
             
             if($httpCode == 200){
                 return true;
             }else{
                 return false;
             }
    
}


?>
