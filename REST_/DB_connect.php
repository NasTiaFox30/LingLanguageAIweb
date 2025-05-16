<?php
    // połączenie ze serwerem
    $servername = "fdb1028.awardspace.net";
    $username = "4596523_linglanguageai";
    $password = "Linglanguage123890AI.";
    $dbname = "4596523_linglanguageai";

    $MySQLconection = new mysqli($servername, $username, $password, $dbname);

    if ($MySQLconection->connect_error){
        die("Connection failed: " . $MySQLconection->connect_error);
    }
?>