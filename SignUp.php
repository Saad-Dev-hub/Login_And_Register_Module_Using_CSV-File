<?php
session_start();
include_once 'auth/verified.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
if ($_POST) {
    $errors = [];
    // check for name input
    if ($_POST['name'] == '') {
        $errors['empty'] = "<div class='alert alert-danger'>This Field Can't Be Blank</div>";
    }
    if ($_POST['name'] != strip_tags($_POST['name'])) {
        $errors['invalid-string'] = "<div class='alert alert-danger'>Please Enter valid Name</div>";
    }
    if ((strlen($_POST['name']) < 3 ||  strlen($_POST['name']) > 20 and ($_POST['name']) != '')) {
        $errors['wrong-lenght'] = "<div class='alert alert-danger'>Your Name must be greater than 3 letters and less than 20 letters </div>";
    }
    if (is_numeric($_POST['name'])) {
        $errors['string'] = "<div class='alert alert-danger'> Your name must be string</div>";
    }
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
    if (preg_match($pattern, $_POST['name'])) {
        $errors['special-charcters'] = "<div class='alert alert-danger'>Please Enter Name as only String Format.</div>";
    }
    // check for email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['wrong-email-format'] = "<div class='alert alert-danger'> Wrong Email Format</div>";
    }
    // check for phone
    if (empty($_POST['phone'])) {
        $errors['empty-phone']="<div class='alert alert-danger'>This Field Cannot be Empty</div>";
    }
    else if (!preg_match('/^01[0-2,5]{1}[0-9]{8}$/',$_POST['phone'])) {
        $errors['mobile-format']="<div class='alert alert-danger'> Please Enter Valid Mobile Number</div>";
    }
    // check for address 
    if (empty($_POST['address'])) {
        $errors['empty-address']="<div class='alert alert-danger'>This Field Cannot be Empty</div>";
    }
    elseif(is_numeric($_POST['address'])){
        $errors['numeric-address']="<div class='alert alert-danger'>Please enter valid address</div>";
    }elseif (strlen($_POST['address']) > 40 || strlen($_POST['address']) < 3 ) {
        $errors['lenght-address']="<div class='alert alert-danger'>Your Address must be greater than 3 letters and less than 40 letters </div>";
    }
    // check for job
    if (empty($_POST['job'])) {
        $errors['empty-job']="<div class='alert alert-danger'>This Field Cannot be Empty</div>";  
    }
    elseif (is_numeric($_POST['job'])) {
        $errors['job-numeric']="<div class='alert alert-danger'> This Field Can't Be Numbers </div>";
    }
     elseif (strlen($_POST['job']) < 3 || strlen($_POST['job']) > 30 ) {
        $errors['job-lenght']="<div class='alert alert-danger'> Your Job must be greater than 3 letters and less than 40 letters</div>";
    }
    // check for Password
    if (strlen($_POST["password"]) <= 8 || strlen($_POST["password"]) > 40 ) {
        $errors['pass-lenght'] = "<div class='alert alert-danger'>Your Password Must Contain At Least 8 Characters! And At Most 30 Characters! </div>";
    }
    // password should contain 1 Number
    else if (!preg_match("#[0-9]+#", $_POST['password'])) {
        $errors['pass-num'] = "<div class='alert alert-danger'>Your Password Must Contain At Least 1 Number!</div>";    
    } 
    //Password must contain 1 capital letter
    else if (!preg_match("#[A-Z]+#", $_POST['password'])) {
        $errors['pass-Capital'] = "<div class='alert alert-danger'>Your Password Must Contain At Least 1 Capital Letter!</div>";
    }
        //Password must contain 1 small letter 
    else if (!preg_match("#[a-z]+#", $_POST['password'])) {
        $errors['pass-Small'] = "<div class='alert alert-danger'>Your Password Must Contain At Least 1 Lowercase Letter!</div>";
    }

    //check Comfirm Password
    if ($_POST['password'] != $_POST['re_password']) {
        $errors['confirm'] = "<div class='alert alert-danger'> Please Enter the above Password</div>";
    }

    if (empty($errors)) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $Job = $_POST['job'];
        $password = $_POST['password'];
        $phone=$_POST['phone'];
        $address=$_POST['address'];
        $image="default-user.jpg";
        $newUser = fopen('Users.csv', 'a');
        fwrite($newUser, "\n");
        fwrite($newUser, "$name;$email;$password;$Job;$phone;$address;$image");
        fclose($newUser);
        header('location:login.php');
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" media="all">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda+One">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include_once 'includes/nav.php';
    ?>
    <div class="main">

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" id="register-form" novalidate>
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Your Name" />
                                <?php
                                if (isset($errors['empty'])) echo $errors['empty'];
                                else echo '';
                                if (isset($errors['invalid-string'])) echo $errors['invalid-string'];
                                else echo '';
                                if (isset($errors['wrong-lenght'])) echo $errors['wrong-lenght'];
                                else echo '';
                                if (isset($errors['string'])) echo $errors['string'];
                                else echo '';
                                if (isset($errors['special-charcters'])) echo $errors['special-charcters'];
                                else echo '';

                                ?>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Your Email" />
                                <?php
                                if (isset($errors['wrong-email-format'])) echo $errors['wrong-email-format'];
                                else echo '';
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="Phone"><i class="fas fa-phone"></i></label>
                                <input type="text" name="phone" id="Phone" placeholder="Phone" />
                                <?php
                                if (isset($errors['empty-phone'])) echo $errors['empty-phone'];
                               
                                 elseif (isset($errors['mobile-format'])) echo $errors['mobile-format'];
                                 else echo ''; 
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="Address"><i class="fas fa-map-marker-alt"></i></label>
                                <input type="text" name="address" id="Address" placeholder="Address" />
                                <?php
                                if (isset($errors['empty-address'])) {
                                    echo $errors['empty-address'];
                                }
                                elseif (isset($errors['numeric-address'])) {
                                    echo $errors['numeric-address'];
                                }elseif (isset($errors['lenght-address'])) {
                                    echo $errors['lenght-address'];
                                }
                                ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="job"><i class="fas fa-user-md"></i></label>
                                <input type="text" name="job" id="job" placeholder="Your Job" />
                                <?php
                                if (isset($errors['empty-job'])) {
                                    echo $errors['empty-job']; 
                                }elseif (isset($errors['job-lenght'])) {
                                    echo $errors['job-lenght'];
                                }elseif (isset($errors['job-numeric'])) {
                                 echo $errors['job-numeric'];   
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="pass" placeholder="Password" />
                                <?php
                                if (isset($errors['pass-lenght'])) echo $errors['pass-lenght'];
                                else echo '';
                                if (isset($errors['pass-num'])) echo $errors['pass-num'];
                                else echo '';
                                if (isset($errors['pass-Capital'])) echo $errors['pass-Capital'];
                                else echo '';
                                if (isset($errors['pass-Small'])) echo $errors['pass-Small'];
                                else echo '';

                                ?>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_password" id="re_pass" placeholder="Confirm your password" />
                                <?php
                                if (isset($errors['confirm'])) echo $errors['confirm'];
                                else echo '';

                                ?>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Register" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/signup-image.jpg" alt="sing up image"></figure>
                        <a href="login.php" class="signup-image-link">I am already member</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>