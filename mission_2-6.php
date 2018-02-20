<!DOCTYPE HTML>
<html>

<head>
<meta charset="UTF-8">
<title>好きなゲームタイトルなど</title>
</head>

<body>
<h1>好きなゲームタイトルなど</h1>
<form action="mission_2-6.php" method="POST">
投稿者名<input type="text" name="name1" size="20"><br>
Password<input type="text" name="name9" size="10"><br>
コメント<input type="text" name="name2" size="50"><br>
<input type="submit" value="発言">
</form>
<br>
<form action="mission_2-6.php" method="POST">
削除対象番号<input type="text" name="name3" size="5"><br>
Password<input type="text" name="name10" size="10"><br>
<input type="submit" value="削除実行">
</form>
<br>
<form action="mission_2-6_edit.php" method="POST">
編集対象番号<input type="text" name="name4" size="5"><br>
<input type="submit" value="編集開始">
<br>
<br>
<?php
//入力フォームの文字列をテキストに保存するプログラム
	if(!empty($_POST["name1"])){//if(empty)は受信したデータが空欄のとき、(!empty)なので空欄でないとき
		$filename = 'M2_data.txt';
		$fp = fopen($filename, 'a+');//書き出しと読み込み用でオープンする。ファイルポインタはファイルの終端。常に追記
		//変数の定義

		$hai = file($filename);//ファイルの中身を1行毎に配列$haiに代入。
		$id = count($hai) + 1;//$haiの配列の数を数える。初期値が0になっているので+1。
		$user_name = $_POST["name1"];//formから送られてきた投稿者名"name1"
		$comment = $_POST["name2"];//formから送られてきたコメント"name2"
		$time = date("Y/m/d H:i:s");//formから送られてきたときの投稿日時
		$v_type = 0;//0が通常1が編集済み2が削除済み、編集時などの条件付けに使用（削除済みは変更不可など）
		$pass = $_POST["name9"];//フォームで入力されたパスワード
		fwrite($fp, $id ."[%@`F]". $user_name ."[%@`F]". $comment ."[%@`F]". $time ."[%@`F]".$v_type."[%@`F]".$pass."[%@`F]". "\n");//番号・名前・コメント・投稿時間・（改行）
		$datalog = fgets($fp);
		fclose($fp);
	}


//削除機能ver5
	$filename = 'M2_data.txt';
	if(!empty($_POST["name3"])){//削除実行されてname3がフォームから送信されたとき
		$fp = fopen($filename, 'r+');//読み込みと書き出し用でオープンする。ファイルポインタはファイルの先頭。
		$hai = file($filename);

		$fp2 = fopen($filename, 'w');//読み込みと書き出し用でオープンする。ファイルポインタはファイルの先頭。
		$delete_num = $_POST["name3"];//変数定義
		$i = 0;
		foreach ($hai as  $hai_num => $hai_hyou){
			$pieces = explode("[%@`F]",$hai_hyou);
			if($delete_num == $pieces[0]){//ファイル1行を分割して投稿番号のみ抽出、それを削除指定番号と比較
				if($_POST["name10"]==$pieces[5]){//$pieces[5]の中身は削除指定番号の「パスワード」データ
				fwrite($fp2,$pieces[0] . "[%@`F]※削除済み※[%@`F]※コメントは削除されました※[%@`F]".$pieces[3]."[%@`F]"."2"."[%@`F]".$pass."[%@`F]"."\n");//削除する項の処理
				}else{
					echo "パスワードが間違っています。正しい手順で再度編集を行ってください。";
					fwrite($fp2,$hai[$i]);//削除しない項は元々と同じデータを上書き
			}
			}else{
				fwrite($fp2,$hai[$i]);//削除しない項は元々と同じデータを上書き
			}
			$i = $i + 1;
		}
		fclose($fp);
		fclose($fp2);
	}
	/*
	工夫点：一度目のfopen"r+"　これでファイルの中身を配列に代入している。
	二度目のfopen"w"　これによってファイルを一度全消去している。元ファイルに直接の上書きではないので「削除プログラム失敗ver3」と違い、ゴミデータが残らないはず。
	最後に参照に使った$haiを含めた$fpをfclose。その後書き込みに使った$fp2もfclose。
	問題点：foreachで$hai_numを使っているが前のプログラミングの残りなので消しても良い。
	*/


