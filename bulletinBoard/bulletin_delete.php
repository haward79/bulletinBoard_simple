<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('isLogin.php'); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/string.php'); ?>
<?php require_once('php/confirm.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php

    /*
     *  Before deleting bulletin from database,
     *  check if the bulletin exists.
     */

    if(isset($_GET['id']) && isNotEmpty($_GET['id']) && is_numeric($_GET['id']))
    {
        $bulletin_id = (int)$_GET['id'];

        // Retrieve bulletin from database.
        $dbRetrieve = mysqlQuery('SELECT `title`, `content` FROM `bulletin` WHERE `id`='.$bulletin_id.' LIMIT 1;');

        if(mysqli_num_rows($dbRetrieve) == 0)  // Bulletin not exists.
        {
            $msg_pageTitle = '消息不存在';
            $msg_title = '消息不存在';
            $msg_content = '您欲刪除的最新消息不存在！';
            $msg_type = ConfirmType::$ok;
        }
        else  // Bulletin exists.
        {
            /*
             *  Confirm deletion bulletin from database.
             *  Confirmation flag is set to true : directly delete bulletin.
             *  Confirmation flag is NOT set to true : confirm deletion (html page).
             */

            if(isset($_GET['confirm']) && $_GET['confirm']=='true')  // Directly delete bulletin.
            {
                // Delete linked file.
                $content = mysqli_fetch_row($dbRetrieve)[1];

                if(strlen($content)>1 && substr($content, 0, 1)=='2')
                {
                    $files = explode("\n", substr($content, 1));
                    
                    for($i=0, $len=count($files)-1; $i<$len; ++$i)
                        if(file_exists('upload/'.$files[$i]))
                            unlink('upload/'.$files[$i]);
                }

                // Delete data row from database.
                mysqlQuery('DELETE FROM `bulletin` WHERE `id`='.$_GET['id'].';');

                header('Location: index.php');
                exit(0);
            }
            else  // Confirm deletion.
            {
                $msg_pageTitle = '確認刪除最新消息 - 南大輔導中心';
                $msg_title = '確認刪除最新消息？';
                $msg_content = '您確定要刪除「'.mysqli_fetch_row($dbRetrieve)[0].'」嗎？';
                $msg_type = ConfirmType::$yesNo;
            }
        }
    }
    else
    {
        header('Location: 404');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $msg_pageTitle; ?></title>
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/confirm.css" />
    </head>
    <body>

        <section class="confirm_block">
            <h1><?php echo $msg_title; ?></h1>
            <form>
                <p class="confirm_text_message"><?php echo $msg_content; ?></p>
                <?php

                    if($msg_type == ConfirmType::$ok)
                    {
                        echo '
                            <div class="confirm_button_container_ok">
                                <input id="confirm_button_yes" class="input_button input_button_withIcon" type="button" value="確認" style="background-image:url(\'image/icon_confirm_yes.png\');" onClick="window.location=\'index.php\';" />
                            </div>
                        '."\n";
                    }
                    else
                    {
                        echo '
                            <div class="confirm_button_container_yesNo">
                                <input id="confirm_button_yes" class="input_button input_button_withIcon" type="button" value="確認" style="background-image:url(\'image/icon_confirm_yes.png\');" onClick="window.location=\'bulletin_delete.php?id='.$_GET['id'].'&confirm=true\';" />
                                <input id="confirm_button_no" class="input_button input_button_withIcon" type="button" value="取消" style="background-image:url(\'image/icon_confirm_no.png\');" onClick="window.location=\'index.php\';" />
                            </div>
                        '."\n";
                    }

                ?>
            </form>
        </section>

    </body>
</html>

    