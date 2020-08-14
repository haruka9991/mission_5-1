<html lang="ja">

<head>

    <title>mission_5-1</title>
</head>

<body>

    <?php

    // DB接続設定
    $dsn = 'データベース名';

    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO(
        $dsn,
        $user,
        $password,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
    );
    //CREATE文：データベース内にデータを登録するためのテーブルを作成。
    $sql = "CREATE TABLE IF NOT EXISTS tbtest511" //テーブル名は「tbtest511」。
        //「 IF NOT EXISTS 」は「もしまだこのテーブルが存在しないなら」という意味

        //登録できる項目（カラム）create table テーブル名 (フィールド名 データ型,フィールド名 データ型);
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY," //自動で登録されるナンバリング。
        . "codename1 CHAR(32)," //名前を入れる。文字列、半角英数で32文字。
        . "comment1 TEXT," //コメントを入れる。文字列、長めの文章も入る。
        . "tokoday1 DATETIME," //時刻を入れる。
        . "password1 CHAR(32)" //パスワードを入れる。文字列、半角英数で32文字。
        . ");";
    $stmt = $pdo->query($sql);

    //データベースに現在、どのようなテーブルが作成されているかを確認する。
    //全てのテーブル名を表示させてみる。

    $sql = 'SHOW TABLES';
    $result = $pdo->query($sql);
    foreach ($result as $row) {
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";
    //テーブルの中身のフィールドを確認したいときはコマンド「desc テーブル名;」で見ることができる。

    //データベースへの登録
    $sql = $pdo->prepare("INSERT INTO tbtest511 (id,codename1,comment1,tokoday1,password1) 
    VALUES (:id, :codename1, :comment1, :tokoday1, :password1)");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->bindParam(':codename1', $codename1, PDO::PARAM_STR);
    $sql->bindParam(':comment1', $comment1, PDO::PARAM_STR);
    $sql->bindParam(':tokoday1', $tokoday1, PDO::PARAM_STR);
    $sql->bindParam(':password1', $password1, PDO::PARAM_STR);
    //$params = array(':nam' => $nam, ':com' => $com);

    //名前とコメントが入力された場合
    if (!empty($_POST["codename"]) && !empty($_POST["comment"])) {

        $nam = $_POST["codename"];
        $com = $_POST["comment"];
        $tokoday = date("Y/m/d H:i:s");


        //パスワードが空じゃない場合
        if (!empty($_POST["pass1"])) {

            $pass1 = $_POST["pass1"];

            //パスワードが"a"の場合
            if ($pass1 == "a") {


                //隠れ編集フォームが空の場合・・・新規投稿
                if (empty($_POST["edithidden"])) {

                    

                    //好きな名前、好きな言葉は自分で決めること
                    $codename1 = $nam;
                    $comment1 = $com;
                    $tokoday1 = $tokoday;
                    $password1 = $pass1;
                    //行内容をテーブルに書き込む
                    $sql->execute();

                    
                    

                    echo "隠れ編集フォームが空";
                } //隠れ編集フォームが空の場合のif終了

                //隠れ編集フォームが空じゃない場合
                else {

                    //編集内容が問題ない場合：投稿番号と編集対象番号を
                    //比較して、等しい場合は、ファイルに書き込む内容を
                    //送信内容に差し替える。


                    $hid = $_POST["edithidden"];


                    //データベースへの登録

                    //値をバインド？
                    $id = $hid;
                    $codename1 = $nam;
                    $comment1 = $com; //変更したい名前、変更したいコメントは自分で決めること
                    $sql = 'UPDATE tbtest511 SET codename1=:codename1,comment1=:comment1 
                    WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':codename1', $codename1, PDO::PARAM_STR);
                    $stmt->bindParam(':comment1', $comment1, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();


                   
                    


                    echo "隠れ編集フォームが空じゃない";
                } //隠れ編集フォームが空じゃない場合のif終了


            } //パスワードが"a"の場合のelse終了

            //パスワードが"a"じゃない場合
            else {
                echo "パスワードが違います。";
            } //パスワードが"a"じゃない場合のelse終了

        } //パスワードが空じゃない場合のif終了

        //パスワードが空の場合
        else {
            echo "パスワードを入力してください。";
        } //パスワードが空の場合のelse終了


    } //名前とコメントが入力された場合のif終了



    //削除機能を付ける

    //削除フォームから削除番号が送信された場合
    if (!empty($_POST["number"])) {

        $del = $_POST["number"];

        //パスワードが空じゃない場合
        if (!empty($_POST["pass2"])) {
            $pass2 = $_POST["pass2"];

            //パスワードが"i"の場合
            if ($pass2 == "i") {


                $id = $del;
                $sql = 'delete from tbtest511 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

               
                

                echo "削除成功";
            } //パスワードが"i"の場合のif終了

            //パスワードが"i"じゃない場合
            else {
                echo "パスワードが違います。";
            }
        } //パスワードが空じゃない場合のif終了

        //パスワードが空の場合
        else {
            echo "パスワードを入力してください。";
        } //パスワードが空の場合のelse終了

    } //削除番号が送信された場合のif終了




    //編集機能をつける

    //編集ボタンが押されたら
    if (isset($_POST["edit_btn"])) {

        //編集フォームが空じゃない場合
        if (!empty($_POST["editnumber"])) {
            $edt = $_POST["editnumber"];

            //パスワードが空じゃない場合
            if (!empty($_POST["pass3"])) {
                $pass3 = $_POST["pass3"];

                //パスワードが"u"の場合
                if ($pass3 == "u") {


                    echo "編集する番号が分かりました";

                } //パスワードが"u"の場合のif終了

                //パスワードが"u"じゃない場合
                else {
                    echo "パスワードが違います。";
                }
            } //パスワードが空じゃない場合のif終了

            //パスワードが空の場合
            else {
                echo "パスワードを入力してください。";
            } //パスワードが空の場合のelse終了


        } //編集フォームが空じゃない場合のif終了


    } //編集ボタンが押された場合のif終了




    //ちなみに…<?= ？>は<?php echo ？>と同じ意味
    ?>


    <form method="post" action="mission_5-1-4.php">

        <p>
            <input type="text" name="comment" placeholder="コメント" value="<?php
            //編集ボタンが押されたら
            if (isset($_POST["edit_btn"])) {

                //編集フォームが空じゃない場合
                if (!empty($_POST["editnumber"])) {
                    $edt = $_POST["editnumber"];

                    //パスワードが空じゃない場合
                    if (!empty($_POST["pass3"])) {
                        $pass3 = $_POST["pass3"];

                        //パスワードが"u"の場合
                        if ($pass3 == "u") {

                            //テーブルに登録されたデータを取得し、表示する
                            $id = $edt; // idがこの値のデータだけを抽出したい、とする

                            $sql = 'SELECT * FROM tbtest511 WHERE id=:id';
                            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                            $stmt->execute();                             // ←SQLを実行する。

                            $results = $stmt->fetchAll();
                            foreach ($results as $row) {
                                //$rowの中にはテーブルのカラム名が入る
                                echo $row['comment1'];
                            }
                        } //パスワードが"u"の場合のif終了


                    } //パスワードが空じゃない場合のif終了


                } //編集フォームが空じゃない場合のif終了


            } //編集ボタンが押された場合のif終了


            ?>">
        </p>

        <p>
            <input type="text" name="codename" placeholder="名前" value="<?php
                                                                        //編集ボタンが押されたら
                                                                        if (isset($_POST["edit_btn"])) {

                                                                            //編集フォームが空じゃない場合
                                                                            if (!empty($_POST["editnumber"])) {
                                                                                $edt = $_POST["editnumber"];

                                                                                //パスワードが空じゃない場合
                                                                                if (!empty($_POST["pass3"])) {
                                                                                    $pass3 = $_POST["pass3"];

                                                                                    //パスワードが"u"の場合
                                                                                    if ($pass3 == "u") {

                                                                                        //テーブルに登録されたデータを取得し、表示する
                                                                                        $id = $edt; // idがこの値のデータだけを抽出したい、とする

                                                                                        $sql = 'SELECT * FROM tbtest511 WHERE id=:id';
                                                                                        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                                                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                                                        $stmt->execute();                             // ←SQLを実行する。

                                                                                        $results = $stmt->fetchAll();
                                                                                        foreach ($results as $row) {
                                                                                            //$rowの中にはテーブルのカラム名が入る
                                                                                            echo $row['codename1'];
                                                                                        }
                                                                                    } //パスワードが"u"の場合のif終了

                                                                                } //パスワードが空じゃない場合のif終了


                                                                            } //編集フォームが空じゃない場合のif終了


                                                                        } //編集ボタンが押された場合のif終了

                                                                        ?>">
        </p>
        <!--
           投稿フォームが「新規投稿」と「編集」を
           兼ねているため「新規投稿か、編集か」を
           判断できるようにする。
           作成の過程としてフォーム内に新しい項目
           （テキストボックス※）を用意して、そこに
           編集したい投稿番号が表示される状態にしておく。
           -->
        <p>
            <input type="hidden" name="edithidden" placeholder="隠し編集対象番号" value="<?php
                                                                                    //編集ボタンが押されたら
                                                                                    if (isset($_POST["edit_btn"])) {

                                                                                        //編集フォームが空じゃない場合
                                                                                        if (!empty($_POST["editnumber"])) {
                                                                                            $edt = $_POST["editnumber"];

                                                                                            //パスワードが空じゃない場合
                                                                                            if (!empty($_POST["pass3"])) {
                                                                                                $pass3 = $_POST["pass3"];

                                                                                                //パスワードが"u"の場合
                                                                                                if ($pass3 == "u") {
                                                                                                    //テーブルに登録されたデータを取得し、表示する
                                                                                                    $id = $edt; // idがこの値のデータだけを抽出したい、とする

                                                                                                    $sql = 'SELECT * FROM tbtest511 WHERE id=:id';
                                                                                                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                                                                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                                                                    $stmt->execute();                             // ←SQLを実行する。

                                                                                                    $results = $stmt->fetchAll();
                                                                                                    foreach ($results as $row) {
                                                                                                        //$rowの中にはテーブルのカラム名が入る
                                                                                                        echo $row['id'];
                                                                                                    }
                                                                                                } //パスワードが"u"の場合のif終了


                                                                                            } //編集フォームが空じゃない場合のif終了


                                                                                        } //編集ボタンが押された場合のif終了

                                                                                    }

                                                                                    ?>">
        </p>

        <p>
            <input type="text" name="pass1" placeholder="パスワード" value="">
        </p>

        <p>
            <input type="submit" name="submit" value="送信">
        </p>

        <p>
            <input type="text" name="number" placeholder="削除対象番号" value="">
        </p>

        <p>
            <input type="text" name="pass2" placeholder="パスワード" value="">
        </p>

        <p>
            <input type="submit" name="delete" value="削除">
        </p>

        <p>
            <input type="text" name="editnumber" placeholder="編集対象番号" value="">
        </p>

        <p>
            <input type="text" name="pass3" placeholder="パスワード" value="">
        </p>

        <p>
            <input type="submit" name="edit_btn" value="編集">
        </p>




    </form>

    <?php

    //表示機能を付ける
    //テーブルに登録されたデータを取得し、表示する
    $sql = 'SELECT * FROM tbtest511';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'] . ' ';
        echo $row['codename1'] . ' ';
        echo $row['comment1'] . ' ';
        echo $row['tokoday1'] . ' ';
        echo $row['password1'] . '<br>';
        echo "<hr>";
    }


    // 接続を閉じる
    $dbh = null;

    ?>

</body>

</html>