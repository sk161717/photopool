<?php
ini_set('display_errors',1);
//////ajaxデータを受信1
$file_id = $_POST["file_id"]; //ファイル名
$count = $_POST["count"]; //投票数
$number=$_POST["imagenumber"];
$cookieName = "vote_" . $file_id; //クッキー名。
$cookieTime = time() + 10; //クッキーの有効期限（投票を制限する秒数）

///////クッキーが有効
if(isset($_COOKIE[$cookieName])){
     echo "クッキー制御により投票不可です。";

}else{
///////クッキーが無効＝カウントアップ
 $count = $_POST["count"]; //投票数

 //カウント数を書き出すファイル名
 /*$fileName = "log/" . $file_id . ".count";

 $fp = @fopen($fileName , "w"); //書き込みモードで開く

 flock($fp , LOCK_EX); //排他的ロック(書く準備) 他のロックをすべてブロック
 fputs($fp , $count); //カウント数を書き込み
 flock($fp , LOCK_UN); //ロック開放
fclose($fp);*/
try {
 $pdo = new PDO('sqlite:photopooltest3.db');

 // SQL実行時にもエラーの代わりに例外を投げるように設定
 // (毎回if文を書く必要がなくなる)
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 // デフォルトのフェッチモードを連想配列形式に設定
 // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
 $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

 $sql='update image set bad =:bad where id = :value';
 $stmt=$pdo->prepare($sql);
 //$stmt = $pdo->prepare("UPDATE INTO image(good) VALUES (:good)");
 $stmt->bindValue(':bad', $count, PDO::PARAM_INT);
 $stmt->bindValue(':value', $number, PDO::PARAM_INT);
 $stmt->execute();

} catch (Exception $e) {

   echo $e->getMessage() . PHP_EOL;

}




 setcookie($cookieName , $count , $cookieTime); //10秒有効のクッキーをセット

 echo "Complete"; //clickCount.jsにはここの値を返す
 }
?>
