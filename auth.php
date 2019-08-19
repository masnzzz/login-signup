<?php

//=============================
//ログイン認証・自動ログアウト
//=============================
//ログインしている場合
if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです');

    //現在日時が最終ログイン日時＋有効期限を超えていた場合
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
        debug('ログイン有効期限オーバー');

        //セッションを解除（ログアウトする）
        session_destroy();
        header("Location:login.php");
    }else{
        debug('ログイン有効期限以内です');
        //最終ログイン日時を現在日時に更新
        $_SESSION['login_date'] = time();

        //現在実行中のスクリプトファイル名がlogin.phpの場合
        //$_SERVER['PHP_SELF']はドメインからのパを返すため、今回だと・・・
        //basename関数を使うことでファイル名だけが取り出せる
        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            debug('indexへ遷移します');
            header("Location:index.php");
        }
    }

}else{
    debug('未ログインユーザーです');
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        header("Location:login.php");
    }
}