<?php
    session_start();
    session_destroy();
    echo "You have successfully logged out";
    header("Location:Index.php");
?>