<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/string.php'); ?>

<?php

    /*
     *  Check login form data.
     *  Check if unauthorized message is enabled.
     */

    // Check if logged in.
    if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
    {
        header('Location: index.php');
        exit(0);
    }
    else if(isset($_SESSION['username']))  // Not logged in.
        unset($_SESSION['username']);

    // Check login if post data provided.
    if(isset($_POST['login_username']) && isNotEmpty($_POST['login_username']) && isset($_POST['login_password']) && isNotEmpty($_POST['login_password']))
    {
        $dbRetrieve = mysqlQuery('SELECT `password` FROM `user` WHERE `username`=\''.addslashes($_POST['login_username']).'\' LIMIT 1;');

        if(mysqli_num_rows($dbRetrieve) > 0)
        {
            $login_passwordSalt = mysqli_fetch_row($dbRetrieve)[0];

            if(crypt($_POST['login_password'], $login_passwordSalt) == $login_passwordSalt)
            {
                $_SESSION['username'] = $_POST['login_username'];
                header('Location: index.php');
                exit(0);
            }
            else
                $login_errorMsg = '您輸入的使用者名稱與密碼有誤，請您檢查拼字及大小寫後重試。';
        }
        else
            $login_errorMsg = '您輸入的使用者名稱不存在，請您檢查拼字後重試。';
        
        if(isset($_SESSION['username']))
            unset($_SESSION['username']);
    }

    // Check if unauthorized message is enabled.
    if(isset($_GET['unauth']) && $_GET['unauth']=='true')
        $login_errorMsg = '您沒有權限訪問該資源，請您登入後重試。';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>登入管理後台</title>
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/login.css" />
    </head>
    <body>

        <section class="login_block">
            <h1>管理後台</h1>
            <form action="login.php" method="post">
                <div class="login_field_container">
                    <input id="login_field_username" class="input_textbox input_textbox_withIcon" name="login_username" type="text" placeholder="使用者名稱" value="" style="background-image:url('image/icon_login_username.png')" required />
                    <input id="login_field_password" class="input_textbox input_textbox_withIcon" name="login_password" type="password" placeholder="通行字串" value="" style="background-image:url('image/icon_login_password.png');" required />
                </div>
                
                <p id="login_text_errorMessage" class="login_text_errorMsg"></p>
                
                <?php
                
                    // Display login error message.
                    if(isset($login_errorMsg) && isNotEmpty($login_errorMsg))
                    {
                        echo
                        '
                            <script>
                                document.getElementById("login_text_errorMessage").innerHTML = "'.$login_errorMsg.'";
                                document.getElementById("login_text_errorMessage").style.display = "block";
                            </script>
                        ';
                    }
                    
                ?>

                <input id="login_button_submit" class="input_button" type="submit" value="登入" />
                <input id="login_button_goBack" class="input_button" type="button" value="回首頁" onClick="window.location='index.php';" />
            </form>
        </section>

    </body>
</html>

