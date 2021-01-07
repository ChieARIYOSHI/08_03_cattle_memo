<?php
// var_dump($_POST);
// exit();

// 受け取ったデータを変数に入れる
$id = $_POST['id'];
$current_weight = $_POST['current_weight'];

include('functions.php');
$pdo = connect_to_db();

// 入力したデータをエクセルファイルに保存する
// $json_data = array();
// /* json 保存先ファイル */
// $file = "data.xlsx";
// if(is_file($file) === false) {
//   /* 無ければ新規作成 */
//   touch($file);
//   chmod($file, 0777);
// } else {
//   /* ある時は保存データを取得 */
//   $json_data = json_decode(file_get_contents($file), true);
// }
// $json_data[] = array(
//   "id" => $_POST["id"], 
//   "current_weight" => $_POST["current_weight"], 
//   "updated_date" => $_POST["updated_date"]
// );
// /* 書き込み */
// file_put_contents($file, json_encode($json_data));

// データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'UPDATE cattle_memo SET current_weight=:current_weight, updated_date=sysdate() WHERE id=:id';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':current_weight', $current_weight, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:edit.php?id=$id");
  exit();
}