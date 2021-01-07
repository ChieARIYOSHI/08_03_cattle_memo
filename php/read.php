<?php

// DB接続情報
// $dbn = 'mysql:dbname=gsacf_d07_03;charset=utf8;port=3306;host=localhost';
// $user = 'root';
// $pwd = '';

// // DB接続
// try {
//   $pdo = new PDO($dbn, $user, $pwd);
// } catch (PDOException $e) {
//   echo json_encode(["db error" => "{$e->getMessage()}"]);
//   exit();
// }

include('functions.php');
$pdo = connect_to_db();

$sql = 'SELECT * FROM cattle_memo';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
// $img = $stmt->fetchObject();

if ($status==false) {
  $error = $stmt->errorInfo(); 
  exit('sqlError:'.$error[2]); // 失敗時􏰁エラー出力
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $output = "";
  foreach ($result as $record) {
    //誕生日から月齢を算出
    //誕生日の年月日をそれぞれ取得
    $birth_year = (int)date("Y", strtotime($record["birthday"]));
    $birth_month = (int)date("m", strtotime($record["birthday"]));
    $birth_day = (int)date("d", strtotime($record["birthday"]));
    //現在の年月日を取得
    $now_year = (int)date("Y");
    $now_month = (int)date("m");
    $now_day = (int)date("d");
    //月齢を計算
    $age = ($now_year - $birth_year)*12 + ($now_month - $birth_month);
    //「日」で月齢を微調整
    if($now_day < $birth_day) {
      $age--;
    }
    // var_dump($age);
    // exit();

    $output .= "<a class='cattle_info' href='edit.php?id={$record["id"]}'>
    <div class='image' id='{$record['id']}'>
    <img src='image.php?id={$record['id']}' width='auto' height='100px'>
    </div>
    <div class='text'>
    <li>名前　　{$record["cattle_name"]}</li><li>月齢　　{$age} ヶ月</li><li>性別　　{$record["gender"]}</li><li>特長　　{$record["feacher"]}</li><br>
    </div>
    </a>";
    // <li>誕生日　{$record["birthday"]}</li>
  } 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/read.css">
  <link rel="stylesheet" href="../css/hamburger.css">
  <link rel="icon" href="../img/icon.ico">
  <title>牛さんメモ</title>
</head>

<body>
  <header>
    <!-- ハンバーガーメニューの表記内容です -->
    <nav class="navi_menu">
      <ul class="menu_items">
        <li class="menu_content"><a href="index.html">トップページ</a></li>
        <li class="menu_content"><a href="https://localhost/08_03_cattle_memo/php/input.php">新しい牛さんの登録</a></li>
        <li class="menu_content"><a href="https://localhost/08_03_cattle_memo/php/read.php">牛さんリスト一覧</a></li>
        <li class="menu_content"><a href="">マップ</a></li>
        <!-- <li class="menu_content"><a href="add.new.html">レシピ登録</a></li> -->
      </ul>
      </nav>
      <!-- アイコン --> 
      <div class="navi_icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
  </header>

  <main class="list_main">
    <div class="title">
      <img class="icon" src="../img/icon.png" alt="牛さんのアイコン">
      <h1>牛さんリスト</h1>
    </div>
  
    <fieldset>
      <ul id="output">
        <?= $output ?>
      </ul>
    </fieldset>
  
    <a class="submit_btn" href="input.php">新しい牛さんを登録</a>
  </main>

  <!-- ハンバーガーメニューに関するjs -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/hamburger.js"></script>
</body>

</html>