<?php
    ob_start();
    session_start();

    include_once 'dbconnector.php';

    $code = $_SERVER['QUERY_STRING'];

    $sql = mysql_query( "SELECT * FROM verification WHERE code='$code' AND expired=0" );
    $row = mysql_fetch_array($sql);
    $count = mysql_num_rows($sql);

    if ( $count == 1 ) {
        $email = $row['email'];
        echo "Account verified successfully. You can now login.";
        $sql = mysql_query( "UPDATE verification SET expired = 1 WHERE email='$email'" );
        $sql = mysql_query( "UPDATE users SET active = 1 WHERE email='$email'" );
    } else {
        echo "Verification link is wrong or expired";
    }
 ?>

 <!DOCTYPE html>
 <html>
     <head>
         <meta charset="utf-8">
         <title>Yo</title>
     </head>
     <body>
     </body>
 </html>
