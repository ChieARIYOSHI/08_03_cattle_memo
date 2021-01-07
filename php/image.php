<?php

// require_once 'functions.php';

// DB接続情報//作成したデータベース名を指定
$dbn = 'mysql:dbname=gsacf_d07_03;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

$sql = 'SELECT * FROM cattle_memo WHERE id=:id LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
$stmt->execute();
$image = $stmt->fetch();

header('Content-type: ' . $image['img_type']);
echo $image['img_content'];

unset($pdo);
exit();