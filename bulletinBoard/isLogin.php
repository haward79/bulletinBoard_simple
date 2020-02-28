<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/string.php'); ?>
<?php

    /*
     *  Check authority.
     *  Logged in : no operation.
     *  Not logged in : redirect to unauth login page and terminate current script.
     */

    if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
    {}
    else  // Not logged in.
    {
        // Clear login session.
        if(isset($_SESSION['username']))
            unset($_SESSION['username']);

        // Redirect to unauth login page.
        header('Location: login.php?unauth=true');
        exit(0);
    }

