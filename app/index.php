<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>thinkjapan Developer day LT demo</title>
</head>
<body>
<h1>Think Japan Developer Day / Developer Night </h1>
<h2>use case IBM Cloud Kubernetes Service + DevOps DEMO</h2>
<hr>
<br>
<?php
	$watson_json_env = getenv('WATSON_VR');
	$watson_json_decoded = json_decode($watson_json_env,true);
	if($watson_json_env==false){
		echo("ERROR: SERVICE BINDが定義されていません。Watson Visual Recognitionへ接続できません。");
		exit();
	};
	
	
?>

<?php
$msg = null;

if(isset($_FILES['upfile']) && is_uploaded_file($_FILES['upfile']['tmp_name'])){
	if(!file_exists('upload')){
		mkdir('upload');
		chmod('upload',0777);
	}
	$new_name = date("YmdHis");
	$a = 'upload/'.basename($_FILES['upfile']['name'].$new_name);
	
	if(move_uploaded_file($_FILES['upfile']['tmp_name'], $a)){
		$msg = $a. "のアップロードに成功しました";
	}else{
		$msg = "アップロードに失敗しました。";
	}
}
?>
<h3>Watson Visual Recognitionで画像分析</h3>
<form action="./" method="post" enctype="multipart/form-data">
<input type="file" name="upfile">
<input type="submit" value="送信" name="upload">
</form>

<?php
if($_FILES['upfile']['tmp_name']):
?>
判定結果：
<hr>
<img src="<?php echo($a); ?>"/>
<?php
    $watson_json_env = getenv('WATSON_VR');
    $watson_json_decoded = json_decode($watson_json_env,true);
    $url=$watson_json_decoded["url"]."/v3/classify?api_key=".$watson_json_decoded["api_key"]."&version=2016-05-17";
    $curl = curl_init();
   
    $jpg = $a;
 
    $data = array("images_file" => new CURLFile($jpg,mime_content_type($jpg),basename($jpg)));
    curl_setopt($curl,CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    
    $response=curl_exec($curl);
    $info=curl_getinfo($curl);
    curl_close($curl);
    $vr_array=json_decode($response,true);
if($response):
?>
<table border=1>
<?php
foreach ($vr_array['images'][0]['classifiers'][0]['classes'] as $record) {
  foreach ($record as $key => $value) {
    print "<tr><td>$key</td><td>$value</td></tr>";
  }
  print "<tr><td colspan=\"2\">&nbsp;</td></tr>";
}
endif;
?>
</table>
<?php
endif;
?>
</body> 
</html>