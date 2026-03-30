<<<<<<< HEAD
<?php
$host = "localhost";
$db = "project";
$user = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
=======
<?php
$host = "localhost";
$db = "project";
$user = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
>>>>>>> 3780024 (update)
?>