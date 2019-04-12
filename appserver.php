
<?php
//匿名性がポイントー＞ユーザー登録にそこまで意味がないのでは？
//1メッセージ機能
//プールの分離
//twitter連携
//セキュリティ
//array scandir ( string $directory
 //[, int $sorting_order = SCANDIR_SORT_ASCENDING [, resource $context ]] )

if(is_uploaded_file($_FILES["upfile"]["tmp_name"])){
  if(move_uploaded_file($_FILES["upfile"]["tmp_name"],"files/".$_FILES["upfile"]["name"])){
     chmod("files/".$_FILES["upfile"]["name"],0644);
     //echo "go under the pool";
     //urlの相対パス
     $url=$_FILES["upfile"]["name"];
     if(isset($_POST["MessageForImage"])){
     $message=$_POST["MessageForImage"];
   }
     $good=0;
     $bad=0;

    //画像ブロック


    try {

    // 接続
    $pdo = new PDO('sqlite:photopooltest3.db');

    // SQL実行時にもエラーの代わりに例外を投げるように設定
    // (毎回if文を書く必要がなくなる)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // デフォルトのフェッチモードを連想配列形式に設定
    // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // テーブル作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS image(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url VARCHAR(100),
        message VARCHAR(100),
        good INTEGER,
        bad INTEGER

    )");
    //$url.$messageにpostされてきたものを入れる
    // 挿入（プリペアドステートメント）
    $stmt = $pdo->prepare("INSERT INTO image(url, message,good,bad) VALUES (:url, :message,:good,:bad)");
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':good', $good, PDO::PARAM_INT);
    $stmt->bindParam(':bad', $bad, PDO::PARAM_INT);
    $stmt->execute();


    //選択 (プリペアドステートメント)
    $stmt = $pdo->prepare("SELECT * FROM image ");
    $stmt->execute();
    $r = $stmt->fetchAll();//ここで配列にする

    // 結果を確認
    //var_dump($r);

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

     $dir='files/';
     //$files1=scandir($dir,1);//格納画像の配列生成
     $length=count($r);

     $number1=rand(0,$length-1);//ランダムで画像1の番号を取得
     $image_path1=$r[$number1]["url"];//画像1のパス
     $message1=$r[$number1]["message"];


     $number2=rand(0,$length-1);
     $image_path2=$r[$number2]["url"];
     while($image_path1==$image_path2){
       $number2=rand(0,$length-1);
       $image_path2=$r[$number2]["url"];
     }
     $message2=$r[$number2]["message"];


     $number3=rand(0,$length-1);
     $image_path3=$r[$number3]["url"];
     while($image_path3==$image_path2||$image_path3==$image_path1){
     $number3=rand(0,$length-1);
     $image_path3=$r[$number3]["url"];
     }
     $message3=$r[$number3]["message"];


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
    $dirx=realpath("./")."/imagepost1/";
    $dirz=realpath("./")."/imagepost2/";
    $dirv=realpath("./")."/imagepost3/";
    deleteData ($dirx);
    deleteData ($dirz);
    deleteData ($dirv);


      copy(realpath("./").'/files/'.$image_path1,realpath("./")."/imagepost1/".$image_path1);
      copy(realpath("./").'/files/'.$image_path2,realpath("./")."/imagepost2/".$image_path2);
      copy(realpath("./").'/files/'.$image_path3,realpath("./")."/imagepost3/".$image_path3);

      /*header('Content-Type:image/jpeg');
      readfile('files/'.$image_path1);
      readfile('files/'.$image_path2);
      $files2=scandir("tempdir/");
      print_r($files2);*/
     //imagepostは毎回の格納庫
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



  }else{
    echo "cannot throw in the pool";
  }
}else{
    echo "no file";
  }

  ?>



  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>3枚の画像</title>
      <link rel="stylesheet" type="text/css" href="app.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
      <script type="text/javascript">
