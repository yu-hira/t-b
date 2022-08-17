<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
<body>

  
  <?php
  $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //クリエイト文：テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS KEIjibAN"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    ."date TEXT, "
    . "pass char(32) "
    .");";
    $stmt = $pdo->query($sql);
  
  $name2=NULL;
  $comment2=NULL; 
  $editnumber=NULL;
  $pass2=NULL;
  if( !empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnumber"]) && !empty($_POST["postpass"])){
       
       //新規投稿
        $sql = $pdo -> prepare("INSERT INTO KEIjibAN (name, comment , date , pass ) VALUES (:name, :comment, :date, :pass)");
        
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["postpass"];
        $sql -> execute();
        
     //どちらかしか入力されてない場合の警告メッセージの表示 
 } elseif (empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnumber"]) && !empty($_POST["postpass"])){
        echo "<font color=\"red\">名前が未入力です<br></font>";
       
 } elseif (empty($_POST["comment"]) && !empty($_POST["name"]) && empty($_POST["editnumber"]) && !empty($_POST["postpass"])){
    echo "<font color=\"red\">コメントが未入力です<br></font>";
 
 } elseif (!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnumber"]) && empty($_POST["postpass"])){
    echo "<font color=\"red\">パスワードが未入力です<br></font>"; 
    
 } elseif (!empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["editnumber"]) && empty($_POST["postpass"])){
    echo "<font color=\"red\">コメントとパスワードが未入力です<br></font>";
 
 } elseif (empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnumber"]) && empty($_POST["postpass"])){
    echo "<font color=\"red\">名前とパスワードが未入力です<br></font>";
 
 } elseif (empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["editnumber"]) && !empty($_POST["postpass"])){
    echo "<font color=\"red\">名前とコメントが未入力です<br></font>";
 
  //投稿フォームが両方空欄の時


 //削除番号が空欄ならファイルの中身を表示


 } elseif (empty($_POST["comment"]) && empty($_POST["name"]) && empty($_POST["editnumber"]) && empty($_POST["postpass"])){

    if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
       
        
        //削除処理開始、idとpassが一致すれば削除
        
        $pass=$_POST["delpass"];
        
        $sql = 'SELECT * FROM KEIjibAN';
         //クエリ
        $stmt = $pdo->query($sql);
        //テーブルの値を抽出する
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            $id=$row['id'];
            if( $id== $_POST["delete"] && $pass == $_POST["delpass"]){
            $sql= 'delete from KEIjibAN where id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
    }  
        
    elseif(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
        
 
        $editnumber=$_POST["editnum"];
        $editpass=$_POST["editpass"];
        $sql = 'SELECT * FROM KEIjibAN';
         //クエリ
        $stmt = $pdo->query($sql);
        //テーブルの値を抽出する
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id'] == $editnumber && $row['pass'] == $editpass){
                $name2=$row['name'];
                $comment2=$row['comment'];
                $pass2=$row['pass'];
                break;    
            }
        }    
        
    }
        
 } elseif( !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["editnumber"]) && !empty($_POST["postpass"])){
    
    
    $editnumber=$_POST["editnumber"];
    $name=$_POST["name"];
    $comment=$_POST["comment"];  
    $pass=$_POST["postpass"];
    $date=date("Y/m/d H:i:s")."(編集済み)"; 
    
    $sql = 'SELECT * FROM KEIjibAN';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
        foreach ($results as $row){
            $id=$row['id'];
            if($id == $editnumber && $row['pass'] == $pass){
           
            $sql = 'UPDATE KEIjibAN SET name=:name,comment=:comment,pass=:pass,date=:date WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->execute();            
            }
        }  
         
    
        
 } elseif( !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["editnumber"]) && empty($_POST["postpass"])){
     echo "<font color=\"red\">パスワードが未入力です<br></font>";
 
 }
   
        
       
  
  ?>
  
 
  <form action=""method="post">
    名前　　　：    <input type="text" name="name"  placeholder="送信者" value="<?php echo $name2;?>"><br>
    コメント　：   <input type="text" name="comment" placeholder="コメントを入力" value="<?php echo $comment2;?>" style="width:300px;height:15px">
        <input type="hidden" name="editnumber" value="<?php echo $editnumber;?>" ><br>
    パスワード：    <input type="password" name="postpass" placeholder="パスワード" value="<?php echo $pass2;?>">
        <input type="submit" name="submit"><br><br>
     
    削除　　　：    <input type="number" name="delete" placeholder="削除する番号"><br>
    パスワード：    <input type="password" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br><br>
     
     
    編集　　　：    <input type="number" name="editnum"  placeholder="編集をする投稿番号"><br>
    パスワード：    <input type="password" name="editpass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集"><br><br>
  </form>
  
  <?php
  
    $sql = 'SELECT * FROM KEIjibAN';
    //クエリ
    $stmt = $pdo->query($sql);
    //テーブルの値を抽出する
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' , ';
        echo $row['name'].' , ';
        echo $row['comment'].' , ';
        echo $row['date'].'<br>';
    
    }        
        
  ?>
  
</body>
</html>