<?php
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
    session_start();
    if(isset($_SESSION["user_id_session"])){
        header("Location:display_blog.php?user_id=$user_id");
    }
    else{
        header("Location:login_user.php");
    }

?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <h3>Welcome to Invennico Blog System</h3>

    </body>
</html>
