<?php
   $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css"></link>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User Registration</title>
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

        $user_register_status = filter_var($_GET['email_exists'],FILTER_SANITIZE_STRING); 
        
        if($user_register_status==='true') {
            $register_status = "danger";
            $register_message = "Email already exists";
            

        } else {
            $register_status = "danger";
            $register_message = "We are facing some issue please try again later.";
        }
        ?>
        <div
            class="alert alert-<?=$register_status?> alert-dismissible fade show"
            role="alert"
        >
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
            <strong><?=($register_status==='success')?'Success!':'Error!'?></strong> <?=$register_message?>
        </div>
        
        <script>
            var alertList = document.querySelectorAll(".alert");
            alertList.forEach(function (alert) {
                new bootstrap.Alert(alert);
            });
        </script>
    <?php }?> 
    <div class="img-create-blog">
        <img src="./images/invennico_logo-removebg.PNG" alt="Retailer" class="img-fluid">  
    </div>  
<div class="container form-container div-design">
<div class="div-design-register">

<form action="create_new_complaint.php" method="POST" name="create-blog" id="myForm">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
    <div class="d-flex justify-content-between">
        <div>    
            <h3 class="register-text-style">Report a Complaint</h3>
        </div>   
    </div>

        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="complainttitle">Complaint Title:</label>
            <input type="complainttitle"id="complainttitle" name="complainttitle"class="form-control w-100">
            <small id="complainttitle_helpid" class="text-muted" hidden>Required</small> 
            
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="complaintdescription">Complaint Description:</label>
            <textarea type="complaintdescription" id="complaintdescription" name="complaintdescription" class="form-control w-100" ></textarea> 
            <small id="complaintdescription_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group">
                <input type="submit" value="Create Blog" class="form-control themecolor button-design-login-register">
        </div>
        <div>
                <a href="display_my_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary btn-display">Go Back</a> 
    </div>
        </div>
    </form>
        </div>
</div>

</body>
</html>
<script>
$(document).ready(function () {
    $("#myForm").submit(function (e) {
        e.preventDefault();  

        let isValid = true; 
    let complainttitle = $("#complainttitle").val().trim();
        if (complainttitle.length < 3) {
            $("#complainttitleError").text("complainttitle must be at least 3 characters");
            isValid = false;
            $('#complainttitle').addClass("border-danger");
            $('#complainttitle_helpid').removeAttr("hidden");
        } else {
            $("#complainttitleError").text("");
            $('#complainttitle_helpid').attr("hidden", true);
            $('#complainttitle').removeClass("border-danger");
        }

        let complaintdescription = $("#complaintdescription").val().trim();
        if (complaintdescription.length < 3) {
            $("#complaintdescriptionError").text("complaintdescription must be at least 3 characters");
            isValid = false;
            $('#complaintdescription').addClass("border-danger");
            $('#complaintdescription_helpid').removeAttr("hidden");
        } else {
            $("#complaintdescriptionError").text("");
            $('#complaintdescription_helpid').attr("hidden", true);
            $('#complaintdescription').removeClass("border-danger");
        }
        if (isValid === true) {
                        // Submit the form
                        $("#myForm").off("submit").submit();  // Submit the form
                    } else {
                        alert("You have not entered all the details or the entered details are incorrect. Please check the red input box.");
                    }
    })
})  
</script>