<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php

    /*
     *  Clear login session.
     */

    // Clear login session.
    unset($_SESSION['username']);

    header('Location: index.php');

