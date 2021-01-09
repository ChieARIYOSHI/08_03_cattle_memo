<?php
// var_dump($_GET);
// exit();

// 送信されたidをgetで受け取る
$id = $_GET['id'];
// DB接続&id名でテーブルから検索
include('functions.php');
$pdo = connect_to_db();

$sql = 'SELECT * FROM cattle_memo WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();
// $img = $stmt->fetchObject();

if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
}
  // var_dump($record);
  // exit();

//誕生日から月齢を算出
//誕生日の年月日をそれぞれ取得
$birth_year = (int)date("Y", strtotime($record["birthday"]));
$birth_month = (int)date("m", strtotime($record["birthday"]));
$birth_day = (int)date("d", strtotime($record["birthday"]));
//現在の年月日を取得
$now_date = (int)date("Ymd");
$now_year = (int)date("Y");
$now_month = (int)date("m");
$now_day = (int)date("d");
//月齢を計算
$age = ($now_year - $birth_year)*12 + ($now_month - $birth_month);
//「日」で月齢を微調整
if($now_day < $birth_day) {
  $age--;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <link rel="stylesheet" href="../css/edit.css">
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

  <div class="cattle_info">
    <div class="image" id="<?= "{$record['id']}" ?>">
      <img src="<?= "image.php?id={$record['id']}" ?>" width="auto" height="200px">
    </div>
    <div class="cattle_name"><?= $record["cattle_name"] ?> / <?= $record["gender"] ?></div>
    <div class="age"><?= $record["birthday"] ?> 生まれ （<?= $age ?>ヶ月）</div>
  </div>

  <form action="update.php" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>今日の体重を入力</legend>
      <div>
        <input id="current_weight" type="text" name="current_weight" value=""> kg
      </div>
      <!-- idを見えないように送る -->
      <!-- input type="hidden"を使用する! -->
      <!-- form内に以下を追加 -->
      <div>
        <input id="id" type="hidden" name="id" value="<?=$record['id']?>">
        <input id="updated_date" type="hidden" name="updated_date" value="<?= $now_date ?>">
        <input id="age" type="hidden" name="age" value="<?= $age ?>">
      </div>
      <div class="button">
        <button class="submit_btn">送　信</button>
      </div>
    </fieldset>

    <div class="ct-chart"></div>

  </form>

  <!-- ここからjs。入力した体重のデータをlocal storageに保存するように書きました（第3回の授業の復習も兼ねてやってみた） -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    $(function () {
      //1.Save クリックイベント
      $('.submit_btn').on('click', function() {
        const data = {
          id: $('#id').val(),
          current_weight: $('#current_weight').val(),
          updated_date: $('#updated_date').val(),
          age: $('#age').val(),
        };
        const jsonData = JSON.stringify(data);
        localStorage.setItem('<?= $record['id'] ?>_<?= $age ?>', jsonData);
        
        // console.log(data);
        // console.log(jsonData);
      });

      //2.ページ読み込み：保存データ取得表示
      if(localStorage.getItem('<?= $record['id'] ?>_<?= $age ?>')) {
        const jsonData = localStorage.getItem('<?= $record['id'] ?>_<?= $age ?>');
        const data = JSON.parse(jsonData);
        $('#id').val(data.id);
        $('#current_weight').val(data.current_weight);
        $('#text_area').val(data.updated_date);
        $('#age').val(data.age);
      };

      // 3.保存したデータを取得してグラフに表記
      const info = {};
      for(let key in localStorage) {
        info[key] = localStorage.getItem(key);
      }
      // console.log(info);



      // const value = localStorage.where('id', '=', '24').getItem('24_9');
      // console.log(value);

      // $('.submit_btn').on('click', function() {
      //   const value = localStorage.getItem(selectedItems)
      //   const selectedItems = [];
      //   localStorage.where('id', '=', '24').getItem(function() {

      //   })
      // })

      const data = {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        series: [
          [15, 25, 35, 45, 55, 60, 65, 70, 75],
          [15, 22, 29, 36, 43, 50, 57, 65, 72],
          // [2, 1, 3, 4, 2, 5]
        ]
      };

      const options = {
        height: 300
      };
      new Chartist.Line('.ct-chart', data, options);

      // localStorage.where('id', '=', '').orderBy('age', 'asc').getItem(function (value) {
      //   const foodList = [];
      //   querySnapshot.docs.forEach(function(value) {
      //     const data = {
      //       labels: [age],
      //       series: [current_weight],
      //     }
      //     console.log(data);
      //   });
      // });
    });
  
  </script>

  <!-- ハンバーガーメニューに関するjs -->
  <script type="text/javascript" src="../js/hamburger.js"></script>

</body>
</html>