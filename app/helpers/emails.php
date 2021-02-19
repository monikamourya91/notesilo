<?php

function sendWelcomeMailNew($email, $name = ""){	
$template = "Hi ".$name.",<br/><br/>";
$template .= "Thanks for signing up with Page2Leads!<br/><br/>";
$template .= "Thank you<br/>Page2Leads Team";
	return sendGridApi($email, "Thankyou for signing up to Page2Leads!", $template);
}

function sendWelcomeMailNewPaddleUser($email, $password, $name = ""){	
    
    $template = "Hi there!<br/><br/>";
    $template .= "Thank you for signing up for Page2Leads.<br/><br/>";

    $template .= "To login, please use the credentials below:<br/><br/>";

    $template .= "Email: ".$email." <br/>Password: ".$password."<br/><br/>"; 

    $template .= "Thank you<br/>Page2Leads Team.";

	return sendGridApi($email, "Thankyou for signing up to Page2Leads!", $template);
}

function sendUpgradeDowngradeMail($email,$name,$plan, $action="upgraded"){	
	$template = "Hi ".$name.",<br/><br/>";
	$template .= "Your Subscription plan has ". $action ." to ".$plan."<br/><br/>";
	$template .= "Thanks<br/>Page2Leads Team";
	return sendGridApi($email, "Page2Leads Subscription Changed", $template);
}

function cancelSubscriptionMail($email,$name,$paymentFailed = false, $effective_date = false){
    $template = "Hi ".$name.",<br/><br/>";
    if($effective_date){
        $template .= "You have cancelled your subscription plan. Subscription Cancellation will be effective from ". $effective_date ."<br/><br/>";
    } elseif($paymentFailed){
        $template .= "Your Subscription plan has been cancelled due to payment failure on paddle.<br/><br/>";
    } else {
        $template .= "Your Subscription plan has been cancelled. Please contact administrator to reactivate.<br/><br/>";
    }
	$template .= "Thanks<br/>Page2Leads Team";
	return sendGridApi($email, "Page2Leads Subscription Cancelled", $template);
}

//function sendGridApi($to, $subject, $message, $from = 'Page2Leads <'.config('constant.HELP_EMAIL').'>')
function sendGridApi($to, $subject, $message, $from = 'Page2Leads'){
	$curl_post_data = [
		'personalizations' => [
			[
				'to' => [
					[
						'email'=> $to
					]
				],
				'subject' => $subject,
			]
		],
		'from' => [
			'email'=> config('constant.HELP_EMAIL'),
			'name'=> config('constant.SUPPORT_NAME')
		],
		'content' =>[[
			'type'=> "text/html",
			'value'=> $message
		]]
	];
	
    $json = json_encode($curl_post_data);
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => config('constant.SENDGRID_SERVICEURL'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".config('constant.SENDGRID_APIKEY')
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    
    $response = json_decode($response);
    
    return [];
}

//function sendMailgunApi($to, $subject, $message, $from = 'Page2Leads <'.config('constant.HELP_EMAIL').'>')
function sendMailgunApi($to, $subject, $message, $from = 'Page2Leads'){
    /*$boundary = md5(date('r', time()));
    $headers = "From: $from";
    $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"_1_$boundary\"";

            $message = "This is a multi-part message in MIME format.

--_1_$boundary
Content-Type: multipart/alternative; boundary=\"_2_$boundary\"

--_2_$boundary
Content-Type: text/html; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message

--_2_$boundary--";

    mail($to, $subject, $message, $headers);
	return [];*/
}

function sentResetPasswordLink($name,$email,$tempLicense){
    $template = "Hi ".$name.",<br/><br/>";
	$template .= "Here is your new license for Notesilo: ". $tempLicense ."<br/><br/>";
	$template .= "Thanks<br/>Notesilo Team";
	return sendGridApi($email, "Recover Your Notesilo License", $template);
}

function sendResetLinkMail($name,$license,$email){	
	$template = "Hi ".$name.",<br/><br/>";
	$template .= "Here is your License Key : ". $license ."<br/><br/>";
	$template .= "Thanks<br/>Page2Leads Team";
	//sendMailgunApi($user['email'], "Recover Your Page2Leads License", $template);
	sendGridApi($email, "Recover Your Page2Leads License", $template);
	return true;
}

?>