//編集機能
	$filename = 'M2_data.txt';
	if(!empty($_POST["name6"])){//編集実行されてname6がフォームから送信されたとき
		$fp = fopen($filename, 'r+');//読み込みと書き出し用でオープンする。ファイルポインタはファイルの先頭。
		$hai = file($filename);
		$fp2 = fopen($filename, 'w');//読み込みと書き出し用でオープンする。ファイルポインタはファイルの先頭。なおこの処理によってファイルが全削除される
		$edit_num = $_POST["name5"];//変数定義
		$i = 0;
		$e_user_name = $_POST["name6"];//formから送られてきた編集用投稿者名"name6"
		$e_comment = $_POST["name7"];//formから送られてきたコ編集用メント"name7"
		$e_time = date("Y/m/d H:i:s");//formから送られてきたときの投稿日時
		foreach ($hai as  $hai_num => $hai_hyou){
			$pieces = explode("[%@`F]",$hai_hyou);
			if($edit_num == $pieces[0]){//ファイル1行を分割して投稿番号のみ抽出、それを編集指定番号と比較
				if($pieces[4]==2){//データの種類の読み取り、0通常1編集済2削除済、削除済みなら編集不可
					echo "削除済みのデータのため編集はできません。";
					fwrite($fp2,$hai[$i]);//削除しない項は元々と同じデータを上書き
				}else if($_POST["name8"]==$pieces[5]){//$pieces[5]の中身は編集指定番号の「パスワード」データ
					fwrite($fp2,$pieces[0] . "[%@`F]". $e_user_name ."[%@`F]". $e_comment ."[%@`F]". $e_time ."[%@`F]"."1"."[%@`F]".$pieces[5]."[%@`F]". "\n");//削除する項の処理
				}else{
					echo "パスワードが間違っています。正しい手順で再度編集を行ってください。";
					fwrite($fp2,$hai[$i]);//削除しない項は元々と同じデータを上書き
				}
			}else{
				fwrite($fp2,$hai[$i]);//削除しない項は元々と同じデータを上書き
			}
			$i = $i + 1;
		}
		fclose($fp2);
		fclose($fp);
	}



?>

<br>
<br>
<br>
=============================================<br>
<?php
//ファイルを1行ずつ配列に代入して、その配列を細かい配列に分割して、順番に表示するプログラム
	if(file_exists($filename)){//ファイルが存在するか確認。存在しないのに表示させるプログラムを動作させるとエラーが起きるため。（具体的なエラーは$piecesがないので「|」だけたくさん並ぶ）
		$filename = 'M2_data.txt';
		$fp = fopen($filename, 'r');
		$hai = file($filename);//file(A) でAを配列として全て読み込む
		//echo "#".$hai_num ."_". $hai_hyou."<br>";//添字が使える
		foreach ($hai as  $hai_num=> $hai_hyou){//foreach (A as B => C)とすると、配列Aの要素をCに代入し、添字（番号を）Bに代入する。
			$pieces = explode("[%@`F]",$hai_hyou);
				echo $pieces[0]."|";//配列$haiを分割した配列の0要素目（番号）
				echo $pieces[1]."|";//配列$haiを分割した配列の1要素目（投稿者名）
				echo $pieces[2]."|";//配列$haiを分割した配列の2要素目（コメント）
				echo $pieces[3]."|";//配列$haiを分割した配列の3要素目（投稿時間）
				echo "<br>";
		}
		fclose($fp);
}
?>
=============================================<br>
</body>
</html>