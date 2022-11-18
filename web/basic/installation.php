<?php 
function checkDatabase($dbhost, $dbuser, $dbpass, $dbname)
{
    $link = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Invalid database information");
    mysqli_select_db($link, $dbname) or die("Invalid database information");

    echo "Connected database";
}
?>