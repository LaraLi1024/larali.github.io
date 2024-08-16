<?php
$username = $_POST['username'] ?? '無名';
$comment = $_POST['comment'] ?? '';

date_default_timezone_set('Asia/Taipei');
$now = date('Y-m-d H:i:s', time());


$data = <<< HEREDOC
時間：{$now}
姓名：{$username}
內容：{$comment}
-------------------------------

HEREDOC;


// 存檔 (原先的)
/*
$filename = 'data/userdata.txt';
file_put_contents($filename, $data, FILE_APPEND);
*/

// 存檔
$filename = 'data/userdata_' . date('Ymd', time()) . '.txt';

if(!file_exists($filename)) {
    file_put_contents($filename, '');
}

$old = file_get_contents($filename);
$new = $data . $old;
file_put_contents($filename, $new);  //


// email 通知
/*
$content = 'Hello 這是PHP寄信測試，<br>HTML格式呢？';
$content .= $data;


$to = 'lara.li@vteamsysteam.com.tw';
$title = 'Hello mail test';
@mail($to, $title, $content);

echo mail($to, $title, $content);
*/

// LINE 通知
// Line Notify: PHP_LINE
$token = 'u9DoB0aglWCnO3jHLL4iHkHw6UIH8OCAAgRCLOxEj8H';  // 更換自己的 token

$url = "https://notify-api.line.me/api/notify";

$headers = array(
   'Content-Type: multipart/form-data',
   'Authorization: Bearer ' . $token);

$message = array('message' => $data);


/*$message = array(
    'message' => $data,
    'imageFile' => curl_file_create('C:\\xampp\htdocs\myweb\line\images\dog.png'),
    'stickerPackageId' => 446,
    'stickerId' => 1988
    );
*/


$ch = curl_init();
curl_setopt($ch , CURLOPT_URL , $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
$result = curl_exec($ch);
curl_close($ch);


$html = <<< HEREDOC
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>已收到留言</p>
</body>
</html>
HEREDOC;

echo $html;
?>