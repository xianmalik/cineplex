<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: dashboard.php");
 }
 include_once 'dbconnector.php';
 include 'functions.php';

 $nameError = "";
 $errUsername = "";
 $emailError = "";
 $passError = "";
 $error = false;

 if ( isset($_POST['signup-button']) ) {
  // clean user inputs to prevent sql injections
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  $query = "SELECT username FROM users WHERE username='$username'";
  $result = mysql_query($query);
  $count = mysql_num_rows($result);
  if ( $count!=0 ) {
    $error = true;
    $errUsername = "username not available";
  }

  // basic name validation
  if (empty($name)) {
   $error = true;
   $nameError = "Please enter your full name.";
  }

  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check email exist or not
   $query = "SELECT email FROM users WHERE email='$email'";
   $result = mysql_query($query);
   $count = mysql_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Please enter password.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "Password must have atleast 6 characters.";
  }

  // password encrypt using md5();
  $password = md5($pass);

  // if there's no error, continue to signup
  if( !$error ) {
   $query = "INSERT INTO users(name,username,email,password) VALUES('$name','$username','$email','$password')";
   $res = mysql_query($query);
   if ($res) {
    $errMSG = "Successfully registered, please activate your account by verification link.";
    $verificationCode = generateRandomString();
    $verifyQuery = mysql_query("INSERT INTO verification(email,code) VALUES('$email','$verificationCode')");
    unset($name);
    unset($email);
    unset($pass);
   } else {
    $errMSG = "Something went wrong, try again later...";
   }
  }
 }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Register</title>
  </head>
  <body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <?php if ( isset($errMSG) ) { echo $errMSG; } ?>
      <h1>Sign Up.</h1>

      <input type="text" name="name" placeholder="Enter Your Name">
      <span><?php echo $nameError; ?></span>

      <input type="text" name="username" placeholder="Enter username">
      <span><?php echo $errUsername; ?></span>

      <input type="email" name="email" placeholder="Enter Your Email">
      <span><?php echo $emailError; ?></span>

      <input type="password" name="pass" placeholder="Enter Password">
      <span><?php echo $passError; ?></span>

      <button type="submit" name="signup-button">Sign Up</button>
      <br><br>
      <a href="index.php">Sign in Here...</a>
    </form>
  </body>
</html>
<?php ob_end_flush(); ?>
