<?php
   $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User Registration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>




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
    <div  class="img-navbar">
    <a href="index.php?user_id=<?php echo $user_id; ?>">
        <img src="./images/invennico_logo-removebg.PNG" alt="Retailer"></img>
    </a>
    <a class="navbar-brand" href="display_blog.php?user_id=<?php echo $user_id; ?>">Blogs</a>
    <a class="navbar-brand" href="display_my_blog.php?user_id=<?php echo $user_id; ?>">My Blogs</a>
    <a class="navbar-brand" href="create_blog.php?user_id=<?php echo $user_id; ?>">Create New Blog</a>
    <?php
    session_start();
    if(isset($_SESSION['user_id_session'])) { 
        $user_id = $_SESSION['user_id_session'];
    ?> 
    <a class="navbar-brand" href="logout.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Logout</a>
    <?php 
    }
    else{
    ?>
    <a class="navbar-brand" href="register_user.php?user_id=<?php echo $user_id; ?>">Register</a>
    <a class="navbar-brand" href="login_user.php?user_id=<?php echo $user_id; ?>">Login</a>
        
    <?php }?>
    </div>
        </div>
      </div>
</nav>
<?php
if(isset($_GET['email_exists'])) {
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
    <div class="img-register">
        <img src="./images/invennico_logo-removebg.PNG" alt="Retailer">
    </div>
<div class="container form-container div-design">
    <form action="register.php" method="POST" name="registration" id="myForm" enctype="multipart/form-data">
    <div class="div-design-register">
    <div class="d-flex justify-content-between">
        <div>    
            <h3 class="register-text-style">Sign Up</h3>
        </div> 
        <div>
           <a href="login_user.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-0-hover">I have an account</a>
        </div>   
    </div>

        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="firstname">First Name:</label>
            <input type="firstname"id="firstname" name="firstname"class="form-control w-100">
            <small id="firstname_helpid" class="text-muted" hidden>Required</small> 
            
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="lastname">Last Name:</label>
            <input type="lastname" id="lastname" name="lastname" class="form-control w-100" > 
            <small id="lastname_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="lastname">Username:</label>
            <input type="username" id="username" name="username" class="form-control w-100" > 
            <small id="username_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="country">Country:</label>
            <select class="form-select w-100" autocomplete="country" id="country" name="country">
                
                <option value="">country</option>
                <option value="Afghanistan">Afghanistan</option>
                <option value="Åland Islands">Åland Islands</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antarctica">Antarctica</option>
                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia (Plurinational State of)">Bolivia (Plurinational State of)</option>
                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Bouvet Island">Bouvet Island</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                <option value="Brunei Darussalam">Brunei Darussalam</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cabo Verde">Cabo Verde</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Caribbean Netherlands">Caribbean Netherlands</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaçao">Curaçao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Eswatini (Swaziland)">Eswatini (Swaziland)</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Territories</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guernsey">Guernsey</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-Bissau">Guinea-Bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jersey">Jersey</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea, North">Korea, North</option>
                <option value="Korea, South">Korea, South</option>
                <option value="Kosovo">Kosovo</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macao">Macao</option>
                <option value="Macedonia North">Macedonia North</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia">Micronesia</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montenegro">Montenegro</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar (Burma)">Myanmar (Burma)</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antilles</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn Islands">Pitcairn Islands</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russian Federation">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Barthelemy">Saint Barthelemy</option>
                <option value="Saint Helena">Saint Helena</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                <option value="Saint Lucia">Saint Lucia</option>
                <option value="Saint Martin">Saint Martin</option>
                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Serbia">Serbia</option>
                <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Sint Maarten">Sint Maarten</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                <option value="South Sudan">South Sudan</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Timor-Leste">Timor-Leste</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey (Türkiye)">Turkey (Türkiye)</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="U.S. Outlying Islands">U.S. Outlying Islands</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City Holy See">Vatican City Holy See</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands, British">Virgin Islands, British</option>
                <option value="Virgin Islands, U.S">Virgin Islands, U.S</option>
                <option value="Wallis and Futuna">Wallis and Futuna</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Yemen">Yemen</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
            </select>
            <small id="country_helpid" class="text-muted" hidden>Required</small>
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="email">Email:</label>
            <input type="email"id="email" name="email"class="form-control w-100" > 
            <small id="email_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="image">Profile Photo:</label>
            <input type="file" id="image" name="image" class="form-control w-100"> 
            <small id="email_helpid" class="text-muted" hidden>Required</small> 
        </div>
        <div class="mb-1 form-group password-container">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control w-100">
            <button type="button" class="toggle-password" onclick="togglePassword()"><img id="toggleIcon" src="./images/icons8-eye-24.PNG" alt="Show"></button>

            <small id="password_helpid" class="text-muted" hidden>Required</small>
        </div>
        <div class="mb-1 form-group mt-1">
                <center><input type="submit" class="form-control w-100 themecolor button-design-login-register" value="Register"></center>
        </div>
                <div class="mb-1 form-group ms-2 register-footer-text">
            <p>This site is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.<br>You also agree to receive product-related marketing emails from Designership, which you can unsubscribe from at any time.</p>
        </div>
        </div>
    </form>
</div>
</body>
</html>
<script>
$(document).ready(function () {
    $("#myForm").submit(function (e) {
        e.preventDefault();  

        let isValid = true;  // Flag to check if the form is valid

        // Form validation for each field
        let firstname = $("#firstname").val().trim();
        if (firstname.length < 3) {
            $("#firstnameError").text("Firstname must be at least 3 characters");
            isValid = false;
            $('#firstname').addClass("border-danger");
            $('#firstname_helpid').removeAttr("hidden");
        } else {
            $("#firstnameError").text("");
            $('#firstname_helpid').attr("hidden", true);
            $('#firstname').removeClass("border-danger");
        }

        let lastname = $("#lastname").val().trim();
        if (lastname.length < 3) {
            $("#lastnameError").text("Lastname must be at least 3 characters");
            isValid = false;
            $('#lastname').addClass("border-danger");
            $('#lastname_helpid').removeAttr("hidden");
        } else {
            $("#lastnameError").text("");
            $('#lastname_helpid').attr("hidden", true);
            $('#lastname').removeClass("border-danger");
        }

        let email = $("#email").val().trim();
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com)$/;
        if (!emailRegex.test(email)) {
            $("#emailError").text("Enter a valid email ending with .com");
            isValid = false;
            $('#email').addClass("border-danger");
            $('#email_helpid').removeAttr("hidden");
        } else {
            $("#emailError").text("");
            $('#email_helpid').attr("hidden", true);
            $('#email').removeClass("border-danger");
        }

        let password = $("#password").val().trim();
        if (password.length < 6) {
            $("#passwordError").text("Password must be at least 6 characters");
            isValid = false;
            $('#password').addClass("border-danger");
            $('#password_helpid').removeAttr("hidden");
        } else {
            $("#passwordError").text("");
            $('#password_helpid').attr("hidden", true);
            $('#password').removeClass("border-danger");
        }

        let country = $("#country").val().trim().toLowerCase();
        if (country === "country" || country === "") {
            $("#countryError").text("Please select a valid country");
            isValid = false;
            $('#country').addClass("border-danger");
            $('#country_helpid').removeAttr("hidden");
        } else {
            $("#countryError").text("");
            $('#country_helpid').attr("hidden", true);
            $('#country').removeClass("border-danger");
        }

        // If all form fields are valid, proceed with username check via AJAX
        if (isValid) {
            let username = $("#username").val().trim();
            
            // AJAX request to check if username is available
            $.ajax({
                url: "check_username.php",  // PHP file to check username
                type: "POST",
                data: { username: username },
                success: function(response) {
                    // Debug: Log the full response to verify its structure
                    console.log("AJAX Response:", response);

                    // Parse the JSON response properly
                    try {
                        response = JSON.parse(response); // Ensure response is parsed as JSON
                    } catch (error) {
                        console.log("Error parsing response:", error);
                        return;
                    }

                    // Now, check if the 'exists' field is present in the response
                    if (response && response.exists !== undefined) {
                        if (response.exists === true) {
                            console.log("Username exists in the database");
                            $("#usernameError").text("Username is already taken");
                            $("#username").addClass("border-danger");
                            isValid = false;
                        } else if (response.exists === false) {
                            console.log("Username does not exist in the database");
                            $("#usernameError").text("");  // Clear error message
                            $("#username").removeClass("border-danger");
                        }
                    } else {
                        console.log("Response structure is incorrect or empty.");
                        isValid = false;
                    }

                    // After AJAX request is complete, check if the form is valid
                    if (isValid === true) {
                        // Submit the form
                        $("#myForm").off("submit").submit();  // Submit the form
                    } else {
                        alert("You have not entered all the details or the entered details are incorrect. Please check the red input box.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error in AJAX request:", status, error);
                    $("#usernameError").text("Error checking username");
                    isValid = false;
                }
            });
        } else {
            alert("You have not entered all the details or the entered details are incorrect. Please check the red input box.");
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