$(function(){

   $('.letsVote').on('click' , function(){

        var $this = $(this);
        var id = $this.data("id"); //識別用ID（重複NG）
        var numHtml = "." + $this.data("numhtml"); //カウント数を表示するHTML
        var nowCount = Number($(numHtml).html()); //現在のカウント数
        var newCount = nowCount + 1;
        //場合わけが必要になってくる
        var number = $this.data("number");
        console.log(number);
        $.ajax({
             type : "POST",
             url : "vote.php",
             data: {
                  "file_id" : id,
                  "count" : newCount,
                  "imagenumber":number
             }
        }).done(function(data , datatype){
                  //送信先のvote.phpから、Completeが返ってきたらカウント更新
                  if(data == "Complete"){
                       $(numHtml).html(newCount);
                  }else{
                       alert("押しすぎ(´・ω・｀)");
                  }
             }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
                    $("#textStatus").html("textStatus : " + textStatus);
                    $("#errorThrown").html("errorThrown : " + errorThrown.message);
                });
      });

      $('.badVote').on('click',function(){
        var $this=$(this);
        var id=$this.data("id");
        var number=$this.data("number");
        var nowCount=<?php echo $r[$number1]['bad']; ?>;
        var newCount = nowCount + 1;
        console.log(nowCount);
        $.ajax({
          type:"POST",
          url:"badvote.php",
          data:{
            "file_id":id,
            "count" : newCount,
            "imagenumber":number
          }
        }).done(function(data , datatype){
                  //送信先のvote.phpから、Completeが返ってきたらカウント更新
                  if(data == "Complete"){
                  }else{
                       alert("押しすぎ(´・ω・｀)");
                  }
             }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
                    $("#textStatus").html("textStatus : " + textStatus);
                    $("#errorThrown").html("errorThrown : " + errorThrown.message);
      });
    });
  });
  $(document).ready(function(){
       $('.slider').bxSlider();
     });
      </script>
      <style >
        .M1{
          color: red;
        }
      </style>
    </head>
    <body>
      <div class="slider">
        <div><img src=<?php echo "http://133.130.106.75/~w1347573229/06_kadai/files/".$image_path1; ?> alt="" class="photox"></div>
        <div><img src=<?php echo "http://133.130.106.75/~w1347573229/06_kadai/files/".$image_path2; ?> alt="" class="photox"></div>
        <div><img src=<?php echo "http://133.130.106.75/~w1347573229/06_kadai/files/".$image_path3; ?> alt="" class="photox"></div>


      </div>
      <div class="container">
          <div class="photo1">
                <?php
                imageShow(1);
                ?>
          </div>
          <div class="info1">
            <span class="spn">Message</span>
              <span class="mes"><?php echo $message1; ?></span><br>
             <button class="letsVote" data-id="buttonID1" data-numhtml="countNum1" data-number=<?php echo $r[$number1]["id"]; ?> >いいね！</button>
               <span class="countNum1"><?php //データベースから対応いいね数を引っ張ってくる
               echo $r[$number1]["good"];
                 ?></span>
               <span>人がよかったと言っています。</span><br>

             <button class="badVote" data-id="buttonID4"  data-number=<?php echo $r[$number1]["id"]; ?>>悪いね…</button><br>
          </div>
          <div class="photo2">
                 <?php
                imageShow(2);
                  ?>
          </div>
          <div class="info2">
            <span class="spn">Message</span>
                <span class="mes"><?php echo $message2; ?></span><br>
              <button class="letsVote" data-id="buttonID2" data-numhtml="countNum2" data-number=<?php echo $r[$number2]["id"];  ?> >いいね！</button>
               <span class="countNum2"><?php //データベースから対応いいね数を引っ張ってくる
               echo $r[$number2]["good"];
                 ?></span>
               <span>人がよかったと言っています。</span><br>

               <button class="badVote" data-id="buttonID5"  data-number=<?php echo $r[$number2]["id"]; ?>>悪いね…</button><br>
           </div>
           <div class="photo3">
                  <?php
                imageShow(3);
                   ?><br>
           </div>
           <div class="info3">
             <span class="spn">Message</span>
                   <span class="mes"><?php echo $message3; ?></span><br>
                <button class="letsVote" data-id="buttonID3" data-numhtml="countNum3" data-number=<?php echo $r[$number3]["id"]; ?> >いいね！</button>
                <span class="countNum3"><?php //データベースから対応いいね数を引っ張ってくる
                echo $r[$number3]["good"];
                  ?></span>
                <span>人がよかったと言っています。</span><br>

                <button class="badVote" data-id="buttonID6"  data-number=<?php echo $r[$number3]["id"]; ?>>悪いね…</button>
          </div>

   </div>
    </body>
  </html>
