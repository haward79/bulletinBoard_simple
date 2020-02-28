<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('isLogin.php'); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>

<?php

    if(isset($_GET['id']) && isNotEmpty($_GET['id']) && is_numeric($_GET['id']))
    {}
    else
    {
        header('Location: 404');
        exit(0);
    }

    /*
     *  Send bulletin edited data.
     *  Confirmation flag is set to true : send updated bulletin data.
     *  Confirmation flag is NOT set to true : show edition page.
     */

    if(isset($_GET['confirm']) && $_GET['confirm']=='true')  // Send updated bulletin data.
    {
        if(isset($_POST['bulletin_field_bulletinType']))
        {
            // Check previous bulletin type.
            $dbRetrieve = mysqlQuery('SELECT `content` FROM `bulletin` WHERE `id`='.$_GET['id'].';');
            
            if(mysqli_num_rows($dbRetrieve) > 0)  // Bulletin id found.
            {
                $dbExtract = mysqli_fetch_row($dbRetrieve)[0];

                if(substr($dbExtract, 0, 1) == '2')  // Previous type is file.
                {
                    // Delete previous file.
                    $files = explode("\n", substr($dbExtract, 1));

                    for($i=0, $len=count($files)-1; $i<$len; ++$i)
                        if(file_exists('upload/'.$files[$i]))
                            unlink('upload/'.$files[$i]);
                }
            }
            else  // The bulletin id is invalid.
            {
                header('index.php');
                exit(0);
            }

            $bulletinMode = $_POST['bulletin_field_bulletinType'];
            $bulletinType = is_numeric(addslashes($_POST['bulletin_field_type'])) ? $_POST['bulletin_field_type'] : 3;

            // Update database.
            if($bulletinMode == "bulletin_field_bulletinType_1")
                mysqlQuery('UPDATE `bulletin` SET `title`=\''.addslashes($_POST['bulletin_field_title']).'\', `content`=\'1'.$_POST['bulletin_field_content'].'\', `type`='.$bulletinType.', `datetime`=NOW() WHERE `id`='.$_GET['id'].';');
            else if($bulletinMode == "bulletin_field_bulletinType_2")
            {
                // File fields are set.
                if(count($_FILES['bulletin_field_fileUrl']['name']) > 0)
                {
                    $filenameGroup = '';

                    // Deal each file.
                    for($i=0, $len=count($_FILES['bulletin_field_fileUrl']['name']); $i<$len; ++$i)
                    {
                        // File picked.
                        if($_FILES['bulletin_field_fileUrl']['name'][$i] != '')
                        {
                            // Get new filename.
                            $filename = date('YmdHis').'_'.$i.'_'.preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES['bulletin_field_fileUrl']['name'][$i]));
                            
                            // Add current file to file list.
                            $filenameGroup = $filenameGroup . $filename . "\n";
                            
                            // Move uploaded file.
                            move_uploaded_file($_FILES['bulletin_field_fileUrl']['tmp_name'][$i], 'upload/'.$filename);
                        }
                    }

                    // Update database.
                    mysqlQuery('UPDATE `bulletin` SET `title`=\''.addslashes($_POST['bulletin_field_title']).'\', `content`=\'2'.$filenameGroup.'\', `type`='.$bulletinType.', `datetime`=NOW() WHERE `id`='.$_GET['id'].';');
                }
            }
            else
                mysqlQuery('UPDATE `bulletin` SET `title`=\''.addslashes($_POST['bulletin_field_title']).'\', `content`=\'3'.$_POST['bulletin_field_content'].'\', `type`='.$bulletinType.', `datetime`=NOW() WHERE `id`='.$_GET['id'].';');
        }
        
        header('Location: index.php');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>編輯最新消息 - 南大輔導中心</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
        <link rel="stylesheet" href="css/bulletin_edit.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bulletin.js"></script>
    </head>
    <body>

        <header>
            <h1>編輯最新消息</h1>
        </header>

        <section>
            <form id="bulletinEdit_form" action="bulletin_edit.php?id=<?php echo $_GET['id']; ?>&confirm=true" method="post" enctype="multipart/form-data">
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
                                    <td colspan="3">該消息不存在喔！</td>
                                </tr>
                                '."\n";
                            }
                            else  // Bulletin exists.
                            {
                                // Fetch and print basic info: title, type, datetime.
                                $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                                echo
                                '
                                <tr></tr>
                                <tr>
                                    <td><input class="bulletin_input_textbox" name="bulletin_field_title" value="'.toHtml($dbExtract['title']).'" placeholder="公告標題" required /></td>
                                    <td class="bulletin_column_type">
                                        <select class="bulletin_input_selection" name="bulletin_field_type">
                                ';

                                // Print type selection box.
                                for($i=1; $i<=BulletinType::$kMaxType; ++$i)
                                {
                                    if((int)$dbExtract['type'] == $i)
                                        echo '<option value="'.$i.'" selected>'.BulletinType::numberToName($i).'</option>';
                                    else
                                        echo '<option value="'.$i.'">'.BulletinType::numberToName($i).'</option>';
                                }

                                echo '
                                        </select>
                                    </td>
                                    <td class="bulletin_column_datetime">自動更新</td>
                                </tr>
                                '."\n";

                                // Print content.
                                $ident = substr($dbExtract['content'], 0, 1);
                                echo '
                                <tr>
                                    <td colspan="3" style="min-width:750px; max-width:750px;">
                                        <!-- Mode selection. -->
                                        類型：
                                        <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_1" '.(($ident=='1')?'checked':'').' />連結
                                        <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_2" '.(($ident=='2')?'checked':'').' />檔案
                                        <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_3" '.(($ident=='3')?'checked':'').' />文字
                                        
                                        <br />

                                        <!-- Link and text. -->
                                        <textarea '.(($ident=='2')?'style="display:none;"':'').' class="bulletin_input_textarea" id="bulletin_field_content" name="bulletin_field_content" placeholder="內容">'.substr($dbExtract['content'], 1).'</textarea>
                                        
                                        <!-- File. -->
                                        <div style="display:none;" id="bulletin_field_fileUrl_container">
                                            <input style="margin:5px 0px; width:100%;" name="bulletin_field_fileUrl[]" type="file" value="" />
                                        </div>
                                        <input style="display:none;" id="bulletin_field_addFile" type="button" value="新增檔案" />
                                    </td>
                                </tr>
                                '."\n";

                                if($ident == '2')
                                {
                                    echo '
                                    <script>
                                        $(\'#bulletin_field_fileUrl_container\').show();
                                        $(\'#bulletin_field_addFile\').show();
                                        $(\'#bulletin_field_content\').hide();
                                    </script>
                                    '."\n";
                                }

                                // Print action buttons.
                                echo '
                                <tr>
                                    <td class="" colspan="3">
                                '."\n";

                                        echo '
                                            <input class="input_button input_button_withIcon" type="submit" value="儲存修改" style="background-image:url(\'image/icon_confirm_yes.png\');" />
                                            <input class="input_button input_button_withIcon" type="button" value="取消修改" onClick="window.location=\'index.php\';" style="background-image:url(\'image/icon_confirm_no.png\');" />
                                        '."\n";

                                echo '
                                    </td>
                                </tr>
                                '."\n";
                            }

                        ?>
                    </tbody>
                </table>
            </form>
        </section>

        <footer></footer>

    </body>
</html>

    