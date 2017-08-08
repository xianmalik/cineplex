<?php
    ob_start();
    session_start();
    require_once 'dbconnector.php';

     if ( !isset( $_SESSION['user'] ) ) {
         header("Location: index.php");
         exit;
     }

     $username = $_SESSION['user'];
     $res = mysql_query("SELECT * FROM users WHERE username='$username'");
     $row = mysql_fetch_array($res);
     $count = mysql_num_rows($res);

     if( isset($_POST['submit-button']) ) {
        $oldPass = trim($_POST['oldPass']);
        $oldPass = strip_tags($oldPass);
        $oldPass = htmlspecialchars($oldPass);
        $oldPass = md5($oldPass);

        $newPass = trim($_POST['newPass']);
        $newPass = strip_tags($newPass);
        $newPass = htmlspecialchars($newPass);
        $newPass = md5($newPass);

        $newPassConfirm = trim($_POST['newPassConfirm']);
        $newPassConfirm = strip_tags($newPassConfirm);
        $newPassConfirm = htmlspecialchars($newPassConfirm);
        $newPassConfirm = md5($newPassConfirm);

        if( $oldPass != $row['password'] ){
             $msg = "Old password didn't match";
        } else { // password hashing using MD5
             if( $newPass == $newPassConfirm ) {
                 $sql =  mysql_query("UPDATE users SET password = '$newPass' WHERE username = '$username'");
                 $msg = "Password upadated";
             } else {
                 $msg = "New passwords didn't match";
             }
     	}
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <title>Welcome - <?php echo $row['email']; ?></title>
	</head>
	<body>
        <header>
            <ul>
              Hello <?php echo $row['name']; ?>
              <li><a href="dashboard.php">Dashboard</a></li>
              <li><a href="changepassword.php">Change Password</a></li>
              <li><a href="logout.php?logout">Logout</a></li>
            </ul>
        </header>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <?php if ( isset($msg) ) { echo $msg; } ?>
            <h1>Change Password</h1>

            <input type="password" name="oldPass" placeholder="Old Password">
            <input type="password" name="newPass" placeholder="New Password">
            <input type="password" name="newPassConfirm" placeholder="Confirm New Password">

            <button type="submit" name="submit-button">Change password</button>
            <br><br>
        </form>
	</body>
</html>
<?php ob_end_flush(); ?>
