<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('isLogin.php'); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>

<?php

    /*
     *  Send new bulletin data.
     *  Confirmation flag is set to true : send new bulletin data.
     *  Confirmation flag is NOT set to true : show add bulletin fileds page.
     */

    if(isset($_GET['confirm']) && $_GET['confirm']=='true')  // Send updated bulletin data.
    {
        if(isset($_POST['bulletin_field_bulletinType']))
        {
            $bulletinMode = $_POST['bulletin_field_bulletinType'];
            $bulletinType = is_numeric(addslashes($_POST['bulletin_field_type'])) ? $_POST['bulletin_field_type'] : 3;

            // Update database.
            if($bulletinMode == "bulletin_field_bulletinType_1")
                mysqlQuery('INSERT INTO `bulletin` (`title`, `content`, `type`, `datetime`) VALUES (\''.addslashes($_POST['bulletin_field_title']).'\', \'1'.$_POST['bulletin_field_content'].'\', '.$bulletinType.', NOW());');
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
                    mysqlQuery('INSERT INTO `bulletin` (`title`, `content`, `type`, `datetime`) VALUES (\''.addslashes($_POST['bulletin_field_title']).'\', \'2'.$filenameGroup.'\', '.$bulletinType.', NOW());');
                }
            }
            else
                mysqlQuery('INSERT INTO `bulletin` (`title`, `content`, `type`, `datetime`) VALUES (\''.addslashes($_POST['bulletin_field_title']).'\', \'3'.$_POST['bulletin_field_content'].'\', '.$bulletinType.', NOW());');
        }

        header('Location: index.php');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>新增最新消息</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
        <link rel="stylesheet" href="css/bulletin_edit.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bulletin.js"></script>
    </head>
    <body>

        <header>
            <h1>新增最新消息</h1>
        </header>

        <section>
            <form id="bulletinEdit_form" action="bulletin_add.php?confirm=true" method="post" enctype="multipart/form-data">
                <table class="bulletin_table bulletin_table_zebraTexture">
                    <thead>
                        <tr>
                            <th>標題</th>
                            <th class="bulletin_column_type">類型</th>
                            <th class="bulletin_column_datetime">發布日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                        <tr>
                            <td><input class="bulletin_input_textbox" name="bulletin_field_title" value="" placeholder="公告標題" required /></td>
                            <td class="bulletin_column_type">
                                <select class="bulletin_input_selection" name="bulletin_field_type">

                                <?php

                                    // Print type selection box option.
                                    for($i=1; $i<=BulletinType::$kMaxType; ++$i)
                                        echo '<option value="'.$i.'">'.BulletinType::numberToName($i).'</option>';

                                ?>

                                </select>
                            </td>
                            <td class="bulletin_column_datetime">自動產生</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="min-width:750px; max-width:750px;">
                                <!-- Mode selection. -->
                                類型：
                                <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_1" checked />連結
                                <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_2" />檔案
                                <input name="bulletin_field_bulletinType" type="radio" value="bulletin_field_bulletinType_3" />文字
                                
                                <br />

                                <!-- Link and text. -->
                                <textarea class="bulletin_input_textarea" id="bulletin_field_content" name="bulletin_field_content" placeholder="內容"></textarea>

                                <!-- File. -->
                                <div style="display:none;" id="bulletin_field_fileUrl_container">
                                    <input style="margin:5px 0px; width:100%;" name="bulletin_field_fileUrl[]" type="file" value="" />
                                </div>
                                <input style="display:none;" id="bulletin_field_addFile" type="button" value="新增檔案" />
                            </td>
                        </tr>
                        <tr>
                            <td class="" colspan="3">
                                <input class="input_button input_button_withIcon" type="submit" value="確定新增" style="background-image:url('image/icon_confirm_yes.png');" />
                                <input class="input_button input_button_withIcon" type="button" value="放棄新增" onClick="window.location='index.php';" style="background-image:url('image/icon_confirm_no.png');" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>

    </body>
</html>

    