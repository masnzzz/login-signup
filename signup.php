<?php

$siteTitle = 'ユーザー登録';

//共通変数・関数ファイルを読込み
require('function.php');

//post送信されていた場合
if(!empty($_POST)){

    //変数にユーザー情報を代入
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');

    if(empty($err_msg)){

        //emailのチェック
        validEmail($email, 'email');
        validMaxLen($email, 'email');
        validEmailDup($email);

        //パスワードのチェック
        validHalf($pass, 'pass');
        validMaxLen($pass, 'pass');
        validMinLen($pass, 'pass');

        //パスワード（再入力）のチェック
        validMaxLen($pass_re, 'pass_re');
        validMinLen($pass_re, 'pass_re');

        if(empty($err_msg)){

            //パスワードとパスワード再入力が合っているかチェック
            validMatch($pass, $pass_re, 'pass_re');

            if(empty($err_msg)){

                //例外処理
                try {
                    // DBへ接続
                    $dbh = dbConnect();
                    // SQL文作成
                    $sql = 'INSERT INTO users (email,pass,login_time) VALUES(:email,:pass,:login_time)';
                    $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                                    ':login_time' => date('Y-m-d H:i:s'));
                    // クエリ実行
                    queryPost($dbh, $sql, $data);

                    header("Location:index.php");

                } catch (Exception $e) {
                    error_log('エラー発生:' . $e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
        }
    }
}

?>

<?php require('head.php') ?>


<body class="page-signup page-1colum">

<!-- メインコンテンツ -->
<div id="contents" class="site-width">

    <!-- Main -->
    <section id="main" >

    <div class="form-container">

        <h2 class="title"><?php echo $siteTitle; ?></h2>

        <form method="post" action="" class="form">

            <div class="area-msg">
                <?php if(!empty($err_msg['common'])) echo $err_msg['common'];　?>
            </div>
            
            <!-- email -->
            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                Email
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <div class="area-msg"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></div>

            <!-- pass -->
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
                パスワード
                <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <div class="area-msg"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></div>

            <!-- pass_re -->
            <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
                パスワード（再入力）
                <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
            </label>
            <div class="area-msg"><?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?></div>

            <!-- submit -->
            <div class="btn-container">
                <input type="submit" class="btn btn-mid" value="登録する">
            </div>

            <br>
            <a href="login.php">ログインする</a>

        </form>
        
    </div>

    </section>

</div>

<?php require('footer.php'); ?>