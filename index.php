<?php
 ob_start();
 session_start();
 require_once 'dbconnector.php';

 // it will never let you open index(login) page if session is set
 if ( isset($_SESSION['user'])!="" ) {
	header("Location: dashboard.php");
	exit;
 }

 if( isset($_POST['login-button']) ) {
	// prevent sql injections/ clear user invalid inputs
	$username = trim($_POST['username']);
	$username = strip_tags($username);
	$username = htmlspecialchars($username);

	$pass = trim($_POST['password']);
	$pass = strip_tags($pass);
	$pass = htmlspecialchars($pass);
	// prevent sql injections / clear user invalid inputs

	if( empty($username) || empty($pass) ){
        $errMSG = "Please enter your login details";
    } else {
        $password = md5( $pass); // password hashing using MD5

        $res = mysql_query("SELECT username, password, active FROM users WHERE username='$username'");
        $row = mysql_fetch_array($res);
        $count = mysql_num_rows($res); // if uname/pass correct it returns must be 1 row

        if( $count == 1 && $row['password'] == $password ) {
            if( $row['active'] == 1 ){
                $_SESSION['user'] = $row['username'];
                header("Location: dashboard.php");
            } else {
                $errMSG = "Your account is not activated. Please check your email to activate your account.";
            }
        } else {
            $errMSG = "Please insert correct login details";
        }
	}
 }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Instagram Solutions</title>
	</head>
	<body>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<?php if ( isset($errMSG) ) { echo $errMSG; } ?>
			<h1>Sign In</h1>

			<input type="text" name="username" placeholder="Username" maxlength="40">

			<input type="password" name="password" placeholder="Your Password" maxlength="15">

			<button type="submit" name="login-button">Sign In</button>
			<br><br>
			<a href="register.php">Sign Up Here...</a>
		</form>
	</body>
</html>
<?php ob_end_flush(); ?>
