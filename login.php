<?php
session_start();
include_once 'auth/verified.php';

$header_row = [];
$users = [];
$read_header_row = TRUE;
$column_count = 0;

$file = fopen('Users.csv', 'r');
while ($line = fgetcsv($file, 1000, ';')) {
    // Read the Header
    if ($read_header_row) {
        $header = $line;
        $column_count = count($header);
        $read_header_row = FALSE;
    } else {
        // Read the data and create the array
        $data = [];
        for ($i = 0; $i < $column_count; $i++) {
            $data[$header[$i]] = $line[$i];
        }

        array_push($users, $data);
    }
}
// print_r($users);die;

if ($_POST) {
    $errors = [];
    if (empty($_POST['email'])) {
        $errors['email'] = "<div class='alert alert-danger'> Email is required</div>";
    }
    if (empty($_POST['Password'])) {
        $errors['Password'] = "<div class='alert alert-danger'> Password is required</div>";
    }
    //check for valid email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email-format'] = "<div class='alert alert-danger'> Please enter a valid Email</div>";
    }
    if (empty($errors)) {
        function validUser($user)
        {
            return ($_POST['email'] == $user['email'] and $_POST['Password'] == $user['password']);
        }
        $user = array_values(array_filter($users, 'validUser'));
        if (empty($user)) {
            $errors['wrong-email-or-password'] = "<div class='alert alert-danger'> Wrong Email or Password</div>";
        } else {
            //save data to session 
            $_SESSION['user'] = $user;
            header('location:home.php');
        }
    }
    // check remember me and save data to cookies
    if (isset($_POST['Checked']) and !empty($user)) {// bug of creating deleted cookies

        setcookie('UserName', $user[0]['name'], time() + 60 * 60 * 24, '/');
        setcookie('UserEmail', $user[0]['email'], time() + 60 * 60 * 24, '/');
        setcookie('UserPassword', encrypt_decrypt($user[0]['password'],'encrypt'), time() + 60 * 60 * 24, '/');
       // setcookie('UserPassword', base64_encode($user[0]['password']), time() + 60 * 60 * 24, '/');
        $_COOKIE['UserName'] = $user[0]['name'];
        $_COOKIE['UserEmail'] = $user[0]['email'];
        $_COOKIE['UserPassword'] = encrypt_decrypt($user[0]['password'],'encrypt');
       // $_COOKIE['UserPassword'] = base64_encode($user[0]['password']);
    }
    /* 
    The problem comes from the fact that setcookie() doesn't set the cookies immediately,
     it sends the headers so the browser sets the cookies. This means that,
     for the current page load, setcookie() will no generate any $_COOKIE.
    
    When the browser later on requests a page, it sends the cookies
     in the headers so the PHP can retrieve them in the form of $_COOKIE.
    
    Simple, old solution
    About solutions, the obvious one:
    
    setcookie('username',$username,time()+60*60*24*365);
    // 'Force' the cookie to exists
    $_COOKIE['username'] = $username;
    
    */ 
    else {
        setcookie('UserName', '', time() - 3600, '/');
        setcookie('UserEmail', '', time() - 3600, '/');
        setcookie('UserPassword', '', time() - 3600, '/');
    }
}
// Function to Encrypt and Decrypt Password
function encrypt_decrypt($string, $action = 'encrypt')
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
    $secret_iv = '5fgf5HJ5g27'; // user define secret key
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="stylce.css" type="text/css">
    <style>
        body {
            background: #eeeeee;
        }

        .form-inline {
            display: inline-block;
        }

        .navbar-header.col {
            padding: 0 !important;
        }

        .navbar {
            background: #fff;
            padding-left: 16px;
            padding-right: 16px;
            border-bottom: 1px solid #d6d6d6;
            box-shadow: 0 0 4px rgba(0, 0, 0, .1);
            z-index: 200;
        }

        .nav-link img {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            margin: -8px 0;
            float: left;
            margin-right: 10px;
        }

        .navbar .navbar-brand {
            color: #555;
            padding-left: 0;
            padding-right: 50px;
            font-family: 'Merienda One', sans-serif;
        }

        .navbar .navbar-brand i {
            font-size: 20px;
            margin-right: 5px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            box-shadow: none;
            padding-right: 35px;
            border-radius: 3px !important;
        }

        .search-box .input-group-addon {
            min-width: 35px;
            border: none;
            background: transparent;
            position: absolute;
            right: 0;
            z-index: 9;
            padding: 7px;
            height: 100%;
        }

        .search-box i {
            color: #a0a5b1;
            font-size: 19px;
        }

        .navbar .nav-item i {
            font-size: 18px;
        }

        .navbar .dropdown-item i {
            font-size: 16px;
            min-width: 22px;
        }

        .navbar .nav-item.open>a {
            background: none !important;
        }

        .navbar .dropdown-menu {
            border-radius: 1px;
            border-color: #e5e5e5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .navbar .dropdown-menu a {
            color: #777;
            padding: 8px 20px;
            line-height: normal;
        }

        .navbar .dropdown-menu a:hover,
        .navbar .dropdown-menu a:active {
            color: #333;
        }

        .navbar .dropdown-item .material-icons {
            font-size: 21px;
            line-height: 16px;
            vertical-align: middle;
            margin-top: -2px;
        }

        .navbar .badge {
            color: #fff;
            background: #f44336;
            font-size: 11px;
            border-radius: 20px;
            position: absolute;
            min-width: 10px;
            padding: 4px 6px 0;
            min-height: 18px;
            top: 5px;
        }

        .navbar a.notifications,
        .navbar a.messages {
            position: relative;
            margin-right: 10px;
        }

        .navbar a.messages {
            margin-right: 20px;
        }

        .navbar a.notifications .badge {
            margin-left: -8px;
        }

        .navbar a.messages .badge {
            margin-left: -4px;
        }

        .navbar .active a,
        .navbar .active a:hover,
        .navbar .active a:focus {
            background: transparent !important;
        }

        @media (min-width: 1200px) {
            .form-inline .input-group {
                width: 300px;
                margin-left: 30px;
            }
        }

        @media (max-width: 1199px) {
            .form-inline {
                display: block;
                margin-bottom: 10px;
            }

            .input-group {
                width: 100%;
            }
        }
    </style>
    <title>Login</title>

