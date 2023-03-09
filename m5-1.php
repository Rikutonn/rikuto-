<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset ="UTF-8">
    <title>mission3-5</title>
    <h1>行ってみたい場所を共有しよう！</h1>
</head>
<body>
<?php
// データベース接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest" 
    ."("
    . "id INT AUTO_INCREMENT PRIMARY KEY," 
    . "name char(32)," 
    . "comment TEXT," 
    . "date char(32),"
    . "pass char(32)"
    . ");";
    $stmt = $pdo->query($sql);

        
        //編集選択
        if(!empty($_POST["editnum2"]) && isset($_POST["edit"]) && !empty($_POST["epass"])){
                $id = $_POST["editnum2"];
                $epass = $_POST["epass"];
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt -> fetchAll();

                foreach($results as $row){  //編集元の内容を取得
                    if($row['id'] == $id && $row['pass'] == $epass){  
                        $ename = $row['name'];
                        $ecomment = $row['comment'];
                        $pass = $row['pass'];
                        $editnumber = $row['id'];
                    }
                }
        }
?>

<!-- 入力フォーム -->
<form action =""method="post">
    <input type="text"name="name"placeholder="名前"value="<?php if(!empty($pass)) {echo $ename;} ?>"><br>
    <input type="text"name="str"placeholder="コメント" value="<?php if(!empty($pass)) {echo $ecomment;} ?>"><br>
    <input type="password"name="cpass"placeholder="パスワード"><br>
    <input type="submit"name="submit"value="送信"><br>
    <input type="hidden"name="edit2" value="<?php if(!empty($pass)) {echo $id;} ?>"><br>
    <input type="text"name="number"placeholder="消去対象番号"><br>
    <input type="password"name="dpass"placeholder="パスワード"><br>
    <input type="submit" name="delete"value="消去"><br><br>
    <input type="text" name = "editnum2"placeholder="編集対象番号"><br>
    <input type="password"name="epass"placeholder="パスワード"><br>
    <input type="submit"name= "edit" value="編集">
    </form>


<?php 
        //編集実行
        if(!empty($_POST["edit2"])){
            $id = $_POST["edit2"]; 
            $name = $_POST["name"]; //編集内容の取得
            $comment = $_POST["str"];
            $pass = $_POST["cpass"];
            $date = date("Y/m/d H:i:s");
        if(!empty($_POST["cpass"])){
            $sql = 'update tbtest set name=:name,comment=:comment, pass=:pass, date=:date where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);    //編集実行（書き換え）
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt ->execute();
        }
            
            
        }else{
                
//新規投稿機能
        if(isset($_POST["name"]) && isset($_POST["str"])&&isset($_POST["submit"]) && !empty($_POST["cpass"])){
            $name = $_POST["name"]; //投稿内容の取得
            $comment = $_POST["str"];
            $pass = $_POST["cpass"];
            $date = date("Y/m/d H:i:s");
            $sql = $pdo->prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);      //書き込み
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql ->execute();
        }

        }
        
               
                
                
            
        
//消去機能
        if(!empty($_POST["number"]) && isset($_POST["delete"]) && !empty($_POST["dpass"])){
            $id = $_POST["number"];
            $dpass = $_POST["dpass"];
            $sql = 'delete from tbtest where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt ->bindParam(':id', $id,PDO::PARAM_INT);
            $stmt ->execute();
        }

// 表示機能
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']. '<br>';
        echo "<hr>";
        }
    ?>

                
</body>
</html>