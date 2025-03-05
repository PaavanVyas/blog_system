<?php
    include './conn.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
    ></script>

    <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
    ></script>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="style.css"></link>
</head>
<body>
    
    
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
  <a class="navbar-brand" href="index.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id);} ?>">
  <img src="./images/invennico_logo-removebg.PNG" alt="Retailer" style="height: 40px;">
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">  
        <li class="nav-item">
          <a class="nav-link" href="display_blog.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id);} ?>">Blogs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="display_my_blog.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id);} ?>">My Blogs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create_blog.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id);} ?>">Create New Blog</a>
        </li>

        <?php if(isset($_SESSION['user_id_session'])) { ?> 
        <li class="nav-item">
          <a class="nav-link" href="logout.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id); }?>">Logout</a>
        </li>
        <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" href="register_user.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id); }?>">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login_user.php?user_id=<?php if(isset($user_id)){echo htmlspecialchars($user_id); }?>">Login</a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
    <div class="div-design-register mt-5 container">
    <form action="login.php" method="POST" id="myForm">
        <div class="mb-1 form-group" style="margin-top:8%;">
            <label class="form-label position-relative" for="email"><b>Username or Email:</b></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com"> 
            <small id="email_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group password-container" style="margin-top:5%;">
            <label class="form-label" for="password"><b>Password</b></label>
            <input type="password" name="password" id="password" class="form-control">
            <button type="button" class="toggle-password" onclick="togglePassword()"><img id="toggleIcon" src="./images/icons8-eye-24.PNG" alt="Show"></button>
            <small id="password_helpid" class="text-muted" hidden>Required</small>
        </div>
        <div class="mb-1 form-group ms-2">
                <p><a href="forgot_password.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-0-hover">Forgot Password</a><p>
        </div>
        <div class="mb-1 form-group">
            <center><input type="submit" value="Log In" class="form-control themecolor button-design-login-register"></center>
        </div>
        <div class="create-account-div">
            <hr _ngcontent-ng-c3257282837="">
        </div>
        
        <div class="mb-1 form-group create-account-div">
            <center><a href="register_user.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-100 link-underline-opacity-75-hover"><center>Create a Invennico Account</center></a></center>
        </div>
    </form>
</div>
</div>
</div>
</body>
</html>

<script>
     $(document).ready(function () {
            $("#myForm").submit(function (e) {
                e.preventDefault(); 

                let isValid = 0;

                let email = $("#email").val().trim();
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com)$/;
                if (!emailRegex.test(email)) {
                    $("#emailError").text("Enter a valid email ending with .com");
                    isValid++;
                    $('#email').addClass("border-danger");
                    $('#email_helpid').removeAttr("hidden");
                    // alert("Enter a valid email ending with .com")
                } else {
                    $("#emailError").text("");
                    $('#email_helpid').attr("hidden",true);
                    $('#email').removeClass("border-danger");
                }

                let password = $("#password").val().trim();
                if (password.length < 6) {
                    $("#passwordError").text("Password must be at least 6 characters");
                    isValid++;
                    $('#password').addClass("border-danger");
                    $('#password_helpid').removeAttr("hidden");
                    // alert("password must be at least 6 characters")
                } else {
                    $("#passwordError").text("");
                    $('#password_helpid').attr("hidden",true);
                    $('#password').removeClass("border-danger");
                }

                if (isValid==0) {
                    this.submit();
                }
                else{
                    alert("You have not entered all the details or the entered details are incorrect check the red input box");
                }
            });
        });

        function togglePassword() {
            let passwordField = document.getElementById("password");
            let toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.src = "./images/icons8-eye-24.PNG"; 
                toggleIcon.alt = "Hide";
            } else {
                passwordField.type = "password";
                toggleIcon.src = "./images/icons8-invisible-24.PNG";
                toggleIcon.alt = "Show";
            }
        }
        </script>
