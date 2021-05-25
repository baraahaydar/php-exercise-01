<?php
    session_start();
    $fullName = $username = $password = $confirmPassword = $email = $phone = $dateOfBirth = $securityNumber = "";

    $fullNameError = $usernameError = $passwordError = $confirmPasswordError = $emailError = $phoneError = $dateOfBirthError = $securityNumberError = "";

    $fullNameErrorClass = $usernameErrorClass = $passwordErrorClass = $confirmPasswordErrorClass = $emailErrorClass = $phoneErrorClass = $dateOfBirthErrorClass = $securityNumberErrorClass = "";

    $usernameLogin = $passwordLogin = "";

    $usernameLoginError = $passwordLoginError = "";

    $usernameLoginErrorClass = $passwordLoginErrorClass = "";

    $loginText = "";

    if(!isset($_SESSION['id'])) {
        $_SESSION['id'] = 1;
    }

    if(!isset($_SESSION['users'])) {
        $_SESSION['users'] = array();
    }

    if(isset($_SESSION['goneBack'])) {
        $loginText = $_SESSION['goneBack'];
        unset($_SESSION['goneBack']);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['register'])) {
            $validate = true;
            if(empty($_POST['fullName'])) {
                $fullNameError = "Full name is required";
                $fullNameErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $fullName = filterData($_POST['fullName']);
                if (!preg_match("/^[a-zA-Z-' ]*$/",$fullName)) {
                    $fullNameError = "Incorrect name";
                    $fullNameErrorClass = " form-control__error";
                    $validate = false;
                }
            }
    
            if(empty($_POST['username'])) {
                $usernameError = "Username is required";
                $usernameErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $username = filterData($_POST['username']);
                if(isset($_SESSION['users'])) {
                    foreach($_SESSION['users'] as $user) {
                        if($user['username'] == $username) {
                            $validate = false;
                            $usernameError = "Username already exists";
                            $usernameErrorClass = " form-control__error";
                        }
                    }    
                }
            }
    
            if(empty($_POST['password'])) {
                $passwordError = "Password is required";
                $passwordErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $password = filterData($_POST['password']);
            }
    
            if(empty($_POST['confirmPassword'])) {
                $confirmPasswordError = "Confirm password is required";
                $confirmPasswordErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $confirmPassword = filterData($_POST['confirmPassword']);
                if($password != $confirmPassword) {
                    $passwordError = "Password doesn't match";
                    $passwordErrorClass = " form-control__error";
                    $confirmPasswordErrorClass = " form-control__error";
                    $validate = false;
                }
            }
    
            if(empty($_POST['email'])) {
                $emailError = "Email is required";
                $emailErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $email = filterData($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailError = "Invalid email format";
                    $emailErrorClass = " form-control__error";
                    $validate = false;
                }
                if(isset($_SESSION['users'])) {
                    foreach($_SESSION['users'] as $user) {
                        if($user['email'] == $email) {
                            $validate = false;
                            $emailError = "Email already exists";
                            $emailErrorClass = " form-control__error";
                        }
                    }    
                }
            }
    
            if(empty($_POST['phone'])) {
                $phoneError = "Phone is required";
                $phoneErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $phone = filterData($_POST['phone']);
            }
    
            if(empty($_POST['dateOfBirth'])) {
                $dateOfBirthError = "Date of birth is required";
                $dateOfBirthErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $dateOfBirth = filterData($_POST['dateOfBirth']);
            }
    
            if(empty($_POST['securityNumber'])) {
                $securityNumberError = "Social security number is required";
                $securityNumberErrorClass = " form-control__error";
                $validate = false;
            }
            else {
                $securityNumber = filterData($_POST['securityNumber']);
            }
    
            if($validate) {
                $user = array(
                    "id" => $_SESSION['id'],
                    "fullName" => $fullName,
                    "username" => $username,
                    "password" => $password,
                    "email" => $email,
                    "phone" => $phone,
                    "dateOfBirth" => $dateOfBirth,
                    "securityNumber" => $securityNumber
                );
                $_SESSION['id']++;
                array_push($_SESSION['users'], $user); 
            }
        }

        if(isset($_POST['login'])) {
            $validateLogin = true;
            if(empty($_POST['username'])) {
                $usernameLoginError = "Username is required";
                $usernameLoginErrorClass = " form-control__error";
                $validateLogin = false;
            }
            else {
                $usernameLogin = filterData($_POST['username']);
            }
    
            if(empty($_POST['password'])) {
                $passwordLoginError = "Password is required";
                $passwordLoginErrorClass = " form-control__error";
                $validateLogin = false;
            }
            else {
                $passwordLogin = filterData($_POST['password']);
            }
            if(isset($_SESSION['users']) && $validateLogin) {
                $usernameCheck = false;
                $passwordCheck = false;
                foreach($_SESSION['users'] as $user) {
                    if($user['username'] == $usernameLogin) {
                        $usernameCheck = true;
                        if($user['password'] == $passwordLogin) {
                            $passwordCheck = true;
                            break;
                        }
                    }
                }
                if($usernameCheck && $passwordCheck) {
                    $_SESSION['welcome'] = $usernameLogin;
                    $_SESSION['token'] = 123123;
                    header('Location: safe.php');
                }
                if($usernameCheck && !$passwordCheck) {
                    $loginText = "Wrong password";
                }
                if(!$usernameCheck){
                    $loginText = "Username $usernameLogin doesn't exist";
                }
            }
        }
    }

    
    function filterData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h1 class="header">Registration Form</h1>
            <h2 class="header"><?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if($validate) { echo "User $username was registered"; } }  ?></h2>
            <form method="post" action=
            "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-wrapper">

                <div class="form-group">
                    
                    <p class="error"><?php echo $fullNameError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $fullName; else echo ""; } ?>"
                     type="text" class="form-control<?php echo $fullNameErrorClass ?>" name="fullName" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $usernameError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $username; else echo ""; } ?>"
                     type="text" class="form-control<?php echo $usernameErrorClass ?>" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $passwordError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $password; else echo ""; } ?>"
                     type="password" class="form-control<?php echo $passwordErrorClass ?>" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $confirmPasswordError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $confirmPassword; else echo ""; } ?>"
                     type="password" class="form-control<?php echo $confirmPasswordErrorClass ?>" name="confirmPassword" placeholder="Confirm Password">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $emailError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $email; else echo ""; } ?>"
                     type="email" class="form-control<?php echo $emailErrorClass ?>" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $phoneError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $phone; else echo ""; } ?>"
                     type="tel" class="form-control<?php echo $phoneErrorClass ?>" name="phone" placeholder="Phone">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $dateOfBirthError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $dateOfBirth; else echo ""; } ?>"
                     type="text" class="form-control<?php echo $dateOfBirthErrorClass ?>" name="dateOfBirth" placeholder="dd / mm / yyyy">
                </div>
                <div class="form-group">
                    <p class="error"><?php echo $securityNumberError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validate)) { if(!$validate) echo $securityNumber; else echo ""; } ?>"
                     type="text" class="form-control<?php echo $securityNumberErrorClass ?>" name="securityNumber" placeholder="Social Security Number">
                </div>
                <div class="form-group">
                    <button type="submit" name="register" class="btn">Register</button>
                </div>

                <div class="form-group">
                    <button type="reset" class="btn">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="wrapper">
        <div class="container">
            <h1 class="header">Login Form</h1>
            <h2 class="header"><?php if($_SERVER['REQUEST_METHOD'] == "POST" || isset($loginText)) echo $loginText; ?></h2>
            <form method="post" action="" class="form-wrapper">
                <div class="form-group">
                    <p class="error"><?php echo $usernameLoginError; ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validateLogin)) { if(!$validateLogin) echo $usernameLogin; else echo ""; } ?>"
                     type="text" class="form-control<?php echo $usernameLoginErrorClass; ?>" name="username" placeholder="Username">
                </div>

                <div class="form-group">
                    <p class="error"><?php echo $passwordLoginError ?></p>
                    <input value="<?php if($_SERVER['REQUEST_METHOD'] == "POST" && isset($validateLogin)) { if(!$validateLogin) echo $passwordLogin; else echo ""; } ?>"
                     type="password" class="form-control<?php echo $passwordLoginErrorClass; ?>" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <button type="submit" name="login" class="btn">Login</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>