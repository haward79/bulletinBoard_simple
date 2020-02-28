<?php

    abstract class ConfirmType
    {
        static public $ok = 0;
        static public $yesNo = 1;
    }

    $msg_pageTitle = '';
    $msg_title = '';
    $msg_content = '';
    $msg_type = ConfirmType::$ok;

    