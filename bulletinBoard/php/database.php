<?php require_once('debug.php'); ?>

<?php

    function mysqlQuery($sqlSyntax)
    {

        $dbCon = mysqli_connect('localhost', 'username', 'password', 'bulletinBoard');

        if($dbCon === false)
        {
            fatalErrorReport('Database error.');
            exit(0);
        }

        mysqli_query($dbCon, 'SET NAMES UTF8;');
        $dbRetrieve = mysqli_query($dbCon, $sqlSyntax);

        mysqli_close($dbCon);

        if($dbRetrieve === false)
        {
            fatalErrorReport('SQL query error occurred.');
            exit(0);
        }

        return $dbRetrieve;

    }

