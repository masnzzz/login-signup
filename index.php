<?php

$siteTitle = 'INDEX';
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

?>

<?php require('head.php'); ?>

<body>

    <h2 class="title"><?php echo $siteTitle; ?></h2>

    index.php<br>

    <a href="signup.php">新規登録する</a><br>
    <a href="logout.php">ログアウトする</a><br>

<?php require('footer.php'); ?>