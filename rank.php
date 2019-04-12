<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ランキング</title>
    <link rel="stylesheet" href="app.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
  </head>
  <body>
    <header id="top">
      <div class="menu">
        <a href="http://133.130.106.75/~w1347573229/06_kadai/app.php">   <img src="http://133.130.106.75/~w1347573229/06_kadai/photos/rogo.png" alt="" width="15%" height="100%" class="icon" ></a>
        <a  href="http://133.130.106.75/~w1347573229/06_kadai/rank.php"    class="rank" >ranking</a>
        <a  href="http://133.130.106.75/~w1347573229/06_kadai/explain.php"     class="info"> information </a>
      </div>
    </header>
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
      $sort[$key] = $value['good'];
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

    /*foreach ((array) $rx as $key => $value) {
      $sort[$key] = $value['good'];
    }

    array_multisort($sort, SORT_DESC, $rx);*/




    //print_r($rx);
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

    $image_pathn1=$rx[0]["url"];
    $image_pathn2=$rx[1]["url"];
    $image_pathn3=$rx[2]["url"];
    $messagen1=$rx[0]["message"];
    $messagen2=$rx[1]["message"];
    $messagen3=$rx[2]["message"];

    function deleteData ( $diry ) {
   if ( $dirHandle = opendir ( $diry )) {
       while ( false !== ( $fileName = readdir ( $dirHandle ) ) ) {
           if ( $fileName != "." && $fileName != ".." ) {
               unlink ( $diry.$fileName );
           }
       }
       closedir ( $dirHandle );
     }
   }

   $dirn1=realpath("./")."/imagepostn1/";
   $dirn2=realpath("./")."/imagepostn2/";
   $dirn3=realpath("./")."/imagepostn3/";
   deleteData ($dirn1);
   deleteData ($dirn2);
   deleteData ($dirn3);
   copy(realpath("./").'/files/'.$image_pathn1,realpath("./")."/imagepostn1/".$image_pathn1);
   copy(realpath("./").'/files/'.$image_pathn2,realpath("./")."/imagepostn2/".$image_pathn2);
   copy(realpath("./").'/files/'.$image_pathn3,realpath("./")."/imagepostn3/".$image_pathn3);

    function imageShow($dirNumber){
       $dir_path ='imagepost'.$dirNumber.'/';
       if (is_dir($dir_path))
       {
       if(is_readable($dir_path))
       { // ? ファイルが読み込み可能かどうか
       $ch_dir = dir($dir_path); //ディレクトリクラス
       //ディレクトリ内の画像を一覧表示
       while (false !== ($file_name = $ch_dir -> read()))
       {
       $ln_path = $ch_dir -> path . "/" .$file_name;
       if (@getimagesize($ln_path))
       { //画像かどうか？
       echo "<a href = \"imgview.php?d=" .urlencode(mb_convert_encoding($ln_path, "UTF-8")). "\" target = \"_blank\" >";
       echo "<img src = \"" .$ln_path. "\" width=\"500\" ></a> ";
       }
       }
       $ch_dir -> close();
       }
       else
       {
       echo "<p>" .htmlspecialchars($dir_path)."　は読み込みが許可されていません。";
       }
       }
       else
       {
       echo 'DIR 画像がないよ';
     }
    }
     ?>
    <h2>ランキング</h2>
    <div class="container">
          <div class="photo1">
          <?php
          imageShow(n1);
          ?>
    </div>
    <div class="info1">
        <h3>1位</h3>
        <span class="spn">Message</span>
      <?php echo $messagen1; ?>
    </div>

    <div class="photo2">
          <?php
          imageShow(n2);
          ?>
    </div>
    <div class="info2">
          <h3>2位</h3>
          <span class="spn">Message</span>
          <?php echo $messagen2;?>
    </div>
    <div class="photo3">
          <?php
          imageShow(n3);
          ?>
    </div>
    <div class="info3">
          <h3>3位</h3>
          <span class="spn">Message</span>
          <?php echo $messagen3; ?>
    </div>
  </div>
  </body>
</html>
