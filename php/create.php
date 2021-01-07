<?php
// var_dump($_POST);
// var_dump($_FILES);
// exit();

if (
    !isset($_POST['cattle_name']) || $_POST['cattle_name']=='' || !isset($_POST['id_number']) || $_POST['id_number']=='' || !isset($_POST['birthday']) || $_POST['birthday']=='' || !isset($_POST['gender']) || $_POST['gender']=='' || !isset($_FILES['img']['name']) || $_FILES['img']['name']=='' || !isset($_FILES['img']['type']) || $_FILES['img']['type']=='' || !isset($_FILES['img']['tmp_name']) || $_FILES['img']['tmp_name']=='' || !isset($_FILES['img']['size']) || $_FILES['img']['size']=='' || !isset($_POST['feacher']) || $_POST['feacher']=='') {
      exit('ParamError'); 
    }
      
      $cattle_name = $_POST['cattle_name'];
      $id_number = $_POST['id_number'];
      $birthday = $_POST['birthday'];
      $gender = $_POST['gender'];
      // $img = $_POST['img'];
      // 画像データの取得
      $img_name = $_FILES['img']['name'];
      $img_type = $_FILES['img']['type'];
      $img_content = file_get_contents($_FILES['img']['tmp_name']);
      $img_size = $_FILES['img']['size'];
      $feacher = $_POST['feacher'];

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

      //SQL作成&実行
      $sql = 'INSERT INTO cattle_memo(id, cattle_name, id_number, birthday, gender, img_name, img_type, img_content, img_size, feacher) VALUES(NULL, :cattle_name, :id_number, :birthday, :gender, :img_name, :img_type, :img_content, :img_size, :feacher)';
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':cattle_name', $cattle_name, PDO::PARAM_STR);
      $stmt->bindValue(':id_number', $id_number, PDO::PARAM_STR);
      $stmt->bindValue(':birthday', $birthday, PDO::PARAM_STR);
      $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindValue(':img_name', $img_name, PDO::PARAM_STR);
      $stmt->bindValue(':img_type', $img_type, PDO::PARAM_STR);
      $stmt->bindValue(':img_content', $img_content, PDO::PARAM_STR);
      $stmt->bindValue(':img_size', $img_size, PDO::PARAM_INT);
      $stmt->bindValue(':feacher', $feacher, PDO::PARAM_STR);
      // var_dump($stmt);
      // exit();
      $status = $stmt->execute(); // SQLを実行

      if ($status == false) {
        $error = $stmt->errorInfo();
        exit('sqlError:' . $error[2]);
      } else {
        header('Location:read.php');
      }
      
      ?>