<?php
$access_token = 'q91Y9dZHe1t/z+l21rO0V0sca7frsOukY8PfEPdKOFlu9c9c0IJs2BF6KN8Yh1LmsJHBBIcjSiXhTKxH8eVoQ3dxpC0CdK4tkNfSwHIbAmrqheQGX92ZE88eN+PBDO55EHTxsKxaiIGkUMUFJ7SinAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		//if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			if ($event['message']['type'] == 'text') {
			     $text = $event['message']['text'];
			}
			else {
			     $text = "ได้รับข้อมูลเรียบร้อยแล้ว";
			}
						
			// connect to 142 for get reply message
			$ch = curl_init("http://app3.shinee.com:85/Line/gate_api.php");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($event));
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			//$text = $result;
			
			// end connect
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		//}
	}
}
echo "OK";
