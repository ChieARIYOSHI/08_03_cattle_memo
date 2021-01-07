<?php

function connect_to_db() {
  $dbn = 'mysql:dbname=gsacf_d07_03;charset=utf8;port=3306;host=localhost';
  $user = 'root';
  $pwd = '';
  
  try {
    // ここでDB接続処理を実行する
    return new PDO($dbn, $user, $pwd);
  } catch (PDOException $e) {
    // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }
}
