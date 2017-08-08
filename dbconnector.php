<?php
  define('DBHOST', 'localhost');
  define('DBUSER', 'admin');
  define('DBPASS', 'root');
  define('DBNAME', 'cineplex_management');

  $conn = mysqli_connect(DBHOST, DBUSER, DBPASS);
  $dbcon = mysqli_select_db($conn, DBNAME);

  if ( !$conn ) {
    die("Connection Failed : " . mysql_error());
  }

  if ( !$dbcon ) {
    die("Database Connection Failed : " . mysql_error());
  }
 ?>
