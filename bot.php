<?php
function textreply($text,$replyToken){

    
            $textreply ="";
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_URL, 'https://igad.kku.ac.th/vehicleService/web/index.php?r=mobile/humanmsg&humanmsg='.$text.'&replytoken='.$replyToken);
            $result1 = curl_exec($ch1);
            curl_close($ch1);
            
            $obj = json_decode($result1, true);
            foreach ($obj as $key => $value) {
                $textreply = $textreply.$value.".";
            }
            return $textreply;
    

    
}


function sender($post,$access_token){
	$url = 'https://api.line.me/v2/bot/message/reply';
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			return $result . "\r\n";
}

$access_token = 'lQ1tyURqNWThAgW7FCgu2+guaTBDWJYCApuK6j3r0nyQH3d3teyfj6J/sxRPne3MVUknjIZe6yYCP13BmL04WJrNH3JKdIw0T8GnDDhFSEG+jCw51EowqrNkqjx/o9Qe22Bs2nHhdxFsYQEzzU9jfAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// $textexplode = explode(':',$text);
		$messagereply = textreply($text,$replyToken);
			// Build message to reply back
			$messages = [
				'type' => 'text',
				//'text'=>'test',
				'text' => $messagereply,
			];

			// Make a POST Request to Messaging API to reply to sender
			
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			
			
			$post = json_encode($data);
			echo  sender($post,$access_token);
		}
	}
}


