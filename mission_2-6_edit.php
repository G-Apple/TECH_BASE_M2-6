<!DOCTYPE HTML>
<html>

<head>
<meta charset="UTF-8">
<title>好きなゲームタイトルなど</title>
</head>

<?php
$edit_num = $_POST["name4"];//ここでの$_POST["name4"]は2-5.phpのフォームから受け取った編集番号

		$filename = 'M2_data.txt';
		$fp = fopen($filename, 'r');//読み込み用でオープンする。
		//変数の定義

		$hai = file($filename);//ファイルの中身を1行毎に配列$haiに代入。
		$pieces = explode("[%@`F]",$hai[$edit_num-1]);

		$user_name =$pieces[1];
		$comment = $pieces[2];



?>



<body>
<h1>好きなゲームタイトルなど</h1>
===============↓編集用フォーム↓=============<br>
<?php echo $edit_num; ?>を編集中<br>
<form action="mission_2-6.php" method="POST">
<input type="hidden" name="name5" value="<?php echo $edit_num; ?>"><br>
投稿者名<input type="text" name="name6" value="<?php echo $user_name;?>" size="20">
コメント<input type="text" name="name7" value="<?php echo $comment ;?>" size="50">
パスワード<input type="text" name="name8" size="50">
<input type="submit" value="編集実行">
</form>
=========================================<br>
<br>
<br>
</body>
</html>