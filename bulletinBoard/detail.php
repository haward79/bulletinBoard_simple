<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>最新消息詳情 - 南大輔導中心</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
    </head>
    <body>

        <header>
            <h1>最新消息詳情</h1>
        </header>

        <section>
            <table class="bulletin_table bulletin_table_zebraTexture">
                <thead>
                    <tr>
                        <th>標題</th>
                        <th class="bulletin_column_type">類型</th>
                        <th class="bulletin_column_datetime">發布日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        /*
                         *  Retrieve bulletin from database and show on page.
                         */
                    
                        // Retrieve bulletin.
                        $dbRetrieve = mysqlQuery('SELECT * FROM `bulletin` WHERE `id`='.$_GET['id'].' LIMIT 1;');

                        if(mysqli_num_rows($dbRetrieve) == 0)  // Bulletin not exists.
                        {
                            echo
                            '
                            <tr>
                                <td colspan="3">該公告不存在喔！</td>
                            </tr>
                            '."\n";
                        }
                        else  // Bulletin exists.
                        {
                            // Print basic info: title, type, datetime.
                            $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                            echo
                            '
                            <tr></tr>
                            <tr>
                                <td>'.toHtml($dbExtract['title']).'</td>
                                <td class="bulletin_column_type">'.BulletinType::numberToName($dbExtract['type']).'</td>
                                <td class="bulletin_column_datetime">'.explode(' ', $dbExtract['datetime'])[0].'</td>
                            </tr>
                            '."\n";

                            // Print content.
                            if(strlen($dbExtract['content']) <= 1)  // Destroyed bulletin.
                            {
                                // Delete this bulletin and redirect to index.
                                mysqlQuery('DELETE FROM `bulletin` WHERE `id`='.$_GET['id'].';');
                                header('Location: index.php');
                                exit(0);
                            }
                            else
                            {
                                $ident = substr($dbExtract['content'], 0, 1);
                                $realContent = substr($dbExtract['content'], 1);
                                
                                if($ident == '1')  // This bulletin content is a link.
                                {
                                    header('Location: '.addslashes($realContent));
                                    exit(0);
                                }
                                else if($ident == '2')  // This bulletin content is an file or image.
                                {
                                    $files = explode("\n", $realContent);

                                    echo '
                                    <tr>
                                        <td colspan="3">
                                            共有 ' . (count($files) - 1) . ' 個檔案。<br />
                                    '."\n";

                                    for($i=0, $len=count($files)-1; $i<$len; ++$i)
                                    {
                                        echo '<br />第 ' . ($i + 1) . ' 個檔案：' . "\n";

                                        // Show image.
                                        if(endsWith($files[$i], '.jpg', false) || endsWith($files[$i], '.jpeg', false) || endsWith($files[$i], '.gif', false) || endsWith($files[$i], '.png', false) || endsWith($files[$i], '.bmp', false))
                                            echo '<br /><img style="width:100%;" src="download.php?filename='.urldecode($files[$i]).'" alt="公告說明圖片" /><br />'."\n";
                                        else  // Download file.
                                            echo '<a href="download.php?filename=/'.urlencode($files[$i]).'" target="_blank">'.toHtml($dbExtract['title']).'</a><br />';
                                    }

                                    echo '
                                        </td>
                                    </tr>
                                    '."\n";
                                }
                                else  // This bulletin content is a text.
                                {
                                    echo '
                                    <tr>
                                        <td colspan="3">'.markAnchor(toHtml($realContent)).'</td>
                                    </tr>
                                    '."\n";
                                }

                                // Print action buttons.
                                echo '
                                <tr>
                                    <td class="" colspan="3">
                                '."\n";

                                        echo '
                                            <input class="input_button input_button_withIcon" type="button" value="回上頁" onClick="window.location=\'index.php\';" style="background-image:url(\'image/icon_goBack.png\');" />
                                        '."\n";

                                echo '
                                    </td>
                                </tr>
                                '."\n";
                            }
                        }

                    ?>
                </tbody>
            </table>
        </section>

    </body>
</html>

