<html lang="ja">
    <head>
        <meta charset="utf-8">
    </head>
    <body bgcolor = skyblue>
        <font size="7">
        <h>
            簡易掲示板
        </h>
        </font>
        <?php
            $dsn = 'DB';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            // ↑データベース作成
            $sql = "CREATE TABLE IF NOT EXISTS keijiban"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "pass TEXT"
            .");";
            $stmt = $pdo->query($sql);
        // ↑テーブル作成
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $editpass=$_POST["editpass"];
            $editnumber=$_POST["editnumber"];
            if(!empty($editnumber)&&!empty($editpass)){
            $id = $editnumber;
            $sql = "SELECT * FROM keijiban WHERE id =:id";
            $stmt = $pdo->prepare($sql);              
            $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT); 
            $stmt->execute();
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
            if($row["pass"]==$editpass){
            $edit_name = $row["name"];
            $edit_comment = $row["comment"];
            $edit_pass = $row["pass"];
            $hidden_edit = $row["id"];
            }
            }
            }
        }
        ?>
            <form method="post">
            <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit_name)){echo $edit_name;}?>"><br>
            <!--edit_nameがあったらedit_nameを入れてください-->
            <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit_comment)){echo $edit_comment;}?>"><br>
            <!--edit_commentがあったらedit_commentを入れてください-->
            <input type="hidden" name="edit" value="<?php if(!empty($hidden_edit)){echo $hidden_edit;}?>">
            <!--hidden_editがあったらhidden_editを入れてください-->
            <input type="text" name="pass" placeholder="パスワード" value="<?php if(!empty($edit_pass)){echo $edit_pass;}?>">
            <!--passがあったらpassを入れてください-->
            <input type="submit" name="submit"><br>
            <!--投稿機能-->
            <input type="number" name="delete" placeholder="削除番号"><br>
            <input type="text" name="deletepass" placeholder="パスワード">
            <input type="submit" name="deletebutton" value="削除"><br>
            <!--削除機能-->
            <input type="number" name="editnumber" placeholder="編集対象番号"><br>
            <input type="text" name="editpass" placeholder="パスワード">
            <input type="submit" name="editbutton" value="編集"><br>
            <!--編集機能-->
            </form>
        <?php
            if($_SERVER["REQUEST_METHOD"]==="POST"){
            $name=$_POST["name"];
            $pass=$_POST["pass"];
            $edit=$_POST["edit"];
            $hidden_edit=$_POST["edit"];
            $comment=$_POST["comment"];
            $deletepass=$_POST["deletepass"];
            $editpass=$_POST["editpass"];
            $editnumber=$_POST["editnumber"];
            $delete=$_POST["delete"];
            $date=date("Y/m/d H:i:s");
            // ↑諸々の定義
            if(!empty($comment)&&!empty($name)&&!empty($pass)&&empty($hidden_edit)){
                $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, pass) VALUES (:name, :comment, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql -> execute();
                $name = $name;
                $comment = $comment; 
                $pass = $pass;
            }
            // ↑投稿処理
            if(!empty($comment)&&!empty($name)&&!empty($pass)&&!empty($hidden_edit)){
                $id = $hidden_edit;
                $pass = $pass;
                $name = $name;
                $comment = $comment; 
                $sql = "UPDATE keijiban SET name=:name,comment=:comment WHERE id=:id AND pass=:pass";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
                $stmt->execute();
            }
            // ↑編集処理
            if(!empty($delete)&&!empty($deletepass)){
                $id = $delete;
                $pass = $deletepass;
                // $sql = "delete from keijiban WHERE id = '{$delete}' AND pass = '{$deletepass}'";
                $sql = "delete from keijiban WHERE id=:id AND pass=:pass";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $deletepass, PDO::PARAM_STR);
                $stmt->execute();
            }
        // ↑削除処理
        ?>
        <table border="2">
            <tr backgroundcolor="#AAAAAA">
                <th>番号</th>
                <th>名前</th>
                <th>コメント</th>
                <th>パス</th>
        <!--↑テーブルレイアウト-->
        <?php
            $sql = 'SELECT * FROM keijiban';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $id = $row['id'];
                $name = $row['name'];
                $comment = $row['comment'];
                $pass = $row['pass'];
                print "<tr><td>{$id}</td><td>{$name}</td><td>{$comment}</td><td>{$pass}</td></tr>";
            }
        // ↑掲示板からデータを取得し、表にぶちこむ
        // $sql = 'DROP TABLE keijiban';
        // $stmt = $pdo->query($sql);
        // ↑全部削除
        }
        ?>
    </body>
</html>
