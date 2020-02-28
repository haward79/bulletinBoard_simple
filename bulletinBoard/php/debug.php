<?php

    /*  When this value goes true, error reporting in details is enabled.
        When this value goes false, only report error in short.            */
    $kErrorReport = true;

    function fatalErrorReport($errorDescription)
    {
        global $kErrorReport;

        if($kErrorReport)
            echo 'Fatal error: '.$errorDescription;
        else
            echo 'Fatal error occured. Program terminated.';

        exit(0);
    }

    function warnErrorReport($errorDescription)
    {
        global $kErrorReport;

        if($kErrorReport)
            echo 'Warnning: '.$errorDescription;
    }

