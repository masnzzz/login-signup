# login-signup
今後このシステムを会員制サイト用開発のテンプレに使えるように作りました。
DBはMySQL

【機能】

-signup.php    新規登録機能
-login.php     ログイン機能
-auth.php      このファイルを読み込むことで、ログインしていなければアクセスできずlogin.phpに自動的に遷移する
-function.php  DB接続やバリデーション関数をまとめているファイル

-index.php     ログインしていなければアクセス出来ないページ(auth.phpを読み込んでいるため)
