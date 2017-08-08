<?php
    ob_start();
    session_start();
    require_once 'dbconnector.php';

    if ( !isset( $_SESSION['user'] ) ) {
        header("Location: index.php");
        exit;
    }
    $username = (string)$_SESSION['user'];
    $res = mysql_query("SELECT * FROM users WHERE username = '$username'");
    if (!$res) { die("Error: " . mysql_error()); }
    $userRow = mysql_fetch_array($res);
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>Welcome - <?php echo $userRow['email']; ?></title>
   </head>
   <body>
     <header>
       <ul>
         Hello <?php echo $userRow['name']; ?>
         <li><a href="dashboard.php">Dashboard</a></li>
         <li><a href="changepassword.php">Change Password</a></li>
         <li><a href="logout.php?logout">Logout</a></li>
       </ul>
     </header>


     <h1>BOT HERE..</h1>
   </body>
 </html>
<?php ob_end_flush(); ?>
