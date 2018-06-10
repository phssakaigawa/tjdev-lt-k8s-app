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
	};
	
	
?>

<input id="fileupload" type="file" name="files[]" data-url="./" multiple>
<span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
<br>
<br>
<!-- The global progress bar -->
<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success"></div>
</div>
<?php

	var_dump($_FILES);
    //一字ファイルができているか（アップロードされているか）チェック
    if(is_uploaded_file($_FILES['up_file']['tmp_name'])){

        //一字ファイルを保存ファイルにコピーできたか
        if(move_uploaded_file($_FILES['up_file']['tmp_name'],"./".$_FILES['up_file']['name'])){

            //正常
            echo "uploaded";

        }else{

            //コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
            echo "error while saving.";
        }

    }else{

        //そもそもファイルが来ていない。
        echo "file not uploaded.";

    }
    ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>
<link rel="stylesheet" href="css/jquery.fileupload.css" />
<script>
    $(function () {
        'use strict';

        var url = "./";
        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<p/>').text(file.name).appendTo('#files');
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                );
            },
            add: function(e, data){
               var uploadErrors = [];
               var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i; //content type
               if(data.files[0]['type'].length && !acceptFileTypes.test(data.files[0]['type'])) {
                   uploadErrors.push('Not an accepted file type');
               }
               if(data.files[0]['size'].length && data.files[0]['size'] > 5000000) {
                   uploadErrors.push('Filesize is too big');
               }
               if(uploadErrors.length > 0) {
                   alert(uploadErrors.join("\n"));
               } else {
                   data.submit();
               }
           },
        
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
</script>
</body> 
</html>