<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>最新消息 - 南大輔導中心</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
    </head>
    <body>

        <section>
            <table class="bulletin_table">
                <tbody>
                    <?php
                    
                        /*
                         *  Retrieve bulletins from database and show on page.
                         */

                        // Retrieve bulletins.
                        $dbRetrieve = mysqlQuery('SELECT `id`, `title`, `type`, `datetime` FROM `bulletin` ORDER BY `datetime` DESC;');

                        // Show bulletins.
                        if(mysqli_num_rows($dbRetrieve) == 0)  // No bulletin.
                        {
                            echo
                            '
                            <tr>
                                <td colspan="3">目前沒有公告喔，過幾天再來看看吧！</td>
                            </tr>
                            '."\n";
                        }
                        else
                        {
                            for($i=0, $len=mysqli_num_rows($dbRetrieve); $i<$len; ++$i)
                            {
                                $dbExtract = mysqli_fetch_assoc($dbRetrieve);
                                
                                if($dbExtract['type'] == 1)  // 活動
                                    $bulletinTypeColor = '#c24201';
                                else if($dbExtract['type'] == 2)  // 宣導
                                    $bulletinTypeColor = '#ab7a1b';
                                else if($dbExtract['type'] == 3)  // 公告
                                    $bulletinTypeColor = '#6fab1b';
                                else if($dbExtract['type'] == 4)  // 徵人
                                    $bulletinTypeColor = '#265d91';
                                else if($dbExtract['type'] == 5)  // 其他
                                    $bulletinTypeColor = '#6c1bab';
                                else  // Unknown type.
                                    $bulletinTypeColor = '#707070';

                                // Print bulletin.
                                $extractedDate = explode('-', explode(' ', $dbExtract['datetime'])[0]);

                                echo '
                                <tr>
                                    <!-- Bulletin title -->
                                    <td class="bulletin_column_title_compact">
                                        <!-- type -->
                                        <div class="bulletin_type_span" style="background-color:'.$bulletinTypeColor.';">'.
                                            BulletinType::numberToName($dbExtract['type']).
                                        '</div>

                                        <!-- title -->
                                        <div class="bulletin_title_span">
                                            <a href="detail.php?id='.$dbExtract['id'].'" target="_blank">'.
                                                toHtml($dbExtract['title']).'
                                            </a>
                                        </div>
                                    </td>
                                    
                                    <!-- Bulletin publish time -->
                                    <td class="bulletin_column_datetime_compact">'.$extractedDate[1].'/'.$extractedDate[2].'</td>
                                ';

                                // Check if logged in.
                                if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
                                {
                                    echo
                                    '
                                        <td><input class="input_button input_button_withIcon" type="button" value="編輯" onClick="window.location=\'bulletin_edit.php?id='.$dbExtract['id'].'\';" style="background-image:url(\'image/icon_bulletin_edit.png\');" /></td>
                                        <td><input class="input_button input_button_withIcon" type="button" value="刪除" onClick="window.location=\'bulletin_delete.php?id='.$dbExtract['id'].'\';" style="background-image:url(\'image/icon_bulletin_delete.png\');" /></td>
                                    '."\n";

                                    // Javascript to adjust bulletin width.
                                    echo '
                                    <script>
                                        bulletinTitleClass = document.getElementsByClassName(\'bulletin_title_span\');
                                        
                                        for(i=0, len=bulletinTitleClass.length; i<len; ++i)
                                            bulletinTitleClass[i].style.width = "calc(100vw - 270px)";
                                    </script>';
                                }

                                echo '
                                </tr>
                                '."\n";
                            }
                        }

                    ?>
                </tbody>
            </table>
        </section>
        
        <section>
            <?php

                // Print login or logout button.
                if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
                {
                    echo '
                    <input class="input_button input_button_withIcon" type="button" value="新增公告" onClick="window.location=\'bulletin_add.php\';" style="background-image:url(\'image/icon_bulletin_add.png\');" />
                    <input class="input_button input_button_withIcon" type="button" value="登出管理後台" onClick="window.location=\'logout.php\';" style="background-image:url(\'image/icon_manage.png\');" />
                    '."\n";
                }
                else  // Not logged in.
                    echo '<input class="input_button input_button_withIcon" type="button" value="管理後台" onClick="window.open(\'login.php\', \'_blank\');" style="background-image:url(\'image/icon_manage.png\');" />'."\n";

            ?>
        </section>

    </body>
</html>