</head>

<body>
    <?php include_once 'includes/nav.php'; ?>
    <div class="parent">
        <!-- <div class="welcome">
                        <div class="welcome-content">
                               <h1>welcome to login</h1>
                               <p> do not have an account ?</p>
                               <a href="Sign_up.php">Sign up</a>

                        </div>
                </div> -->
        <div class="login">
            <div class="login-content">
                <div class="title-social">
                    <h3>Sign In</h3>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook-square"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram-square"></i></a></li>
                    </ul>
                </div>
                <div class="clear-both"></div>
                <form method="POST" novalidate>
                    <h4>Email</h4>
                    <input class="form-control" type="email" name="email" placeholder="Email" value="<?php
                                                                                                        if (isset($_COOKIE['UserEmail'])) {
                                                                                                            echo $_COOKIE['UserEmail'];
                                                                                                        }
                                                                                                        ?>">
                    <?php if (isset($errors['email'])) echo $errors['email'];
                    elseif (isset($errors['email-format'])) echo $errors['email-format'];

                    ?>
                    <h4>Password</h4>
                    <input class="form-control" type="password" name="Password" placeholder="password" value="<?php if (isset($_COOKIE['UserPassword'])) {
                                                                                                                    //echo base64_decode($_COOKIE['UserPassword']);
                                                                                                                    echo encrypt_decrypt($_COOKIE['UserPassword'],'decrypt');
                                                                                                                } ?>">
                    <?php echo (isset($errors['Password'])) ? $errors['Password'] : ''; ?>
                    <?php echo (isset($errors['wrong-email-or-password'])) ? $errors['wrong-email-or-password'] : ''; ?>
                    <input class="btn btn-primary" type="submit" value="Log in">
                    <div class="forgot-remember">
                        <input type="checkbox" class="form-check-input" name="Checked" <?php
                                                                                        if (isset($_COOKIE['UserEmail'])) {
                                                                                            echo 'checked';
                                                                                        } else echo '';

                                                                                        ?>>
                        <span>remember me</span>
                        <p>forgot password</p>

                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>