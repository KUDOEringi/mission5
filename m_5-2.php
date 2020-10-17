<?php

//DB接続設定
$dsn ='データベース名';
$user ='ユーザー名';
$password ='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


//編集選択機能

if (isset($_POST['edit']) && isset($_POST['edipass'])){
    if (!empty($_POST['edit']) && !empty($_POST['edipass'])){
        $sql = 'SELECT * FROM mission5_1';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if ($row['id'] == $_POST['edit']){
	            if ($row['dpass'] == $_POST['edipass']){
	                $editnum=$row['id'];
	                $editname=$row['name'];
	                $editcomment=$row['comment'];
	                $pass0=$row['dpass'];
	            }else{
	                echo "";
	            }
	        }
	    }
    }
}

?>

<form action="" method="post">
        <input type="name" size="20" name="name" style="width:200px;" placeholder="名前" value="<?php if(isset($editname)){echo$editname;} ?>">
        <input type="text" size="20" name="str" style="width:200px;" placeholder="コメント" value="<?php if(isset($editcomment)){echo$editcomment;} ?>">
        <input type="hidden" name="editNO" value="<?php if(isset($editnum)){echo$editnum;} ?>">
        <input type="text" size="20" name="pass" style="width:200px;" placeholder="パスワード">
        <input type="hidden" name="pass0" value="<?php if(isset($pass0)){echo$pass0;} ?>">
        <input type="submit" name="submit">
    </form>
    
    <form action="" method="POST">
        <input type="text" size="20" name="delete" style="width:300px;" placeholder="削除したい番号を入力してください">
        <input type="text" size="20" name="delepass" style="width:200px;" placeholder="パスワード">
        <input type="hidden" name="delkey" value="<?php if(isset($delpass)){echo$delpass;} ?>">
        <input type="submit" name="submit2" value="削除">
     </form>
     
    <form action="" method="post">
        <input type="text" size="20" name="edit" style="width:300px;" placeholder="編集したい番号を入力してください">
        <input type="text" size="20" name="edipass" style="width:200px;" placeholder="パスワード">
        <input type="hidden" name="edikey" value="<?php if(isset($edipass)){echo$edipass;} ?>">
        <input type="submit" value="編集">
    </form>

<?php

$date=date("Y年m月d日 H:i:s");

//投稿機能
if (isset($_POST['name']) && isset($_POST['str'])){
    if(!empty($_POST['name']) && !empty($_POST['str']) && !empty($_POST['pass'])){
    	$name = $_POST['name'];
	    $str = $_POST['str'];
	    $pass = $_POST['pass'];
	    $pass0=$_POST["pass0"];
	    if(empty($_POST['editNO'])){
            $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, time, dpass) VALUES (:name, :comment, :time, :dpass)");
            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	        $sql -> bindParam(':name', $name2, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $str2, PDO::PARAM_STR);
	        $sql -> bindParam(':dpass', $dpass, PDO::PARAM_STR);
	        $time = $date;
            $name2 = $name;
	        $str2 = $str;
	        $dpass = $pass;
	        $sql -> execute();
	        echo "<font color=\"blue\"> 新規投稿を送信しました！</font><br>";
	    }elseif($pass0==$_POST['pass']){
	        $pass0=$_POST["pass0"];
	        $id = $_POST['editNO']; //編集したい投稿番号
	        $name3 = " ".$_POST['name'];
	        $str3 = " ".$_POST['str'];
	        $dpass = $pass0;
	        $time = " ".$date;
            $sql = 'UPDATE mission5_1 SET time=:time,name=:name,comment=:comment,dpass=:dpass WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
	        $stmt->bindParam(':name', $name3, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $str3, PDO::PARAM_STR);
	        $stmt->bindParam(':dpass', $dpass, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	        echo "<font color=\"blue\"> 投稿を編集しました！</font><br>";
	   }else{
	        echo "<font color=\"red\"> 正しいパスワードを入力してください</font><br>";
	   }
	}else{
	    echo "<font color=\"red\"> 名前、コメント、パスワードを入力してください</font><br>";
	}
}

//削除機能
if (isset($_POST['submit2']) && isset($_POST['delepass'])){
    if (!empty($_POST['submit2']) && !empty($_POST['delepass'])) {
        $delete=$_POST['delete'];
        $delepass=$_POST['delepass'];
	    $sql = 'SELECT * FROM mission5_1';
	    $stmt = $pdo->query($sql);
	    $delresults = $stmt->fetchAll(); 
	    foreach ($delresults as $row){
        
            if($row['id'] == $_POST['delete']){
                if($row['dpass'] == $_POST['delepass']){
                    $id = $_POST['delete'];
                    $sql = 'delete from mission5_1 where id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->execute();
	                echo "<font color=\"blue\"> 削除しました</font><br>";
                }else{
                    echo "<font color=\"red\"> 正しいパスワードを入力してください</font><br>";
                }
            }
	    }
    }else{
        echo "<font color=\"red\"> 削除したい番号とパスワードを入力してください</font><br>";
    }
}

//表示機能

    $sql = 'SELECT * FROM mission5_1';
    $stmt = $pdo->query($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->execute();
    $results = $stmt->fetchAll(); 
	    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['comment'].',';
		    echo $row['time'].'<br>';
	        echo "<hr>";
	   }

?>