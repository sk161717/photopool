<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" >
<link rel="stylesheet" href="app.css" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<!-- CDN -->
<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropper/1.0.0/cropper.min.css" rel="stylesheet" type="text/css" media="all"/>

<!-- JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/1.0.0/cropper.min.js"></script>
<script>
$(document).ready(function(){
     $('.slider').bxSlider();
   });
//window.onload{function(){
/*$(function(){
$('.hoge').fadeOut();
});*/
//}}
</script>
<title>photopool</title>
</head>
<body>
  <header id="top">
    <div class="menu">
      <a href="http://133.130.106.75/~w1347573229/06_kadai/app.php">   <img src="http://133.130.106.75/~w1347573229/06_kadai/photos/rogo.png" alt="" width="15%" height="100%" class="icon" ></a>
      <a  href="http://133.130.106.75/~w1347573229/06_kadai/rank.php"    class="rank" >ranking</a>
      <a  href="http://133.130.106.75/~w1347573229/06_kadai/explain.php"     class="info"> information </a>
    </div>
  </header>
  <div class="slider">
    <div><img src="http://133.130.106.75/~w1347573229/06_kadai/slidephoto/moru.jpg" alt=""></div>
    <div><img src="http://133.130.106.75/~w1347573229/06_kadai/slidephoto/girl.jpeg" alt=""></div>
    <div><img src="http://133.130.106.75/~w1347573229/06_kadai/slidephoto/star.jpeg" alt=""></div>
    <div><img src="http://133.130.106.75/~w1347573229/06_kadai/slidephoto/foreigner.jpeg" alt=""></div>
    <div><img src="http://133.130.106.75/~w1347573229/06_kadai/slidephoto/monkey.jpg" alt=""></div>

  </div>
  <p class="share">share</p>
  <form  action="appserver.php" method="post" enctype="multipart/form-data">
  <input type="file" name="upfile" id="file"  style="display:none;" onchange="$('#fake_input_file').val($(this).val())"><input type="button" value="Photo" class="btn" onClick="$('#file').click();">
  <input id="fake_input_file" readonly type="text" class="photocontent "value=""  ><br>

  <div  class="btn" >Message</div>
  <input type="text" name="MessageForImage" value="" class="box"><br>
  <input type="submit" name="" value="Throw In The Pool" class="button">
  </form>
  <?php
  try {

  // 接続
  $pdo = new PDO('sqlite:photopooltest3.db');

  // SQL実行時にもエラーの代わりに例外を投げるように設定
  // (毎回if文を書く必要がなくなる)
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // デフォルトのフェッチモードを連想配列形式に設定
  // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  $stmt = $pdo->prepare("SELECT * FROM image ");
  $stmt->execute();
  $rx = $stmt->fetchAll();//ここで配列にする

  foreach ((array) $rx as $key => $value) {
    $sort[$key] = $value['bad'];
  }

  array_multisort($sort, SORT_DESC, $rx);

  $length=count($rx);
  //echo $length;

  for($i=0;$i<$length;$i++){
    $badNumber=$rx[$i]['bad'];
    ///echo $badNumber;
    if($badNumber>=2){
      // DELETE文を変数に格納
      $id=$rx[$i]['id'];
      echo $id;
  $sql = "DELETE FROM image WHERE id = :id";

  // 削除するレコードのIDは空のまま、SQL実行の準備をする
  $stmt = $pdo->prepare($sql);
  // 削除するレコードのIDを配列に格納する
  $params = array(':id'=>$id);

  // 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
  $stmt->execute($params);
     }
  }

  
  } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
  }
?>


</body>
</html>
