<?php

$siteTitle = 'ログイン';

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//=====================
//ログイン画面処理
//=====================
//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります');

    //変数にユーザー情報そ代入
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($pass, 'email');
    
    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');
  
    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');

    if(empty($err_msg)){
        debug('バリデーションOKです');

        try{
            $dbh = dbConnect();
            $sql = 'SELECT pass,id FROM users WHERE email = :email';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            debug('クエリ実行の中身:'.print_r($result,true));

            //パスワード照合
            if(!empty($result) && password_verify($pass, array_shift($result))){
                debug('パスワードがマッチしました');

                //ログイン有効期限
                $sesLimit = 60*60;
                $_SESSION['login_date'] = time();

                //ログイン保持にチェックがあった場合
                if($pass_save){
                    debug('ログイン保持にチェックがあります');
                    //ログイン有効期限を３０日にしてセット
                    $_SESSION['login_limit'] = $sesLimit * 24 * 30;
                }else{
                    debug('ログイン保持にチェックはありません');
                    //次回からログイン保持しないので、ログイン有効期限を１時間後にセット
                    $_SESSION['login_limit'] = $sesLimit;
                }
                //ユーザーIDを格納
                $_SESSION['user_id'] = $result['id'];

                debug('セッション変数の中身:'.print_r($_SESSION,true));
                debug('マイページへ遷移します');
                header("Location:index.php");
            }else{
                debug('パスワードがアンマッチです');
                $err_msg['common'] = MSG09;
            }
        } catch(Exception $e) {
            error_log('エラー発生:'. $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');


?>

<?php require('head.php') ?>


<body class="page-login page-1colum">

<!-- メインコンテンツ -->
<div>

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

            <!-- submit -->
            <label>
                <input type="checkbox" name="pass_save">次回ログインを省略する
            </label>

            <div class="btn-container">
                <input type="submit" value="ログイン">
            </div>

            <br>
            <a href="signup.php">新規登録する</a>

        </form>
        
    </div>

    </section>

</div>

<?php require('footer.php'); ?>