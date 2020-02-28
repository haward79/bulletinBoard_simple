<?php

    if(isset($_GET['filename']) && $_GET['filename']!='')
    {
        if(file_exists('upload/'.$_GET['filename']))  // File not found.
        {
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename='.$_GET['filename']);
            header('Content-Length: '.filesize('upload/'.$_GET['filename']));

            $fileOpener = fopen('upload/'.$_GET['filename'], 'rb');
            fpassthru($fileOpener);
            fclose($fileOpener);
        }
        else
        {
            header('Location: 404NotFound');
            exit(0);
        }
    }
    else
    {
        header('Location: index.php');
        exit(0);
    }

