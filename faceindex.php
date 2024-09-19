<?php
// Set verification token and access token << Code by https://shreateh.net/links
$verifyToken = 'hardex';
$accessToken = '1193598685178284';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] === $verifyToken) {
    echo $_GET['hub_challenge'];
    exit;
}

// Handle incoming messages
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$command= $input['entry'][0]['messaging'][0]['postback']['payload'];


if (!empty($command)) {
if($command == 'followus') 
    {
     sendMessage($senderId,'Find all social media accounts here.. https://shreateh.net/links');
    } 
  else if($command == 'ytsubscribe') 
    {
     sendMessage($senderId,'Subscribe to my youtube and share with your friends .. .. https://youtube.com/shreateh-net');
    }   

}
else if (!empty($messageText)) {

  $buttonsText='Please choose an option:';
        $buttons=[
                    [
                        'type' => 'postback',
                        'title' => 'Follow us',
                        'payload' => 'followus',
                    ],
                    [
                        'type' => 'postback',
                        'title' => 'Subscribe',
                        'payload' => 'ytsubscribe',
                    ],
                // Add more buttons if needed
                ];
            sendMessageButtons($senderId, $buttonsText,$buttons);
    
} 


// Do not modify bellow ... 
function sendMessage($recipientId, $messageText)
{
    global $accessToken;
    $data = [
        'recipient' => ['id' => $recipientId],
        'message' => ['text' => $messageText],
    ];
    $options = [
        'http' => [
            'method' => 'POST',
            'content' => json_encode($data),
            'header' => "Content-Type: application/json\n",
        ],
    ];
    $context = stream_context_create($options);
    file_get_contents("https://graph.facebook.com/v12.0/me/messages?access_token=$accessToken", false, $context);
}



// send buttons 
function sendMessageButtons($recipientId, $messageText, $buttons)
{
    global $accessToken;
$message = [
    'attachment' => [
        'type' => 'template',
        'payload' => [
            'template_type' => 'button',
            'text' => $messageText,
            'buttons' => $buttons,
        ],
    ],
];

$data = [
    'recipient' => [
        'id' => $recipientId,
    ],
    'message' => $message,
];

$json_data = json_encode($data);

$url = 'https://graph.facebook.com/v14.0/me/messages?access_token=' . $accessToken;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

curl_close($ch);

}

// Copyright::  Code by https://shreateh.net/links
?>